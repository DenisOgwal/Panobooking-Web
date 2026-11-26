<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateHomeguestlocationsTable extends Migration
{
    public function up()
    {
        Schema::create('homeguestlocations', function (Blueprint $table) {
            $table->increments('IDs');
            $table->string('City')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('homeguestlocations');
    }
}
