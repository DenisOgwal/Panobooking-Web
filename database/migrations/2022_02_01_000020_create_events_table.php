<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateEventsTable extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('City')->nullable();
            $table->string('Country')->nullable();
            $table->text('Description')->nullable();
            $table->date('EndDate')->nullable();
            $table->string('EventName')->nullable();
            $table->string('PhotoUri', 500)->nullable();
            $table->text('SearchKeyWords')->nullable();
            $table->text('ShortDescription')->nullable();
            $table->date('StartDate')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
}
