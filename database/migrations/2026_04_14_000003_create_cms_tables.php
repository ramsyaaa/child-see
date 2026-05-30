<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One row per page slug
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 60)->unique();   // home, about, classes, instructors, bundles, schedule
            $table->string('title', 120);
            $table->string('seo_title', 160)->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->string('seo_keywords', 320)->nullable();
            $table->string('og_image', 255)->nullable();
            $table->timestamps();
        });

        // Named sections within a page, each holds a JSON content blob
        Schema::create('cms_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_page_id')->constrained('cms_pages')->cascadeOnDelete();
            $table->string('section_key', 80);   // hero, about_snapshot, marquee, cta …
            $table->string('label', 120);         // human-readable label for the editor
            $table->json('content');              // flexible key→value store
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['cms_page_id', 'section_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_sections');
        Schema::dropIfExists('cms_pages');
    }
};
