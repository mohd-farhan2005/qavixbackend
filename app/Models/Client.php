<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'industry_id',
        'name',
        'logo',
        'website_url',
        'description',
        'featured',
        'order',
        'is_active',
    ];

    protected $casts = [
        'industry_id' => 'integer',
        'featured' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected $appends = [
        'logo_url',
    ];

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
