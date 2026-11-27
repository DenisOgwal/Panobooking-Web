<?php

namespace App\Admin;

/** Form fields for the word-valued flag columns shared by listings (see HasListingScopes). */
final class Flags
{
    public const APPROVED = 'Approved';
    public const NOT_APPROVED = 'Not Approved';

    public static function approved(string $column = 'Approved', string $label = 'Approval'): Field
    {
        return Field::status($column, $label, [self::APPROVED, self::NOT_APPROVED])->default(self::APPROVED)
            ->help('Only approved records appear on the site.');
    }

    public static function availability(): Field
    {
        return Field::status('Availability', 'Availability', ['Available', 'Unavailable'])->default('Available');
    }

    public static function offered(): Field
    {
        return Field::select('Offered', 'Special offer', ['Offered' => 'On offer', 'No' => 'No'])->default('No')->span(3);
    }

    public static function rating(string $column = 'Rating', string $label = 'Star rating'): Field
    {
        return Field::number($column, $label)->rules(['min:0', 'max:5'])->span(3);
    }

    /** approval() definition for resources with a standard Approved column. */
    public static function approval(string $column = 'Approved'): array
    {
        return ['column' => $column, 'on' => self::APPROVED, 'off' => self::NOT_APPROVED];
    }

    /** List filter for an approval column: approved, or anything else (NULL, '', Not Approved). */
    public static function approvalFilter(string $column = 'Approved'): array
    {
        return [$column => ['Approval', [self::APPROVED => 'Approved', '__pending' => 'Awaiting approval']]];
    }
}
