<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->string('assessment_code', 100)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('child_id')->constrained('children')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->dateTime('assessment_date');
            $table->decimal('total_score', 8, 2)->default(0);
            $table->string('result_label')->nullable();
            $table->longText('result_description')->nullable();
            $table->longText('recommendation')->nullable();
            $table->string('severity_level', 20)->nullable();
            $table->string('color', 20)->nullable();
            $table->enum('status', ['draft','submitted','completed'])->default('draft');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('assessments'); }
};
