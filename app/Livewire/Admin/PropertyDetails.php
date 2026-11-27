<?php

namespace App\Livewire\Admin;

use App\Admin\CacheBuster;
use App\Admin\PropertyDetailSections;
use App\Livewire\Admin\Concerns\AdminComponent;
use App\Models\HomeGuestModel;
use App\Models\HotelsModel;
use App\Support\Features;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * /admin/properties/{type}/{id}/details: amenities, policies and surroundings of a hotel
 * or home stay. The rows are keyed by the property's name (see PropertyDetailSections).
 */
class PropertyDetails extends Component
{
    use AdminComponent;

    #[Locked]
    public string $type;

    #[Locked]
    public int $propertyId;

    #[Url]
    public string $tab = 'overview';

    /** flags sections: [table][column] => bool */
    public array $flags = [];
    /** form sections: [table][column] => string */
    public array $forms = [];
    /** rows sections: [table][id][column] => string */
    public array $rows = [];
    /** rows sections, the "add" line: [table][column] => string */
    public array $newRow = [];

    public function mount(string $type, int $id): void
    {
        $this->type = $type;
        $this->propertyId = $id;
        $this->property(); // 404 if missing
        if (! array_key_exists($this->tab, PropertyDetailSections::TABS)) {
            $this->tab = 'overview';
        }
        $this->load();
    }

    protected function property(): Model
    {
        return match ($this->type) {
            'hotel' => HotelsModel::findOrFail($this->propertyId),
            'guesthouse' => HomeGuestModel::findOrFail($this->propertyId),
            default => abort(404),
        };
    }

    protected function name(): string
    {
        $property = $this->property();

        return (string) ($this->type === 'hotel' ? $property->Place : $property->GuestHouseName);
    }

    private function flagColumns(string $model): array
    {
        $table = (new $model)->getTable();

        return array_values(array_diff(Schema::getColumnListing($table), ['id', 'PropertyName', 'created_at', 'updated_at']));
    }

    private function load(?string $only = null): void
    {
        $name = $this->name();
        foreach (PropertyDetailSections::all() as $key => $_) {
            if ($only && $only !== $key) {
                continue;
            }
            $s = PropertyDetailSections::get($key);
            $query = $s['model']::query()->where('PropertyName', $name);

            if ($s['kind'] === 'flags') {
                $row = $query->first();
                foreach ($this->flagColumns($s['model']) as $column) {
                    $this->flags[$key][$column] = Features::isOn($row?->getAttribute($column));
                }
            } elseif ($s['kind'] === 'form') {
                $row = $query->first();
                foreach ($s['fields'] as $column => $_label) {
                    $this->forms[$key][$column] = (string) ($row?->getAttribute($column) ?? '');
                }
            } else {
                $this->rows[$key] = [];
                foreach ($query->orderBy('id')->get() as $row) {
                    foreach ($s['fields'] as $column => $_label) {
                        $this->rows[$key][$row->id][$column] = (string) ($row->getAttribute($column) ?? '');
                    }
                }
                $this->newRow[$key] = array_fill_keys(array_keys($s['fields']), '');
            }
        }
    }

    public function saveSection(string $key): void
    {
        abort_unless(array_key_exists($key, PropertyDetailSections::all()), 404);
        $s = PropertyDetailSections::get($key);
        $name = $this->name();
        $max = $key === 'propertydescription' ? 5000 : 500;

        if ($s['kind'] === 'flags') {
            $values = [];
            foreach ($this->flagColumns($s['model']) as $column) {
                $values[$column] = ! empty($this->flags[$key][$column]) ? 'Yes' : 'No';
            }
            $this->upsertSingle($s['model'], $name, $values);
        } elseif ($s['kind'] === 'form') {
            $rules = [];
            foreach ($s['fields'] as $column => $_label) {
                $rules["forms.$key.$column"] = ['nullable', 'string', 'max:' . $max];
            }
            $this->validate($rules, [], $this->attributeNames($key, 'forms'));
            $values = [];
            foreach ($s['fields'] as $column => $_label) {
                $values[$column] = trim((string) ($this->forms[$key][$column] ?? '')) ?: null;
            }
            $this->upsertSingle($s['model'], $name, $values);
        } else {
            $rules = [];
            foreach (array_keys($this->rows[$key] ?? []) as $id) {
                foreach ($s['fields'] as $column => $_label) {
                    $rules["rows.$key.$id.$column"] = ['nullable', 'string', 'max:' . $max];
                }
            }
            foreach ($s['fields'] as $column => $_label) {
                $rules["newRow.$key.$column"] = ['nullable', 'string', 'max:' . $max];
            }
            $this->validate($rules, [], $this->attributeNames($key));

            foreach ($this->rows[$key] ?? [] as $id => $values) {
                $clean = $this->clean($s['fields'], $values);
                $row = $s['model']::query()->where('PropertyName', $name)->whereKey($id)->first();
                if (! $row) {
                    continue;
                }
                array_filter($clean) ? $row->forceFill($clean)->save() : $row->delete();
            }

            $new = $this->clean($s['fields'], $this->newRow[$key] ?? []);
            if (array_filter($new)) {
                (new $s['model'])->forceFill(['PropertyName' => $name] + $new)->save();
            }
        }

        CacheBuster::flush();
        $this->load($key);
        $this->toast($s['label'] . ' saved.');
    }

    public function deleteRow(string $key, int $id): void
    {
        abort_unless(array_key_exists($key, PropertyDetailSections::all()), 404);
        $s = PropertyDetailSections::get($key);
        $s['model']::query()->where('PropertyName', $this->name())->whereKey($id)->delete();
        unset($this->rows[$key][$id]);
        $this->toast('Removed.');
    }

    private function clean(array $fields, array $values): array
    {
        $clean = [];
        foreach ($fields as $column => $_label) {
            $clean[$column] = trim((string) ($values[$column] ?? '')) ?: null;
        }

        return $clean;
    }

    private function upsertSingle(string $model, string $name, array $values): void
    {
        $row = $model::query()->where('PropertyName', $name)->first() ?? new $model;
        $row->forceFill(['PropertyName' => $name] + $values)->save();
    }

    private function attributeNames(string $key, string $prefix = 'rows'): array
    {
        $names = [];
        foreach (PropertyDetailSections::get($key)['fields'] as $column => $label) {
            $names["$prefix.$key.*.$column"] = strtolower($label);
            $names["newRow.$key.$column"] = strtolower($label);
            $names["forms.$key.$column"] = strtolower($label);
        }

        return $names;
    }

    public function render()
    {
        $property = $this->property();
        $name = $this->name();
        $sections = [];
        $suggestions = [];
        foreach (PropertyDetailSections::all() as $key => $_) {
            $s = PropertyDetailSections::get($key);
            $sections[$s['tab']][$key] = $s;
            if ($s['kind'] === 'rows' && array_keys($s['fields']) === ['Amenity'] && $s['tab'] === $this->tab) {
                $suggestions[$key] = $s['model']::query()->whereNotNull('Amenity')->distinct()->orderBy('Amenity')->limit(80)->pluck('Amenity')->all();
            }
        }
        $resource = $this->type === 'hotel' ? 'properties' : 'home-stays';

        return view('livewire.admin.property-details', [
            'property' => $property,
            'name' => $name,
            'sections' => $sections[$this->tab] ?? [],
            'tabCounts' => array_map('count', $sections),
            'suggestions' => $suggestions,
            'resourceSlug' => $resource,
            'resourceLabel' => $this->type === 'hotel' ? 'Properties' : 'Home stays',
            'siteUrl' => $this->type === 'hotel'
                ? route('HotelSingle', ['hotelslug' => $property->IDs])
                : route('HomeGuestSingle', ['homeguestslug' => $property->IDs]),
        ])->layout('components.layouts.admin', $this->adminLayout('Amenities · ' . $name));
    }
}
