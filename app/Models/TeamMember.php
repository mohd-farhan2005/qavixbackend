<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'email',
        'photo',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected $appends = [
        'photo_url',
    ];

    /**
     * Get absolute accessible URL for the team member's photo
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (empty($this->photo)) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=16a34a&color=fff&size=512';
        }

        if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://')) {
            return $this->photo;
        }

        return secure_asset('storage/' . ltrim($this->photo, '/'));
    }

    /**
     * Scope active team members ordered by display priority
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order', 'asc')->orderBy('id', 'asc');
    }
}
