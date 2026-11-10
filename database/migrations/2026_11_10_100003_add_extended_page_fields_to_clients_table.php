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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->string('tagline')->nullable()->after('slug');
            $table->string('banner_image')->nullable()->after('logo');
            $table->string('location')->nullable()->after('website_url');
            $table->string('project_year')->nullable()->after('location');
            $table->string('client_type')->nullable()->after('project_year');

            // Detailed Story & Services for Frontend Client Page
            $table->longText('about')->nullable()->after('description');
            $table->text('challenge')->nullable()->after('about');
            $table->text('solution')->nullable()->after('challenge');
            $table->json('services')->nullable()->after('solution');

            // Impact Metrics (Stats on Frontend Client Page)
            $table->string('stat_1_value')->nullable()->after('services');
            $table->string('stat_1_label')->nullable()->after('stat_1_value');
            $table->string('stat_2_value')->nullable()->after('stat_1_label');
            $table->string('stat_2_label')->nullable()->after('stat_2_value');
            $table->string('stat_3_value')->nullable()->after('stat_2_label');
            $table->string('stat_3_label')->nullable()->after('stat_3_value');

            // Testimonial & Review
            $table->text('testimonial_quote')->nullable()->after('stat_3_label');
            $table->string('testimonial_author')->nullable()->after('testimonial_quote');
            $table->string('testimonial_position')->nullable()->after('testimonial_author');
            $table->string('testimonial_avatar')->nullable()->after('testimonial_position');

            // Media & Gallery
            $table->json('gallery')->nullable()->after('testimonial_avatar');
            $table->string('video_url')->nullable()->after('gallery');

            // SEO Metadata
            $table->string('meta_title')->nullable()->after('video_url');
            $table->text('meta_description')->nullable()->after('meta_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'tagline',
                'banner_image',
                'location',
                'project_year',
                'client_type',
                'about',
                'challenge',
                'solution',
                'services',
                'stat_1_value',
                'stat_1_label',
                'stat_2_value',
                'stat_2_label',
                'stat_3_value',
                'stat_3_label',
                'testimonial_quote',
                'testimonial_author',
                'testimonial_position',
                'testimonial_avatar',
                'gallery',
                'video_url',
                'meta_title',
                'meta_description',
            ]);
        });
    }
};
