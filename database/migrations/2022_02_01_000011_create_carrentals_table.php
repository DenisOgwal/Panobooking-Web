<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateCarrentalsTable extends Migration
{
    public function up()
    {
        Schema::create('carrentals', function (Blueprint $table) {
            $table->increments('IDs');
            $table->string('Approved')->nullable();
            $table->string('Automatic')->nullable();
            $table->string('Availability')->nullable();
            $table->string('CarCategory')->nullable();
            $table->text('CarSpec')->nullable();
            $table->string('City')->nullable();
            $table->string('Company')->nullable();
            $table->string('CurrentLocation')->nullable();
            $table->text('Description')->nullable();
            $table->string('Doors')->nullable();
            $table->integer('DriverAge')->nullable();
            $table->string('DriverName')->nullable();
            $table->string('EmailAddress')->nullable();
            $table->string('Laggages')->nullable();
            $table->string('Offered')->nullable();
            $table->string('Passengers')->nullable();
            $table->string('Password')->nullable();
            $table->string('PhotoUri', 500)->nullable();
            $table->decimal('Price', 15, 2)->nullable();
            $table->decimal('PricePerDay', 15, 2)->nullable();
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
        Schema::dropIfExists('carrentals');
    }
}
