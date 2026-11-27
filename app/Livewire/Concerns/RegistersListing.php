<?php

namespace App\Livewire\Concerns;

use App\Listings\ListingForm;
use Livewire\WithFileUploads;

/**
 * Shared bits of the "list with us" forms. Rules and saving live in App\Listings (shared with
 * the mobile API); the page maps its property names onto the listing's field names.
 * New listings are saved without approval, so they stay hidden until an admin approves them.
 */
trait RegistersListing
{
    use WithFileUploads;

    public $formFile;

    public bool $submitted = false;

    /** @return array<string, string> listing field => component property (photo => formFile) */
    abstract protected function listingFields(): array;

    abstract protected function listingForm(): ListingForm;

    protected function rules(): array
    {
        $rules = [];
        foreach ($this->listingForm()->rules() as $field => $rule) {
            $rules[$this->listingFields()[$field]] = $rule;
        }

        return $rules;
    }

    protected function validationAttributes(): array
    {
        $names = [];
        foreach ($this->listingForm()->attributes() as $field => $label) {
            if (isset($this->listingFields()[$field])) {
                $names[$this->listingFields()[$field]] = $label;
            }
        }

        return $names;
    }

    public function updated($field): void
    {
        if (array_key_exists($field, $this->rules())) {
            $this->validateOnly($field);
        }
    }

    protected function submitListing(): void
    {
        $this->validate();

        $data = [];
        foreach ($this->listingFields() as $field => $property) {
            $data[$field] = $this->{$property};
        }
        $this->listingForm()->store($data, $this->formFile);

        $this->submitted = true;
    }
}
