<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
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
        'summary',
        'challenge',
        'solution',
        'result',
        'volume',
        'impact_highlights',
        'workflow_steps',
        'components',
        'cover_image',
        'is_featured',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'impact_highlights' => 'array',
            'workflow_steps' => 'array',
            'components' => 'array',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Workflow steps as [name, description] pairs. Older rows store plain strings.
     *
     * @return list<array{name: string, description: string}>
     */
    public function getStepsAttribute(): array
    {
        return array_map(fn ($step) => is_array($step)
            ? ['name' => $step['name'] ?? '', 'description' => $step['description'] ?? '']
            : ['name' => (string) $step, 'description' => ''], $this->workflow_steps ?? []);
    }

    /** Short pitch for cards and the page lead; falls back to the challenge. */
    public function getTeaserAttribute(): string
    {
        return $this->summary ?: $this->challenge;
    }

    /**
     * Screens for the case study page: the cover first, then the gallery in order. Local files
     * missing from the public disk are skipped so the page never renders a broken image.
     *
     * @return \Illuminate\Support\Collection<int, array{url: string, title: ?string, description: ?string}>
     */
    public function getScreensAttribute(): Collection
    {
        return collect([['path' => $this->cover_image, 'title' => null, 'description' => null]])
            ->concat($this->images->map(fn (ProjectImage $image) => $image->only(['path', 'title', 'description'])))
            ->map(fn ($screen) => ['url' => self::publicUrl($screen['path'])] + $screen)
            ->filter(fn ($screen) => $screen['url'])
            ->map(fn ($screen) => collect($screen)->except('path')->all())
            ->values();
    }

    /** Thumbnail for cards: the first existing image (cover, else gallery), checked lazily. */
    public function getThumbnailUrlAttribute(): ?string
    {
        return collect([$this->cover_image])->concat($this->images->pluck('path'))
            ->lazy()
            ->map(fn ($path) => self::publicUrl($path))
            ->first(fn ($url) => $url);
    }

    private static function publicUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return Storage::disk('public')->exists($path) ? asset('storage/' . $path) : null;
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
