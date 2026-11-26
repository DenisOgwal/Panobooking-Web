<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateHouserulesTable extends Migration
{
    public function up()
    {
        Schema::create('houserules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('AgeRestriction')->nullable();
            $table->string('CardAcceptability')->nullable();
            $table->string('CheckIn')->nullable();
            $table->string('CheckOut')->nullable();
            $table->text('PaymentCancelation')->nullable();
            $table->string('Pets')->nullable();
            $table->string('PropertyName')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('houserules');
    }
}
