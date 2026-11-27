<?php

namespace App\Livewire\Admin;

use App\Admin\CacheBuster;
use App\Admin\Field;
use App\Admin\MediaFiles;
use App\Admin\RecordActions;
use App\Admin\Resource;
use App\Admin\ResourceRegistry;
use App\Livewire\Admin\Concerns\AdminComponent;
use App\Support\Features;
use App\Support\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * /admin/r/{resource}/create and /admin/r/{resource}/{id}/edit: the form for any registered table,
 * with its Yes/No feature checklist and photo gallery when the resource has them.
 *
 * Attributes are assigned explicitly (forceFill) because the models guard the approval and
 * promotion flags against mass assignment from the public forms; here staff set them on purpose.
 */
class ResourceForm extends Component
{
    use AdminComponent, WithFileUploads;

    #[Locked]
    public string $resource;

    #[Locked]
    public ?string $recordId = null;

    /** Form values keyed by column. */
    public array $data = [];

    /** Checklist Yes/No values keyed by column, and its free-text columns. */
    public array $checks = [];
    public array $checkText = [];

    /** Current values of select fields that are not among the options (legacy data), kept selectable. */
    #[Locked]
    public array $legacy = [];

    /** New photos to add. */
    public array $uploads = [];

    public function mount(string $resource, int|string|null $id = null): void
    {
        $def = ResourceRegistry::resolve($resource);
        $this->resource = $resource;

        if ($id === null) {
            abort_unless($def::canCreate(), 404);
            foreach ($def::fields() as $field) {
                $this->data[$field->name] = $field->default ?? '';
            }
            foreach ($def::checklistColumns() as $column) {
                $this->checks[$column] = false;
            }
            foreach (array_keys($def::checklist()['text'] ?? []) as $column) {
                $this->checkText[$column] = '';
            }

            return;
        }

        $record = $def::query()->whereKey($id)->firstOrFail();
        $this->recordId = (string) $record->getKey();
        $this->fillFrom($record);
    }

    /** @return class-string<Resource> */
    protected function def(): string
    {
        return ResourceRegistry::resolve($this->resource);
    }

    protected function record(): ?Model
    {
        return $this->recordId === null ? null : $this->def()::query()->whereKey($this->recordId)->firstOrFail();
    }

    private function fillFrom(Model $record): void
    {
        $def = $this->def();
        foreach ($def::fields() as $field) {
            $value = $field->type === 'password' ? '' : $record->getAttribute($field->name);
            if ($value instanceof \DateTimeInterface) {
                $value = $value->format('Y-m-d');
            } elseif ($field->type === 'date' && is_string($value) && strlen($value) > 10) {
                $value = substr($value, 0, 10);
            }
            $this->data[$field->name] = $value ?? '';

            if (in_array($field->type, ['select', 'yesno'], true) && filled($value)
                && ! array_key_exists((string) $value, $field->resolvedOptions())) {
                $this->legacy[$field->name] = (string) $value;
            }
        }

        $row = $def::checklistRow($record->getAttributes());
        foreach ($def::checklistColumns() as $column) {
            $this->checks[$column] = Features::isOn($row?->getAttribute($column));
        }
        foreach (array_keys($def::checklist()['text'] ?? []) as $column) {
            $this->checkText[$column] = (string) ($row?->getAttribute($column) ?? '');
        }
    }

    protected function rules(): array
    {
        $def = $this->def();
        $creating = $this->recordId === null;
        $extra = $def::uniqueRules($this->record());

        $rules = [];
        foreach ($def::fields() as $field) {
            $legacy = isset($this->legacy[$field->name]) ? [$this->legacy[$field->name]] : [];
            $rules['data.' . $field->name] = [...$field->validationRules($creating, $legacy), ...($extra[$field->name] ?? [])];
        }
        foreach (array_keys($def::checklist()['text'] ?? []) as $column) {
            $rules['checkText.' . $column] = ['nullable', 'string', 'max:2000'];
        }
        $rules['uploads'] = ['array', 'max:20'];
        $rules['uploads.*'] = ['image', 'mimes:jpeg,jpg,png,webp', 'max:5120'];

        return $rules;
    }

    protected function validationAttributes(): array
    {
        $names = ['uploads.*' => 'photo'];
        foreach ($this->def()::fields() as $field) {
            $names['data.' . $field->name] = strtolower($field->label);
        }

        return $names;
    }

    public function save(): void
    {
        abort_unless($this->def()::canEdit(), 403);
        $this->validate();

        $def = $this->def();
        $record = $this->record() ?? $def::newModel();
        $creating = ! $record->exists;
        $before = $creating ? [] : $record->getAttributes();
        $mediaType = $def::media();
        $oldBase = ($mediaType && ! $creating) ? Media::baseName($mediaType, $record) : null;

        DB::transaction(function () use ($def, $record, $before) {
            foreach ($def::fields() as $field) {
                $value = $this->data[$field->name] ?? null;
                if ($field->type === 'password') {
                    if (blank($value)) {
                        continue;
                    }
                    $value = $field->hasher ? ($field->hasher)((string) $value) : $value;
                } elseif (is_string($value)) {
                    $value = trim($value);
                    $value = $value === '' ? null : $value;
                }
                $record->setAttribute($field->name, $value);
            }

            $def::beforeSave($record, $this->data);
            $record->save();
            $this->saveChecklist($def, $before, $record->getAttributes());
            $def::afterSave($record, $before);
        });

        // Photos follow the record's name (e.g. {Place}.jpg), so a rename moves them.
        if ($mediaType) {
            $record->refresh();
            if ($oldBase !== null) {
                MediaFiles::rename($mediaType, $oldBase, Media::baseName($mediaType, $record), MediaFiles::count($mediaType, $record));
            }
            if ($this->uploads !== []) {
                MediaFiles::append($mediaType, $record, $this->uploads);
                $this->uploads = [];
            }
        }

        CacheBuster::flush($def::cacheKeys());
        $message = ucfirst($def::singular()) . ' “' . $def::title($record) . '” ' . ($creating ? 'created.' : 'saved.');

        if ($creating) {
            $this->flashToast($message);
            $this->redirectRoute('admin.resources.edit', ['resource' => $this->resource, 'id' => $record->getKey()]);

            return;
        }

        $this->fillFrom($record->refresh());
        $this->toast($message);
    }

    private function saveChecklist(string $def, array $before, array $after): void
    {
        $checklist = $def::checklist();
        if (! $checklist) {
            return;
        }
        $match = ($checklist['match'])($after);
        if (array_filter($match, fn ($v) => $v !== null && $v !== '') === []) {
            return;
        }

        // Find the row by the old link values, so a rename (e.g. a new plate) carries the row along.
        $row = ($before ? $def::checklistRow($before) : null) ?? $def::checklistRow($after) ?? new $checklist['model'];
        $values = $match + (isset($checklist['extra']) ? ($checklist['extra'])($after) : []);
        foreach ($def::checklistColumns() as $column) {
            $values[$column] = ! empty($this->checks[$column]) ? 'Yes' : 'No';
        }
        foreach (array_keys($checklist['text'] ?? []) as $column) {
            $values[$column] = trim((string) ($this->checkText[$column] ?? '')) ?: null;
        }
        $row->forceFill($values)->save();
    }

    public function removePhoto(int $n): void
    {
        $record = $this->record();
        if (! $record || ! ($type = $this->def()::media())) {
            return;
        }
        MediaFiles::remove($type, $record, $n);
        CacheBuster::flush();
        $this->toast('Photo removed.');
    }

    public function makeMainPhoto(int $n): void
    {
        $record = $this->record();
        if (! $record || ! ($type = $this->def()::media())) {
            return;
        }
        MediaFiles::makeMain($type, $record, $n);
        $this->toast('Main photo changed.');
    }

    public function removeUpload(int $index): void
    {
        array_splice($this->uploads, $index, 1);
    }

    public function delete(): void
    {
        Gate::authorize('admin.manage');
        $def = $this->def();
        $record = $this->record();
        if (! $record) {
            return;
        }
        $title = $def::title($record);
        if ($error = RecordActions::delete($def, $record)) {
            $this->toast($error, 'error');

            return;
        }
        $this->flashToast("Deleted “{$title}”.");
        $this->redirectRoute('admin.resources.index', ['resource' => $this->resource]);
    }

    public function render()
    {
        $def = $this->def();
        $record = $this->record();
        $mediaType = $def::media();

        $photos = [];
        if ($record && $mediaType) {
            $count = Media::countColumn($mediaType) ? (int) $record->getAttribute(Media::countColumn($mediaType)) : 1;
            $photos = $count > 0 ? Media::gallery($mediaType, $record, $count) : [];
        }

        $options = [];
        $suggestions = [];
        foreach ($def::fields() as $field) {
            if (in_array($field->type, ['select', 'yesno'], true)) {
                $options[$field->name] = $field->resolvedOptions();
                if (isset($this->legacy[$field->name])) {
                    $options[$field->name][$this->legacy[$field->name]] = $this->legacy[$field->name] . ' (current)';
                }
            }
            if ($field->suggest) {
                $suggestions[$field->name] = $field->resolvedSuggestions();
            }
        }

        $heading = $record ? $def::title($record) : 'New ' . $def::singular();

        return view('livewire.admin.resource-form', [
            'def' => $def,
            'record' => $record,
            'fields' => $def::fields(),
            'options' => $options,
            'suggestions' => $suggestions,
            'checklist' => $def::checklist(),
            'checklistColumns' => $def::checklistColumns(),
            'mediaType' => $mediaType,
            'photos' => $photos,
            'singlePhoto' => $mediaType && ! Media::countColumn($mediaType),
            'links' => $record ? $def::links($record) : [],
            'heading' => $heading,
            'readOnly' => ! $def::canEdit(),
            'canManage' => Gate::allows('admin.manage'),
        ])->layout('components.layouts.admin', $this->adminLayout($heading . ' · ' . $def::label()));
    }
}
