<?php

namespace App\Admin\Resources;

use App\Admin\Column;
use App\Admin\Field;
use App\Admin\Flags;
use App\Admin\Resource;
use App\Models\CurrencySelect;
use App\Models\UserRegistrationModel;
use App\Support\CustomerAuth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\PersonalAccessToken;

/** Customer accounts of the public site (`registration`). New sign-ups wait for approval. */
class CustomerResource extends Resource
{
    public static function model(): string { return UserRegistrationModel::class; }
    public static function label(): string { return 'Customers'; }
    public static function singular(): string { return 'customer'; }
    public static function icon(): string { return 'bi-people'; }
    public static function group(): string { return 'Bookings & customers'; }
    public static function titleColumn(): string { return 'FullNames'; }
    public static function approval(): ?array { return Flags::approval('ApprovalStatus'); }
    public static function searchColumns(): array { return ['FullNames', 'Email', 'Telephone']; }
    public static function defaultSort(): array { return ['id', 'desc']; }
    public static function filters(): array { return Flags::approvalFilter('ApprovalStatus'); }

    public static function columns(): array
    {
        return [
            Column::make('FullNames', 'Name'),
            Column::make('Email', 'Email'),
            Column::make('Telephone', 'Phone'),
            Column::make('SelectedCurrency', 'Currency'),
            Column::make('created_at', 'Joined')->date(),
            Column::make('email_verified_at', 'Email verified')->badge()
                ->using(fn ($r) => $r->email_verified_at ? 'Yes' : 'No'),
            Column::make('ApprovalStatus', 'Approval')->badge(),
        ];
    }

    public static function fields(): array
    {
        return [
            Field::text('FullNames', 'Full name')->required(),
            Field::email('Email', 'Email')->required(),
            Field::text('Telephone', 'Phone')->rules(['regex:/^\+?[0-9 ]{7,20}$/']),
            Field::select('SelectedCurrency', 'Currency', fn () => CurrencySelect::orderBy('CurrencyName')->pluck('CurrencyName', 'CurrencyName')->all())
                ->default('UGX')->span(3),
            Flags::approved('ApprovalStatus')->help('Only approved customers can sign in.'),
            Field::password('Password', 'New password', fn (string $plain) => CustomerAuth::hash($plain))->required(),
        ];
    }

    public static function uniqueRules(?Model $record): array
    {
        return ['Email' => [Rule::unique('registration', 'Email')->ignore($record?->getKey())]];
    }

    /** An admin approving a customer stands in for email verification. */
    public static function afterSave(Model $record, array $before): void
    {
        if ($record->ApprovalStatus === Flags::APPROVED && ! $record->email_verified_at) {
            $record->forceFill(['email_verified_at' => now()])->save();
        }
    }

    public static function afterApprove(array $ids, bool $on): void
    {
        if ($on) {
            UserRegistrationModel::whereKey($ids)->whereNull('email_verified_at')->update(['email_verified_at' => now()]);
        } else {
            PersonalAccessToken::where('tokenable_type', UserRegistrationModel::class)->whereIn('tokenable_id', $ids)->delete();
        }
    }

    /** Suspending a customer signs them out of the app. */
    public static function beforeSave(Model $record, array $data): void
    {
        if ($record->exists && $record->isDirty('ApprovalStatus') && $record->ApprovalStatus !== Flags::APPROVED) {
            $record->tokens()->delete();
        }
    }

    public static function links(Model $record): array
    {
        return [['Bookings', route('admin.bookings', ['q' => $record->Email]), 'bi-receipt']];
    }
}
