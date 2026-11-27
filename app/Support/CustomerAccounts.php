<?php

namespace App\Support;

use App\Mail\ResetPasswordCode;
use App\Mail\VerifyEmail;
use App\Models\UserRegistrationModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Throwable;

/**
 * Customer sign-up, email verification and password reset, shared by the website and the API.
 *
 * New accounts start "Not Approved". Verifying the email (6-digit code or signed link) approves
 * them; an admin can also approve them from the admin backend. Codes are stored hashed, expire
 * after CODE_TTL minutes and are void after MAX_ATTEMPTS wrong tries.
 */
class CustomerAccounts
{
    public const CODE_TTL = 30;
    public const MAX_ATTEMPTS = 5;

    /** Sign-up rules shared by the website (/Register) and the API (POST /auth/register). */
    public const RULES = [
        'name' => 'required|string|min:3|max:120',
        'email' => 'required|email|max:190|unique:registration,Email',
        'phone_code' => ['required', 'regex:/^\+\d{1,4}$/'],
        'phone' => ['required', 'regex:/^[0-9 ]{6,15}$/'],
        'password' => 'required|string|min:8|max:100',
    ];

    public const MESSAGES = [
        'email.unique' => 'An account with this email already exists. Try signing in instead.',
        'phone.regex' => 'Enter digits only, e.g. 700 123456.',
    ];

    public const OK = 'ok';
    public const INVALID = 'invalid';   // wrong code, unknown email, or no code pending
    public const EXPIRED = 'expired';
    public const LOCKED = 'locked';     // too many wrong tries; ask for a new code

    /** Phone stored as "+256700123456". */
    public static function phone(string $code, string $number): string
    {
        return $code . preg_replace('/\s+/', '', $number);
    }

    /** @param  array{name: string, email: string, telephone: string, password: string, currency?: ?string}  $data */
    public function register(array $data): UserRegistrationModel
    {
        $account = new UserRegistrationModel;
        $account->forceFill([
            'FullNames' => $data['name'],
            'Email' => $data['email'],
            'Password' => CustomerAuth::hash($data['password']),
            'Telephone' => $data['telephone'],
            'ApprovalStatus' => UserRegistrationModel::NOT_APPROVED,
            'SelectedCurrency' => $data['currency'] ?? Currency::BASE,
        ])->save();

        $this->sendVerification($account);

        return $account;
    }

    /** Email a fresh verification code and link. Does nothing for accounts already verified. */
    public function sendVerification(UserRegistrationModel $account): void
    {
        if ($account->isVerified()) {
            return;
        }

        $code = $this->newCode();
        $account->forceFill([
            'verification_code' => Hash::make($code),
            'verification_expires_at' => now()->addMinutes(self::CODE_TTL),
            'code_attempts' => 0,
        ])->save();

        $link = URL::temporarySignedRoute('verification.verify', now()->addMinutes(self::CODE_TTL),
            ['id' => $account->id, 'hash' => sha1((string) $account->Email)]);

        $this->mail($account, new VerifyEmail($account->FullNames, $code, $link, self::CODE_TTL));
    }

    public function verify(string $email, string $code): string
    {
        $account = $this->find($email);
        if (! $account) {
            return self::INVALID;
        }
        if ($account->isVerified()) {
            return self::OK;
        }

        $result = $this->checkCode($account, 'verification_code', 'verification_expires_at', $code);
        if ($result === self::OK) {
            $this->markVerified($account);
        }

        return $result;
    }

    /** Signed-link verification: the link itself is the proof. */
    public function verifyFromLink(int $id, string $hash): bool
    {
        $account = UserRegistrationModel::find($id);
        if (! $account || ! hash_equals(sha1((string) $account->Email), $hash)) {
            return false;
        }
        if (! $account->isVerified()) {
            $this->markVerified($account);
        }

        return true;
    }

    /** Verified email = approved account (also used when an admin approves a customer). */
    public function markVerified(UserRegistrationModel $account): void
    {
        $account->forceFill([
            'email_verified_at' => $account->email_verified_at ?? now(),
            'ApprovalStatus' => UserRegistrationModel::APPROVED,
            'verification_code' => null,
            'verification_expires_at' => null,
            'code_attempts' => 0,
        ])->save();
    }

    /** Email a reset code. Silent for unknown emails, so it never reveals who is registered. */
    public function sendPasswordReset(string $email): void
    {
        $account = $this->find($email);
        if (! $account) {
            return;
        }

        $code = $this->newCode();
        $account->forceFill([
            'password_reset_code' => Hash::make($code),
            'password_reset_expires_at' => now()->addMinutes(self::CODE_TTL),
            'code_attempts' => 0,
        ])->save();

        $this->mail($account, new ResetPasswordCode($account->FullNames, $code, self::CODE_TTL));
    }

    public function resetPassword(string $email, string $code, string $password): string
    {
        $account = $this->find($email);
        if (! $account) {
            return self::INVALID;
        }

        $result = $this->checkCode($account, 'password_reset_code', 'password_reset_expires_at', $code);
        if ($result !== self::OK) {
            return $result;
        }

        $account->forceFill([
            'Password' => CustomerAuth::hash($password),
            'password_reset_code' => null,
            'password_reset_expires_at' => null,
            'code_attempts' => 0,
        ]);
        // Receiving the code proves the email address.
        if (! $account->isVerified() && ! $account->isApproved()) {
            $account->forceFill(['email_verified_at' => now(), 'ApprovalStatus' => UserRegistrationModel::APPROVED]);
        }
        $account->save();
        $account->tokens()->delete(); // sign out every device

        return self::OK;
    }

    public static function message(string $result): string
    {
        return match ($result) {
            self::EXPIRED => 'That code has expired. Request a new one.',
            self::LOCKED => 'Too many wrong attempts. Request a new code.',
            default => 'That code is not valid.',
        };
    }

    private function checkCode(UserRegistrationModel $account, string $column, string $expiresColumn, string $code): string
    {
        $hash = $account->getAttribute($column);
        if (! $hash) {
            return self::INVALID;
        }
        if ((int) $account->code_attempts >= self::MAX_ATTEMPTS) {
            return self::LOCKED;
        }
        if ($account->getAttribute($expiresColumn)?->isPast()) {
            return self::EXPIRED;
        }
        if (! Hash::check(trim($code), $hash)) {
            $account->forceFill(['code_attempts' => (int) $account->code_attempts + 1])->save();

            return (int) $account->code_attempts >= self::MAX_ATTEMPTS ? self::LOCKED : self::INVALID;
        }

        return self::OK;
    }

    private function find(string $email): ?UserRegistrationModel
    {
        return UserRegistrationModel::where('Email', trim($email))->first();
    }

    private function newCode(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /** A mail outage must not break sign-up; the customer can ask for the code again. */
    private function mail(UserRegistrationModel $account, $mailable): void
    {
        try {
            Mail::to($account->Email, $account->FullNames)->send($mailable);
        } catch (Throwable $e) {
            report($e);
        }
    }
}
