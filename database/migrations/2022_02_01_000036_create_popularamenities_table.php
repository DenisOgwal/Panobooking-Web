<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreatePopularamenitiesTable extends Migration
{
    public function up()
    {
        // Idempotent: an existing table (e.g. the production database) is left as it is.
        if (Schema::hasTable('popularamenities')) {
            return;
        }

        Schema::create('popularamenities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Bar')->nullable();
            $table->string('BreakFast')->nullable();
            $table->string('FitnessCenter')->nullable();
            $table->string('Forex')->nullable();
            $table->string('NoSmokingRoom')->nullable();
            $table->string('PropertyName')->nullable();
            $table->string('Restaurant')->nullable();
            $table->string('Shuttle')->nullable();
            $table->string('Spa')->nullable();
            $table->string('SwimmingPool')->nullable();
            $table->string('TeaMaker')->nullable();
            $table->string('Wifi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('popularamenities');
    }
}
