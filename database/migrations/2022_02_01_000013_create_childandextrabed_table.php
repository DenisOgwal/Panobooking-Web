<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateChildandextrabedTable extends Migration
{
    public function up()
    {
        Schema::create('childandextrabed', function (Blueprint $table) {
            $table->increments('id');
            $table->text('ExtraCondition')->nullable();
            $table->string('PropertyName')->nullable();
            $table->string('YearsRange')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('childandextrabed');
    }
}
