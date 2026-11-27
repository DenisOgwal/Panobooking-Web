<?php

return [

    /*
    | Base URL that listing photos are served from. Images are looked up by listing name,
    | e.g. {media_url}/AndroidFiles/DestinationImages/{Place}.jpg
    */
    'media_url' => rtrim(env('PANOBOOKING_MEDIA_URL', 'https://panobooking.com'), '/'),

    /*
    | Disk that uploaded photos are written to (config/filesystems.php → disks.media).
    | A photo present on this disk is served locally and takes precedence over media_url.
    */
    'media_disk' => env('PANOBOOKING_MEDIA_DISK', 'media'),

    /*
    | Customer password hashing.
    |
    | Customer passwords have historically been stored as unsalted MD5. Login accepts both
    | MD5 and bcrypt. The mobile app does not verify passwords itself (it relays the web
    | result), so upgrading is on by default.
    |
    | true:  new registrations use bcrypt and MD5 passwords are re-hashed on next login.
    | false: keep writing MD5 (only needed if some other system compares MD5 hashes in
    |        `registration.Password` directly).
    */
    'password_upgrade' => (bool) env('PASSWORD_UPGRADE', true),

    /*
    | Booking notifications to providers via the legacy panobooking.com/SendEmails scripts.
    | Off unless APP_ENV=production (or PROVIDER_NOTIFICATIONS=true), so development and
    | test bookings never hit the live site.
    */
    'notifications' => [
        'enabled' => (bool) env('PROVIDER_NOTIFICATIONS', env('APP_ENV') === 'production'),
        'base_url' => rtrim(env('PROVIDER_NOTIFICATIONS_URL', 'https://www.panobooking.com/SendEmails'), '/'),
    ],

];
