<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateAccessibilityTable extends Migration
{
    public function up()
    {
        Schema::create('accessibility', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Amenity')->nullable();
            $table->string('PropertyName')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('accessibility');
    }
}
