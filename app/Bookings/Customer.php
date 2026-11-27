<?php

namespace App\Bookings;

use App\Models\UserRegistrationModel;
use App\Support\CustomerAuth;

/** Who is booking: from the website session or the API token. */
final class Customer
{
    public function __construct(public readonly string $email, public readonly string $name) {}

    public static function fromSession(CustomerAuth $auth): ?self
    {
        return $auth->check() ? new self((string) $auth->email(), (string) $auth->name()) : null;
    }

    public static function fromAccount(UserRegistrationModel $account): self
    {
        return new self((string) $account->Email, (string) $account->FullNames);
    }
}
