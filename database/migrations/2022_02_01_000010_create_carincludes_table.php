<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateCarincludesTable extends Migration
{
    public function up()
    {
        // Idempotent: an existing table (e.g. the production database) is left as it is.
        if (Schema::hasTable('carincludes')) {
            return;
        }

        Schema::create('carincludes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('AllDegreeCamera')->nullable();
            $table->string('AllWheelDrive')->nullable();
            $table->string('AudioInput')->nullable();
            $table->string('BackCamera')->nullable();
            $table->string('Bluetooth')->nullable();
            $table->string('Car')->nullable();
            $table->string('Company')->nullable();
            $table->string('FMRadio')->nullable();
            $table->string('GPSNavigation')->nullable();
            $table->string('PopupRoof')->nullable();
            $table->string('SunRoof')->nullable();
            $table->string('TV')->nullable();
            $table->string('USBInput')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carincludes');
    }
}
