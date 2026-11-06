<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaunchpadLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'whatsapp_number',
        'startup_name',
        'industry',
        'status',
        'notes',
        'is_read',
        'ip_address',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Scope for unread leads
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, ?string $status = null)
    {
        if ($status && $status !== 'all') {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope for filtering by industry
     */
    public function scopeByIndustry($query, ?string $industry = null)
    {
        if ($industry && $industry !== 'all') {
            return $query->where('industry', $industry);
        }
        return $query;
    }

    /**
     * Generate direct WhatsApp click-to-chat URL
     */
    public function getWhatsappLinkAttribute(): string
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $this->whatsapp_number);
        $message = urlencode("Hi {$this->full_name}, thank you for applying to the Qavix Launchpad!");
        return "https://wa.me/{$cleanPhone}?text={$message}";
    }
}

