<?php

use App\Http\Controllers\LogoutController;
use App\Livewire;
use Illuminate\Support\Facades\Route;

// Public URLs and route names are kept exactly as before (links and bookmarks depend on them).

Route::get('/', Livewire\HomePage::class);
Route::get('/contactus', Livewire\ContactPage::class)->name('contactus');
Route::get('/ambulance', Livewire\AmbulancePage::class);
Route::get('/cars', Livewire\CarsPage::class);
Route::get('/taxis', Livewire\TaxisPAge::class);
Route::get('/blog', Livewire\BlogPage::class);
Route::get('/homeguests', Livewire\HomeGuestHouses::class)->name('homeguests');

// Search
Route::get('/HotelSearch', Livewire\HotelSearch::class)->name('HotelSearch');
Route::get('/HotelSearch/fetch_data', Livewire\HotelSearch::class);
Route::get('/generalSearchs', Livewire\GeneralSearch::class)->name('generalSearchs');
Route::get('/CarSearch', Livewire\CarSearch::class)->name('CarSearch');
Route::get('/TaxiSearch', Livewire\TaxiSearch::class)->name('TaxiSearch');
Route::get('/AmbulanceSearch', Livewire\AmbulanceSearch::class)->name('AmbulanceSearch');
Route::get('/HomeGuestSearch', Livewire\HomeGuestSearch::class)->name('HomeGuestSearch');

// Listings and details
Route::get('/HotelSingle', Livewire\HotelSingle::class)->name('HotelSingle');
Route::get('/BlogDetail', Livewire\BlogDetailComponent::class)->name('BlogDetail');
Route::get('/DestinationsList', Livewire\DetinationsListComponent::class)->name('DestinationsList');
Route::get('/CarDetails', Livewire\CarRentalDetails::class)->name('CarDetails');
Route::get('/CarsList', Livewire\CarsListComponent::class)->name('CarsList');
Route::get('/TaxisList', Livewire\TaxiListComponent::class)->name('TaxisList');
Route::get('/TaxiDetails', Livewire\TaxiDetails::class)->name('TaxiDetails');
Route::get('/AmbulanceDetails', Livewire\AmbulancedetailsComponent::class)->name('AmbulanceDetails');
Route::get('/AmbulanceList', Livewire\AmbulanceListComponent::class)->name('AmbulanceList');
Route::get('/HomeGuestsList', Livewire\GuestHouseList::class)->name('HomeGuestsList');
Route::get('/HomeGuestSingle', Livewire\GuestHouseDetails::class)->name('HomeGuestSingle');
Route::get('/Regions', Livewire\Regions::class)->name('Regions');
Route::get('/Cities', Livewire\Cities::class)->name('Cities');
Route::get('/Properties', Livewire\Properties::class)->name('Properties');
Route::get('/PropertyTypes', Livewire\PropertyTypes::class)->name('PropertyTypes');
Route::get('/TourismSites', Livewire\TourismSites::class)->name('TourismSites');
Route::get('/TourismProperties', Livewire\TourismProperties::class)->name('TourismProperties');
Route::get('/TourismSiteDetails', Livewire\Tourismsitedetails::class)->name('TourismSiteDetails');
Route::get('/Events', Livewire\Events::class)->name('Events');
Route::get('/EventsProperties', Livewire\EventsProperties::class)->name('EventsProperties');
Route::get('/EventsDetails', Livewire\EventsDetails::class)->name('EventsDetails');
Route::get('/TourPackages', Livewire\TourPackages::class)->name('TourPackages');
Route::get('/TourPackageDetails', Livewire\TourPackageDetails::class)->name('TourPackageDetails');

// Booking
Route::get('/CompleteBooking', Livewire\CompleteBooking::class)->name('CompleteBooking');
Route::get('/CompleteAmbulanceBooking', Livewire\CompleteAmbulanceBooking::class)->name('CompleteAmbulanceBooking');
Route::get('/CompleteCarBooking', Livewire\CompleteCarRentalBooking::class)->name('CompleteCarBooking');
Route::get('/CompleteTaxiBooking', Livewire\CompleteTaxiBooking::class)->name('CompleteTaxiBooking');
Route::get('/CompleteHomeGuestBooking', Livewire\CompleteHomeGuestBooking::class)->name('CompleteHomeGuestBooking');
Route::get('/BookingSuccess', Livewire\BookingSuccess::class)->name('BookingSuccess');

// Account
Route::get('/Login', Livewire\LoginPage::class)->name('Login');
Route::get('/Register', Livewire\RegisterPage::class)->name('Register');
Route::get('/clientlogout', LogoutController::class)->name('clientlogout');
Route::get('/PendingBookings', Livewire\PendingBookings::class)->name('PendingBookings');
Route::get('/Bookings', Livewire\Bookings::class)->name('Bookings');
Route::get('/RegisterProperty', Livewire\RegisterProperty::class)->name('RegisterProperty');
Route::get('/RegisterCars', Livewire\RegisterCars::class)->name('RegisterCars');
Route::get('/RegisterAmbulance', Livewire\RegisterAmbulance::class)->name('RegisterAmbulance');

// Information
Route::get('/privacypolicy', Livewire\PrivacyPolicyPage::class)->name('privacypolicy');
Route::get('/TermsAndConditions', Livewire\TermsAndConditionsComponent::class)->name('TermsAndConditions');
Route::get('/Faqs', Livewire\Faqs::class)->name('Faqs');
Route::get('/HelpCenter', Livewire\HelpCenter::class)->name('HelpCenter');
