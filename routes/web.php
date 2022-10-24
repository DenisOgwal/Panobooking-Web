<?php

use App\Http\Livewire\AmbulancedetailsComponent;
use App\Http\Livewire\AmbulanceListComponent;
use App\Http\Livewire\AmbulancePage;
use App\Http\Livewire\CarsPage;
use App\Http\Livewire\ContactPage;
use App\Http\Livewire\HomePage;
use App\Http\Livewire\HotelSearch;
use App\Http\Livewire\CarSearch;
use App\Http\Livewire\TaxiSearch;
use App\Http\Livewire\AmbulanceSearch;
use App\Http\Livewire\BlogDetailComponent;
use App\Http\Livewire\BlogPage;
use App\Http\Livewire\Bookings;
use App\Http\Livewire\BookingSuccess;
use App\Http\Livewire\CarRentalDetails;
use App\Http\Livewire\CarsListComponent;
use App\Http\Livewire\Cities;
use App\Http\Livewire\CompleteAmbulanceBooking;
use App\Http\Livewire\CompleteBooking;
use App\Http\Livewire\CompleteCarRentalBooking;
use App\Http\Livewire\CompleteHomeGuestBooking;
use App\Http\Livewire\CompleteTaxiBooking;
use App\Http\Livewire\HomeGuestHouses;
use App\Http\Livewire\TaxisPAge;
use App\Http\Livewire\HomeGuestSearch;
use App\Http\Livewire\HotelSingle;
use App\Http\Livewire\PrivacyPolicyPage;
use App\Http\Livewire\DetinationsListComponent;
use App\Http\Livewire\Events;
use App\Http\Livewire\EventsDetails;
use App\Http\Livewire\EventsProperties;
use App\Http\Livewire\Faqs;
use App\Http\Livewire\GeneralSearch;
use App\Http\Livewire\GuestHouseDetails;
use App\Http\Livewire\GuestHouseList;
use App\Http\Livewire\HelpCenter;
use App\Http\Livewire\LoginPage;
use App\Http\Livewire\RegisterPage;
use App\Http\Livewire\RentalCarSingle;
use App\Http\Livewire\TaxiDetails;
use App\Http\Livewire\TaxiListComponent;
use App\Http\Livewire\TermsAndConditionsComponent;
use App\Http\Livewire\Logout;
use App\Http\Livewire\PendingBookings;
use App\Http\Livewire\Properties;
use App\Http\Livewire\PropertyTypes;
use App\Http\Livewire\Regions;
use App\Http\Livewire\RegisterAmbulance;
use App\Http\Livewire\RegisterCars;
use App\Http\Livewire\RegisterProperty;
use App\Http\Livewire\TourismProperties;
use App\Http\Livewire\Tourismsitedetails;
use App\Http\Livewire\TourismSites;
use App\Http\Livewire\TourPackageDetails;
use App\Http\Livewire\TourPackages;
use Illuminate\Support\Facades\Route;


/*Route::get('/', function () {
    return view('welcome'); homeguests
});*/
//Route::post('/contactus','ContactPage@sendemail')->name('contact-page.send');
//Route::post('/send',[ContactPage::class,'send'])->name('send_email');
Route::get('/',HomePage::class);
Route::get('/contactus',ContactPage::class)->name('contactus');
Route::get('/ambulance',AmbulancePage::class);
Route::get('/cars',CarsPage::class);
Route::get('/taxis',TaxisPAge::class);
Route::get('/blog',BlogPage::class);
Route::get('/homeguests',HomeGuestHouses::class)->name('homeguests');
Route::get('/HotelSearch',HotelSearch::class)->name('HotelSearch');
Route::get('/generalSearchs',GeneralSearch::class)->name('generalSearchs');
Route::get('/HotelSearch/fetch_data',HotelSearch::class,'fetch_data');
Route::get('/CarSearch',CarSearch::class)->name('CarSearch');
Route::get('/TaxiSearch',TaxiSearch::class)->name('TaxiSearch');
Route::get('/AmbulanceSearch',AmbulanceSearch::class)->name('AmbulanceSearch');
Route::get('/HomeGuestSearch',HomeGuestSearch::class)->name('HomeGuestSearch');
Route::get('/BlogDetail',BlogDetailComponent::class)->name('BlogDetail');
Route::get('/HotelSingle',HotelSingle::class)->name('HotelSingle');
Route::get('/privacypolicy',PrivacyPolicyPage::class)->name('privacypolicy');
Route::get('/DestinationsList',DetinationsListComponent::class)->name('DestinationsList');
Route::get('/TermsAndConditions',TermsAndConditionsComponent::class)->name('TermsAndConditions');
Route::get('/CarDetails',CarRentalDetails::class)->name('CarDetails');
Route::get('/CarsList',CarsListComponent::class)->name('CarsList');
Route::get('/TaxisList',TaxiListComponent::class)->name('TaxisList');
Route::get('/TaxiDetails',TaxiDetails::class)->name('TaxiDetails');
Route::get('/Login',LoginPage::class)->name('Login');
Route::get('/RegisterProperty',RegisterProperty::class)->name('RegisterProperty');
Route::get('/RegisterCars',RegisterCars::class)->name('RegisterCars');
Route::get('/RegisterAmbulance',RegisterAmbulance::class)->name('RegisterAmbulance');
//Route::post('/clientlogout',Logout::class)->name('clientlogout');
Route::get('/clientlogout',[LoginPage::class,'logouts'])->name('clientlogout');
Route::get('/Register',RegisterPage::class)->name('Register');
Route::get('/AmbulanceDetails',AmbulancedetailsComponent::class)->name('AmbulanceDetails');
Route::get('/AmbulanceList',AmbulanceListComponent::class)->name('AmbulanceList');
Route::get('/HomeGuestsList',GuestHouseList::class)->name('HomeGuestsList');
Route::get('/HomeGuestSingle',GuestHouseDetails::class)->name('HomeGuestSingle');
Route::get('/CompleteBooking',CompleteBooking::class)->name('CompleteBooking');
Route::get('/BookingSuccess',BookingSuccess::class)->name('BookingSuccess');
Route::get('/CompleteAmbulanceBooking',CompleteAmbulanceBooking::class)->name('CompleteAmbulanceBooking');
Route::get('/CompleteCarBooking',CompleteCarRentalBooking::class)->name('CompleteCarBooking');
Route::get('/CompleteTaxiBooking',CompleteTaxiBooking::class)->name('CompleteTaxiBooking');
Route::get('/CompleteHomeGuestBooking',CompleteHomeGuestBooking::class)->name('CompleteHomeGuestBooking');
Route::get('/PendingBookings',PendingBookings::class)->name('PendingBookings');
Route::get('/Bookings',Bookings::class)->name('Bookings');
Route::get('/Regions',Regions::class)->name('Regions');
Route::get('/Cities',Cities::class)->name('Cities');
Route::get('/Properties',Properties::class)->name('Properties');
Route::get('/PropertyTypes',PropertyTypes::class)->name('PropertyTypes');
Route::get('/TourismSites',TourismSites::class)->name('TourismSites');
Route::get('/TourismProperties',TourismProperties::class)->name('TourismProperties');
Route::get('/Events',Events::class)->name('Events');
Route::get('/EventsProperties',EventsProperties::class)->name('EventsProperties');
Route::get('/TourismSiteDetails',Tourismsitedetails::class)->name('TourismSiteDetails');
Route::get('/EventsDetails',EventsDetails::class)->name('EventsDetails');
Route::get('/TourPackages',TourPackages::class)->name('TourPackages');
Route::get('/TourPackageDetails',TourPackageDetails::class)->name('TourPackageDetails');
Route::get('/Faqs',Faqs::class)->name('Faqs');
Route::get('/HelpCenter',HelpCenter::class)->name('HelpCenter');
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
