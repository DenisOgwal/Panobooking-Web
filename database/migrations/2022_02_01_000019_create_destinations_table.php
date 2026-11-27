<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateDestinationsTable extends Migration
{
    public function up()
    {
        // Idempotent: an existing table (e.g. the production database) is left as it is.
        if (Schema::hasTable('destinations')) {
            return;
        }

        Schema::create('destinations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('City')->nullable();
            $table->string('Country')->nullable();
            $table->integer('DestinationID')->nullable();
            $table->string('Region')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('destinations');
    }
}
