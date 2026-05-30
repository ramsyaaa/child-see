<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('assessment_domain_scores', function (Blueprint $table) {
            $table->string('severity_level')->nullable()->after('result_description');
            $table->string('color')->nullable()->after('severity_level');
        });
    }

    public function down(): void
    {
        Schema::table('assessment_domain_scores', function (Blueprint $table) {
            $table->dropColumn(['severity_level', 'color']);
        });
    }
};
