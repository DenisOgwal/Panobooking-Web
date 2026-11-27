<?php

namespace App\Listings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

/**
 * A "list with us" submission (property, rental car, ambulance), shared by the website forms
 * and the mobile API. Submissions are saved unapproved, so they stay hidden until an admin
 * approves them. Field names are the API's; the website maps its own property names onto them.
 */
abstract class ListingForm
{
    /** Names end up in photo file names, so path characters are not allowed. */
    public const SAFE_NAME = ['required', 'string', 'min:2', 'max:120', 'not_regex:/[\/\\\\]|\.\./'];

    public const PHOTO = ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'];

    public const PHONE = ['required', 'regex:/^\+?[0-9 ]{7,20}$/'];

    /** URL key: property | car | ambulance */
    abstract public static function key(): string;

    /** @return array<string, mixed> keyed by API field name, including `photo` */
    abstract public function rules(): array;

    abstract public function attributes(): array;

    /** Save validated data and the photo; returns the new (unapproved) record. */
    abstract public function store(array $data, UploadedFile $photo): Model;

    /** Validate API input (including the `photo` file) and store it. */
    public function submit(array $input): Model
    {
        $data = Validator::make($input, $this->rules(), [], $this->attributes())->validate();

        return $this->store($data, $data['photo']);
    }

    protected function storePhoto(UploadedFile $photo, string $directory, string $baseName): void
    {
        $photo->storeAs($directory, $baseName . '.jpg');
    }

    /** @return array<string, class-string<ListingForm>> */
    public static function forms(): array
    {
        return [
            PropertyListing::key() => PropertyListing::class,
            CarListing::key() => CarListing::class,
            AmbulanceListing::key() => AmbulanceListing::class,
        ];
    }
}
