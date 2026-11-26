# Panobooking Web

Booking site for stays (hotels, lodges, home stays), rental cars, airport taxis, ambulances, tour packages, tourism sites and events in Uganda.

**Stack:** Laravel 13 · Livewire 4 · PHP 8.4 · MySQL 8 · Bootstrap 5.3 (Sass) · Vite · Swiper · GLightbox · Bootstrap Icons

## Quick start (Docker)

```bash
docker compose up -d --build                     # app, MySQL 8, phpMyAdmin
docker compose exec app php artisan db:seed --force   # fictional sample data
```

| Service    | URL                    | Notes                                             |
|------------|------------------------|---------------------------------------------------|
| Site       | http://localhost:8000  | demo login: `demo@panobooking.test` / `password123` |
| phpMyAdmin | http://localhost:8081  | root / root                                       |
| MySQL      | `localhost:3307`       | `panobooking` / `secret`                          |

On start the container waits for MySQL, runs migrations and keeps `APP_KEY` stable across rebuilds (stored in the storage volume). The seeder empties the tables it fills, so re-running it resets the sample data.

## Tests

```bash
docker compose exec -u www-data app php artisan test
```

Tests use a separate `panobooking_test` database (created by `docker/db-init/01-test-database.sql`) and refuse to run against any database whose name does not end in `_test`.

## Front-end

```bash
npm install
npm run dev     # Vite dev server with hot reload
npm run build   # production assets into public/build (the Docker image builds these itself)
```

- `resources/scss/_tokens.scss`: design tokens (brand colours, type, radii, shadows, spacing) feeding Bootstrap and `--pb-*` CSS variables
- `resources/scss/_base|_shell|_hero|_cards|_listing.scss`: site components. Views use these classes and Bootstrap utilities, not inline styles
- `resources/js/app.js`: Bootstrap, card rails (Swiper), photo lightbox, toasts for Livewire `alert` events and flashed messages

## Code map

| Area | Where |
|------|-------|
| Layout, header, footer | `resources/views/components/layouts/app.blade.php`, `components/site-header.blade.php`, `site-footer.blade.php` (data from `App\View\Composers\FooterComposer`, cached) |
| UI components | `resources/views/components/*`: `listing-card`, `price`, `rating`, `gallery`, `search-panel`, `card-rail`, `field`, `page-hero`, `amenity-list`, `empty-state` |
| Stays | `HotelSingle`, `GuestHouseDetails` → `livewire/partials/property-detail`; lists/search → `partials/stay-results` |
| Cars, taxis, ambulances | `App\Support\Vehicles` (config) + `App\Livewire\Vehicles\Vehicle{Landing,Results,Detail,Booking}` |
| Bookings | `CompleteBooking`, `CompleteHomeGuestBooking`, `Complete{CarRental,Taxi,Ambulance}Booking`, `TourPackageDetails` → `cart` table (`Confirmed` = `Pending` until the provider confirms) |
| Customer login | `App\Support\CustomerAuth` (session key `pano_client_data`); inline sign-in via `Concerns\SignsInInline` |
| Currency | `App\Support\Currency`: prices are stored in UGX, `currency.CurrencyValue` = UGX per unit; `<x-price :amount="…">` renders in the visitor's currency |
| Query scopes | `Models\Concerns\HasListingScopes` (`approved`, `available`, `offered`, `trending`, `featured`, grouped `search` / `searchAny`) |
| Static copy | `resources/views/livewire/content/*.blade.php` (FAQs, help centre, privacy policy, terms) |

## Configuration (`config/panobooking.php`)

| Env var | Default | Purpose |
|---------|---------|---------|
| `PANOBOOKING_MEDIA_URL` | `https://panobooking.com` | Host that listing photos are served from (`/AndroidFiles/...`, `/AndroidDriver/...`) |
| `PASSWORD_UPGRADE` | `true` | Legacy MD5 customer passwords are re-hashed with bcrypt at login; new accounts use bcrypt |
| `PROVIDER_NOTIFICATIONS` | on only when `APP_ENV=production` | Call the `panobooking.com/SendEmails/*.php` scripts after bookings; otherwise the call is just logged |
| `PROVIDER_NOTIFICATIONS_URL` | `https://www.panobooking.com/SendEmails` | Base URL of those scripts |

## Known limitations

- The database schema was not in the original repository; migrations `2022_02_01_*` were reconstructed from how the code uses each table. Compare them with production before running them there.
- Provider notifications are GET requests with booking details (including the customer's name) in the query string, because the existing scripts only accept GET.
- Driver passwords for cars and ambulances are still MD5, because the separate driver app checks them.
- Photo uploads are written to the local disk (`config/filesystems.php`), under the same paths the media host serves.
