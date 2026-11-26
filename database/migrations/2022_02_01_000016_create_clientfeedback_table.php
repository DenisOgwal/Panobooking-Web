<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateClientfeedbackTable extends Migration
{
    public function up()
    {
        Schema::create('clientfeedback', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Approved')->nullable();
            $table->string('ClientCompany')->nullable();
            $table->string('ClientEmail')->nullable();
            $table->string('ClientJob')->nullable();
            $table->string('ClientName')->nullable();
            $table->text('FeedBack')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientfeedback');
    }
}
