<?php

namespace Tests\Feature\Api;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FormsTest extends ApiTestCase
{
    public function test_contact_testimonial_and_comment_are_stored_for_review(): void
    {
        $this->postJson($this->api('contact'), ['name' => 'An', 'email' => 'ann@example.test', 'message' => 'short'])
            ->assertUnprocessable()->assertJsonValidationErrors(['name', 'message']);
        $this->postJson($this->api('contact'), ['name' => 'Ann Example', 'email' => 'ann@example.test', 'message' => 'I would like to ask about group bookings.'])
            ->assertCreated();
        $this->assertDatabaseHas('messages', ['Email' => 'ann@example.test']);

        $this->postJson($this->api('testimonials'), ['name' => 'Ann Example', 'email' => 'ann@example.test', 'company' => 'Acme',
            'text' => 'Booking was quick and the hotel was lovely.'])->assertCreated();
        $this->assertDatabaseHas('clientfeedback', ['ClientEmail' => 'ann@example.test', 'Approved' => null]);

        $this->postJson($this->api('blog/1/comments'), ['name' => 'Ann Example', 'email' => 'ann@example.test', 'comment' => 'Great list, Sipi Falls is a must!'])
            ->assertCreated();
        $this->assertDatabaseHas('blogcomments', ['blog_model_id' => 1, 'CommentEmail' => 'ann@example.test', 'Approval' => null]);
        $this->assertNotContains('Great list, Sipi Falls is a must!', array_column($this->getJson($this->api('blog/1'))->json('data.comments'), 'comment'));
        $this->postJson($this->api('blog/9999/comments'), [])->assertNotFound();
    }

    public function test_listings_are_saved_unapproved_with_their_photo(): void
    {
        Storage::fake('media');

        $this->post($this->api('listings/car'), [
            'plate' => 'UBX 777X', 'category' => 'SUV', 'model' => 'Toyota Rav4', 'company' => 'App Hire', 'location' => 'Kampala',
            'kampala_rate' => 100000, 'upcountry_rate' => 120000, 'passengers' => 5, 'doors' => 4, 'automatic' => 'Yes',
            'luggage' => 2, 'min_driver_age' => 23, 'rating' => 4, 'description' => 'Clean and reliable.', 'payment_method' => 'Cash',
            'phone' => '+256700000123', 'email' => 'hire@example.test', 'driver_password' => 'driver-pass',
            'photo' => UploadedFile::fake()->image('car.jpg'),
        ])->assertCreated()->assertJsonPath('data.status', 'pending_approval');

        $this->assertDatabaseHas('carrentals', ['TaxiNo' => 'UBX 777X', 'Approved' => null, 'Price' => 105000, 'Password' => md5('driver-pass')]);
        Storage::disk('media')->assertExists('AndroidDriver/FileUpload/App HireUBX 777X.jpg');
        $this->assertNotContains('UBX 777X', array_column($this->getJson($this->api('vehicles/car?per_page=50'))->json('data'), 'plate'));

        $this->postJson($this->api('listings/property'), ['name' => '../etc'])->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'type', 'city', 'photo']);
        $this->postJson($this->api('listings/ambulance'), [])->assertUnprocessable()->assertJsonValidationErrors(['plate', 'photo']);
        $this->postJson($this->api('listings/boat'), [])->assertNotFound();
    }
}
