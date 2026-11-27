<?php

namespace Tests\Feature;

use App\Livewire\Admin\ResourceForm;
use App\Models\DestinationsModel;
use App\Models\User;
use App\Support\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class MediaTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_photos_without_an_upload_load_from_the_media_host(): void
    {
        Storage::fake('media');

        $this->assertSame(config('panobooking.media_url') . '/AndroidFiles/CityImages/Kampala.jpg', Media::city('Kampala'));
    }

    public function test_an_uploaded_city_photo_is_served_by_the_site_and_shown_on_the_home_page(): void
    {
        Storage::fake('media');
        $city = DestinationsModel::orderBy('DestinationID')->firstOrFail();

        $this->actingAs(User::where('email', 'staff@panobooking.test')->firstOrFail());
        Livewire::test(ResourceForm::class, ['resource' => 'destinations', 'id' => $city->id])
            ->set('uploads', [UploadedFile::fake()->image('city.jpg', 800, 600)])
            ->call('save')->assertHasNoErrors();

        $path = "AndroidFiles/CityImages/{$city->City}.jpg";
        Storage::disk('media')->assertExists($path);

        $url = Media::city($city->City);
        $this->assertStringStartsWith(url('/media/AndroidFiles/CityImages/'), $url);

        $this->get('/')->assertOk()->assertSee($url, false);
        $this->get(parse_url($url, PHP_URL_PATH))->assertOk()->assertHeader('Content-Type', 'image/jpeg');
    }

    public function test_the_media_route_only_serves_photos(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('AndroidFiles/notes.txt', 'secret');
        Storage::disk('media')->put('other/x.jpg', 'x');

        $this->get('/media/AndroidFiles/notes.txt')->assertNotFound();
        $this->get('/media/other/x.jpg')->assertNotFound();
        $this->get('/media/AndroidFiles/../other/x.jpg')->assertNotFound();
        $this->get('/media/AndroidFiles/CityImages/Nowhere.jpg')->assertNotFound();
    }

    public function test_city_tiles_without_photos_get_varied_gradients_not_black(): void
    {
        $this->get('/')->assertOk()->assertSee('district-tile', false)->assertSee('Most Popular Destinations');

        $css = collect(glob(public_path('build/assets/app-*.css')))->map(fn ($f) => file_get_contents($f))->implode('');
        if ($css !== '') {
            $this->assertStringContainsString('.district-grid>li:nth-child(11n+2) .district-tile', str_replace(["\n", '  '], '', $css));
        }
    }
}
