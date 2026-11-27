<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreatePickuplocationsTable extends Migration
{
    public function up()
    {
        // Idempotent: an existing table (e.g. the production database) is left as it is.
        if (Schema::hasTable('pickuplocations')) {
            return;
        }

        Schema::create('pickuplocations', function (Blueprint $table) {
            $table->increments('IDs');
            $table->string('Approved')->nullable();
            $table->string('BedBreakFast')->nullable();
            $table->string('City')->nullable();
            $table->text('Comment')->nullable();
            $table->string('ContactNo')->nullable();
            $table->string('Country')->nullable();
            $table->string('CreditCard')->nullable();
            $table->text('Description')->nullable();
            $table->string('EmailAddress')->nullable();
            $table->text('ExactLocation')->nullable();
            $table->integer('FaturedRank')->nullable();
            $table->string('Featured')->nullable();
            $table->string('Parking')->nullable();
            $table->string('PhotoUrl', 500)->nullable();
            $table->string('Place')->nullable();
            $table->string('PlaceType')->nullable();
            $table->string('Prepayment')->nullable();
            $table->text('PriceSpecifications')->nullable();
            $table->decimal('Prices', 15, 2)->nullable();
            $table->decimal('Rating', 3, 1)->nullable();
            $table->text('SearchTerms')->nullable();
            $table->string('TaxesInclusive')->nullable();
            $table->integer('TrendRank')->nullable();
            $table->string('Trending')->nullable();
            $table->string('coordinates')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pickuplocations');
    }
}
