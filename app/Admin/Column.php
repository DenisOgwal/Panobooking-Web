<?php

namespace App\Admin;

use App\Support\Media;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/** One column of an admin list. */
final class Column
{
    public string $format = 'text';
    public bool $sortable = true;
    public int $limit = 60;
    public ?Closure $value = null;

    private function __construct(public string $name, public string $label) {}

    public static function make(string $name, string $label): self { return new self($name, $label); }

    /** Main photo of the record (the resource's media type). */
    public static function photo(): self { $c = new self('_photo', ''); $c->format = 'photo'; $c->sortable = false; return $c; }

    public function money(): self { $this->format = 'money'; return $this; }
    public function badge(): self { $this->format = 'badge'; return $this; }
    public function date(): self { $this->format = 'date'; return $this; }
    public function limit(int $chars): self { $this->limit = $chars; return $this; }
    public function unsortable(): self { $this->sortable = false; return $this; }
    /** Computed value: callable(Model): string. */
    public function using(Closure $value): self { $this->value = $value; $this->sortable = false; return $this; }

    public function display(Model $record, ?string $mediaType): string
    {
        if ($this->format === 'photo') {
            return $mediaType ? Media::image($mediaType, $record) : '';
        }

        $value = $this->value ? ($this->value)($record) : $record->getAttribute($this->name);
        if ($value === null || $value === '') {
            return '';
        }

        return match ($this->format) {
            'money' => 'UGX ' . number_format((float) $value),
            'date' => $value instanceof \DateTimeInterface ? $value->format('j M Y') : rescue(fn () => Carbon::parse($value)->format('j M Y'), (string) $value, false),
            default => Str::limit(strip_tags((string) $value), $this->limit),
        };
    }

    /** Bootstrap colour for a word-valued status badge. */
    public static function badgeTone(string $value): string
    {
        return match (strtolower($value)) {
            'approved', 'yes', 'available', 'offered', 'confirmed', 'admin' => 'success',
            'pending', 'staff' => 'warning',
            'not approved', 'no', 'unavailable', 'declined', 'suspended' => 'secondary',
            default => 'light',
        };
    }
}
