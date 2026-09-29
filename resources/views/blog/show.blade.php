@extends('layouts.public')

@section('title', $post->title . ' | Slamet Rivai')
@section('meta_description', $post->excerpt)
@section('og_image', $post->cover_image_url)

@php
    $shareBtn = 'inline-flex h-11 w-11 items-center justify-center rounded-lg border border-edge bg-surface text-content-secondary transition hover:border-accent-deep hover:text-accent';
    $shareText = rawurlencode($post->title);
    $shareUrl = rawurlencode(url()->current());
    $hasSections = preg_match('/<h[23][\s>]/i', $htmlContent) === 1;
@endphp

@section('content')
<div id="reading-progress" class="fixed left-0 top-16 z-50 h-1 bg-accent" style="width: 0%" aria-hidden="true"></div>

<article class="mx-auto max-w-7xl px-4 pb-20 pt-8 sm:px-6 lg:px-8">
    <div class="mb-8">
        <a href="{{ route('blog.index') }}" class="inline-flex min-h-11 items-center gap-1.5 text-sm font-semibold text-content-secondary transition hover:text-accent">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Knowledge Base
        </a>
    </div>

    <header class="max-w-4xl">
        <p class="mb-4 flex flex-wrap items-center gap-2 text-sm text-content-secondary">
            <span class="inline-flex rounded-md border border-accent-deep/40 bg-accent/10 px-2.5 py-1 font-semibold text-accent-soft">{{ $post->category?->name }}</span>
            <span aria-hidden="true">•</span>
            <span>{{ $post->reading_time }} min read</span>
            <span aria-hidden="true">•</span>
            <span>{{ $post->published_at?->format('d F Y') }}</span>
            <span aria-hidden="true">•</span>
            <span class="tabular-nums">{{ number_format($post->views_count) }} views</span>
        </p>

        <h1 class="text-3xl font-extrabold leading-[1.2] tracking-tight text-content sm:text-4xl lg:text-5xl">{{ $post->title }}</h1>
        <p class="mt-6 text-lg leading-relaxed text-content-secondary sm:text-xl">{{ $post->excerpt }}</p>

        <div class="mt-8 flex flex-wrap items-center justify-between gap-4 border-y border-edge py-4">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-accent text-sm font-bold text-void" aria-hidden="true">SR</span>
                <div>
                    <p class="text-sm font-bold text-content">Slamet Rivai</p>
                    <p class="text-xs text-content-secondary">Operations & Systems Leader</p>
                </div>
            </div>

            <div class="flex items-center gap-2" x-data="{ copied: false }">
                <span class="mr-1 hidden text-sm text-content-secondary sm:inline">Share:</span>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener" class="{{ $shareBtn }}" aria-label="Share on LinkedIn (opens in new tab)">
                    <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 10.9v8.37H9.2V10.9H6.46M7.83 6.45a1.64 1.64 0 1 0 0 3.27 1.64 1.64 0 0 0 0-3.27Z"/></svg>
                </a>
                <a href="https://twitter.com/intent/tweet?text={{ $shareText }}&url={{ $shareUrl }}" target="_blank" rel="noopener" class="{{ $shareBtn }}" aria-label="Share on X (opens in new tab)">
                    <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <a href="https://api.whatsapp.com/send?text={{ rawurlencode($post->title . ' ' . url()->current()) }}" target="_blank" rel="noopener" class="{{ $shareBtn }}" aria-label="Share on WhatsApp (opens in new tab)">
                    <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654z"/></svg>
                </a>
                <button type="button" @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2000)"
                        class="inline-flex min-h-11 items-center gap-1.5 rounded-lg border border-edge bg-surface px-3 text-sm text-content-secondary transition hover:border-accent-deep hover:text-accent">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <span aria-live="polite" x-text="copied ? 'Link copied' : 'Copy link'">Copy link</span>
                </button>
            </div>
        </div>
    </header>

    @if($post->cover_image)
        <div class="mt-8 max-w-5xl overflow-hidden rounded-2xl border border-edge bg-void">
            <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" class="h-72 w-full object-cover sm:h-96">
        </div>
    @endif

    <div class="mt-12 grid grid-cols-1 gap-12 lg:grid-cols-12">
        <div class="lg:col-span-8">
            <div id="article-body" class="prose prose-invert max-w-none lg:prose-lg prose-headings:scroll-mt-24 prose-headings:font-bold prose-headings:tracking-tight prose-a:text-accent-soft hover:prose-a:text-accent prose-pre:rounded-xl prose-pre:border prose-pre:border-edge prose-pre:bg-surface">
                {!! $htmlContent !!}
            </div>

            <div class="mt-16 flex flex-col items-start gap-5 rounded-2xl border border-edge bg-surface p-6 sm:flex-row sm:items-center">
                <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-accent text-xl font-bold text-void" aria-hidden="true">SR</span>
                <div>
                    <h2 class="text-base font-bold text-content">Written by Slamet Rivai</h2>
                    <p class="mt-1 text-sm leading-relaxed text-content-secondary">
                        Operations & Systems Leader with 9+ years designing process automation, enterprise-scale contact center operations, and WhatsApp Cloud API and OCR pipeline integrations.
                    </p>
                    <a href="{{ route('home') }}#contact" data-open-contact aria-haspopup="dialog" class="mt-2 inline-flex min-h-11 items-center text-sm font-semibold text-accent-soft hover:text-accent hover:underline">Get in touch</a>
                </div>
            </div>
        </div>

        {{-- Below lg the aside dissolves (display: contents) so the TOC can sit above the article
             while Related Articles stays below it; from lg it is the usual sticky sidebar. --}}
        <aside class="contents lg:col-span-4 lg:block lg:space-y-8">
            @if($hasSections)
                {{-- Collapsible so a long TOC never pushes the article off screen; the list scrolls
                     inside the box (60vh on mobile, rest of the viewport under the sticky header on lg). --}}
                <nav class="order-first lg:sticky lg:top-24 lg:order-none" aria-labelledby="toc-title">
                    <details id="toc" class="group rounded-2xl border border-edge bg-surface">
                        <summary class="flex min-h-11 cursor-pointer list-none items-center justify-between gap-3 rounded-2xl px-6 py-4 [&::-webkit-details-marker]:hidden">
                            <h2 id="toc-title" class="text-sm font-semibold text-content">Table of Contents</h2>
                            <svg class="h-5 w-5 shrink-0 text-content-secondary transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </summary>
                        <div id="table-of-contents" class="max-h-[60vh] space-y-2 overflow-y-auto border-t border-edge px-6 py-4 text-sm lg:max-h-[calc(100vh-12rem)]"></div>
                    </details>
                </nav>
            @endif

            @if($relatedPosts->isNotEmpty())
                <div class="rounded-2xl border border-edge bg-surface p-6">
                    <h2 class="mb-4 text-sm font-semibold text-content">Related Articles</h2>
                    <ul class="space-y-4">
                        @foreach($relatedPosts as $related)
                            <li>
                                <p class="text-xs text-accent-soft">{{ $related->category?->name }}</p>
                                <a href="{{ route('blog.show', $related->slug) }}" class="mt-0.5 block text-sm font-bold text-content transition hover:text-accent">{{ $related->title }}</a>
                                <p class="text-xs text-content-secondary">{{ $related->reading_time }} min read</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </aside>
    </div>
</article>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const progress = document.getElementById('reading-progress');
    window.addEventListener('scroll', () => {
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        progress.style.width = (docHeight > 0 ? (window.scrollY / docHeight) * 100 : 0) + '%';
    }, { passive: true });

    const toc = document.getElementById('table-of-contents');
    if (!toc) return; // rendered only when the article has h2/h3 headings

    // Open beside the article on desktop; collapsed above it on mobile, closing again after a jump.
    const tocBox = document.getElementById('toc');
    const desktop = window.matchMedia('(min-width: 1024px)');
    tocBox.open = desktop.matches;
    toc.addEventListener('click', (e) => {
        if (e.target.closest('a') && !desktop.matches) tocBox.open = false;
    });

    document.getElementById('article-body').querySelectorAll('h2, h3').forEach((heading, idx) => {
        heading.id = 'heading-' + idx;
        const link = document.createElement('a');
        link.href = '#' + heading.id;
        link.textContent = heading.textContent;
        link.className = heading.tagName === 'H3'
            ? 'block pl-3 leading-relaxed text-content-secondary transition hover:text-accent'
            : 'block font-semibold leading-relaxed text-content transition hover:text-accent';
        toc.appendChild(link);
    });
});
</script>
@endpush
@endsection
