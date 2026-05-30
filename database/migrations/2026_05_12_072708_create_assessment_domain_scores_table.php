<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('assessment_domain_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_score', 8, 2)->default(0);
            $table->string('result_label')->nullable();
            $table->longText('result_description')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('assessment_domain_scores'); }
};
