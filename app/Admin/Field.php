<?php

namespace App\Admin;

use Closure;
use Illuminate\Validation\Rule;

/**
 * One input on an admin form, bound to a column of the record.
 *
 * Flag columns hold words, not booleans, so `yesno` writes Yes/No and `select`
 * writes the option key verbatim (e.g. Approved / Not Approved, Available, Offered).
 */
final class Field
{
    public array $rules = [];
    public array|Closure $options = [];
    public ?string $help = null;
    public int $span = 6;
    public bool $required = false;
    public mixed $default = null;
    /** password fields: callable(string $plain): string that hashes the new value. Null = store as given (model cast). */
    public ?Closure $hasher = null;
    /** text fields: suggestions shown in a datalist. */
    public array|Closure $suggest = [];

    private function __construct(public string $name, public string $label, public string $type) {}

    public static function text(string $name, string $label): self { return new self($name, $label, 'text'); }
    public static function textarea(string $name, string $label): self { return (new self($name, $label, 'textarea'))->span(12)->rules(['max:65000']); }
    public static function email(string $name, string $label): self { return (new self($name, $label, 'email'))->rules(['email', 'max:190']); }
    public static function number(string $name, string $label): self { return (new self($name, $label, 'number'))->rules(['numeric']); }
    public static function money(string $name, string $label): self { return (new self($name, $label, 'money'))->rules(['numeric', 'min:0'])->help('UGX'); }
    public static function date(string $name, string $label): self { return (new self($name, $label, 'date'))->rules(['date']); }
    public static function select(string $name, string $label, array|Closure $options): self { return (new self($name, $label, 'select'))->options($options); }
    public static function yesno(string $name, string $label): self { return (new self($name, $label, 'yesno'))->options(['Yes' => 'Yes', 'No' => 'No'])->span(3); }

    public static function password(string $name, string $label, ?Closure $hasher = null): self
    {
        $field = (new self($name, $label, 'password'))->rules(['string', 'min:8', 'max:190'])
            ->help('Leave blank to keep the current password.');
        $field->hasher = $hasher;

        return $field;
    }

    /** A word-valued status column, e.g. Approved / Not Approved. */
    public static function status(string $name, string $label, array $values): self
    {
        return (new self($name, $label, 'select'))->options(array_combine($values, $values))->span(3);
    }

    public function required(bool $required = true): self { $this->required = $required; return $this; }
    public function rules(array $rules): self { $this->rules = [...$this->rules, ...$rules]; return $this; }
    public function options(array|Closure $options): self { $this->options = $options; return $this; }
    public function help(?string $help): self { $this->help = $help; return $this; }
    public function span(int $span): self { $this->span = $span; return $this; }
    public function default(mixed $value): self { $this->default = $value; return $this; }
    public function suggest(array|Closure $values): self { $this->suggest = $values; return $this; }

    public function resolvedOptions(): array
    {
        return $this->options instanceof Closure ? ($this->options)() : $this->options;
    }

    public function resolvedSuggestions(): array
    {
        return $this->suggest instanceof Closure ? ($this->suggest)() : $this->suggest;
    }

    /** Validation rules for `data.{name}`. Passwords are only required when creating. */
    public function validationRules(bool $creating, array $alsoAllowed = []): array
    {
        $required = $this->required && ($this->type !== 'password' || $creating);
        $rules = [$required ? 'required' : 'nullable', ...$this->rules];

        if (in_array($this->type, ['text', 'email', 'password'], true) && ! $this->hasRule('max:')) {
            $rules[] = 'max:255';
        }
        if (in_array($this->type, ['select', 'yesno'], true)) {
            $rules[] = Rule::in([...array_map('strval', array_keys($this->resolvedOptions())), ...$alsoAllowed]);
        }

        return $rules;
    }

    private function hasRule(string $prefix): bool
    {
        foreach ($this->rules as $rule) {
            if (is_string($rule) && str_starts_with($rule, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
