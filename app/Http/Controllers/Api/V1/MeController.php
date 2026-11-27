<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\CustomerResource;
use App\Support\Currency;
use App\Support\CustomerAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/** The signed-in customer's profile. */
class MeController extends ApiController
{
    /** GET /me */
    public function show(Request $request)
    {
        return new CustomerResource($this->customer($request));
    }

    /** PATCH /me {name?, phone?, currency?} (phone in full, e.g. +256700123456) */
    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|min:3|max:120',
            'phone' => ['sometimes', 'regex:/^\+?[0-9 ]{7,20}$/'],
            'currency' => ['sometimes', 'string', Rule::in(app(Currency::class)->available())],
        ]);

        $customer = $this->customer($request);
        $customer->forceFill(array_filter([
            'FullNames' => $data['name'] ?? null,
            'Telephone' => isset($data['phone']) ? preg_replace('/\s+/', '', $data['phone']) : null,
            'SelectedCurrency' => $data['currency'] ?? null,
        ], fn ($v) => $v !== null))->save();

        return new CustomerResource($customer->refresh());
    }

    /** PUT /me/password {current_password, password, password_confirmation}: other devices are signed out. */
    public function password(Request $request, CustomerAuth $auth)
    {
        $data = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|max:100|confirmed|different:current_password',
        ]);

        $customer = $this->customer($request);
        [$result] = $auth->verifyCredentials((string) $customer->Email, $data['current_password']);
        if ($result !== CustomerAuth::RESULT_OK) {
            throw ValidationException::withMessages(['current_password' => 'Your current password is incorrect.']);
        }

        $customer->forceFill(['Password' => CustomerAuth::hash($data['password'])])->save();
        $current = $customer->currentAccessToken();
        $customer->tokens()->when($current, fn ($q) => $q->where('id', '!=', $current->id))->delete();

        return response()->json(['message' => 'Password changed.']);
    }

    /** DELETE /me/tokens: sign out on every device (including this one). */
    public function logoutEverywhere(Request $request)
    {
        $this->customer($request)->tokens()->delete();

        return response()->json(['message' => 'Signed out on all devices.']);
    }
}
