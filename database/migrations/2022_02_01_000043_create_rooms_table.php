<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateRoomsTable extends Migration
{
    public function up()
    {
        // Idempotent: an existing table (e.g. the production database) is left as it is.
        if (Schema::hasTable('rooms')) {
            return;
        }

        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('IDs');
            $table->string('Availability')->nullable();
            $table->string('BreakFast')->nullable();
            $table->decimal('Cost', 15, 2)->nullable();
            $table->string('HotelName')->nullable();
            $table->integer('NoOfRooms')->nullable();
            $table->string('Offered')->nullable();
            $table->string('PhotoUrl', 500)->nullable();
            $table->decimal('Price', 15, 2)->nullable();
            $table->text('RefundPolicy')->nullable();
            $table->text('RoomDesc')->nullable();
            $table->string('RoomName')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
