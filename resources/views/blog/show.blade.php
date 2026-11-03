@extends('layouts.public')

@section('title', $post->title . ' | Slamet Rivai')
@section('meta_description', $post->excerpt)
@section('og_image', $post->cover_image_url)

@php
    $shareText = rawurlencode($post->title);
    $shareUrl = rawurlencode(url()->current());
    $shareBtn = 'inline-flex h-11 w-11 items-center justify-center rounded-full border border-line text-navy transition-colors hover:bg-panel';
    $postMeta = fn ($p) => collect([$p->category?->name, $p->published_at?->format('d M Y'), $p->reading_time . ' min read'])->filter()->implode(' · ');
@endphp

@section('content')
<article>
    <header class="page-container flex flex-col gap-[1.125rem] pb-8 pt-8 lg:pb-10 lg:pt-12">
        <a href="{{ route('blog.index') }}" class="link-brand self-start">
            <x-lucide-arrow-left class="h-[1.125rem] w-[1.125rem]" />
            All articles
        </a>

        <p class="flex flex-wrap items-center gap-3">
            @if($post->category)
                <a href="{{ route('blog.category', $post->category->slug) }}" class="chip px-3.5 py-1.5 transition-colors hover:bg-line/50">{{ $post->category->name }}</a>
            @endif
            <span class="text-[0.9375rem] font-semibold text-body">
                <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('d F Y') }}</time> · {{ $post->reading_time }} min read
            </span>
        </p>
        <h1 class="max-w-[67.5rem] text-[2.5rem] font-semibold leading-[1.1] tracking-[-0.0625rem] text-navy lg:text-[4rem] lg:leading-[4.3125rem]">{{ $post->title }}</h1>
        <p class="max-w-[56.25rem] text-[1.0625rem] leading-[1.625rem] text-body lg:text-[1.1875rem] lg:leading-[1.8125rem]">{{ $post->excerpt }}</p>

        <div class="mt-2 flex flex-wrap items-center justify-between gap-4 border-y border-line py-5">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/slamet-rivai-avatar.jpg') }}" alt="" class="h-12 w-12 rounded-full object-cover">
                <div>
                    <p class="text-[1.0625rem] font-bold text-navy">Slamet Rivai</p>
                    <p class="text-[0.9375rem] text-body">Operations & Systems Leader</p>
                </div>
            </div>
            <div class="flex items-center gap-2.5" x-data="{ copied: false }">
                <span class="hidden text-[0.9375rem] font-semibold text-body sm:inline">Share</span>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener" class="{{ $shareBtn }}" aria-label="Share on LinkedIn (opens in new tab)">
                    <x-lucide-linkedin class="h-[1.125rem] w-[1.125rem]" />
                </a>
                <a href="https://twitter.com/intent/tweet?text={{ $shareText }}&url={{ $shareUrl }}" target="_blank" rel="noopener" class="{{ $shareBtn }}" aria-label="Share on X (opens in new tab)">
                    <x-lucide-twitter class="h-[1.125rem] w-[1.125rem]" />
                </a>
                <button type="button" @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2000)"
                        class="inline-flex min-h-11 items-center gap-2 rounded-full border border-line px-4 text-[0.9375rem] font-semibold text-navy transition-colors hover:bg-panel">
                    <x-lucide-link class="h-[1.125rem] w-[1.125rem]" />
                    <span aria-live="polite" x-text="copied ? 'Link copied' : 'Copy link'">Copy link</span>
                </button>
            </div>
        </div>
    </header>

    @if($post->has_cover)
        <div class="page-container pb-8 lg:pb-10">
            <img src="{{ $post->cover_image_url }}" alt="" class="aspect-[1280/480] w-full rounded-3xl border border-line object-cover">
        </div>
    @endif

    <div class="page-container flex flex-col gap-8 pb-12 pt-2 lg:flex-row lg:gap-16 lg:pb-[4.5rem] lg:pt-6">
        <div class="order-2 min-w-0 flex-1 lg:order-1">
            <div id="article-body" class="article-body">
                {!! $htmlContent !!}
            </div>

            <div class="mt-10 flex flex-col items-start gap-4 rounded-3xl bg-panel p-5 sm:flex-row lg:mt-14 lg:p-6">
                <img src="{{ asset('images/slamet-rivai-avatar.jpg') }}" alt="" class="h-16 w-16 shrink-0 rounded-full object-cover">
                <div class="flex flex-1 flex-col items-start gap-1.5">
                    <h2 class="text-2xl font-semibold text-navy">Written by Slamet Rivai</h2>
                    <p class="text-[0.9375rem] leading-[1.4375rem] text-body">Operations & Systems Leader with 9+ years designing process automation, enterprise-scale contact center operations, and WhatsApp Cloud API and OCR pipeline integrations.</p>
                    <a href="#contact" data-open-contact aria-haspopup="dialog" class="inline-flex min-h-8 items-center text-base font-bold text-brand-dark transition-colors hover:text-brand">Get in touch</a>
                </div>
            </div>
        </div>

        <aside class="order-1 flex flex-col gap-4 lg:order-2 lg:w-[23.75rem] lg:shrink-0">
            <div class="flex flex-col gap-4 lg:sticky lg:top-28">
                @if(count($toc))
                    <nav aria-labelledby="toc-title">
                        <details id="toc" class="group rounded-3xl bg-panel" x-data="articleToc(@js(array_column($toc, 'id')))">
                            <summary class="flex min-h-11 cursor-pointer list-none items-center justify-between gap-3 px-[1.375rem] pt-[1.375rem] group-open:pb-1 lg:pointer-events-none [&:not(:is(.group[open]_*))]:pb-[1.375rem] [&::-webkit-details-marker]:hidden">
                                <h2 id="toc-title" class="text-lg font-bold text-navy">On this page</h2>
                                <span class="text-sm font-semibold tabular-nums text-body"><span x-text="progress">0</span>% read</span>
                            </summary>
                            <div class="px-[1.375rem] pb-[1.375rem]">
                                <div class="mt-2 h-[0.1875rem] rounded-full bg-line" aria-hidden="true">
                                    <div class="h-full rounded-full bg-brand" :style="`width: ${progress}%`"></div>
                                </div>
                                <ol id="table-of-contents" class="mt-3 flex max-h-[50vh] flex-col gap-2.5 overflow-y-auto">
                                    @foreach($toc as $item)
                                        <li>
                                            <a href="#{{ $item['id'] }}" @click="if (!desktop.matches) $root.open = false"
                                               :aria-current="active === @js($item['id']) ? 'location' : null"
                                               class="block text-[0.9375rem] leading-5 transition-colors"
                                               :class="active === @js($item['id']) ? 'font-bold text-brand-dark' : 'font-medium text-body hover:text-navy'">{{ $item['text'] }}</a>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        </details>
                    </nav>
                @endif

                @if($relatedProject)
                    @php $metrics = array_slice($relatedProject->impact_highlights ?? [], 0, 2); @endphp
                    <div class="band-dark relative hidden flex-col gap-2.5 rounded-3xl p-[1.375rem] lg:flex">
                        <p class="text-sm font-bold uppercase tracking-[0.0625rem] text-sky">See it in production</p>
                        <h2 class="text-[1.375rem] font-semibold leading-[1.625rem] text-white">
                            <a href="{{ route('projects.show', $relatedProject->slug) }}" class="after:absolute after:inset-0">{{ $relatedProject->title }}</a>
                        </h2>
                        @if($metrics)
                            <dl class="flex flex-col gap-2.5">
                                @foreach($metrics as $h)
                                    <div class="flex flex-col-reverse gap-0.5">
                                        <dt class="text-sm leading-[1.125rem] text-mist">{{ $h['label'] }}</dt>
                                        <dd class="text-2xl font-bold text-sky">{{ $h['metric'] }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        @endif
                        <span class="inline-flex items-center gap-1.5 text-base font-bold text-white" aria-hidden="true">
                            Read the case study
                            <x-lucide-arrow-up-right class="h-[1.125rem] w-[1.125rem]" />
                        </span>
                    </div>
                @endif
            </div>
        </aside>
    </div>
</article>

@if($relatedPosts->isNotEmpty())
    <section class="page-container flex flex-col gap-5 pb-12 lg:gap-6 lg:pb-[5.5rem]" aria-labelledby="keep-reading-title">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 id="keep-reading-title" class="text-[2rem] font-semibold text-navy lg:text-[2.5rem]">Keep reading</h2>
            <a href="{{ route('blog.index') }}" class="link-brand shrink-0">All articles</a>
        </div>
        <ul class="flex flex-col gap-4">
            @foreach($relatedPosts as $related)
                <li class="group relative flex flex-col gap-2 rounded-3xl bg-panel p-5 transition-colors hover:bg-line/40 lg:p-8">
                    <p class="text-sm font-semibold text-body lg:text-[0.9375rem]">{{ $postMeta($related) }}</p>
                    <h3 class="text-[1.625rem] font-semibold leading-8 text-navy lg:text-[1.875rem] lg:leading-9">
                        <a href="{{ route('blog.show', $related->slug) }}" class="after:absolute after:inset-0">{{ $related->title }}</a>
                    </h3>
                    <p class="text-base leading-[1.5625rem] text-body">{{ $related->excerpt }}</p>
                </li>
            @endforeach
        </ul>
    </section>
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

// Code blocks get a header with the language and a copy button.
document.querySelectorAll('#article-body > pre').forEach((pre) => {
    const lang = (pre.querySelector('code')?.className.match(/language-([\w-]+)/) || [])[1] || 'code';
    const wrap = document.createElement('div');
    wrap.className = 'code-block';
    wrap.innerHTML = `<div class="flex items-center justify-between border-b border-white/20 px-4 py-2.5">
        <span class="font-mono text-sm text-mist"></span>
        <button type="button" class="inline-flex min-h-8 items-center gap-1.5 text-sm font-bold text-mist transition-colors hover:text-white">Copy</button>
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
