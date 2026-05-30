<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Rename password_hash to password if it exists
        if (Schema::hasColumn('users', 'password_hash') && !Schema::hasColumn('users', 'password')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('password_hash', 'password');
            });
        }

        // Rename phone_number to phone if it exists
        if (Schema::hasColumn('users', 'phone_number') && !Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('phone_number', 'phone');
            });
        }

        // Add name column if it doesn't exist
        if (!Schema::hasColumn('users', 'name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('name')->nullable()->after('id');
            });
        }

        // Update name column from full_name if name is empty
        DB::statement("UPDATE users SET name = full_name WHERE name IS NULL OR name = ''");

        // Add username column if it doesn't exist
        if (!Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username')->unique()->nullable()->after('email');
            });
        }

        // Note: We're keeping the existing role and status ENUM values as they are
        // The User model will handle case-insensitive role checking
        // If you need to change ENUM values, do it manually in the database
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't reverse this migration as it might break existing data
    }
};

