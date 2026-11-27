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
        // Idempotent: an existing table (e.g. the production database) is left as it is.
        if (Schema::hasTable('childandextrabed')) {
            return;
        }

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
