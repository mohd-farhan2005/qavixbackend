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
        Schema::create('seo_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_name'); // e.g. "Home Page", "About Us", "Contact"
            $table->string('path')->unique(); // e.g. "/", "/about", "/services", "/contact", "/portfolio"
            
            // Standard SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('robots')->default('index, follow'); // e.g. "index, follow" or "noindex, nofollow"

            // OpenGraph (Facebook, LinkedIn, etc.)
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type')->default('website');

            // Twitter Cards
            $table->string('twitter_card')->default('summary_large_image');
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();

            // Schema.org Structured Data (JSON-LD)
            $table->json('schema_markup')->nullable();

            // Custom Arbitrary Meta Tags [{ "name": "author", "content": "Qavix" }, { "property": "fb:app_id", "content": "..." }]
            $table->json('extra_meta_tags')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_pages');
    }
};

