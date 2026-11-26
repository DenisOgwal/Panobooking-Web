<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateAmbulancesTable extends Migration
{
    public function up()
    {
        Schema::create('ambulances', function (Blueprint $table) {
            $table->increments('IDs');
            $table->string('AmbulanceCategory')->nullable();
            $table->string('Approved')->nullable();
            $table->string('Availability')->nullable();
            $table->text('CarSpec')->nullable();
            $table->string('City')->nullable();
            $table->string('CurrentLocation')->nullable();
            $table->string('DriverName')->nullable();
            $table->string('EmailAddress')->nullable();
            $table->string('Offered')->nullable();
            $table->string('Password')->nullable();
            $table->string('PhotoUri', 500)->nullable();
            $table->decimal('Price', 15, 2)->nullable();
            $table->decimal('Ratings', 3, 1)->nullable();
            $table->decimal('Repayments', 15, 2)->nullable();
            $table->text('SearchTerms')->nullable();
            $table->string('TaxiNo')->nullable();
            $table->string('TelephoneNumber')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ambulances');
    }
}
