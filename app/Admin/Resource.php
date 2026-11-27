<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Describes one table in the admin backend: how it is listed, searched, filtered and edited.
 *
 * Resources are stateless; the generic ResourceIndex / ResourceForm Livewire components
 * read these definitions. Override the hooks for table-specific behaviour.
 */
abstract class Resource
{
    /** @return class-string<Model> */
    abstract public static function model(): string;

    /** Plural label, e.g. "Rental cars". */
    abstract public static function label(): string;

    /** Singular label, e.g. "rental car". */
    abstract public static function singular(): string;

    /** @return list<Column> */
    abstract public static function columns(): array;

    /** @return list<Field> */
    abstract public static function fields(): array;

    public static function slug(): string
    {
        return Str::plural(Str::kebab(Str::beforeLast(class_basename(static::class), 'Resource')));
    }

    public static function icon(): string { return 'bi-table'; }

    public static function group(): string { return 'Other'; }

    /** Column used as the record's name in headings and messages. */
    public static function titleColumn(): string { return static::newModel()->getKeyName(); }

    public static function title(Model $record): string
    {
        return (string) ($record->getAttribute(static::titleColumn()) ?: '#' . $record->getKey());
    }

    /** @return list<string> */
    public static function searchColumns(): array { return [static::titleColumn()]; }

    /**
     * List filters: column => [label, options (value => label) or a Closure returning them].
     * A value of '__empty' matches NULL or ''.
     */
    public static function filters(): array { return []; }

    /** Approve/unapprove support: ['column' => …, 'on' => 'Approved', 'off' => 'Not Approved'], or null. */
    public static function approval(): ?array { return null; }

    /** Key into App\Support\Media types for thumbnails and photo uploads, or null for no photos. */
    public static function media(): ?string { return null; }

    /**
     * A related one-row table of Yes/No features edited alongside the record, or null:
     * ['label' => …, 'model' => class, 'match' => fn (array $attributes) => [column => value],
     *  'text' => [column => label] (free-text columns)].
     */
    public static function checklist(): ?array { return null; }

    /** @return array{0: string, 1: string} column, direction */
    public static function defaultSort(): array { return [static::newModel()->getKeyName(), 'desc']; }

    public static function canCreate(): bool { return true; }

    /** False makes the form read-only (e.g. contact messages). */
    public static function canEdit(): bool { return true; }

    /** Only admins (not staff) may open this resource at all. */
    public static function adminOnly(): bool { return false; }

    public static function query(): Builder { return static::model()::query(); }

    /** Extra links shown on each row and on the edit page: list of ['label', 'url', 'icon']. */
    public static function links(Model $record): array { return []; }

    /** Extra validation rules keyed by column (e.g. unique names ignoring $record). */
    public static function uniqueRules(?Model $record): array { return []; }

    /** Runs before saving; may adjust the record. $data is the validated form data. */
    public static function beforeSave(Model $record, array $data): void {}

    /**
     * Runs after saving inside the same transaction. $before holds the attributes before the edit
     * (empty when creating), so name-keyed related rows can follow a rename.
     */
    public static function afterSave(Model $record, array $before): void {}

    /** Return a message to refuse the delete, or null to allow it. */
    public static function preventDelete(Model $record): ?string { return null; }

    /** Runs after the record is deleted (inside the transaction): remove dependent rows. */
    public static function afterDelete(Model $record): void {}

    /** Public-site cache keys to forget after any change. */
    public static function cacheKeys(): array { return []; }

    // ------------------------------------------------------------------

    public static function newModel(): Model
    {
        $class = static::model();

        return new $class;
    }

    public static function table(): string
    {
        return static::newModel()->getTable();
    }

    /** @return array<string, Field> keyed by column */
    public static function fieldMap(): array
    {
        $map = [];
        foreach (static::fields() as $field) {
            $map[$field->name] = $field;
        }

        return $map;
    }

    /** Yes/No columns of the checklist table (all columns except keys, match and text columns). */
    public static function checklistColumns(): array
    {
        $checklist = static::checklist();
        if (! $checklist) {
            return [];
        }
        $model = new $checklist['model'];
        $skip = ['id', 'IDs', 'created_at', 'updated_at', 'roomid', 'RoomName', 'PropertyName', 'Car', 'Company', 'Ambulance',
            ...array_keys($checklist['text'] ?? [])];

        return array_values(array_diff(Schema::getColumnListing($model->getTable()), $skip));
    }

    /** The checklist row for a record (attributes array), or null. */
    public static function checklistRow(array $attributes): ?Model
    {
        $checklist = static::checklist();
        if (! $checklist) {
            return null;
        }

        return $checklist['model']::query()->where(($checklist['match'])($attributes))->first();
    }

    /** Distinct non-empty values of a column, for suggestions and filter options. */
    public static function distinct(string $column, ?string $model = null): array
    {
        $model ??= static::model();

        return $model::query()->whereNotNull($column)->where($column, '!=', '')
            ->distinct()->orderBy($column)->pluck($column)->map(fn ($v) => (string) $v)->all();
    }
}
