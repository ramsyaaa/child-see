<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Extend role enum to include ORGANIZATION
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('SUPERADMIN','ADMIN','MEMBER','ORGANIZATION') NOT NULL DEFAULT 'MEMBER'");

        Schema::table('users', function (Blueprint $table) {
            $table->string('organization_name', 255)->nullable()->after('phone');
            $table->string('organization_type', 100)->nullable()->after('organization_name');
            $table->unsignedSmallInteger('child_quota')->default(1)->after('organization_type');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['organization_name', 'organization_type', 'child_quota']);
        });
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('SUPERADMIN','ADMIN','MEMBER') NOT NULL DEFAULT 'MEMBER'");
    }
};
