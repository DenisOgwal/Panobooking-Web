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
        // Idempotent: an existing table (e.g. the production database) is left as it is.
        if (Schema::hasTable('reasonstostayatproperty')) {
            return;
        }

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
