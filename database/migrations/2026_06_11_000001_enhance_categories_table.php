<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Extend type enum to include fisik, mental, majemuk
        DB::statement("ALTER TABLE categories MODIFY COLUMN type
            ENUM('intelektual','sensorik','emosional','akademik','kombinasi','fisik','mental','majemuk')
            NOT NULL DEFAULT 'intelektual'");

        Schema::table('categories', function (Blueprint $table) {
            $table->string('group', 100)->nullable()->after('type');
            $table->string('result_illustration', 500)->nullable()->after('icon');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['group', 'result_illustration']);
        });

        DB::statement("ALTER TABLE categories MODIFY COLUMN type
            ENUM('intelektual','sensorik','emosional','akademik','kombinasi')
            NOT NULL DEFAULT 'intelektual'");
    }
};
