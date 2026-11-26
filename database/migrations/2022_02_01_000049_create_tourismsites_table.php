<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateTourismsitesTable extends Migration
{
    public function up()
    {
        Schema::create('tourismsites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('City')->nullable();
            $table->string('Country')->nullable();
            $table->text('Description')->nullable();
            $table->string('PhotoUri', 500)->nullable();
            $table->text('SearchKeyWords')->nullable();
            $table->text('ShortDescription')->nullable();
            $table->string('SiteName')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tourismsites');
    }
}
