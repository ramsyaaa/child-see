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
        Schema::table('batch_classes', function (Blueprint $table) {
            // Gender restriction fields
            $table->boolean('is_have_gender_restriction')->default(false)->after('status');
            $table->enum('gender_restriction', ['Women', 'All Gender', 'Men'])->nullable()->after('is_have_gender_restriction');

            // Age restriction fields
            $table->boolean('is_have_age_restriction')->default(false)->after('gender_restriction');
            $table->string('age_restriction', 100)->nullable()->after('is_have_age_restriction')->comment('e.g., "18+", "13-17", "All Ages"');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batch_classes', function (Blueprint $table) {
            $table->dropColumn([
                'is_have_gender_restriction',
                'gender_restriction',
                'is_have_age_restriction',
                'age_restriction'
            ]);
        });
    }
};
