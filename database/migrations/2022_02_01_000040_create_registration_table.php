<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateRegistrationTable extends Migration
{
    public function up()
    {
        Schema::create('registration', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ApprovalStatus')->nullable();
            $table->string('Approved')->nullable();
            $table->string('Email')->nullable();
            $table->string('FullNames')->nullable();
            $table->string('Password')->nullable();
            $table->string('SelectedCurrency')->nullable();
            $table->string('Telephone')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('registration');
    }
}
