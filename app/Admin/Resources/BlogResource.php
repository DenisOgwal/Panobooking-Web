<?php

namespace App\Admin\Resources;

use App\Admin\Column;
use App\Admin\Field;
use App\Admin\Resource;
use App\Models\BlogCommentCount;
use App\Models\BlogModel;
use Illuminate\Database\Eloquent\Model;

/** Blog articles (`blogs`). One cover photo named after the title. */
class BlogResource extends Resource
{
    public static function model(): string { return BlogModel::class; }
    public static function slug(): string { return 'blog-posts'; }
    public static function label(): string { return 'Blog posts'; }
    public static function singular(): string { return 'blog post'; }
    public static function icon(): string { return 'bi-journal-richtext'; }
    public static function group(): string { return 'Experiences & content'; }
    public static function titleColumn(): string { return 'BlogTitle'; }
    public static function media(): ?string { return 'blog'; }
    public static function searchColumns(): array { return ['BlogTitle', 'BlogShortDescription', 'Description']; }

    public static function columns(): array
    {
        return [
            Column::photo(),
            Column::make('BlogTitle', 'Title'),
            Column::make('Date', 'Date'),
            Column::make('BlogShortDescription', 'Summary')->limit(80),
            Column::make('comments_count', 'Comments')->using(fn ($r) => (string) $r->comments()->count()),
        ];
    }

    public static function fields(): array
    {
        return [
            Field::text('BlogTitle', 'Title')->required()->rules(['not_regex:/[\/\\\\]|\.\./'])->span(8),
            Field::date('Date', 'Published')->default(now()->toDateString())->span(4),
            Field::textarea('BlogShortDescription', 'Summary')->rules(['max:600']),
            Field::textarea('Description', 'Article')->required()->help('Paragraphs are separated by blank lines.'),
        ];
    }

    public static function links(Model $record): array
    {
        return [
            ['Comments', route('admin.resources.index', ['resource' => 'blog-comments', 'filter' => ['blog_model_id' => $record->id]]), 'bi-chat-left-text'],
            ['View on site', route('BlogDetail', ['blogslug' => $record->id]), 'bi-box-arrow-up-right'],
        ];
    }

    public static function afterDelete(Model $record): void
    {
        BlogCommentCount::where('blog_model_id', $record->id)->delete();
    }
}
