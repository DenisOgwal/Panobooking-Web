<?php

namespace App\Admin\Resources;

use App\Admin\Column;
use App\Admin\Field;
use App\Admin\Flags;
use App\Admin\Resource;
use App\Models\BlogCommentCount;
use App\Models\BlogModel;
use Illuminate\Database\Eloquent\Model;

/** Reader comments on blog posts (`blogcomments`); only those with Approval = Approved are shown. */
class BlogCommentResource extends Resource
{
    public static function model(): string { return BlogCommentCount::class; }
    public static function label(): string { return 'Blog comments'; }
    public static function singular(): string { return 'comment'; }
    public static function icon(): string { return 'bi-chat-left-text'; }
    public static function group(): string { return 'Experiences & content'; }
    public static function titleColumn(): string { return 'NameOfCommenter'; }
    public static function approval(): ?array { return Flags::approval('Approval'); }
    public static function searchColumns(): array { return ['NameOfCommenter', 'CommentEmail', 'Comment']; }

    private static function posts(): array
    {
        return BlogModel::orderBy('BlogTitle')->pluck('BlogTitle', 'id')->all();
    }

    public static function filters(): array
    {
        return [
            ...Flags::approvalFilter('Approval'),
            'blog_model_id' => ['Post', fn () => self::posts()],
        ];
    }

    public static function columns(): array
    {
        return [
            Column::make('NameOfCommenter', 'Name'),
            Column::make('Comment', 'Comment')->limit(90),
            Column::make('blog_model_id', 'Post')->using(fn ($r) => (string) ($r->post?->BlogTitle ?? '')),
            Column::make('CommentDate', 'Date')->date(),
            Column::make('Approval', 'Approval')->badge(),
        ];
    }

    public static function fields(): array
    {
        return [
            Field::select('blog_model_id', 'Post', fn () => self::posts())->required()->span(12),
            Field::text('NameOfCommenter', 'Name')->required(),
            Field::email('CommentEmail', 'Email'),
            Field::textarea('Comment', 'Comment')->required()->rules(['max:2000']),
            Field::date('CommentDate', 'Date')->default(now()->toDateString()),
            Flags::approved('Approval'),
        ];
    }

    /** Older code reads BlogID, newer code blog_model_id: keep both in step. */
    public static function beforeSave(Model $record, array $data): void
    {
        $record->BlogID = $record->blog_model_id;
    }
}
