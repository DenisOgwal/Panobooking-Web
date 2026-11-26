<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateReasonstostayatpropertyTable extends Migration
{
    public function up()
    {
        Schema::create('reasonstostayatproperty', function (Blueprint $table) {
            $table->increments('id');
            $table->string('PropertyName')->nullable();
            $table->text('Reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reasonstostayatproperty');
    }
}
