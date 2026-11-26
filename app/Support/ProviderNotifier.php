<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Notifies providers (hotels, drivers, tour companies) through the legacy
 * panobooking.com/SendEmails/*.php scripts.
 *
 * Disabled outside production by default, so local and test bookings never reach the
 * live site; when disabled the notification is logged instead. Failures never break the
 * booking flow.
 *
 * Note: the scripts only accept GET, so booking details (including customer names) travel
 * in the query string and can end up in web-server logs. Moving them to POST needs a
 * matching change on the server side.
 */
class ProviderNotifier
{
    public function send(string $script, array $params): bool
    {
        $url = config('panobooking.notifications.base_url') . '/' . ltrim($script, '/');

        if (! config('panobooking.notifications.enabled')) {
            $this->log('info', 'Provider notification not sent (disabled)', ['script' => $script, 'params' => array_keys($params)]);

            return false;
        }

        try {
            return Http::timeout(5)->get($url, $params)->successful();
        } catch (Throwable $e) {
            $this->log('warning', 'Provider notification failed', ['script' => $script, 'error' => $e->getMessage()]);

            return false;
        }
    }

    /** Logging must never break the booking that triggered it (e.g. an unwritable log file). */
    private function log(string $level, string $message, array $context): void
    {
        try {
            Log::log($level, $message, $context);
        } catch (Throwable) {
            // ignore
        }
    }
}
