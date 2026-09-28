<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class Category extends Model
{
    use HasFactory, HasSEO;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'category', 'name');
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'industry', 'name');
    }

    public function scopeForPosts(Builder $query): Builder
    {
        return $query->where('type', 'post');
    }

    public function scopeForProjects(Builder $query): Builder
    {
        return $query->where('type', 'project');
    }

    public function scopeForClients(Builder $query): Builder
    {
        return $query->where('type', 'client');
    }

    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function getDynamicSEOData(): SEOData
    {
        $typeName = match ($this->type) {
            'project' => 'Studi Kasus & Proyek',
            'client' => 'Ekosistem Klien & Industri',
            default => 'Artikel Teknis',
        };
        $fallbackDesc = $this->description ?: "Kumpulan {$typeName} kategori {$this->name} oleh Slamet Rivai, Operations & Systems Leader.";

        $targetUrl = match ($this->type) {
            'project' => route('projects.index', ['category' => $this->name]),
            'client' => route('home') . '#clients',
            default => route('blog.index', ['category' => $this->slug]),
        };

        return new SEOData(
            title: $this->seo?->title ?: "{$this->name} | {$typeName}",
            description: $this->seo?->description ?: $fallbackDesc,
            author: $this->seo?->author ?: setting('site_author', 'Slamet Rivai'),
            url: $this->seo?->canonical_url ?: $targetUrl,
            robots: $this->seo?->robots ?: 'index, follow',
            canonical_url: $this->seo?->canonical_url ?: $targetUrl,
        );
    }
}
