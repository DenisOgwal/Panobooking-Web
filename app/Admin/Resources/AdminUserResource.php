<?php

namespace App\Admin\Resources;

use App\Admin\Column;
use App\Admin\Field;
use App\Admin\Resource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/** Admin backend accounts (`users` with a role). Admins only. */
class AdminUserResource extends Resource
{
    public static function model(): string { return User::class; }
    public static function slug(): string { return 'admin-users'; }
    public static function label(): string { return 'Admin users'; }
    public static function singular(): string { return 'admin user'; }
    public static function icon(): string { return 'bi-shield-lock'; }
    public static function group(): string { return 'Settings'; }
    public static function titleColumn(): string { return 'name'; }
    public static function searchColumns(): array { return ['name', 'email']; }
    public static function defaultSort(): array { return ['name', 'asc']; }
    public static function adminOnly(): bool { return true; }
    public static function filters(): array { return ['role' => ['Role', User::ROLES]]; }

    public static function query(): Builder
    {
        return User::query()->whereNotNull('role');
    }

    public static function columns(): array
    {
        return [
            Column::make('name', 'Name'),
            Column::make('email', 'Email'),
            Column::make('role', 'Role')->badge(),
            Column::make('created_at', 'Added')->date(),
        ];
    }

    public static function fields(): array
    {
        return [
            Field::text('name', 'Name')->required(),
            Field::email('email', 'Email')->required(),
            Field::select('role', 'Role', User::ROLES)->required()->default(User::ROLE_STAFF)
                ->help('Staff can view, add, edit and approve. Only administrators can delete and change settings.'),
            // The model's `hashed` cast hashes the password with bcrypt.
            Field::password('password', 'Password')->required(),
        ];
    }

    public static function uniqueRules(?Model $record): array
    {
        return ['email' => [Rule::unique('users', 'email')->ignore($record?->getKey())]];
    }

    public static function beforeSave(Model $record, array $data): void
    {
        if ($record->exists && $record->is(Auth::user()) && $record->role !== User::ROLE_ADMIN) {
            throw ValidationException::withMessages(['data.role' => 'You cannot remove your own administrator role.']);
        }
    }

    public static function preventDelete(Model $record): ?string
    {
        return $record->is(Auth::user()) ? 'You cannot delete your own account.' : null;
    }
}
