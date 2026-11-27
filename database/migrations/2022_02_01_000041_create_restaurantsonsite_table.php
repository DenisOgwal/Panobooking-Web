<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateRestaurantsonsiteTable extends Migration
{
    public function up()
    {
        // Idempotent: an existing table (e.g. the production database) is left as it is.
        if (Schema::hasTable('restaurantsonsite')) {
            return;
        }

        Schema::create('restaurantsonsite', function (Blueprint $table) {
            $table->increments('id');
            $table->text('Food')->nullable();
            $table->string('OpenFor')->nullable();
            $table->string('PropertyName')->nullable();
            $table->string('RestaurantName')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('restaurantsonsite');
    }
}
