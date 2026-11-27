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

## Admin backend

http://localhost:8000/admin, with a separate sign-in from customers. Sample data accounts: `developer@`, `admin@` and `staff@panobooking.test` (password `password123`).

**Deployment accounts.** Every container start runs `AdminAccountsSeeder` after the migrations. It creates an administrator and a developer from these variables:

| Variable | Purpose |
|----------|---------|
| `ADMIN_EMAIL`, `ADMIN_PASSWORD` (optional `ADMIN_NAME`) | the administrator account |
| `DEVELOPER_EMAIL`, `DEVELOPER_PASSWORD` (optional `DEVELOPER_NAME`) | the developer account |

- It is safe to repeat: an account is created only when its email is new, and an existing account keeps its password.
- An account whose variables are missing is skipped, never created with a default password.
- `docker-compose.yml` sets local defaults (`admin@` / `developer@panobooking.test`, `password123`). Set real values in production.

Create or update other accounts, or reset a password:

```bash
docker compose exec app php artisan panobooking:admin you@example.com --role=admin   # asks for the password
```

| Role | Can |
|------|-----|
| `staff` | view, add and edit every record, approve listings, customers, comments and testimonials, confirm or decline bookings |
| `admin` | all of that, plus delete records, manage currencies and admin users (except developer accounts) |
| `developer` | all of that, plus **Developer → Database migrations**, and creating or editing developer accounts |

**Migrations are idempotent.** Each migration checks what already exists (tables, columns, indexes) and skips it. You can run them repeatedly, or against a database that already has the tables, such as production, where they are simply recorded as run.

Developers can run them from the admin (Database migrations page: status, SQL preview, run pending) or with `php artisan migrate --force`. The container runs them on every start.

- Every table the site reads is a **resource** in `app/Admin/Resources/*` (columns, form fields, filters, approval column, photo type, Yes/No feature table). `App\Admin\ResourceRegistry` lists them, and the generic `Livewire\Admin\ResourceIndex` / `ResourceForm` render any of them. To manage a new table, add a resource class and register it.
- Amenities, policies and surroundings (about 30 tables keyed by `PropertyName`) are edited at `/admin/properties/{hotel|guesthouse}/{id}/details` (`App\Admin\PropertyDetailSections`).
- Renaming a property, room, car or ambulance moves its name-keyed rows (rooms, amenities, `carincludes`, `ambulanceaccessories`) and its photo files with it.
- Bookings: `/admin/bookings`. A booking is `Pending`, then `Confirmed` or `Declined`. The note is shown on the customer's booking history.
- Photos are written to the upload disk under the media host's paths (`App\Admin\MediaFiles`), and the photo-count column is kept in step.
- Driver-app passwords for cars and ambulances are stored as MD5 (see Known limitations). Customer passwords use `CustomerAuth::hash()`.

## Deploying to Hostinger

Deploys are manual: GitHub **Actions → Deploy to Hostinger → Run workflow** (`.github/workflows/deploy-hostinger.yml`). Each run tests, builds and uploads over SSH, then runs the idempotent migrations and creates the admin and developer accounts. Setup, including the SSH key, GitHub secrets and the server `.env`, and rollback steps are in [docs/DEPLOY-HOSTINGER.md](docs/DEPLOY-HOSTINGER.md).

## Mobile API

JSON API for the mobile app at **`/api/v1`**, covering everything the website does. Interactive docs are at http://localhost:8000/api/docs, served from `docs/openapi.yaml`; keep that file in step with `routes/api.php`, which `tests/Feature/Api/OpenApiSpecTest` checks.

**Sign-in and accounts**
- Sign-in uses Laravel Sanctum. `POST /auth/login` (email, password, device_name) returns a token, which the app sends as `Authorization: Bearer <token>`.
- `POST /auth/register` emails a 6-digit code. The customer can sign in after `POST /auth/verify-email`, or once an admin approves them in Admin → Customers, which also marks them verified.
- Unapproving a customer revokes their tokens.
- Forgotten passwords are reset with an emailed code (`/auth/forgot-password`, `/auth/reset-password`).
- The website uses the same flow: `/Register` → `/VerifyEmail` (code, or the one-click link in the email), and `/ForgotPassword`.

**Using the API**
- Currency: send `X-Currency: USD`, or `?currency=`. Every price comes back as `{ugx, amount, currency, formatted}`.
- Bookings: `POST /bookings/{room|home-stay|car|taxi|ambulance|tour}/{id}`. `POST /bookings/quote/...` and `GET /bookings/form/...` work without signing in.
- Rate limits: 120 requests/min overall; 6/min per email for sign-in and code endpoints; 10/min for public forms.

**Where the shared logic lives**

| What | Where |
|------|-------|
| Booking rules, prices and cart rows, used by both the website and the API | `app/Bookings/Types/*` and `App\Bookings\BookingService` |
| "List with us" forms | `app/Listings/*` |
| Sign-up, verification and password reset | `App\Support\CustomerAccounts` |
| API controllers and JSON resources | `app/Http/Controllers/Api/V1/*`, `app/Http/Resources/V1/*` |

**Email (needed for verification codes)**
- Locally, `MAIL_MAILER=log`, so codes appear in `storage/logs/laravel.log`.
- In production, set `MAIL_MAILER=smtp` plus `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS` and `MAIL_FROM_NAME`.
- `SANCTUM_EXPIRATION` (minutes) makes tokens expire. By default they last until sign-out.

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

- The database schema was not in the original repository; migrations `2022_02_01_*` were reconstructed from how the code uses each table.
  - On a database that already has a table, its migration skips it, so production tables are never recreated or altered.
  - Columns the code expects but production lacks are not added by those create migrations. Compare the schemas once.
- Provider notifications are GET requests with booking details (including the customer's name) in the query string, because the existing scripts only accept GET.
- Driver passwords for cars and ambulances are still MD5, because the separate driver app checks them.
- Photo uploads (admin, "list with us" forms, API) go to the `media` disk, `storage/app/media` by default (`PANOBOOKING_MEDIA_ROOT`), under the same paths the media host uses (`AndroidFiles/…`, `AndroidDriver/…`).
  - A photo found there is served by the site at `/media/…` and takes precedence.
  - Anything else still loads from `PANOBOOKING_MEDIA_URL`.
  - Destination (city) photos for the home page mosaic are uploaded in Admin → Destinations. Until a city has one, its tile shows a colour gradient.
