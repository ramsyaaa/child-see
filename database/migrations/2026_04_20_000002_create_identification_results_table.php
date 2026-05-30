<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('identification_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // penglihatan, pendengaran, intelektual
            $table->json('answers');
            $table->unsignedTinyInteger('score'); // 0-100
            $table->string('level'); // low, mid, high
            $table->string('child_name');
            $table->string('child_dob')->nullable();
            $table->unsignedTinyInteger('child_age')->nullable();
            $table->string('filler_name');
            $table->string('filler_status'); // Guru, Orang Tua
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('identification_results');
    }
};
