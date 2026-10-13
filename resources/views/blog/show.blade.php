@extends('layouts.public')

@section('title', $post->title . ' | Slamet Rivai')
@section('meta_description', $post->excerpt)
@section('og_image', $post->cover_image_url)

@php
    $shareText = rawurlencode($post->title);
    $shareUrl = rawurlencode(url()->current());
    $shareBtn = 'inline-flex h-11 w-11 items-center justify-center rounded-lg border border-edge text-content-secondary transition hover:border-content-faint hover:text-content';
    $postMeta = fn ($p) => collect([$p->category?->name, $p->published_at?->format('d M Y'), $p->reading_time . ' min read'])->filter()->implode(' · ');
@endphp

@section('content')
<article>
    <header class="page-container pt-10 sm:pt-12">
        <a href="{{ route('blog.index') }}" class="inline-flex min-h-11 items-center gap-2 text-sm font-medium text-content-secondary transition hover:text-content">
            <x-lucide-arrow-left class="h-4 w-4" />
            All articles
        </a>

        <div class="mt-6 max-w-[60rem] sm:mt-8">
            <p class="flex flex-wrap items-center gap-3">
                @if($post->category)
                    <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="chip bg-accent/10 font-semibold text-accent transition hover:bg-accent/15">
                        <x-dynamic-component :component="'lucide-' . $post->category->icon" class="h-[0.8125rem] w-[0.8125rem]" />
                        {{ $post->category->name }}
                    </a>
                @endif
                <span class="text-sm font-medium text-content-secondary">
                    <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('d F Y') }}</time> · {{ $post->reading_time }} min read
                </span>
            </p>
            <h1 class="mt-5 text-[2.125rem] font-bold leading-[1.1] tracking-[-0.03em] text-content sm:text-5xl lg:text-[3.375rem] lg:leading-[3.625rem]">{{ $post->title }}</h1>
            <p class="mt-5 max-w-[51.25rem] text-lg leading-[1.875rem] text-content-secondary sm:text-xl sm:leading-[1.9375rem]">{{ $post->excerpt }}</p>
        </div>

        <div class="mt-8 flex flex-wrap items-center justify-between gap-4 border-y border-edge py-5">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/slamet-rivai-avatar.jpg') }}" alt="" class="h-11 w-11 rounded-full border border-edge object-cover">
                <div>
                    <p class="text-[0.9375rem] font-semibold text-content">Slamet Rivai</p>
                    <p class="text-[0.8125rem] text-content-secondary">Operations & Systems Leader</p>
                </div>
            </div>
            <div class="flex items-center gap-2" x-data="{ copied: false }">
                <span class="mr-1 hidden text-[0.8125rem] font-medium text-content-secondary sm:inline">Share</span>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener" class="{{ $shareBtn }}" aria-label="Share on LinkedIn (opens in new tab)">
                    <x-lucide-linkedin class="h-[0.9375rem] w-[0.9375rem]" />
                </a>
                <a href="https://twitter.com/intent/tweet?text={{ $shareText }}&url={{ $shareUrl }}" target="_blank" rel="noopener" class="{{ $shareBtn }}" aria-label="Share on X (opens in new tab)">
                    <x-lucide-twitter class="h-[0.9375rem] w-[0.9375rem]" />
                </a>
                <button type="button" @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2000)"
                        class="inline-flex min-h-11 items-center gap-1.5 rounded-lg border border-edge px-3.5 text-[0.8125rem] font-medium text-content transition hover:border-content-faint">
                    <x-lucide-link class="h-[0.9375rem] w-[0.9375rem] text-content-secondary" />
                    <span aria-live="polite" x-text="copied ? 'Link copied' : 'Copy link'">Copy link</span>
                </button>
            </div>
        </div>
    </header>

    @if($post->has_cover)
        <div class="page-container pt-10 sm:pt-12">
            <img src="{{ $post->cover_image_url }}" alt="" class="aspect-[1280/480] w-full rounded-[1.25rem] border border-edge object-cover">
        </div>
    @endif

    <div class="page-container flex flex-col gap-10 pt-12 sm:pt-16 lg:flex-row lg:gap-[7.5rem]">
        <div class="order-2 min-w-0 lg:order-1 lg:w-[47.5rem] lg:shrink-0">
            <div id="article-body" class="article-body">
                {!! $htmlContent !!}
            </div>

            <div class="mt-16 flex flex-col items-start gap-5 rounded-2xl border border-edge p-6 sm:flex-row sm:items-center sm:p-7">
                <img src="{{ asset('images/slamet-rivai-avatar.jpg') }}" alt="" class="h-16 w-16 shrink-0 rounded-full border border-edge object-cover">
                <div class="flex-1">
                    <h2 class="text-base font-semibold text-content">Written by Slamet Rivai</h2>
                    <p class="mt-1.5 text-sm leading-[1.375rem] text-content-secondary">Operations & Systems Leader with 9+ years designing process automation, enterprise-scale contact center operations, and WhatsApp Cloud API and OCR pipeline integrations.</p>
                </div>
                <a href="#contact" data-open-contact aria-haspopup="dialog" class="btn-primary shrink-0 px-4 text-sm">Get in touch</a>
            </div>
        </div>

        <aside class="order-1 flex flex-col gap-6 lg:order-2 lg:flex-1">
            <div class="flex flex-col gap-6 lg:sticky lg:top-28">
                @if(count($toc))
                    <nav aria-labelledby="toc-title">
                        <details id="toc" class="group rounded-2xl border border-edge" x-data="articleToc(@js(array_column($toc, 'id')))">
                            <summary class="flex min-h-11 cursor-pointer list-none items-center justify-between gap-3 px-6 pt-6 lg:pointer-events-none [&::-webkit-details-marker]:hidden">
                                <span class="flex items-center gap-2">
                                    <x-lucide-list class="h-4 w-4 text-content-secondary" />
                                    <h2 id="toc-title" class="text-sm font-semibold text-content">On this page</h2>
                                </span>
                                <span class="text-xs font-medium tabular-nums text-content-faint"><span x-text="progress">0</span>% read</span>
                            </summary>
                            <div class="px-6 pb-6">
                                <div class="mt-4 h-[0.1875rem] rounded-full bg-edge" aria-hidden="true">
                                    <div class="h-full rounded-full bg-accent" :style="`width: ${progress}%`"></div>
                                </div>
                                <ol id="table-of-contents" class="mt-4 max-h-[50vh] overflow-y-auto">
                                    @foreach($toc as $item)
                                        <li>
                                            <a href="#{{ $item['id'] }}" @click="if (!desktop.matches) $root.open = false"
                                               :aria-current="active === @js($item['id']) ? 'location' : null"
                                               class="block border-l py-2.5 pl-3.5 text-sm leading-5 transition"
                                               :class="active === @js($item['id']) ? 'border-l-2 border-accent font-semibold text-content' : 'border-edge text-content-secondary hover:text-content'">{{ $item['text'] }}</a>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        </details>
                    </nav>
                @endif

                @if($relatedProject)
                    @php $metrics = array_slice($relatedProject->impact_highlights ?? [], 0, 2); @endphp
                    <div class="glow-corner relative hidden flex-col gap-3.5 rounded-2xl border border-accent/25 bg-surface p-6 lg:flex">
                        <p class="text-[0.6875rem] font-semibold uppercase tracking-[0.13em] text-accent">See it in production</p>
                        <h2 class="text-[1.0625rem] font-bold leading-[1.375rem] text-content">
                            <a href="{{ route('projects.show', $relatedProject->slug) }}" class="after:absolute after:inset-0">{{ $relatedProject->title }}</a>
                        </h2>
                        @if($metrics)
                            <dl class="flex gap-6">
                                @foreach($metrics as $h)
                                    <div class="flex flex-col-reverse gap-0.5">
                                        <dt class="text-xs text-content-secondary">{{ $h['label'] }}</dt>
                                        <dd class="text-xl font-bold text-accent">{{ $h['metric'] }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        @endif
                        <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-content" aria-hidden="true">
                            Read the case study
                            <x-lucide-arrow-right class="h-[0.9375rem] w-[0.9375rem]" />
                        </span>
                    </div>
                @endif
            </div>
        </aside>
    </div>
</article>

@if($relatedPosts->isNotEmpty())
    <section class="page-container py-20 sm:py-[7.5rem]" aria-labelledby="keep-reading-title">
        <div class="flex flex-col justify-between gap-4 border-t border-edge pt-14 sm:flex-row sm:items-end">
            <h2 id="keep-reading-title" class="text-[1.75rem] font-bold tracking-[-0.025em] text-content sm:text-[2rem]">Keep reading</h2>
            <a href="{{ route('blog.index') }}" class="link-accent shrink-0">All articles <x-lucide-arrow-right class="h-4 w-4" /></a>
        </div>
        <ul class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-2">
            @foreach($relatedPosts as $related)
                <li class="group relative flex flex-col gap-3.5 rounded-2xl border border-edge p-6 transition hover:border-content-faint sm:p-8">
                    <p class="flex items-center gap-2 text-[0.8125rem] font-medium text-content-secondary">
                        <x-dynamic-component :component="'lucide-' . ($related->category?->icon ?? 'file-text')" class="h-3.5 w-3.5 text-accent" />
                        {{ $postMeta($related) }}
                    </p>
                    <h3 class="text-[1.375rem] font-bold leading-[1.8125rem] tracking-[-0.018em] text-content">
                        <a href="{{ route('blog.show', $related->slug) }}" class="after:absolute after:inset-0">{{ $related->title }}</a>
                    </h3>
                    <p class="line-clamp-2 text-[0.9375rem] leading-6 text-content-secondary">{{ $related->excerpt }}</p>
                    <span class="mt-auto inline-flex items-center gap-1.5 pt-2 text-sm font-medium text-content" aria-hidden="true">
                        Read article
                        <x-lucide-arrow-up-right class="h-4 w-4 text-content-secondary transition group-hover:text-accent" />
                    </span>
                </li>
            @endforeach
        </ul>
    </section>
@else
    <div class="pb-20 sm:pb-[7.5rem]"></div>
@endif

@push('scripts')
<script>
function articleToc(ids) {
    return {
        active: ids[0],
        progress: 0,
        desktop: window.matchMedia('(min-width: 1024px)'),
        init() {
            // Open beside the article on desktop; collapsed above it on smaller screens.
            this.$root.open = this.desktop.matches;
            const body = document.getElementById('article-body');
            const headings = ids.map((id) => document.getElementById(id)).filter(Boolean);
            const update = () => {
                const rect = body.getBoundingClientRect();
                const read = (window.innerHeight * 0.4 - rect.top) / rect.height;
                this.progress = Math.round(Math.min(1, Math.max(0, read)) * 100);
                const passed = headings.filter((h) => h.getBoundingClientRect().top < 140);
                this.active = (passed[passed.length - 1] ?? headings[0])?.id;
            };
            window.addEventListener('scroll', update, { passive: true });
            update();
        },
    };
}

// Code blocks get a header with the language and a copy button, like the design's code panel.
document.querySelectorAll('#article-body > pre').forEach((pre) => {
    const lang = (pre.querySelector('code')?.className.match(/language-([\w-]+)/) || [])[1] || 'code';
    const wrap = document.createElement('div');
    wrap.className = 'code-block';
    wrap.innerHTML = `<div class="flex items-center justify-between border-b border-edge px-4 py-2.5">
        <span class="font-mono text-xs text-content-secondary"></span>
        <button type="button" class="inline-flex min-h-8 items-center gap-1.5 text-xs font-medium text-content-secondary transition hover:text-content">Copy</button>
    </div>`;
    wrap.querySelector('span').textContent = lang;
    pre.replaceWith(wrap);
    wrap.appendChild(pre);
    const btn = wrap.querySelector('button');
    btn.addEventListener('click', () => {
        navigator.clipboard.writeText(pre.innerText);
        btn.textContent = 'Copied';
        setTimeout(() => { btn.textContent = 'Copy'; }, 2000);
    });
});
</script>
@endpush
@endsection
