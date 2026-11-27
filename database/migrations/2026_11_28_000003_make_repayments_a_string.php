<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * `Repayments` holds the payment method a provider accepts ("Cash", "Mobile Money"…), as the
 * car and ambulance sign-up forms have always written it. The reconstructed schema declared it
 * decimal, which makes MySQL reject those forms. Store it as text, like production does.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (['carrentals', 'ambulances'] as $table) {
            // Idempotent: skip when the column is missing or already text.
            if (! Schema::hasColumn($table, 'Repayments')
                || in_array(Schema::getColumnType($table, 'Repayments'), ['varchar', 'char', 'text', 'string'], true)) {
                continue;
            }
            Schema::table($table, function (Blueprint $t) {
                $t->string('Repayments', 60)->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        // Not reversible without losing the text values.
    }
};
