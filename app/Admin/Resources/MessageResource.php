<?php

namespace App\Admin\Resources;

use App\Admin\Column;
use App\Admin\Field;
use App\Admin\Resource;
use App\Models\Contact;

/** Messages sent through the contact form (`messages`). Read-only. */
class MessageResource extends Resource
{
    public static function model(): string { return Contact::class; }
    public static function slug(): string { return 'messages'; }
    public static function label(): string { return 'Contact messages'; }
    public static function singular(): string { return 'message'; }
    public static function icon(): string { return 'bi-envelope'; }
    public static function group(): string { return 'Bookings & customers'; }
    public static function titleColumn(): string { return 'Names'; }
    public static function searchColumns(): array { return ['Names', 'Email', 'Messages']; }
    public static function defaultSort(): array { return ['id', 'desc']; }
    public static function canCreate(): bool { return false; }
    public static function canEdit(): bool { return false; }

    public static function columns(): array
    {
        return [
            Column::make('Names', 'From'),
            Column::make('Email', 'Email'),
            Column::make('Messages', 'Message')->limit(100),
            Column::make('created_at', 'Received')->date(),
        ];
    }

    public static function fields(): array
    {
        return [
            Field::text('Names', 'From'),
            Field::email('Email', 'Email'),
            Field::textarea('Messages', 'Message'),
        ];
    }
}
