<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class Project extends Model
{
    use HasFactory, HasSEO;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'role',
        'challenge',
        'solution',
        'impact_highlights',
        'workflow_steps',
        'cover_image',
        'is_featured',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'impact_highlights' => 'array',
            'workflow_steps' => 'array',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        if (!$this->cover_image) {
            return null;
        }

        if (str_starts_with($this->cover_image, 'http')) {
            return $this->cover_image;
        }

        return asset('storage/' . $this->cover_image);
    }

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: $this->seo?->title ?: $this->title,
            description: $this->seo?->description ?: ($this->challenge ? \Illuminate\Support\Str::limit(strip_tags($this->challenge), 160) : setting('meta_description')),
            author: $this->seo?->author ?: setting('site_author', 'Slamet Rivai'),
            image: $this->seo?->image ?: $this->cover_image_url,
            url: $this->seo?->canonical_url ?: route('projects.show', $this->slug),
            published_time: $this->created_at,
            section: $this->category,
            robots: $this->seo?->robots ?: 'index, follow',
            canonical_url: $this->seo?->canonical_url ?: route('projects.show', $this->slug),
        );
    }
}
