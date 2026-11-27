<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateTaxitripTable extends Migration
{
    public function up()
    {
        // Idempotent: an existing table (e.g. the production database) is left as it is.
        if (Schema::hasTable('taxitrip')) {
            return;
        }

        Schema::create('taxitrip', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Froms')->nullable();
            $table->string('Tos')->nullable();
            $table->decimal('Price', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('taxitrip');
    }
}
