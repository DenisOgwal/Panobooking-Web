<?php

namespace Tests\Feature;

use App\Livewire\PendingBookings;
use App\Livewire\RegisterCars;
use App\Livewire\RegisterPage;
use App\Livewire\RegisterProperty;
use App\Support\CustomerAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function signIn(string $email = 'demo@panobooking.test'): void
    {
        session([CustomerAuth::SESSION_KEY => ['Email' => $email, 'FullNames' => 'Someone']]);
    }

    private function pendingBookingFor(string $email): int
    {
        return DB::table('cart')->insertGetId([
            'Facility' => 'Nile Breeze Hotel', 'ProductName' => 'Standard Room', 'User' => $email, 'UserNames' => 'X',
            'Quantity' => 1, 'Cost' => 180000, 'Confirmed' => 'Pending',
        ], 'IDs');
    }

    public function test_the_old_get_parameter_no_longer_deletes_bookings(): void
    {
        $id = $this->pendingBookingFor('someone-else@example.test');
        $this->signIn();

        $this->get('/PendingBookings?bookingslug=' . $id)->assertOk();

        $this->assertDatabaseHas('cart', ['IDs' => $id]);
    }

    public function test_customers_can_cancel_only_their_own_pending_bookings(): void
    {
        $mine = $this->pendingBookingFor('demo@panobooking.test');
        $theirs = $this->pendingBookingFor('someone-else@example.test');
        $this->signIn();

        Livewire::test(PendingBookings::class)->call('cancel', $theirs)->call('cancel', $mine);

        $this->assertDatabaseMissing('cart', ['IDs' => $mine]);
        $this->assertDatabaseHas('cart', ['IDs' => $theirs]);
    }

    public function test_confirmed_bookings_cannot_be_cancelled(): void
    {
        $this->signIn();
        $confirmed = DB::table('cart')->where('User', 'demo@panobooking.test')->where('Confirmed', 'Confirmed')->value('IDs');

        Livewire::test(PendingBookings::class)->call('cancel', $confirmed);

        $this->assertDatabaseHas('cart', ['IDs' => $confirmed]);
    }

    public function test_guests_are_sent_to_sign_in(): void
    {
        $this->get('/PendingBookings')->assertRedirect('/Login?return=' . urlencode('/PendingBookings'));
        $this->get('/Bookings')->assertRedirect();
    }

    public function test_bookings_pages_list_and_search(): void
    {
        $this->signIn();

        $this->get('/Bookings')->assertOk()->assertSee('Nile Breeze Hotel');
        Livewire::test(PendingBookings::class)
            ->assertSee('UBA 101A')
            ->set('search', 'zzz-nothing')
            ->assertDontSee('UBA 101A');
    }

    public function test_registration_rejects_duplicates_and_stores_an_unapproved_hashed_account(): void
    {
        Http::fake();
        config(['panobooking.password_upgrade' => true]);

        Livewire::test(RegisterPage::class)
            ->set('name', 'New Person')->set('email', 'demo@panobooking.test')->set('phonenum', '700123456')
            ->set('password', 'secret-pass')->set('confirmpassword', 'secret-pass')
            ->call('submit')
            ->assertHasErrors(['email' => 'unique']);

        Livewire::test(RegisterPage::class)
            ->set('name', 'New Person')->set('email', 'new@example.test')->set('phonenum', '700 123456')
            ->set('password', 'secret-pass')->set('confirmpassword', 'different')
            ->call('submit')
            ->assertHasErrors('confirmpassword')
            ->set('confirmpassword', 'secret-pass')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('registered', true);

        $row = DB::table('registration')->where('Email', 'new@example.test')->first();
        $this->assertSame('Not Approved', $row->ApprovalStatus);
        $this->assertSame('+256700123456', $row->Telephone);
        $this->assertTrue(Hash::check('secret-pass', $row->Password));
    }

    public function test_property_submissions_are_unapproved_and_store_the_photo(): void
    {
        Storage::fake('media');

        Livewire::test(RegisterProperty::class)
            ->set('place', 'Sunset Test Lodge')->set('region', 'Eastern')->set('city', 'Jinja')
            ->set('exactlocation', 'Plot 9, Nile Road')->set('leastprice', 95000)->set('pricedescription', 'Per room per night')
            ->set('slogan', 'Quiet lodge by the river')->set('ratings', 4)->set('telephonenumber', '+256 700 000111')
            ->set('email', 'lodge@example.test')
            ->set('formFile', UploadedFile::fake()->image('photo.jpg', 800, 600))
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('submitted', true);

        $row = DB::table('pickuplocations')->where('Place', 'Sunset Test Lodge')->first();
        $this->assertNull($row->Approved);
        Storage::disk('media')->assertExists('AndroidFiles/DestinationImages/Sunset Test Lodge.jpg');
        $this->get('/DestinationsList?destinationslug=Jinja')->assertDontSee('Sunset Test Lodge');
    }

    public function test_listing_names_cannot_traverse_directories_and_svg_is_refused(): void
    {
        Livewire::test(RegisterCars::class)
            ->set('carname', '../../evil')
            ->set('formFile', UploadedFile::fake()->create('x.svg', 10, 'image/svg+xml'))
            ->call('submit')
            ->assertHasErrors(['carname', 'formFile']);
    }
}
