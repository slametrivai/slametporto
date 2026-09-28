@extends('layouts.public')

@section('title', 'Knowledge Base & Engineering Blog | Slamet Rivai')
@section('meta_description', 'In-depth technical articles on operations architecture, OCR pipelines, WhatsApp Cloud API queues, and contact center systems.')

@php
    $chip = 'inline-flex min-h-11 items-center rounded-lg px-3.5 text-sm font-semibold transition';
    $chipOn = 'bg-accent text-void';
    $chipOff = 'border border-edge bg-surface text-content-secondary hover:border-accent-deep hover:text-accent';
@endphp

@section('content')
<div class="mx-auto max-w-7xl px-4 pb-16 pt-8 sm:px-6 lg:px-8">
    <div class="max-w-2xl">
        <p class="flex items-center gap-3 text-sm font-semibold text-accent-soft"><span class="h-px w-8 bg-accent-deep" aria-hidden="true"></span> Knowledge Base</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-content sm:text-4xl">Operations & Systems Notes</h1>
        <p class="mt-3 text-base text-content-secondary">Field notes, queue reliability patterns, and production post-mortems on customer operations, CRM architectures, and workflow automations.</p>
    </div>

    <div class="mt-8 flex flex-col items-stretch justify-between gap-4 border-b border-edge pb-6 md:flex-row md:items-center">
        <nav class="flex flex-wrap items-center gap-2" aria-label="Filter by topic">
            <a href="{{ route('blog.index') }}" class="{{ $chip }} {{ empty($categorySlug) ? $chipOn : $chipOff }}" @if(empty($categorySlug)) aria-current="page" @endif>All Topics</a>
            @foreach($categories as $cat)
                <a href="{{ route('blog.index', ['category' => $cat->slug]) }}" class="{{ $chip }} {{ $categorySlug === $cat->slug ? $chipOn : $chipOff }}" @if($categorySlug === $cat->slug) aria-current="page" @endif>
                    {{ $cat->name }} <span class="ml-1 tabular-nums opacity-80">({{ $cat->posts_count }})</span>
                </a>
            @endforeach
        </nav>

        <form action="{{ route('blog.index') }}" method="GET" role="search" class="relative w-full md:w-72">
            @if($categorySlug)
                <input type="hidden" name="category" value="{{ $categorySlug }}">
            @endif
            <label for="blog-search" class="sr-only">Search articles</label>
            <input id="blog-search" type="search" name="q" value="{{ $search }}" placeholder="Search articles..."
                   class="h-11 w-full rounded-lg border border-edge bg-surface pl-10 pr-3 text-sm text-content placeholder:text-content-secondary focus:border-accent focus:ring-1 focus:ring-accent">
            <svg class="pointer-events-none absolute left-3 top-3.5 h-4 w-4 text-content-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </form>
    </div>

    @if(empty($categorySlug) && empty($search) && $featuredPost)
        <div class="mt-8 overflow-hidden rounded-2xl border border-edge bg-surface">
            <div class="grid grid-cols-1 lg:grid-cols-12">
                @if($featuredPost->cover_image)
                    <div class="h-64 overflow-hidden bg-void sm:h-80 lg:col-span-7 lg:h-auto">
                        <img src="{{ $featuredPost->cover_image_url }}" alt="{{ $featuredPost->title }}" class="h-full w-full object-cover">
                    </div>
                @endif
                <div class="{{ $featuredPost->cover_image ? 'lg:col-span-5' : 'lg:col-span-12' }} flex flex-col justify-between p-6 sm:p-10">
                    <div>
                        <p class="mb-3 flex flex-wrap items-center gap-2 text-sm">
                            <span class="inline-flex rounded-full bg-accent px-2.5 py-0.5 text-xs font-bold text-void">Featured Architecture Note</span>
                            <span class="text-content-secondary">{{ $featuredPost->reading_time }} min read</span>
                        </p>
                        <h2 class="text-xl font-bold leading-snug text-content sm:text-2xl">
                            <a href="{{ route('blog.show', $featuredPost->slug) }}" class="transition hover:text-accent">{{ $featuredPost->title }}</a>
                        </h2>
                        <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-content-secondary">{{ $featuredPost->excerpt }}</p>
                    </div>
                    <div class="mt-6 flex items-center justify-between border-t border-edge pt-4 text-sm">
                        <span class="tabular-nums text-content-secondary">{{ $featuredPost->published_at?->format('d M Y') }}</span>
                        <a href="{{ route('blog.show', $featuredPost->slug) }}" class="inline-flex min-h-11 items-center font-semibold text-accent-soft hover:text-accent hover:underline">Read full article</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
        @forelse($posts as $post)
            @include('blog._card', ['post' => $post])
        @empty
            <div class="col-span-full py-16 text-center">
                <p class="text-base font-semibold text-content">No articles match your search</p>
                <p class="mt-1 text-sm text-content-secondary">Try a different keyword or pick another topic.</p>
                <a href="{{ route('blog.index') }}" class="mt-4 inline-flex min-h-11 items-center text-sm font-semibold text-accent-soft hover:text-accent hover:underline">Clear filters</a>
            </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $posts->links() }}
    </div>
</div>
@endsection
