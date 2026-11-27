<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Indexes for the columns the site filters and joins on.
 *
 * Property detail tables join to hotels / guest houses by name (PropertyName), so a hotel
 * page with ~30 eager-loaded relations was 30 full table scans without these.
 */
return new class extends Migration
{
    /** Tables joined to a property via their PropertyName column. */
    private const PROPERTY_DETAIL_TABLES = [
        'accessibility', 'bathroom', 'bedroom', 'businessfacilities', 'childandextrabed', 'childrenpolicies',
        'cleaning', 'closestairport', 'foodanddrink', 'houserules', 'kitchen', 'mediaandtechnology',
        'naturalbeauty', 'nearbymarkets', 'nearbyplaces', 'nearbyrestaurants', 'nearbysupermarkets', 'outdoor',
        'parking', 'popularamenities', 'propertydescription', 'publictransport', 'reasonstostayatproperty',
        'restaurantsonsite', 'roomswith', 'security', 'spa', 'topattractions', 'transportation', 'view',
    ];

    /** table => list of index column sets */
    private function indexes(): array
    {
        $indexes = [
            'pickuplocations' => [['Place'], ['Approved', 'Trending', 'TrendRank'], ['Approved', 'Featured', 'FaturedRank'], ['City'], ['PlaceType']],
            'homeguesthouses' => [['GuestHouseName'], ['City']],
            'rooms' => [['HotelName'], ['Availability'], ['Offered']],
            'roomfeatures' => [['roomid']],
            'registration' => [['Email']],
            'cart' => [['User', 'Confirmed']],
            'carrentals' => [['Approved', 'CarCategory'], ['TaxiNo']],
            'carincludes' => [['Car', 'Company']],
            'airporttaxi' => [['CarCategory']],
            'ambulances' => [['Approved', 'AmbulanceCategory'], ['TaxiNo']],
            'ambulanceaccessories' => [['Ambulance']],
            'blogcomments' => [['blog_model_id', 'Approval']],
            'clientfeedback' => [['Approved']],
            'currency' => [['CurrencyName']],
            'destinations' => [['DestinationID'], ['Country', 'Region']],
            'taxitrip' => [['Froms', 'Tos']],
            'events' => [['EndDate']],
            'tourpackage' => [['TravelDate']],
        ];

        foreach (self::PROPERTY_DETAIL_TABLES as $table) {
            $indexes[$table] = [['PropertyName']];
        }

        return $indexes;
    }

    private function name(string $table, array $columns): string
    {
        return strtolower('idx_' . $table . '_' . implode('_', $columns));
    }

    /**
     * Idempotent: an index that already exists is skipped, and so is one whose table or columns
     * are missing (the production schema may differ from the reconstructed one).
     */
    public function up(): void
    {
        foreach ($this->indexes() as $table => $sets) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            $sets = array_values(array_filter($sets, fn (array $columns) => Schema::hasColumns($table, $columns)
                && ! Schema::hasIndex($table, $this->name($table, $columns))));
            if ($sets === []) {
                continue;
            }
            Schema::table($table, function (Blueprint $t) use ($table, $sets) {
                foreach ($sets as $columns) {
                    $t->index($columns, $this->name($table, $columns));
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->indexes() as $table => $sets) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            $sets = array_values(array_filter($sets, fn (array $columns) => Schema::hasIndex($table, $this->name($table, $columns))));
            if ($sets === []) {
                continue;
            }
            Schema::table($table, function (Blueprint $t) use ($table, $sets) {
                foreach ($sets as $columns) {
                    $t->dropIndex($this->name($table, $columns));
                }
            });
        }
    }
};
