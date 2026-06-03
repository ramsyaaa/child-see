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
        // Fakta Unik (unique facts)
        Schema::create('landing_facts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('body')->nullable();
            $table->string('icon', 100)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tim Pengembang (development team)
        Schema::create('landing_team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('role_label', 150)->nullable();
            $table->string('affiliation', 255)->nullable();
            $table->enum('group', ['dosen', 'mahasiswa', 'eksternal'])->default('dosen');
            $table->string('photo', 500)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // HKI Paten info
        Schema::create('landing_hki', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('image', 500)->nullable();
            $table->string('certificate_number', 150)->nullable();
            $table->date('issued_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Partners
        Schema::create('landing_partners', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('logo', 500)->nullable();
            $table->string('website_url', 500)->nullable();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_facts');
        Schema::dropIfExists('landing_team_members');
        Schema::dropIfExists('landing_hki');
        Schema::dropIfExists('landing_partners');
    }
};
