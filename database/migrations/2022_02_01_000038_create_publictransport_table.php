<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreatePublictransportTable extends Migration
{
    public function up()
    {
        Schema::create('publictransport', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Distance')->nullable();
            $table->string('PropertyName')->nullable();
            $table->string('TransportName')->nullable();
            $table->string('TransportType')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('publictransport');
    }
}
