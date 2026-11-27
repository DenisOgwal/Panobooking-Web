<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\BookingDetail;
use App\Livewire\Admin\PropertyDetails;
use App\Livewire\Admin\ResourceForm;
use App\Livewire\Admin\ResourceIndex;
use App\Models\CartModel;
use App\Models\HotelsModel;
use App\Models\RentalCarModel;
use App\Support\CustomerAuth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

class RecordsTest extends AdminTestCase
{
    private function newCar(): array
    {
        return [
            'TaxiNo' => 'UBZ 999Z', 'CarSpec' => 'Zeta Testmobile', 'CarCategory' => 'Van', 'Company' => 'Test Hire',
            'DriverName' => 'Test Hire', 'Price' => '200000', 'Automatic' => 'Yes', 'Availability' => 'Available',
            'Offered' => 'No', 'Approved' => 'Not Approved', 'Password' => 'driver-secret',
        ];
    }

    public function test_staff_create_a_car_that_appears_on_the_site_only_once_approved(): void
    {
        Storage::fake('local');
        $this->asStaff();

        $form = Livewire::test(ResourceForm::class, ['resource' => 'rental-cars']);
        foreach ($this->newCar() as $key => $value) {
            $form->set("data.$key", $value);
        }
        $form->set('checks.Bluetooth', true)
            ->set('uploads', [UploadedFile::fake()->image('a.jpg'), UploadedFile::fake()->image('b.jpg')])
            ->call('save')
            ->assertHasNoErrors();

        $car = RentalCarModel::where('TaxiNo', 'UBZ 999Z')->firstOrFail();
        $this->assertSame('Not Approved', $car->Approved);
        $this->assertSame(md5('driver-secret'), $car->Password, 'the driver app expects MD5');
        $this->assertSame(2, (int) $car->PhotoUri);
        Storage::disk('local')->assertExists('AndroidDriver/FileUpload/Test HireUBZ 999Z.jpg');
        Storage::disk('local')->assertExists('AndroidDriver/FileUpload/Test HireUBZ 999Z2.jpg');
        $this->assertDatabaseHas('carincludes', ['Car' => 'UBZ 999Z', 'Company' => 'Test Hire', 'Bluetooth' => 'Yes', 'TV' => 'No']);

        session()->forget('alert'); // the 'created' toast flashed by the form names the car
        $this->get('/CarsList?carcategoriesslug=Van')->assertDontSee('Zeta Testmobile');

        Livewire::test(ResourceIndex::class, ['resource' => 'rental-cars'])->call('approve', $car->IDs, true);
        $this->assertSame('Approved', $car->fresh()->Approved);
        $this->get('/CarsList?carcategoriesslug=Van')->assertSee('Zeta Testmobile');
    }

    public function test_a_blank_password_keeps_the_current_one(): void
    {
        $car = RentalCarModel::first();
        $car->forceFill(['Password' => md5('original')])->save();

        $this->asAdmin();
        Livewire::test(ResourceForm::class, ['resource' => 'rental-cars', 'id' => $car->IDs])
            ->set('data.Price', '123456')->call('save')->assertHasNoErrors();

        $this->assertSame(md5('original'), $car->fresh()->Password);
        $this->assertEquals(123456, (float) $car->fresh()->Price);
    }

    public function test_validation_errors_are_shown(): void
    {
        $this->asAdmin();
        Livewire::test(ResourceForm::class, ['resource' => 'properties'])
            ->set('data.Place', '')->set('data.Prices', 'abc')->call('save')
            ->assertHasErrors(['data.Place' => 'required', 'data.Prices' => 'numeric']);

        $existing = HotelsModel::value('Place');
        Livewire::test(ResourceForm::class, ['resource' => 'properties'])
            ->set('data.Place', $existing)->set('data.City', 'Kampala')->set('data.Prices', '1000')->call('save')
            ->assertHasErrors(['data.Place' => 'unique']);
    }

    public function test_renaming_a_property_carries_its_rooms_and_amenities(): void
    {
        $hotel = HotelsModel::approved()->firstOrFail();
        $old = $hotel->Place;
        $rooms = DB::table('rooms')->where('HotelName', $old)->count();
        $this->assertGreaterThan(0, $rooms);
        $this->assertDatabaseHas('popularamenities', ['PropertyName' => $old]);

        $this->asAdmin();
        Livewire::test(ResourceForm::class, ['resource' => 'properties', 'id' => $hotel->IDs])
            ->set('data.Place', 'Renamed Palace')->call('save')->assertHasNoErrors();

        $this->assertSame($rooms, DB::table('rooms')->where('HotelName', 'Renamed Palace')->count());
        $this->assertDatabaseHas('popularamenities', ['PropertyName' => 'Renamed Palace']);
        $this->assertDatabaseMissing('houserules', ['PropertyName' => $old]);

        $roomName = DB::table('rooms')->where('HotelName', 'Renamed Palace')->value('RoomName');
        $this->get(route('HotelSingle', ['hotelslug' => $hotel->IDs]))->assertOk()->assertSee('Renamed Palace')->assertSee($roomName);
    }

    public function test_amenities_editor_adds_updates_and_removes_rows(): void
    {
        $hotel = HotelsModel::firstOrFail();
        $this->asStaff();

        $c = Livewire::test(PropertyDetails::class, ['type' => 'hotel', 'id' => $hotel->IDs])
            ->set('tab', 'facilities')
            ->set('newRow.spa.Amenity', 'Sauna')
            ->call('saveSection', 'spa')->assertHasNoErrors();
        $this->assertDatabaseHas('spa', ['PropertyName' => $hotel->Place, 'Amenity' => 'Sauna']);

        $id = DB::table('spa')->where('PropertyName', $hotel->Place)->where('Amenity', 'Sauna')->value('id');
        $c->set("rows.spa.$id.Amenity", 'Steam room')->call('saveSection', 'spa');
        $this->assertDatabaseHas('spa', ['id' => $id, 'Amenity' => 'Steam room']);

        $c->set('flags.popularamenities.Forex', true)->call('saveSection', 'popularamenities');
        $this->assertDatabaseHas('popularamenities', ['PropertyName' => $hotel->Place, 'Forex' => 'Yes']);

        $c->set('forms.houserules.Pets', 'Allowed')->call('saveSection', 'houserules');
        $this->assertDatabaseHas('houserules', ['PropertyName' => $hotel->Place, 'Pets' => 'Allowed']);

        $c->call('deleteRow', 'spa', $id);
        $this->assertDatabaseMissing('spa', ['id' => $id]);

        $this->get(route('HotelSingle', ['hotelslug' => $hotel->IDs]))->assertSee('Allowed');
    }

    public function test_rows_of_another_property_cannot_be_deleted(): void
    {
        [$a, $b] = HotelsModel::orderBy('IDs')->limit(2)->get();
        $id = DB::table('bathroom')->insertGetId(['PropertyName' => $b->Place, 'Amenity' => 'Towels']);

        $this->asAdmin();
        Livewire::test(PropertyDetails::class, ['type' => 'hotel', 'id' => $a->IDs])->call('deleteRow', 'bathroom', $id);

        $this->assertDatabaseHas('bathroom', ['id' => $id]);
    }

    public function test_bookings_are_confirmed_and_declined_and_customers_see_it(): void
    {
        $pending = CartModel::pending()->where('User', 'demo@panobooking.test')->firstOrFail();
        $this->asStaff();

        Livewire::test(BookingDetail::class, ['id' => $pending->IDs])
            ->call('decline')->assertHasErrors('note')
            ->set('note', 'No cars free on those dates.')->call('decline')->assertHasNoErrors();
        $this->assertDatabaseHas('cart', ['IDs' => $pending->IDs, 'Confirmed' => 'Declined', 'ConfirmReason' => 'No cars free on those dates.']);

        session([CustomerAuth::SESSION_KEY => ['Email' => 'demo@panobooking.test', 'FullNames' => 'Demo']]);
        $this->get('/Bookings')->assertOk()->assertSee('Declined')->assertSee('No cars free on those dates.');
        $this->get('/PendingBookings')->assertOk()->assertDontSee('Pearl Car Hire');

        Livewire::test(BookingDetail::class, ['id' => $pending->IDs])->set('note', 'Found one!')->call('confirm');
        $this->assertSame('Confirmed', $pending->fresh()->Confirmed);
    }

    public function test_only_admins_delete_and_dependent_rows_go_too(): void
    {
        $car = RentalCarModel::firstOrFail();
        $this->assertDatabaseHas('carincludes', ['Car' => $car->TaxiNo]);

        $this->asStaff();
        Livewire::test(ResourceForm::class, ['resource' => 'rental-cars', 'id' => $car->IDs])->call('delete')->assertForbidden();
        $this->assertDatabaseHas('carrentals', ['IDs' => $car->IDs]);

        $this->asAdmin();
        Livewire::test(ResourceForm::class, ['resource' => 'rental-cars', 'id' => $car->IDs])->call('delete')
            ->assertRedirect(route('admin.resources.index', ['resource' => 'rental-cars']));
        $this->assertDatabaseMissing('carrentals', ['IDs' => $car->IDs]);
        $this->assertDatabaseMissing('carincludes', ['Car' => $car->TaxiNo, 'Company' => $car->DriverName]);
    }

    public function test_admins_cannot_delete_or_demote_themselves(): void
    {
        $me = $this->admin();
        $this->asAdmin();

        Livewire::test(ResourceIndex::class, ['resource' => 'admin-users'])->call('delete', $me->id);
        $this->assertDatabaseHas('users', ['id' => $me->id]);

        Livewire::test(ResourceForm::class, ['resource' => 'admin-users', 'id' => $me->id])
            ->set('data.role', 'staff')->call('save')->assertHasErrors('data.role');
        $this->assertSame('admin', $me->fresh()->role);
    }

    public function test_customers_are_approved_and_can_then_sign_in(): void
    {
        $id = DB::table('registration')->where('Email', 'pending@panobooking.test')->value('id');
        $this->asStaff();

        Livewire::test(ResourceIndex::class, ['resource' => 'customers'])
            ->set('filter.ApprovalStatus', '__pending')->assertSee('pending@panobooking.test')
            ->set('selected', [(string) $id])->call('approveSelected', true);

        $this->assertDatabaseHas('registration', ['id' => $id, 'ApprovalStatus' => 'Approved']);
        $this->assertSame(CustomerAuth::RESULT_OK, app(CustomerAuth::class)->attempt('pending@panobooking.test', 'password123'));
    }

    public function test_search_and_filters_narrow_the_list(): void
    {
        $hotel = HotelsModel::firstOrFail();
        $other = HotelsModel::where('City', '!=', $hotel->City)->firstOrFail();
        $this->asAdmin();

        Livewire::test(ResourceIndex::class, ['resource' => 'properties'])
            ->set('search', $hotel->Place)->assertSee($hotel->Place)->assertDontSee($other->Place)
            ->set('search', '')->set('filter.City', $other->City)->assertSee($other->Place)
            ->call('sortBy', 'Prices')->assertSet('sort', 'Prices')
            ->call('sortBy', 'nonsense')->assertSet('sort', 'Prices');
    }

    public function test_the_make_admin_command_creates_an_account(): void
    {
        $this->artisan('panobooking:admin', ['email' => 'Ops@Example.test', '--role' => 'staff', '--password' => 'long-password'])
            ->assertSuccessful();

        $this->assertDatabaseHas('users', ['email' => 'ops@example.test', 'role' => 'staff']);
    }
}
