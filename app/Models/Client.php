<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'industry',
        'logo',
        'website_url',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getLogoUrlAttribute(): string
    {
        if (str_starts_with($this->logo, 'http')) {
            return $this->logo;
        }

        return asset('storage/' . $this->logo);
    }

    /** Two-letter mark for the logo slot when the logo file is missing. */
    public function getInitialsAttribute(): string
    {
        $words = preg_split('/\s+/', trim(preg_replace('/^(PT|CV|Yayasan)\s+/i', '', $this->name)));

        return strtoupper(mb_substr($words[0] ?? '', 0, 1) . mb_substr($words[1] ?? $words[0] ?? '', count($words) > 1 ? 0 : 1, 1));
    }

    /** Lucide icon for the industry pill. */
    public function getIndustryIconAttribute(): string
    {
        return match (true) {
            (bool) preg_match('/travel|hospitality/i', $this->industry) => 'plane',
            (bool) preg_match('/health|medic/i', $this->industry) => 'heart-pulse',
            (bool) preg_match('/educat|ngo/i', $this->industry) => 'graduation-cap',
            default => 'briefcase',
        };
    }

    public function getHasLogoAttribute(): bool
    {
        return str_starts_with($this->logo, 'http') || Storage::disk('public')->exists($this->logo);
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class, 'industry', 'name');
    }
}
