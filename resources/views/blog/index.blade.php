@extends('layouts.public')

@section('title', 'Writing | Slamet Rivai')
@section('meta_description', 'Field notes on queue reliability, CRM architecture, OCR pipelines and production post-mortems from running customer operations.')

@php
    $tabs = [['label' => 'All topics', 'count' => $totals['articles'], 'url' => route('blog.index', array_filter(['q' => $search])), 'active' => empty($categorySlug)]];
    foreach ($categories->where('posts_count', '>', 0) as $cat) {
        $tabs[] = ['label' => $cat->name, 'count' => $cat->posts_count, 'url' => route('blog.index', array_filter(['category' => $cat->slug, 'q' => $search])), 'active' => $categorySlug === $cat->slug];
    }
    $activeCategory = $categories->firstWhere('slug', $categorySlug);
@endphp

@section('content')
    @include('partials.page-header', [
        'current' => 'Writing',
        'title' => 'Field notes from the ops floor',
        'lead' => 'Queue reliability patterns, CRM architecture and production post-mortems — written from running customer operations, not from theory.',
        'counts' => $totals,
    ])

    @include('partials.filter-bar', [
        'label' => 'Filter by topic',
        'tabs' => $tabs,
        'action' => route('blog.index'),
        'hidden' => ['category' => $categorySlug, 'sort' => $sort === 'newest' ? null : $sort],
        'search' => $search,
        'placeholder' => 'Search articles…',
    ])

    @if($featuredPost)
        <section class="page-container pt-12" aria-labelledby="featured-title">
            <p class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.12em] text-accent">
                <x-lucide-star class="h-3.5 w-3.5" />
                Featured note
            </p>
            <article class="relative mt-5 flex flex-col overflow-hidden rounded-[1.25rem] border border-edge bg-surface transition hover:border-content-faint lg:min-h-[27.5rem] lg:flex-row">
                <div class="relative flex aspect-[16/9] items-center justify-center border-b border-edge bg-surface-inset bg-[radial-gradient(ellipse_70%_70%_at_30%_20%,rgba(242,184,75,0.13),transparent)] lg:aspect-auto lg:w-1/2 lg:shrink-0 lg:border-b-0 lg:border-r">
                    @if($featuredPost->has_cover)
                        <img src="{{ $featuredPost->cover_image_url }}" alt="" class="absolute inset-0 h-full w-full object-cover">
                    @else
                        <span class="flex h-[4.5rem] w-[4.5rem] items-center justify-center rounded-[1.125rem] border border-accent/30 bg-accent/10 text-accent" aria-hidden="true">
                            <x-dynamic-component :component="'lucide-' . ($featuredPost->category?->icon ?? 'file-text')" class="h-8 w-8" />
                        </span>
                    @endif
                </div>
                <div class="flex flex-1 flex-col justify-center gap-5 p-6 sm:p-12">
                    <p class="text-[0.8125rem] font-medium text-content-secondary">{{ $featuredPost->category?->name }} · {{ $featuredPost->reading_time }} min read</p>
                    <h2 id="featured-title" class="text-[1.75rem] font-bold leading-[1.18] tracking-[-0.024em] text-content sm:text-[2.125rem]">
                        <a href="{{ route('blog.show', $featuredPost->slug) }}" class="after:absolute after:inset-0">{{ $featuredPost->title }}</a>
                    </h2>
                    <p class="text-base leading-[1.625rem] text-content-secondary">{{ $featuredPost->excerpt }}</p>
                    <div class="flex flex-wrap items-center justify-between gap-4 pt-3">
                        <p class="flex items-center gap-2.5 text-[0.8125rem] font-medium text-content-secondary">
                            <img src="{{ asset('images/slamet-rivai-avatar.jpg') }}" alt="" class="h-8 w-8 rounded-full border border-edge object-cover">
                            Slamet Rivai · {{ $featuredPost->published_at?->format('d M Y') }}
                        </p>
                        <span class="inline-flex items-center gap-2 text-[0.9375rem] font-semibold text-content" aria-hidden="true">
                            Read article
                            <x-lucide-arrow-right class="h-4 w-4" />
                        </span>
                    </div>
                </div>
            </article>
        </section>
    @endif

    <section class="page-container pt-16 sm:pt-[4.5rem]" aria-labelledby="latest-title">
        <div class="flex items-center justify-between gap-4 pb-4">
            <h2 id="latest-title" class="text-[1.375rem] font-bold tracking-[-0.018em] text-content">
                {{ $search ? 'Results for “' . $search . '”' : ($activeCategory?->name ?? 'Latest') }}
            </h2>
            @include('partials.sort-menu', ['options' => \App\Http\Controllers\BlogController::SORTS, 'current' => $sort])
        </div>

        <ul>
            @forelse($posts as $post)
                @include('blog._card', ['post' => $post])
            @empty
                <li class="border-t border-edge py-16 text-center">
                    <p class="text-base font-semibold text-content">{{ $featuredPost ? 'No other articles yet' : 'No articles match your search' }}</p>
                    @unless($featuredPost)
                        <p class="mt-1 text-sm text-content-secondary">Try a different keyword or pick another topic.</p>
                        <a href="{{ route('blog.index') }}" class="link-accent mt-2">Clear filters</a>
                    @endunless
                </li>
            @endforelse
        </ul>

        @if($posts->hasPages())
            <div class="mt-10">{{ $posts->links() }}</div>
        @endif
    </section>

    @if($categories->where('posts_count', '>', 0)->count() > 1)
        <section class="page-container pb-20 pt-20 sm:pb-[7.5rem] sm:pt-24" aria-labelledby="topics-title">
            <h2 id="topics-title" class="text-[1.375rem] font-bold tracking-[-0.018em] text-content">Browse by topic</h2>
            <ul class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-3">
                @foreach($categories->where('posts_count', '>', 0) as $cat)
                    <li class="group relative flex flex-col gap-4 rounded-2xl border border-edge p-7 transition hover:border-content-faint">
                        <div class="flex items-center justify-between">
                            <span class="flex h-10 w-10 items-center justify-center rounded-[0.625rem] bg-accent/10 text-accent">
                                <x-dynamic-component :component="'lucide-' . $cat->icon" class="h-[1.125rem] w-[1.125rem]" />
                            </span>
                            <span class="text-[0.8125rem] font-medium text-content-faint">{{ $cat->posts_count }} {{ Str::plural('article', $cat->posts_count) }}</span>
                        </div>
                        <h3 class="text-lg font-semibold text-content">
                            <a href="{{ route('blog.index', ['category' => $cat->slug]) }}" class="after:absolute after:inset-0">{{ $cat->name }}</a>
                        </h3>
                        @if($cat->description)
                            <p class="text-sm leading-[1.375rem] text-content-secondary">{{ $cat->description }}</p>
                        @endif
                    </li>
                @endforeach
            </ul>
        </section>
    @else
        <div class="pb-20 sm:pb-[7.5rem]"></div>
    @endif

    @include('partials.cta')
@endsection
