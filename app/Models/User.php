<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Admin backend accounts (`/admin`). Customers of the public site live in the
 * `registration` table instead (see App\Models\UserRegistrationModel).
 *
 * `role` is `admin` (full access), `staff` (no deletes, no admin users or currencies) or null (no access).
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_STAFF = 'staff';
    public const ROLES = [self::ROLE_ADMIN => 'Administrator', self::ROLE_STAFF => 'Staff'];

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    public function canAccessAdmin(): bool
    {
        return $this->isAdmin() || $this->isStaff();
    }
}
