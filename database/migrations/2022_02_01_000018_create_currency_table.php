<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateCurrencyTable extends Migration
{
    public function up()
    {
        Schema::create('currency', function (Blueprint $table) {
            $table->increments('id');
            $table->string('CurrencyName')->nullable();
            $table->decimal('CurrencyValue', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('currency');
    }
}
