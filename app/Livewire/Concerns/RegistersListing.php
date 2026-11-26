<?php

namespace App\Livewire\Concerns;

use Livewire\WithFileUploads;

/**
 * Shared bits of the "list with us" forms: photo upload and the submitted state.
 * New listings are saved without approval, so they stay hidden until an admin approves them.
 */
trait RegistersListing
{
    use WithFileUploads;

    public $formFile;

    public bool $submitted = false;

    /** Names end up in photo file names, so path characters are not allowed. */
    protected const SAFE_NAME = ['required', 'string', 'min:2', 'max:120', 'not_regex:/[\/\\\\]|\.\./'];

    protected const PHOTO = ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'];

    protected function storePhoto(string $directory, string $baseName): void
    {
        $this->formFile->storeAs($directory, $baseName . '.jpg');
    }

    public function updated($field): void
    {
        if (array_key_exists($field, $this->rules())) {
            $this->validateOnly($field);
        }
    }
}
