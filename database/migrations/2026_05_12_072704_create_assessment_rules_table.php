<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('assessment_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('domain_id')->nullable()->constrained()->nullOnDelete();
            $table->string('label');
            $table->enum('severity_level', ['normal','light','medium','heavy'])->default('normal');
            $table->decimal('min_score', 8, 2)->default(0);
            $table->decimal('max_score', 8, 2)->default(100);
            $table->longText('description')->nullable();
            $table->longText('recommendation')->nullable();
            $table->string('color', 20)->default('#5C477F');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('assessment_rules'); }
};
