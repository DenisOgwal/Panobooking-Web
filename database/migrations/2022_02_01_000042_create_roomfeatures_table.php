<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateRoomfeaturesTable extends Migration
{
    public function up()
    {
        // Idempotent: an existing table (e.g. the production database) is left as it is.
        if (Schema::hasTable('roomfeatures')) {
            return;
        }

        Schema::create('roomfeatures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('AirConditioning')->nullable();
            $table->string('AlarmClock')->nullable();
            $table->string('AttachedBathroom')->nullable();
            $table->string('Balcony')->nullable();
            $table->string('BathRobe')->nullable();
            $table->string('BathTub')->nullable();
            $table->string('Carpeted')->nullable();
            $table->string('CityView')->nullable();
            $table->string('Desk')->nullable();
            $table->string('Dryer')->nullable();
            $table->string('ElectricKettle')->nullable();
            $table->string('Fan')->nullable();
            $table->string('Feet')->nullable();
            $table->string('FlatScreen')->nullable();
            $table->string('FreeWifi')->nullable();
            $table->string('GardensView')->nullable();
            $table->string('HairDryer')->nullable();
            $table->string('HotTub')->nullable();
            $table->string('KingBed')->nullable();
            $table->string('LakeView')->nullable();
            $table->string('Linens')->nullable();
            $table->string('MiniBar')->nullable();
            $table->string('MosquitoNet')->nullable();
            $table->string('PoolView')->nullable();
            $table->string('QueenBed')->nullable();
            $table->string('Refrigerator')->nullable();
            $table->string('RoomName')->nullable();
            $table->string('Safe')->nullable();
            $table->string('SittingArea')->nullable();
            $table->string('Slippers')->nullable();
            $table->string('Socket')->nullable();
            $table->string('TeaMaker')->nullable();
            $table->string('Telephone')->nullable();
            $table->string('Toilet')->nullable();
            $table->string('Toiletries')->nullable();
            $table->string('Towels')->nullable();
            $table->string('TwinBed')->nullable();
            $table->string('WakeupService')->nullable();
            $table->string('Wardrobe')->nullable();
            $table->integer('roomid')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('roomfeatures');
    }
}
