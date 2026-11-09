<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'title_separator',
        'default_meta_title',
        'default_meta_description',
        'default_meta_keywords',
        'default_og_image',
        'twitter_handle',
        'google_site_verification',
        'bing_site_verification',
        'custom_head_scripts',
        'custom_body_scripts',
        'sitemap_enabled',
    ];

    protected $casts = [
        'sitemap_enabled' => 'boolean',
    ];

    public static function instance(): self
    {
        return static::firstOrCreate([], [
            'site_name' => 'Qavyx',
            'title_separator' => ' | ',
            'default_meta_title' => 'Qavyx - Modern Digital Solutions',
            'default_meta_description' => 'Qavyx delivers high quality web development, digital solutions and technology consulting.',
            'default_meta_keywords' => 'qavyx, web development, react, laravel, seo',
            'sitemap_enabled' => true,
        ]);
    }
}

