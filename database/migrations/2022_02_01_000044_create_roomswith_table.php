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
