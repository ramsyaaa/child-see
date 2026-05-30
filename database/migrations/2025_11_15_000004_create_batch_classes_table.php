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
        Schema::create('batch_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_class_id')->constrained('master_classes')->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained('instructors')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('price', 10, 2)->comment('Drop-in price');
            $table->integer('capacity');
            $table->integer('remaining_slots');
            $table->enum('status', ['active', 'cancelled'])->default('active');
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['date', 'start_time']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_classes');
    }
};

