<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'industry_id',
        'name',
        'slug',
        'tagline',
        'logo',
        'banner_image',
        'website_url',
        'location',
        'project_year',
        'client_type',
        'description',
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
        'featured',
        'order',
        'is_active',
    ];

    protected $casts = [
        'industry_id' => 'integer',
        'featured' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
        'services' => 'array',
        'gallery' => 'array',
    ];

    protected $appends = [
        'logo_url',
        'banner_image_url',
        'testimonial_avatar_url',
    ];

    /**
     * Boot model events
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function (Client $client) {
            if (empty($client->slug) && !empty($client->name)) {
                $baseSlug = Str::slug($client->name);
                $slug = $baseSlug;
                $count = 1;
                while (static::where('slug', $slug)->where('id', '!=', $client->id ?? 0)->exists()) {
                    $slug = "{$baseSlug}-{$count}";
                    $count++;
                }
                $client->slug = $slug;
            }
        });
    }

    /**
     * Absolute URL for client logo
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (empty($this->logo)) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0d0f0d&color=22c55e&size=256&bold=true';
        }

        if (str_starts_with($this->logo, 'http://') || str_starts_with($this->logo, 'https://')) {
            return $this->logo;
        }

        return secure_asset('storage/' . ltrim($this->logo, '/'));
    }

    /**
     * Absolute URL for client hero/banner image
     */
    public function getBannerImageUrlAttribute(): ?string
    {
        if (empty($this->banner_image)) {
            return null;
        }

        if (str_starts_with($this->banner_image, 'http://') || str_starts_with($this->banner_image, 'https://')) {
            return $this->banner_image;
        }

        return secure_asset('storage/' . ltrim($this->banner_image, '/'));
    }

    /**
     * Absolute URL for client testimonial avatar
     */
    public function getTestimonialAvatarUrlAttribute(): ?string
    {
        if (empty($this->testimonial_avatar)) {
            return !empty($this->testimonial_author)
                ? 'https://ui-avatars.com/api/?name=' . urlencode($this->testimonial_author) . '&background=0d0f0d&color=22c55e&size=160'
                : null;
        }

        if (str_starts_with($this->testimonial_avatar, 'http://') || str_starts_with($this->testimonial_avatar, 'https://')) {
            return $this->testimonial_avatar;
        }

        return secure_asset('storage/' . ltrim($this->testimonial_avatar, '/'));
    }

    /**
     * Industry relationship
     */
    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('id', 'asc');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
}
