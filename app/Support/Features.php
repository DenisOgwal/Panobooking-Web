<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Turns Yes/No feature rows (room features, popular amenities, car extras, ambulance
 * equipment) into human-readable label => value lists.
 */
class Features
{
    /** Column names that don't headline nicely (or are misspelt in the schema). */
    private const LABELS = [
        'BreakFast' => 'Breakfast',
        'Wifi' => 'Wi-Fi',
        'FreeWifi' => 'Free Wi-Fi',
        'TeaMaker' => 'Tea/coffee maker',
        'Forex' => 'Currency exchange',
        'NoSmokingRoom' => 'Non-smoking rooms',
        'FitnessCenter' => 'Fitness centre',
        'BathTub' => 'Bathtub',
        'MiniBar' => 'Minibar',
        'FlatScreen' => 'Flat-screen TV',
        'HotTub' => 'Hot tub',
        'BathRobe' => 'Bathrobe',
        'MosquitoNet' => 'Mosquito net',
        'WakeupService' => 'Wake-up service',
        'GardensView' => 'Garden view',
        'AttachedBathroom' => 'Private bathroom',
        'Socket' => 'Power socket by the bed',
        'Paramdics' => 'Paramedics on board',
        'SunctionUnit' => 'Suction unit',
        'SpinalBoad' => 'Spinal board',
        'ECGMonitor' => 'ECG monitor',
        'AllDegreeCamera' => '360° camera',
        'GPSNavigation' => 'GPS navigation',
        'FMRadio' => 'FM radio',
        'USBInput' => 'USB input',
        'TV' => 'TV',
    ];

    private const IGNORE = ['id', 'IDs', 'created_at', 'updated_at', 'PropertyName', 'roomid', 'RoomName', 'Feet',
        'Car', 'Company', 'Ambulance', 'MiscelaniousItems'];

    public static function label(string $column): string
    {
        return self::LABELS[$column] ?? Str::ucfirst(Str::lower(Str::headline($column)));
    }

    public static function isOn($value): bool
    {
        return $value === true || (is_string($value) && strtolower(trim($value)) === 'yes');
    }

    /**
     * @param  array<string>  $only  limit to these columns (in this order)
     * @return array<string, mixed>  label => raw value
     */
    public static function fromModel(?Model $model, array $only = [], array $except = []): array
    {
        if (! $model) {
            return [];
        }

        $attributes = $model->getAttributes();
        $columns = $only ?: array_keys($attributes);

        $out = [];
        foreach ($columns as $column) {
            if (in_array($column, self::IGNORE, true) || in_array($column, $except, true) || ! array_key_exists($column, $attributes)) {
                continue;
            }
            $out[self::label($column)] = $attributes[$column];
        }

        return $out;
    }

    /** Only the features that are switched on. */
    public static function enabled(?Model $model, array $only = [], array $except = []): array
    {
        return array_filter(self::fromModel($model, $only, $except), [self::class, 'isOn']);
    }
}
