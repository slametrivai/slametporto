<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class Post extends Model
{
    use HasFactory, HasSEO;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'cover_image',
        'status',
        'views_count',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'views_count' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at');
    }

    public function getReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags($this->content));
        $minutes = ceil($words / 200);
        return max(1, (int) $minutes);
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
            description: $this->seo?->description ?: ($this->excerpt ?: Str::limit(strip_tags($this->content), 160)),
            author: $this->seo?->author ?: setting('site_author', 'Slamet Rivai'),
            image: $this->seo?->image ?: $this->cover_image_url,
            published_time: $this->published_at,
            section: $this->category?->name,
            url: $this->seo?->canonical_url ?: route('blog.show', $this->slug),
            robots: $this->seo?->robots ?: 'index, follow',
            canonical_url: $this->seo?->canonical_url ?: route('blog.show', $this->slug),
        );
    }
}
