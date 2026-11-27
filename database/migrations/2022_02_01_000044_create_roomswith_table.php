<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateRoomswithTable extends Migration
{
    public function up()
    {
        // Idempotent: an existing table (e.g. the production database) is left as it is.
        if (Schema::hasTable('roomswith')) {
            return;
        }

        Schema::create('roomswith', function (Blueprint $table) {
            $table->increments('id');
            $table->string('CityView')->nullable();
            $table->string('GardenView')->nullable();
            $table->string('LakeView')->nullable();
            $table->string('LandMarkView')->nullable();
            $table->string('MountainView')->nullable();
            $table->string('PoolView')->nullable();
            $table->string('PropertyName')->nullable();
            $table->string('StreetView')->nullable();
            $table->string('TerraceView')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('roomswith');
    }
}
