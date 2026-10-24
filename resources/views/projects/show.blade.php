@extends('layouts.public')

@section('title', $project->title . ' | Case Study | Slamet Rivai')
@section('meta_description', Str::limit($project->teaser, 150))
@section('og_image', $project->cover_image_url)

@php
    $steps = $project->steps;
    $stepIcons = ['file-text', 'scan-text', 'shield-check', 'database'];
    // The cover is screen 01, followed by the uploaded gallery.
    $gallery = $project->screens->map(fn ($screen, $i) => [
        'url' => $screen['url'],
        'title' => $screen['title'] ?: 'Screen ' . sprintf('%02d', $i + 1),
        'description' => $screen['description'],
    ]);
    $imageCount = $gallery->count();
    $story = array_values(array_filter([
        ['eyebrow' => 'Operational bottlenecks', 'title' => 'The challenge', 'text' => $project->challenge],
        ['eyebrow' => 'Architecture & implementation', 'title' => 'The solution', 'text' => $project->solution],
        $project->result ? ['eyebrow' => 'Measured outcome', 'title' => 'The result', 'text' => $project->result] : null,
    ]));
    $overview = array_filter([
        'Domain' => $project->category,
        'My role' => $project->role,
        'Volume' => $project->volume,
        'Status' => $project->is_featured ? 'Featured system' : null,
    ]);
@endphp

@section('content')
    <header class="page-container pb-14 pt-10 sm:pt-12">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('projects.index') }}" class="inline-flex min-h-11 items-center gap-2 text-sm font-medium text-content-secondary transition hover:text-content">
                <x-lucide-arrow-left class="h-4 w-4" />
                All case studies
            </a>
            <p class="flex flex-wrap items-center gap-2">
                <span class="chip border border-edge text-content-secondary">{{ $project->category }}</span>
                @if($project->is_featured)
                    <span class="chip bg-accent/10 font-semibold text-accent">
                        <x-lucide-star class="h-3 w-3" />
                        Featured system
                    </span>
                @endif
            </p>
        </div>

        <div class="mt-10 max-w-[61.25rem]">
            <p class="text-[0.9375rem] font-medium text-content-secondary">Case study · {{ $project->role }}</p>
            <h1 class="mt-5 text-[2.25rem] font-bold leading-[1.1] tracking-[-0.03em] text-content sm:text-5xl lg:text-[3.75rem] lg:leading-[4rem]">{{ $project->title }}</h1>
            <p class="mt-5 max-w-[48.75rem] text-lg leading-[1.875rem] text-content-secondary sm:text-[1.1875rem]">{{ $project->teaser }}</p>
        </div>
    </header>

    @if(!empty($project->impact_highlights))
        <section class="page-container" aria-label="Measured impact">
            <dl class="grid grid-cols-1 divide-y divide-edge border-y border-edge sm:grid-cols-3 sm:divide-x sm:divide-y-0">
                @foreach(array_slice($project->impact_highlights, 0, 3) as $h)
                    <div class="flex flex-col gap-2 py-8 sm:px-8 sm:py-9 sm:first:pl-0">
                        <dt class="order-2 text-base font-semibold text-content">{{ $h['label'] }}</dt>
                        <dd class="order-1 text-[2.25rem] font-bold leading-tight tracking-[-0.027em] text-accent lg:text-[2.75rem]">{{ $h['metric'] }}</dd>
                        @if(!empty($h['detail']))
                            <dd class="order-3 text-sm text-content-secondary">{{ $h['detail'] }}</dd>
                        @endif
                    </div>
                @endforeach
            </dl>
        </section>
    @endif

    @if($imageCount)
        <section class="page-container pt-20 sm:pt-24" aria-labelledby="gallery-title" x-data="projectGallery(@js($gallery))">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <p class="eyebrow">In production</p>
                    <h2 id="gallery-title" class="mt-3 text-[1.75rem] font-bold tracking-[-0.025em] text-content sm:text-[2rem]">Screens from the live system</h2>
                </div>
                <button type="button" @click="open(0)" class="inline-flex min-h-11 shrink-0 items-center gap-2 self-start rounded-lg border border-edge px-3.5 text-sm font-medium text-content transition hover:border-content-faint sm:self-auto">
                    <x-lucide-images class="h-4 w-4 text-content-secondary" />
                    View all {{ $imageCount }} {{ Str::plural('screen', $imageCount) }}
                </button>
            </div>

            @php
                $shot = 'group relative block w-full overflow-hidden rounded-[0.875rem] border border-edge bg-surface-inset text-left';
                $shotImg = 'absolute inset-0 h-full w-full object-cover transition duration-300 ease-out group-hover:opacity-90 motion-safe:group-hover:scale-[1.02]';
            @endphp
            <div class="mt-8 grid grid-cols-1 gap-4 {{ $imageCount > 1 ? 'lg:grid-cols-[minmax(0,1fr)_440px]' : '' }}">
                @foreach($gallery->take(3) as $i => $item)
                    @if($i === 1)
                        <div class="grid {{ $imageCount > 2 ? 'grid-cols-2' : 'grid-cols-1' }} gap-4 lg:flex lg:flex-col">
                    @endif
                    <button type="button" @click="open({{ $i }})" class="{{ $shot }} {{ $i === 0 ? 'aspect-[824/496]' : 'aspect-[440/240] lg:aspect-auto lg:min-h-0 lg:flex-1' }}">
                        <img src="{{ $item['url'] }}" alt="{{ $item['title'] }}" loading="lazy" decoding="async" class="{{ $shotImg }}">
                        <span class="absolute right-3 top-3 hidden h-9 w-9 items-center justify-center rounded-lg bg-void/80 text-content sm:flex" aria-hidden="true">
                            <x-lucide-maximize-2 class="h-4 w-4" />
                        </span>
                        @if($i === 2 && $imageCount > 3)
                            <span class="absolute inset-0 flex flex-col items-center justify-center gap-1 bg-void/70">
                                <span class="text-[2rem] font-bold text-content">+{{ $imageCount - 3 }}</span>
                                <span class="text-[0.8125rem] text-content-secondary">more {{ Str::plural('screen', $imageCount - 3) }}</span>
                            </span>
                        @else
                            <span class="absolute bottom-3 left-3 flex max-w-[calc(100%-1.5rem)] items-center gap-2 rounded-lg bg-void/85 px-3 py-2">
                                <span class="text-xs font-bold tabular-nums text-accent">{{ sprintf('%02d', $i + 1) }}</span>
                                <span class="truncate text-[0.8125rem] font-medium text-content">{{ $item['title'] }}</span>
                            </span>
                        @endif
                    </button>
                    @if($i > 0 && $loop->last)
                        </div>
                    @endif
                @endforeach
            </div>

            <dialog x-ref="lightbox" aria-label="{{ $project->title }} screens"
                    @close="document.documentElement.style.overflow = ''"
                    @keydown.arrow-left.prevent="step(-1)" @keydown.arrow-right.prevent="step(1)"
                    class="fixed inset-0 m-0 h-dvh max-h-none w-screen max-w-none bg-void-deep p-0 text-content backdrop:bg-void-deep">
                <div class="flex h-full flex-col">
                    <div class="flex items-center justify-between gap-4 px-4 py-4 sm:px-8 sm:py-5">
                        <p class="truncate text-sm font-medium text-content-secondary">{{ $project->title }}</p>
                        <div class="flex shrink-0 items-center gap-4">
                            <p class="text-sm font-semibold tabular-nums text-content" aria-live="polite">
                                <span x-text="index + 1"></span> / {{ $imageCount }}
                            </p>
                            <button type="button" @click="close()" aria-label="Close gallery"
                                    class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-edge bg-surface text-content transition hover:border-content-faint">
                                <x-lucide-x class="h-[1.125rem] w-[1.125rem]" />
                            </button>
                        </div>
                    </div>

                    <div class="flex min-h-0 flex-1 items-center gap-6 px-4 sm:px-8"
                         @pointerdown="swipeStart($event)" @pointerup="swipeEnd($event)">
                        <button type="button" @click="step(-1)" :disabled="index === 0" aria-label="Previous screen"
                                class="hidden h-12 w-12 shrink-0 items-center justify-center rounded-full border border-edge bg-surface text-content transition hover:border-content-faint disabled:pointer-events-none disabled:opacity-40 sm:inline-flex">
                            <x-lucide-chevron-left class="h-5 w-5" />
                        </button>

                        <div class="flex h-full min-w-0 flex-1 flex-col items-center justify-center gap-5" @click.self="close()">
                            <div class="flex min-h-0 w-full max-w-[71rem] flex-1 items-center justify-center" @click.self="close()">
                                <img :src="current.url" :alt="current.title" draggable="false"
                                     class="max-h-full max-w-full select-none rounded-xl border border-edge object-contain">
                            </div>
                            <div class="flex w-full max-w-[71rem] flex-col justify-between gap-4 sm:flex-row sm:gap-10">
                                <div class="min-w-0 flex-1">
                                    <p class="text-lg font-semibold text-content" x-text="current.title"></p>
                                    <p x-show="current.description" class="mt-1.5 text-sm leading-[1.3125rem] text-content-secondary" x-text="current.description"></p>
                                </div>
                                <p class="hidden shrink-0 items-center gap-2 self-start text-xs text-content-faint [@media(hover:hover)]:flex" aria-hidden="true">
                                    <kbd class="rounded-md border border-edge px-2 py-[0.1875rem] font-sans font-semibold text-content-secondary">←</kbd>
                                    <kbd class="rounded-md border border-edge px-2 py-[0.1875rem] font-sans font-semibold text-content-secondary">→</kbd>
                                    to navigate · Esc to close
                                </p>
                            </div>
                        </div>

                        <button type="button" @click="step(1)" :disabled="index === images.length - 1" aria-label="Next screen"
                                class="hidden h-12 w-12 shrink-0 items-center justify-center rounded-full border border-edge bg-surface text-content transition hover:border-content-faint disabled:pointer-events-none disabled:opacity-40 sm:inline-flex">
                            <x-lucide-chevron-right class="h-5 w-5" />
                        </button>
                    </div>

                    @if($imageCount > 1)
                        <ul class="flex gap-2.5 overflow-x-auto px-4 pb-7 pt-5 sm:justify-center sm:px-8" aria-label="All screens">
                            @foreach($gallery as $i => $item)
                                <li class="w-24 shrink-0 sm:w-[8.25rem]">
                                    <button type="button" @click="index = {{ $i }}" :aria-current="index === {{ $i }} ? 'true' : null" x-ref="thumb{{ $i }}"
                                            class="block w-full text-left">
                                        <img src="{{ $item['url'] }}" alt="" loading="lazy"
                                             class="h-14 w-full rounded-lg object-cover transition sm:h-[4.75rem]"
                                             :class="index === {{ $i }} ? 'border-2 border-accent' : 'border border-edge opacity-60 hover:opacity-100'">
                                        <span class="mt-2 block truncate text-[0.6875rem]"
                                              :class="index === {{ $i }} ? 'font-semibold text-content' : 'font-medium text-content-faint'">{{ $item['title'] }}</span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="h-7"></div>
                    @endif
                </div>
            </dialog>
        </section>
    @endif

    @if($steps)
        <section class="page-container pt-20 sm:pt-24" aria-labelledby="pipeline-title">
            <p class="eyebrow">Architecture & workflow</p>
            <h2 id="pipeline-title" class="mt-3 text-[1.75rem] font-bold tracking-[-0.025em] text-content sm:text-[2rem]">How work moves through the pipeline</h2>

            <ol class="mt-8 flex flex-col items-stretch gap-3 rounded-[1.25rem] border border-edge bg-surface p-4 sm:p-8 lg:flex-row lg:items-center">
                @foreach($steps as $i => $step)
                    @php $last = $loop->last; @endphp
                    <li class="flex flex-1 flex-col gap-3.5 rounded-[0.875rem] border p-6 {{ $last ? 'border-accent/30 bg-accent/10' : 'border-edge bg-surface-inset' }}">
                        <div class="flex items-center justify-between">
                            <span class="flex h-10 w-10 items-center justify-center rounded-[0.625rem] {{ $last ? 'bg-accent/15 text-accent' : 'bg-surface-raised text-content-secondary' }}">
                                <x-dynamic-component :component="'lucide-' . $stepIcons[$i % count($stepIcons)]" class="h-5 w-5" />
                            </span>
                            <span class="text-[0.8125rem] font-semibold tabular-nums text-content-secondary">{{ sprintf('%02d', $i + 1) }}</span>
                        </div>
                        <h3 class="text-[1.0625rem] font-semibold text-content">{{ $step['name'] }}</h3>
                        @if($step['description'])
                            <p class="text-sm leading-[1.375rem] text-content-secondary">{{ $step['description'] }}</p>
                        @endif
                    </li>
                    @unless($last)
                        <li class="flex justify-center text-content-faint" aria-hidden="true">
                            <x-lucide-chevron-right class="h-5 w-5 rotate-90 lg:rotate-0" />
                        </li>
                    @endunless
                @endforeach
            </ol>
        </section>
    @endif

    <div class="page-container flex flex-col gap-14 pt-20 sm:pt-24 lg:flex-row lg:gap-20">
        <div class="flex-1 space-y-14">
            @foreach($story as $i => $section)
                <section class="flex gap-5 sm:gap-8 {{ $loop->last ? '' : 'border-b border-edge pb-14' }}" aria-labelledby="story-{{ $i }}">
                    <p class="w-8 shrink-0 text-[0.9375rem] font-bold tabular-nums text-accent sm:w-10" aria-hidden="true">{{ sprintf('%02d', $i + 1) }}</p>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-content-secondary">{{ $section['eyebrow'] }}</p>
                        <h2 id="story-{{ $i }}" class="mt-3 text-[1.625rem] font-bold tracking-[-0.02em] text-content sm:text-[1.875rem]">{{ $section['title'] }}</h2>
                        <p class="mt-3 text-[1.0625rem] leading-[1.875rem] text-content-body sm:text-lg sm:leading-[1.9375rem]">{{ $section['text'] }}</p>
                    </div>
                </section>
            @endforeach
        </div>

        <aside class="flex flex-col gap-5 lg:w-[23.75rem] lg:shrink-0">
            <div class="rounded-2xl border border-edge p-7">
                <h2 class="text-base font-semibold text-content">Overview</h2>
                <dl class="mt-5 divide-y divide-edge">
                    @foreach($overview as $key => $value)
                        <div class="flex flex-col gap-1 py-3">
                            <dt class="text-[0.8125rem] text-content-secondary">{{ $key }}</dt>
                            <dd class="text-[0.9375rem] font-medium text-content">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
                @if(!empty($project->components))
                    <div class="mt-5">
                        <h3 class="text-[0.8125rem] font-normal text-content-secondary">Key components</h3>
                        <ul class="mt-2.5 flex flex-wrap gap-1.5">
                            @foreach($project->components as $component)
                                <li class="rounded-md bg-surface-raised px-2.5 py-[0.3125rem] text-xs font-medium text-content-body">{{ $component }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="glow-corner rounded-2xl border border-accent/25 bg-surface p-7">
                <h2 class="text-xl font-bold tracking-[-0.015em] text-content">Facing a similar bottleneck?</h2>
                <p class="mt-4 text-sm leading-[1.375rem] text-content-secondary">Workflow automation, WhatsApp or CRM API integration, or contact-center efficiency — tell me what you're working on.</p>
                <a href="#contact" data-open-contact aria-haspopup="dialog" class="btn-primary mt-4 w-full min-h-12">
                    Contact me
                    <x-lucide-arrow-right class="h-4 w-4" />
                </a>
            </div>
        </aside>
    </div>

    @if($otherProjects->isNotEmpty())
        <section class="page-container py-20 sm:py-[7.5rem]" aria-labelledby="more-title">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <h2 id="more-title" class="text-[1.75rem] font-bold tracking-[-0.025em] text-content sm:text-[2rem]">More case studies</h2>
                <a href="{{ route('projects.index') }}" class="link-accent shrink-0">All case studies <x-lucide-arrow-right class="h-4 w-4" /></a>
            </div>
            <ul class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-3">
                @foreach($otherProjects as $other)
                    @php $metric = $other->impact_highlights[0] ?? null; @endphp
                    <li class="group relative flex flex-col gap-5 rounded-2xl border border-edge p-7 transition hover:border-content-faint">
                        <p class="text-[0.8125rem] font-medium text-content-secondary">{{ $other->category }}</p>
                        <h3 class="text-xl font-bold leading-[1.625rem] tracking-[-0.015em] text-content">
                            <a href="{{ route('projects.show', $other->slug) }}" class="after:absolute after:inset-0">{{ $other->title }}</a>
                        </h3>
                        <div class="mt-auto flex items-end justify-between gap-4 border-t border-edge pt-4">
                            @if($metric)
                                <p class="flex flex-col gap-1">
                                    <span class="text-[1.375rem] font-bold text-accent">{{ $metric['metric'] }}</span>
                                    <span class="text-[0.8125rem] text-content-secondary">{{ $metric['label'] }}</span>
                                </p>
                            @endif
                            <x-lucide-arrow-up-right class="ml-auto h-[1.125rem] w-[1.125rem] text-content-secondary transition group-hover:text-accent" />
                        </div>
                    </li>
                @endforeach
            </ul>
        </section>
    @else
        <div class="pb-20 sm:pb-[7.5rem]"></div>
    @endif
@endsection

@push('scripts')
<script>
function projectGallery(images) {
    return {
        images,
        index: 0,
        swipeX: null,
        get current() { return this.images[this.index]; },
        init() {
            this.$watch('index', (i) => this.$refs['thumb' + i]?.scrollIntoView({ block: 'nearest', inline: 'center' }));
        },
        open(i) {
            this.index = i;
            document.documentElement.style.overflow = 'hidden';
            this.$refs.lightbox.showModal();
        },
        close() { this.$refs.lightbox.close(); },
        step(delta) {
            this.index = Math.min(this.images.length - 1, Math.max(0, this.index + delta));
        },
        swipeStart(e) {
            if (e.pointerType !== 'mouse') this.swipeX = e.clientX;
        },
        swipeEnd(e) {
            if (this.swipeX === null) return;
            const dx = e.clientX - this.swipeX;
            this.swipeX = null;
            if (Math.abs(dx) > 50) this.step(dx < 0 ? 1 : -1);
        },
    };
}
</script>
@endpush
