<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Email verification and password-reset codes for customers.
 * Codes are stored hashed; accounts already approved count as verified.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Idempotent: only missing columns are added; approved accounts are marked verified once.
        $hadVerifiedColumn = Schema::hasColumn('registration', 'email_verified_at');
        $columns = [
            'email_verified_at' => fn (Blueprint $t) => $t->timestamp('email_verified_at')->nullable()->after('Email'),
            'verification_code' => fn (Blueprint $t) => $t->string('verification_code')->nullable(),
            'verification_expires_at' => fn (Blueprint $t) => $t->timestamp('verification_expires_at')->nullable(),
            'password_reset_code' => fn (Blueprint $t) => $t->string('password_reset_code')->nullable(),
            'password_reset_expires_at' => fn (Blueprint $t) => $t->timestamp('password_reset_expires_at')->nullable(),
            'code_attempts' => fn (Blueprint $t) => $t->unsignedTinyInteger('code_attempts')->default(0),
        ];
        $missing = array_filter($columns, fn ($_, $name) => ! Schema::hasColumn('registration', $name), ARRAY_FILTER_USE_BOTH);

        if ($missing !== []) {
            Schema::table('registration', function (Blueprint $table) use ($missing) {
                foreach ($missing as $add) {
                    $add($table);
                }
            });
        }

        if (! $hadVerifiedColumn) {
            DB::table('registration')->where('ApprovalStatus', 'Approved')->update(['email_verified_at' => now()]);
        }
    }

    public function down(): void
    {
        Schema::table('registration', function (Blueprint $table) {
            $table->dropColumn(['email_verified_at', 'verification_code', 'verification_expires_at',
                'password_reset_code', 'password_reset_expires_at', 'code_attempts']);
        });
    }
};
