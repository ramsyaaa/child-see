<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
            $table->string('question_code', 100)->nullable();
            $table->longText('question');
            $table->enum('question_type', ['yes_no','single_choice','multiple_choice','scale','text','number'])->default('yes_no');
            $table->text('helper_text')->nullable();
            $table->decimal('weight', 8, 2)->default(1);
            $table->boolean('is_required')->default(true);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('questionnaires'); }
};
