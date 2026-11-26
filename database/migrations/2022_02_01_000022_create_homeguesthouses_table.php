<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateHomeguesthousesTable extends Migration
{
    public function up()
    {
        Schema::create('homeguesthouses', function (Blueprint $table) {
            $table->increments('IDs');
            $table->string('Availability')->nullable();
            $table->string('BedBreakFast')->nullable();
            $table->string('City')->nullable();
            $table->text('Coordinates')->nullable();
            $table->string('CreditCard')->nullable();
            $table->text('Description')->nullable();
            $table->string('EmailAddress')->nullable();
            $table->text('ExactLocation')->nullable();
            $table->string('GuestHouseName')->nullable();
            $table->integer('NoOfRooms')->nullable();
            $table->string('Offered')->nullable();
            $table->string('Parking')->nullable();
            $table->string('PhotoUrl', 500)->nullable();
            $table->string('Place')->nullable();
            $table->decimal('Prices', 15, 2)->nullable();
            $table->decimal('Rating', 3, 1)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('homeguesthouses');
    }
}
