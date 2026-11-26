<?php

namespace App\Support;

use App\Models\UserRegistrationModel;
use Illuminate\Support\Facades\Hash;

/**
 * Customer login against the `registration` table.
 *
 * Customers are stored in the session under `pano_client_data` (only non-sensitive fields).
 *
 * Passwords may be legacy unsalted MD5 or bcrypt; both are accepted. When
 * config('panobooking.password_upgrade') is on, new passwords are bcrypt and MD5 ones are
 * re-hashed on the next successful login (on by default; see config/panobooking.php).
 */
class CustomerAuth
{
    public const SESSION_KEY = 'pano_client_data';

    public const RESULT_OK = 'ok';
    public const RESULT_UNKNOWN = 'unknown';      // no approved account with that email
    public const RESULT_BAD_PASSWORD = 'password';

    public function attempt(string $email, string $password): string
    {
        $account = UserRegistrationModel::query()
            ->where('Email', $email)
            ->where('ApprovalStatus', 'Approved')
            ->first();

        if (! $account) {
            return self::RESULT_UNKNOWN;
        }

        if (! $this->passwordMatches($account, $password)) {
            return self::RESULT_BAD_PASSWORD;
        }

        session()->regenerate();
        session()->put(self::SESSION_KEY, [
            'Email' => $account->Email,
            'FullNames' => $account->FullNames,
            'Telephone' => $account->Telephone,
        ]);
        app(Currency::class)->remember($account->SelectedCurrency);

        return self::RESULT_OK;
    }

    public function check(): bool
    {
        return session()->has(self::SESSION_KEY);
    }

    public function email(): ?string
    {
        return session(self::SESSION_KEY . '.Email');
    }

    public function name(): ?string
    {
        return session(self::SESSION_KEY . '.FullNames');
    }

    public function logout(): void
    {
        session()->forget([self::SESSION_KEY, Currency::SESSION_KEY]);
        session()->regenerateToken();
    }

    public static function upgradeEnabled(): bool
    {
        return (bool) config('panobooking.password_upgrade');
    }

    /** Hash for a new password (used at registration). */
    public static function hash(string $password): string
    {
        return self::upgradeEnabled() ? Hash::make($password) : md5($password);
    }

    private function passwordMatches(UserRegistrationModel $account, string $password): bool
    {
        $stored = (string) $account->Password;

        if (self::isLegacyMd5($stored)) {
            if (! hash_equals(strtolower($stored), md5($password))) {
                return false;
            }
            if (self::upgradeEnabled()) {
                $account->Password = Hash::make($password);
                $account->save();
            }

            return true;
        }

        if (! Hash::check($password, $stored)) {
            return false;
        }

        if (Hash::needsRehash($stored)) {
            $account->Password = Hash::make($password);
            $account->save();
        }

        return true;
    }

    public static function isLegacyMd5(string $hash): bool
    {
        return (bool) preg_match('/^[a-f0-9]{32}$/i', $hash);
    }
}
