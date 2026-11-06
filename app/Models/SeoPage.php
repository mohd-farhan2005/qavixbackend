<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_name',
        'path',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'robots',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'schema_markup',
        'extra_meta_tags',
        'is_active',
    ];

    protected $casts = [
        'schema_markup' => 'array',
        'extra_meta_tags' => 'array',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'seo',
    ];

    /**
     * Complete SEO meta bundle structured for React Helmet / Head manager
     */
    public function getSeoAttribute(): array
    {
        $metaTitle = $this->meta_title ?: $this->page_name;
        $metaDesc = $this->meta_description;

        return [
            'pageName' => $this->page_name,
            'path' => $this->path,
            'title' => $metaTitle,
            'description' => $metaDesc,
            'keywords' => $this->meta_keywords,
            'canonical' => $this->canonical_url,
            'robots' => $this->robots ?: 'index, follow',
            'openGraph' => [
                'title' => $this->og_title ?: $metaTitle,
                'description' => $this->og_description ?: $metaDesc,
                'image' => $this->og_image,
                'type' => $this->og_type ?: 'website',
            ],
            'twitter' => [
                'card' => $this->twitter_card ?: 'summary_large_image',
                'title' => $this->twitter_title ?: ($this->og_title ?: $metaTitle),
                'description' => $this->twitter_description ?: ($this->og_description ?: $metaDesc),
                'image' => $this->twitter_image ?: $this->og_image,
            ],
            'schema' => $this->schema_markup,
            'extraMeta' => $this->extra_meta_tags ?: [],
        ];
    }
}

