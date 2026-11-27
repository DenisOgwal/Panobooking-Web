<?php

namespace App\Admin\Resources;

use App\Admin\Column;
use App\Admin\Field;
use App\Admin\Flags;
use App\Admin\Resource;
use App\Models\FeedBackModel;

/** Customer testimonials (`clientfeedback`); approved ones appear on the home page. */
class FeedbackResource extends Resource
{
    public static function model(): string { return FeedBackModel::class; }
    public static function slug(): string { return 'testimonials'; }
    public static function label(): string { return 'Testimonials'; }
    public static function singular(): string { return 'testimonial'; }
    public static function icon(): string { return 'bi-chat-quote'; }
    public static function group(): string { return 'Experiences & content'; }
    public static function titleColumn(): string { return 'ClientName'; }
    public static function approval(): ?array { return Flags::approval(); }
    public static function searchColumns(): array { return ['ClientName', 'ClientEmail', 'ClientCompany', 'FeedBack']; }
    public static function filters(): array { return Flags::approvalFilter(); }

    public static function columns(): array
    {
        return [
            Column::make('ClientName', 'Name'),
            Column::make('ClientCompany', 'Company'),
            Column::make('FeedBack', 'Testimonial')->limit(90),
            Column::make('Approved', 'Approval')->badge(),
        ];
    }

    public static function fields(): array
    {
        return [
            Field::text('ClientName', 'Name')->required(),
            Field::email('ClientEmail', 'Email'),
            Field::text('ClientJob', 'Job title'),
            Field::text('ClientCompany', 'Company'),
            Field::textarea('FeedBack', 'Testimonial')->required()->rules(['max:2000']),
            Flags::approved(),
        ];
    }
}
