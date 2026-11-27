<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateAmbulanceaccessoriesTable extends Migration
{
    public function up()
    {
        // Idempotent: an existing table (e.g. the production database) is left as it is.
        if (Schema::hasTable('ambulanceaccessories')) {
            return;
        }

        Schema::create('ambulanceaccessories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Ambulance')->nullable();
            $table->string('CervicalCollar')->nullable();
            $table->string('ECGMonitor')->nullable();
            $table->string('Haemoglucometer')->nullable();
            $table->string('JumpBag')->nullable();
            $table->string('MedicationBag')->nullable();
            $table->text('MiscelaniousItems')->nullable();
            $table->string('Paramdics')->nullable();
            $table->string('SpinalBoad')->nullable();
            $table->string('SunctionUnit')->nullable();
            $table->string('Ventilator')->nullable();
            $table->string('WheeledCot')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ambulanceaccessories');
    }
}
