<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'featured_image_alt',
        'featured_image_caption',
        'category_id',
        'author_id',
        'author_name',
        'tags',
        'status',
        'is_featured',
        'views_count',
        'reading_time',
        'published_at',
        // SEO
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'robots_index',
        'robots_follow',
        // OpenGraph
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        // Twitter
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        // Structured data & custom tags
        'schema_markup',
        'extra_meta_tags',
    ];

    protected $casts = [
        'tags' => 'array',
        'schema_markup' => 'array',
        'extra_meta_tags' => 'array',
        'robots_index' => 'boolean',
        'robots_follow' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'reading_time' => 'integer',
    ];

    protected $appends = [
        'seo',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($blog) {
            // Auto generate slug if empty
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }

            // Auto calculate reading time based on average 200 words per minute
            if (!empty($blog->content)) {
                $wordCount = str_word_count(strip_tags($blog->content));
                $blog->reading_time = max(1, (int) ceil($wordCount / 200));
            }

            // Auto set published_at when published
            if ($blog->status === 'published' && empty($blog->published_at)) {
                $blog->published_at = now();
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Complete SEO meta bundle structured for React Helmet / Head manager
     */
    public function getSeoAttribute(): array
    {
        $metaTitle = $this->meta_title ?: $this->title;
        $metaDesc = $this->meta_description ?: ($this->excerpt ?: Str::limit(strip_tags($this->content), 160));
        $ogImage = $this->og_image ?: $this->featured_image;

        $robots = [];
        $robots[] = $this->robots_index !== false ? 'index' : 'noindex';
        $robots[] = $this->robots_follow !== false ? 'follow' : 'nofollow';
        $robotsString = implode(', ', $robots);

        // Standard Article Schema for SEO
        $defaultSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $this->title,
            'description' => $metaDesc,
            'image' => $ogImage ? [$ogImage] : [],
            'datePublished' => $this->published_at?->toIso8601String(),
            'dateModified' => $this->updated_at?->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $this->author_name ?: ($this->author?->name ?: 'Admin'),
            ],
        ];

        return [
            'title' => $metaTitle,
            'description' => $metaDesc,
            'keywords' => $this->meta_keywords,
            'canonical' => $this->canonical_url,
            'robots' => $robotsString,
            'openGraph' => [
                'title' => $this->og_title ?: $metaTitle,
                'description' => $this->og_description ?: $metaDesc,
                'image' => $ogImage,
                'type' => $this->og_type ?: 'article',
                'publishedTime' => $this->published_at?->toIso8601String(),
                'modifiedTime' => $this->updated_at?->toIso8601String(),
                'section' => $this->category?->name,
                'tags' => $this->tags ?: [],
            ],
            'twitter' => [
                'card' => $this->twitter_card ?: 'summary_large_image',
                'title' => $this->twitter_title ?: ($this->og_title ?: $metaTitle),
                'description' => $this->twitter_description ?: ($this->og_description ?: $metaDesc),
                'image' => $this->twitter_image ?: $ogImage,
            ],
            'schema' => !empty($this->schema_markup) ? $this->schema_markup : $defaultSchema,
            'extraMeta' => $this->extra_meta_tags ?: [],
        ];
    }
}

