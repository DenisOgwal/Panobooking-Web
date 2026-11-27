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
        Schema::table('registration', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable()->after('Email');
            $table->string('verification_code')->nullable();
            $table->timestamp('verification_expires_at')->nullable();
            $table->string('password_reset_code')->nullable();
            $table->timestamp('password_reset_expires_at')->nullable();
            $table->unsignedTinyInteger('code_attempts')->default(0);
        });

        DB::table('registration')->where('ApprovalStatus', 'Approved')->update(['email_verified_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('registration', function (Blueprint $table) {
            $table->dropColumn(['email_verified_at', 'verification_code', 'verification_expires_at',
                'password_reset_code', 'password_reset_expires_at', 'code_attempts']);
        });
    }
};
