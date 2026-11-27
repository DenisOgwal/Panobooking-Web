<?php

use App\Http\Controllers\Api\V1;
use App\Http\Middleware\EnsureCustomerActive;
use Illuminate\Support\Facades\Route;

/*
| Mobile API v1, served at /api/v1 (see App\Providers\RouteServiceProvider).
| Documentation: /api/docs (docs/openapi.yaml). Every route here must appear in the spec;
| tests/Feature/Api/OpenApiSpecTest checks that.
*/

$vehicle = 'car|taxi|ambulance';
$booking = 'room|home-stay|car|taxi|ambulance|tour';

// Catalogue (public)
Route::get('/meta', [V1\CatalogueController::class, 'meta'])->name('meta');
Route::get('/home', [V1\CatalogueController::class, 'home'])->name('home');
Route::get('/search', [V1\CatalogueController::class, 'search'])->name('search');
Route::get('/regions', [V1\CatalogueController::class, 'regions'])->name('regions');
Route::get('/cities', [V1\CatalogueController::class, 'cities'])->name('cities');
Route::get('/cities/{city}', [V1\CatalogueController::class, 'city'])->name('cities.show');

Route::get('/stays', [V1\StaysController::class, 'index'])->name('stays.index');
Route::get('/stays/{id}', [V1\StaysController::class, 'show'])->whereNumber('id')->name('stays.show');
Route::get('/rooms/{id}', [V1\StaysController::class, 'room'])->whereNumber('id')->name('rooms.show');
Route::get('/home-stays', [V1\StaysController::class, 'homeStays'])->name('home-stays.index');
Route::get('/home-stays/{id}', [V1\StaysController::class, 'homeStay'])->whereNumber('id')->name('home-stays.show');

Route::get('/vehicles/{type}', [V1\VehiclesController::class, 'index'])->where('type', $vehicle)->name('vehicles.index');
Route::get('/vehicles/{type}/categories', [V1\VehiclesController::class, 'categories'])->where('type', $vehicle)->name('vehicles.categories');
Route::get('/vehicles/{type}/{id}', [V1\VehiclesController::class, 'show'])->where('type', $vehicle)->whereNumber('id')->name('vehicles.show');
Route::get('/taxi-trips', [V1\VehiclesController::class, 'taxiTrips'])->name('taxi-trips');

Route::get('/tour-packages', [V1\ExperiencesController::class, 'tours'])->name('tour-packages.index');
Route::get('/tour-packages/{id}', [V1\ExperiencesController::class, 'tour'])->whereNumber('id')->name('tour-packages.show');
Route::get('/tourism-sites', [V1\ExperiencesController::class, 'sites'])->name('tourism-sites.index');
Route::get('/tourism-sites/{id}', [V1\ExperiencesController::class, 'site'])->whereNumber('id')->name('tourism-sites.show');
Route::get('/events', [V1\ExperiencesController::class, 'events'])->name('events.index');
Route::get('/events/{id}', [V1\ExperiencesController::class, 'event'])->whereNumber('id')->name('events.show');
Route::get('/blog', [V1\ExperiencesController::class, 'posts'])->name('blog.index');
Route::get('/blog/{id}', [V1\ExperiencesController::class, 'post'])->whereNumber('id')->name('blog.show');

// Booking forms and prices (public, so the app can show a price before sign-in)
Route::get('/bookings/form/{type}/{id}', [V1\BookingsController::class, 'form'])->where('type', $booking)->whereNumber('id')->name('bookings.form');
Route::post('/bookings/quote/{type}/{id}', [V1\BookingsController::class, 'quote'])->where('type', $booking)->whereNumber('id')->name('bookings.quote');

// Public forms
Route::middleware('throttle:api-forms')->group(function () {
    Route::post('/contact', [V1\FormsController::class, 'contact'])->name('contact');
    Route::post('/testimonials', [V1\FormsController::class, 'testimonial'])->name('testimonials');
    Route::post('/blog/{id}/comments', [V1\FormsController::class, 'comment'])->whereNumber('id')->name('blog.comments');
    Route::post('/listings/{kind}', [V1\FormsController::class, 'listing'])->where('kind', 'property|car|ambulance')->name('listings');
});

// Accounts
Route::middleware('throttle:api-auth')->prefix('auth')->name('auth.')->group(function () {
    Route::post('/register', [V1\AuthController::class, 'register'])->name('register');
    Route::post('/verify-email', [V1\AuthController::class, 'verifyEmail'])->name('verify-email');
    Route::post('/resend-verification', [V1\AuthController::class, 'resendVerification'])->name('resend-verification');
    Route::post('/login', [V1\AuthController::class, 'login'])->name('login');
    Route::post('/forgot-password', [V1\AuthController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('/reset-password', [V1\AuthController::class, 'resetPassword'])->name('reset-password');
});

// Signed in (Authorization: Bearer <token>)
Route::middleware(['auth:sanctum', EnsureCustomerActive::class])->group(function () use ($booking) {
    Route::post('/auth/logout', [V1\AuthController::class, 'logout'])->name('auth.logout');

    Route::get('/me', [V1\MeController::class, 'show'])->name('me');
    Route::patch('/me', [V1\MeController::class, 'update'])->name('me.update');
    Route::put('/me/password', [V1\MeController::class, 'password'])->name('me.password');
    Route::delete('/me/tokens', [V1\MeController::class, 'logoutEverywhere'])->name('me.tokens');

    Route::get('/bookings', [V1\BookingsController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [V1\BookingsController::class, 'show'])->whereNumber('id')->name('bookings.show');
    Route::delete('/bookings/{id}', [V1\BookingsController::class, 'cancel'])->whereNumber('id')->name('bookings.cancel');
    Route::post('/bookings/{type}/{id}', [V1\BookingsController::class, 'store'])->where('type', $booking)->whereNumber('id')->name('bookings.store');
});
