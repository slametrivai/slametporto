@extends('layouts.public')

@section('title', (($categories->firstWhere('slug', $categorySlug)?->name ?? null) ? $categories->firstWhere('slug', $categorySlug)->name . ' Articles' : 'Writing') . ' | Slamet Rivai')
@section('meta_description', 'Field notes on queue reliability, CRM architecture, OCR pipelines and production post-mortems from running customer operations.')

@php
    $tabs = [['label' => 'All topics', 'count' => $totals['articles'], 'url' => route('blog.index', array_filter(['q' => $search])), 'active' => empty($categorySlug)]];
    foreach ($categories->where('posts_count', '>', 0) as $cat) {
        $tabs[] = ['label' => $cat->name, 'count' => $cat->posts_count, 'url' => route('blog.category', ['category' => $cat->slug] + array_filter(['q' => $search])), 'active' => $categorySlug === $cat->slug];
    }
    $activeCategory = $categories->firstWhere('slug', $categorySlug);
@endphp

@section('content')
    @include('partials.page-header', [
        'current' => 'Writing',
        'title' => 'Field notes from',
        'accent' => 'the ops floor',
        'lead' => 'Queue reliability patterns, CRM architecture and production post-mortems, written from running customer operations, not from theory.',
        'counts' => $totals,
    ])

    @include('partials.filter-bar', [
        'label' => 'Filter by topic',
        'tabs' => $tabs,
        'action' => $categorySlug ? route('blog.category', $categorySlug) : route('blog.index'),
        'hidden' => ['sort' => $sort === 'newest' ? null : $sort],
        'search' => $search,
        'placeholder' => 'Search articles…',
    ])

    @if($featuredPost)
        <section class="band-dark rounded-[2rem] lg:rounded-[3rem]" aria-labelledby="featured-title">
            <div class="page-container flex flex-col gap-[1.125rem] py-10 lg:py-14">
                <p class="text-sm font-bold uppercase tracking-[0.1em] text-sky">Featured note</p>
                <article class="glass-card group relative flex flex-col overflow-hidden rounded-3xl transition-colors hover:bg-white/[0.11] lg:min-h-[27.5rem] lg:flex-row">
                    @include('blog._thumb', ['post' => $featuredPost, 'tone' => 'bg-brand', 'class' => 'h-[12.5rem] flex-row p-5 lg:h-auto lg:w-[32.5rem] lg:flex-col lg:p-7', 'wordClass' => 'text-[2.75rem] lg:text-[5rem]', 'chipClass' => 'h-12 w-12 lg:ml-0 lg:mt-auto lg:h-14 lg:w-14'])
                    <div class="flex flex-1 flex-col justify-center gap-3 p-5 lg:p-9">
                        <p class="text-sm font-semibold text-mist lg:text-[0.9375rem]">{{ $featuredPost->category?->name }} · {{ $featuredPost->reading_time }} min read</p>
                        <h2 id="featured-title" class="text-[1.75rem] font-semibold leading-8 text-white lg:text-[2.25rem] lg:leading-[2.5625rem]">
                            <a href="{{ route('blog.show', $featuredPost->slug) }}" class="after:absolute after:inset-0">{{ $featuredPost->title }}</a>
                        </h2>
                        <p class="text-base leading-[1.5625rem] text-mist">{{ $featuredPost->excerpt }}</p>
                        <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                            <p class="flex items-center gap-2.5 text-[0.9375rem] text-mist">
                                <img src="{{ asset('images/slamet-rivai-avatar.jpg') }}" alt="" class="h-9 w-9 rounded-full object-cover">
                                Slamet Rivai · {{ $featuredPost->published_at?->format('d M Y') }}
                            </p>
                            <span class="inline-flex items-center gap-1.5 text-base font-bold text-white" aria-hidden="true">
                                Read article
                                <x-lucide-arrow-right class="h-[1.125rem] w-[1.125rem]" />
                            </span>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    @endif

    <section class="page-container flex flex-col gap-6 py-12 lg:gap-7 lg:py-[4.5rem]" aria-labelledby="latest-title">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 id="latest-title" class="text-[2rem] font-semibold text-navy lg:text-[2.75rem]">
                {{ $search ? 'Results for “' . $search . '”' : ($activeCategory?->name ?? 'Latest') }}
            </h2>
            @include('partials.sort-menu', ['options' => \App\Http\Controllers\BlogController::SORTS, 'current' => $sort])
        </div>

        <ul class="flex flex-col gap-4">
            @forelse($posts as $post)
                @include('blog._card', ['post' => $post])
            @empty
                <li class="rounded-3xl bg-panel px-5 py-16 text-center">
                    <p class="text-lg font-bold text-navy">{{ $featuredPost ? 'No other articles yet' : 'No articles match your search' }}</p>
                    @unless($featuredPost)
                        <p class="mt-1 text-base text-body">Try a different keyword or pick another topic.</p>
                        <a href="{{ route('blog.index') }}" class="link-brand mt-2">Clear filters</a>
                    @endunless
                </li>
            @endforelse
        </ul>

        @if($posts->hasPages())
            <div class="mt-4">{{ $posts->links() }}</div>
        @endif
    </section>

    @if($categories->where('posts_count', '>', 0)->count() > 1)
        <section class="page-container flex flex-col gap-6 pb-12 lg:gap-7 lg:pb-20" aria-labelledby="topics-title">
            <h2 id="topics-title" class="text-[2rem] font-semibold text-navy lg:text-[2.75rem]">Browse by topic</h2>
            <ul class="grid grid-cols-1 gap-4 lg:gap-6 {{ $categories->where('posts_count', '>', 0)->count() % 2 ? 'md:grid-cols-3' : 'md:grid-cols-2' }}">
                @foreach($categories->where('posts_count', '>', 0) as $cat)
                    <li class="group relative flex flex-col gap-2.5 rounded-3xl border border-line p-5 transition-colors hover:border-brand lg:p-7">
                        <div class="flex items-center justify-between">
                            <span class="icon-chip">
                                <x-dynamic-component :component="'lucide-' . $cat->icon" class="h-5 w-5" />
                            </span>
                            <span class="text-[0.9375rem] font-semibold text-body">{{ $cat->posts_count }} {{ Str::plural('article', $cat->posts_count) }}</span>
                        </div>
                        <h3 class="text-[1.5rem] font-semibold text-navy lg:text-[1.625rem]">
                            <a href="{{ route('blog.category', $cat->slug) }}" class="after:absolute after:inset-0">{{ $cat->name }}</a>
                        </h3>
                        @if($cat->description)
                            <p class="text-[0.9375rem] leading-[1.4375rem] text-body">{{ $cat->description }}</p>
                        @endif
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    @include('partials.cta')
@endsection
