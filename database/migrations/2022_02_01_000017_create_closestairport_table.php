<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateClosestairportTable extends Migration
{
    public function up()
    {
        Schema::create('closestairport', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Airport')->nullable();
            $table->string('Distance')->nullable();
            $table->string('PropertyName')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('closestairport');
    }
}
