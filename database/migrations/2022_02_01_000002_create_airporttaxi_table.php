<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateAirporttaxiTable extends Migration
{
    public function up()
    {
        // Idempotent: an existing table (e.g. the production database) is left as it is.
        if (Schema::hasTable('airporttaxi')) {
            return;
        }

        Schema::create('airporttaxi', function (Blueprint $table) {
            $table->increments('IDs');
            $table->string('Automatic')->nullable();
            $table->string('Availability')->nullable();
            $table->string('CarCategory')->nullable();
            $table->text('CarSpec')->nullable();
            $table->string('CurrentLocation')->nullable();
            $table->text('Description')->nullable();
            $table->string('Doors')->nullable();
            $table->string('DriverName')->nullable();
            $table->string('EmailAddress')->nullable();
            $table->string('Laggages')->nullable();
            $table->string('Offered')->nullable();
            $table->string('Passengers')->nullable();
            $table->string('PhotoUri', 500)->nullable();
            $table->decimal('Price', 15, 2)->nullable();
            $table->decimal('Ratings', 3, 1)->nullable();
            $table->text('SearchTerms')->nullable();
            $table->string('TaxiNo')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('airporttaxi');
    }
}
