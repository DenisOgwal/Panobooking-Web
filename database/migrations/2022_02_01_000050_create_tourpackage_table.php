<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateTourpackageTable extends Migration
{
    public function up()
    {
        Schema::create('tourpackage', function (Blueprint $table) {
            $table->increments('IDs');
            $table->string('City')->nullable();
            $table->text('DestinationsVisted')->nullable();
            $table->string('EmailAddress')->nullable();
            $table->text('ExtraActivites')->nullable();
            $table->decimal('PackageFees2', 15, 2)->nullable();
            $table->string('PackageName')->nullable();
            $table->string('PhotoUri', 500)->nullable();
            $table->text('SearchTerms')->nullable();
            $table->text('ServicesOffered')->nullable();
            $table->string('TravelCompany')->nullable();
            $table->date('TravelDate')->nullable();
            $table->string('TravelHours')->nullable();
            $table->integer('TripDays')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tourpackage');
    }
}
