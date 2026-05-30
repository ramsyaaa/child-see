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
        Schema::create('payment_gateway_configs', function (Blueprint $table) {
            $table->id();
            $table->boolean('offline_enabled')->default(true);
            $table->boolean('mayar_enabled')->default(false);
            $table->timestamps();
        });
        
        // Insert default configuration
        DB::table('payment_gateway_configs')->insert([
            'offline_enabled' => true,
            'mayar_enabled' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateway_configs');
    }
};

