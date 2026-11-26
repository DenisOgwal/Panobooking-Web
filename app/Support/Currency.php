<?php

namespace App\Support;

use App\Models\CurrencySelect;
use App\Models\UserRegistrationModel;
use Illuminate\Support\Facades\Cache;

/**
 * Display currency for the current visitor.
 *
 * All prices are stored in UGX. The `currency` table holds how many UGX one unit of each
 * other currency is worth (CurrencyValue). The visitor's choice lives in the session; for
 * logged-in customers it is also saved to `registration.SelectedCurrency`.
 *
 * Rounding matches the original site: UGX amounts are shown as-is, converted amounts are
 * rounded up to the next whole unit.
 */
class Currency
{
    public const BASE = 'UGX';
    public const SESSION_KEY = 'currency';
    private const CACHE_TTL = 600;

    /** Currently selected code, falling back to UGX for unknown values. */
    public function code(): string
    {
        $code = session(self::SESSION_KEY);

        if ($code === null && app(CustomerAuth::class)->check()) {
            $code = UserRegistrationModel::where('Email', app(CustomerAuth::class)->email())->value('SelectedCurrency');
            $this->remember($code);
        }

        return $this->isAvailable($code) ? $code : self::BASE;
    }

    /** UGX per one unit of $code. */
    public function rate(?string $code = null): float
    {
        $code ??= $this->code();

        return $this->rates()[$code] ?? 1.0;
    }

    /** Codes the visitor can pick, base currency first. */
    public function available(): array
    {
        return array_keys($this->rates());
    }

    public function isAvailable(?string $code): bool
    {
        return $code !== null && array_key_exists($code, $this->rates());
    }

    /** Convert a UGX amount into the selected currency. */
    public function convert(float|int|string|null $ugx, ?string $code = null): float
    {
        $code ??= $this->code();
        $amount = (float) $ugx;

        if ($code === self::BASE) {
            return $amount;
        }

        return ceil($amount / $this->rate($code));
    }

    /** e.g. "UGX 180,000" or "USD 49". */
    public function format(float|int|string|null $ugx, ?string $code = null): string
    {
        $code ??= $this->code();

        return $code . ' ' . number_format($this->convert($ugx, $code), 0);
    }

    /** Change the visitor's currency (and persist it for logged-in customers). */
    public function set(string $code): void
    {
        if (! $this->isAvailable($code)) {
            return;
        }

        $this->remember($code);

        $auth = app(CustomerAuth::class);
        if ($auth->check()) {
            UserRegistrationModel::where('Email', $auth->email())->update(['SelectedCurrency' => $code]);
        }
    }

    /** Store a code in the session without touching the database. */
    public function remember(?string $code): void
    {
        if ($this->isAvailable($code)) {
            session()->put(self::SESSION_KEY, $code);
        }
    }

    /** @return array<string, float> code => UGX per unit, UGX first */
    private function rates(): array
    {
        return Cache::remember('currency.rates', self::CACHE_TTL, function () {
            $rates = CurrencySelect::query()
                ->whereNotNull('CurrencyValue')
                ->where('CurrencyValue', '>', 0)
                ->pluck('CurrencyValue', 'CurrencyName')
                ->map(fn ($v) => (float) $v)
                ->all();

            return [self::BASE => 1.0] + $rates;
        });
    }
}
