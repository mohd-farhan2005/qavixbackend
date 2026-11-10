<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Industry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (Industry $industry) {
            if (empty($industry->slug)) {
                $baseSlug = Str::slug($industry->name);
                $slug = $baseSlug;
                $counter = 1;

                while (static::where('slug', $slug)->where('id', '!=', $industry->id)->exists()) {
                    $slug = "{$baseSlug}-{$counter}";
                    $counter++;
                }

                $industry->slug = $slug;
            }
        });
    }

    /**
     * Clients belonging to this industry
     */
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class)->orderBy('order', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Active clients
     */
    public function activeClients(): HasMany
    {
        return $this->hasMany(Client::class)->where('is_active', true)->orderBy('order', 'asc')->orderBy('id', 'asc');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('name', 'asc');
    }
}

