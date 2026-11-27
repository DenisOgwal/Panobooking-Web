<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateViewTable extends Migration
{
    public function up()
    {
        // Idempotent: an existing table (e.g. the production database) is left as it is.
        if (Schema::hasTable('view')) {
            return;
        }

        Schema::create('view', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Amenity')->nullable();
            $table->string('PropertyName')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('view');
    }
}
