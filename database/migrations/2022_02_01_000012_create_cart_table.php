<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateCartTable extends Migration
{
    public function up()
    {
        // Idempotent: an existing table (e.g. the production database) is left as it is.
        if (Schema::hasTable('cart')) {
            return;
        }

        Schema::create('cart', function (Blueprint $table) {
            $table->increments('IDs');
            $table->string('BookingType')->nullable();
            $table->string('Branch')->nullable();
            $table->text('ConfirmReason')->nullable();
            $table->string('Confirmed')->default('Pending');
            $table->decimal('Cost', 15, 2)->nullable();
            $table->decimal('Credit', 15, 2)->nullable();
            $table->string('Currency')->nullable();
            $table->decimal('CurrencyAmount', 15, 2)->nullable();
            $table->timestamp('Date')->nullable()->useCurrent();
            $table->string('DatesFrom')->nullable();
            $table->string('DatesTo')->nullable();
            $table->decimal('Debit', 15, 2)->nullable();
            $table->text('Descriptions')->nullable();
            $table->string('Facility')->nullable();
            $table->string('OrderCode')->nullable();
            $table->string('ProductName')->nullable();
            $table->integer('Quantity')->nullable();
            $table->string('User')->nullable();
            $table->string('UserNames')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart');
    }
}
