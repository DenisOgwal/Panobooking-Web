<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\CustomerResource;
use App\Models\UserRegistrationModel;
use App\Support\Currency;
use App\Support\CustomerAccounts;
use App\Support\CustomerAuth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Sign-up, email verification, sign-in (Sanctum token) and password reset.
 * An account can sign in once its email is verified, or once an admin has approved it.
 */
class AuthController extends ApiController
{
    /** POST /auth/register: creates the account and emails a 6-digit verification code. */
    public function register(Request $request, CustomerAccounts $accounts)
    {
        $data = $request->validate([
            'name' => CustomerAccounts::RULES['name'],
            'email' => CustomerAccounts::RULES['email'],
            'phone_code' => CustomerAccounts::RULES['phone_code'],
            'phone' => CustomerAccounts::RULES['phone'],
            'password' => [...explode('|', CustomerAccounts::RULES['password']), 'confirmed'],
            'currency' => 'nullable|string|size:3',
        ], CustomerAccounts::MESSAGES);

        $account = $accounts->register([
            'name' => $data['name'],
            'email' => $data['email'],
            'telephone' => CustomerAccounts::phone($data['phone_code'], $data['phone']),
            'password' => $data['password'],
            'currency' => app(Currency::class)->isAvailable($data['currency'] ?? null) ? $data['currency'] : Currency::BASE,
        ]);

        return response()->json([
            'message' => 'Account created. We emailed you a 6-digit code to verify your email address.',
            'data' => new CustomerResource($account),
            'verification_required' => true,
            'code_expires_in_minutes' => CustomerAccounts::CODE_TTL,
        ], 201);
    }

    /** POST /auth/verify-email {email, code}; with device_name, also signs in and returns a token. */
    public function verifyEmail(Request $request, CustomerAccounts $accounts)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6',
            'device_name' => 'nullable|string|max:100',
        ]);

        $result = $accounts->verify($data['email'], $data['code']);
        if ($result !== CustomerAccounts::OK) {
            throw ValidationException::withMessages(['code' => CustomerAccounts::message($result)]);
        }

        $account = UserRegistrationModel::where('Email', $data['email'])->firstOrFail();
        $response = ['message' => 'Email verified.', 'data' => new CustomerResource($account)];

        if (! empty($data['device_name']) && $account->isApproved()) {
            $response['token'] = $account->createToken($data['device_name'])->plainTextToken;
            $response['token_type'] = 'Bearer';
        }

        return response()->json($response);
    }

    /** POST /auth/resend-verification {email}: always the same answer, so it never reveals who is registered. */
    public function resendVerification(Request $request, CustomerAccounts $accounts)
    {
        $data = $request->validate(['email' => 'required|email']);
        if ($account = UserRegistrationModel::where('Email', $data['email'])->first()) {
            $accounts->sendVerification($account);
        }

        return response()->json(['message' => 'If that address has an unverified account, a new code is on its way.']);
    }

    /** POST /auth/login {email, password, device_name} → {token, data: customer} */
    public function login(Request $request, CustomerAuth $auth)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'required|string|max:100',
        ]);

        [$result, $account] = $auth->verifyCredentials($data['email'], $data['password']);

        if ($result === CustomerAuth::RESULT_UNVERIFIED) {
            return response()->json(['message' => CustomerAuth::message($result), 'code' => 'unverified'], 403);
        }
        if ($result !== CustomerAuth::RESULT_OK) {
            throw ValidationException::withMessages(['email' => CustomerAuth::message($result)]);
        }

        return response()->json([
            'token' => $account->createToken($data['device_name'])->plainTextToken,
            'token_type' => 'Bearer',
            'data' => new CustomerResource($account),
        ]);
    }

    /** POST /auth/forgot-password {email}: emails a reset code (silently does nothing for unknown emails). */
    public function forgotPassword(Request $request, CustomerAccounts $accounts)
    {
        $data = $request->validate(['email' => 'required|email']);
        $accounts->sendPasswordReset($data['email']);

        return response()->json(['message' => 'If that address has an account, we emailed it a 6-digit code.',
            'code_expires_in_minutes' => CustomerAccounts::CODE_TTL]);
    }

    /** POST /auth/reset-password {email, code, password, password_confirmation}: also signs out every device. */
    public function resetPassword(Request $request, CustomerAccounts $accounts)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6',
            'password' => [...explode('|', CustomerAccounts::RULES['password']), 'confirmed'],
        ]);

        $result = $accounts->resetPassword($data['email'], $data['code'], $data['password']);
        if ($result !== CustomerAccounts::OK) {
            throw ValidationException::withMessages(['code' => CustomerAccounts::message($result)]);
        }

        return response()->json(['message' => 'Password changed. Sign in with your new password.']);
    }

    /** POST /auth/logout: revokes the token used for this request. */
    public function logout(Request $request)
    {
        $this->customer($request)->currentAccessToken()?->delete();

        return response()->json(['message' => 'Signed out.']);
    }
}
