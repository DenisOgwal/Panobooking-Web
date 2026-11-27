<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * A customer of the public site and mobile app (`registration`).
 *
 * Customers sign in only when ApprovalStatus = Approved, which is set when they verify their
 * email (App\Support\CustomerAccounts) or when an admin approves them. The mobile API
 * authenticates them with Sanctum tokens; the website keeps its own session (CustomerAuth).
 */
class UserRegistrationModel extends Authenticatable
{
    use HasApiTokens, HasFactory;

    public const APPROVED = 'Approved';
    public const NOT_APPROVED = 'Not Approved';

    protected $table = 'registration';

    protected $fillable = [
        'FullNames',
        'Email',
        'Password',
        'Telephone',
        'ApprovalStatus',
        'SelectedCurrency',
    ];

    protected $hidden = [
        'Password', 'remember_token', 'verification_code', 'verification_expires_at',
        'password_reset_code', 'password_reset_expires_at', 'code_attempts',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'verification_expires_at' => 'datetime',
            'password_reset_expires_at' => 'datetime',
        ];
    }

    public function getAuthPassword(): string
    {
        return (string) $this->Password;
    }

    /** The table has no remember-token column. */
    public function getRememberTokenName(): string
    {
        return '';
    }

    public function isApproved(): bool
    {
        return $this->ApprovalStatus === self::APPROVED;
    }

    public function isVerified(): bool
    {
        return $this->email_verified_at !== null;
    }
}
