@extends('layouts.public')

@section('title', $project->title . ' | Case Study | Slamet Rivai')
@section('meta_description', Str::limit($project->teaser, 150))
@section('og_image', $project->cover_image_url)

@php
    $steps = $project->steps;
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
    $shot = 'group relative block w-full overflow-hidden rounded-[1.25rem] border border-line bg-panel text-left';
    $shotImg = 'absolute inset-0 h-full w-full object-cover transition duration-300 ease-out group-hover:opacity-90 motion-safe:group-hover:scale-[1.02]';
    $roundBtn = 'inline-flex shrink-0 items-center justify-center rounded-full border border-white/20 bg-white/10 text-white transition-colors hover:bg-white/20';
@endphp

@section('content')
    <header class="page-container flex flex-col gap-[1.125rem] pb-8 pt-8 lg:pb-10 lg:pt-12">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('projects.index') }}" class="link-brand">
                <x-lucide-arrow-left class="h-[1.125rem] w-[1.125rem]" />
                All case studies
            </a>
            <p class="flex flex-wrap items-center gap-2">
                <span class="chip px-3.5 py-1.5">{{ $project->category }}</span>
                @if($project->is_featured)
                    <span class="chip bg-brand/[0.12] px-3.5 py-1.5 text-brand-dark">
                        <x-lucide-star class="h-3.5 w-3.5" />
                        Featured system
                    </span>
                @endif
            </p>
        </div>

        <p class="text-[0.9375rem] font-semibold text-body">Case study · {{ $project->role }}</p>
        <h1 class="max-w-[65rem] text-[2.5rem] font-semibold leading-[1.1] tracking-[-0.0625rem] text-navy lg:text-[4rem] lg:leading-[4.3125rem]">{{ $project->title }}</h1>
        <p class="max-w-[53.75rem] text-[1.0625rem] leading-[1.625rem] text-body lg:text-[1.1875rem] lg:leading-[1.8125rem]">{{ $project->teaser }}</p>

        @if(!empty($project->impact_highlights))
            <dl class="mt-2 grid grid-cols-1 gap-5 border-y border-line py-6 sm:grid-cols-3 sm:gap-0 lg:py-7" aria-label="Measured impact">
                @foreach(array_slice($project->impact_highlights, 0, 3) as $h)
                    <div class="flex flex-col gap-1 sm:border-r sm:border-line sm:px-6 sm:first:pl-0 sm:last:border-r-0">
                        <dt class="order-2 text-base font-bold text-navy">{{ $h['label'] }}</dt>
                        <dd class="order-1 text-[2rem] font-bold leading-tight text-brand lg:text-[2.5rem]">{{ $h['metric'] }}</dd>
                        @if(!empty($h['detail']))
                            <dd class="order-3 text-[0.9375rem] text-body">{{ $h['detail'] }}</dd>
                        @endif
                    </div>
                @endforeach
            </dl>
        @endif
    </header>

    @if($imageCount)
        <section class="page-container flex flex-col gap-6 pb-12 pt-4 lg:gap-7 lg:pb-[4.5rem] lg:pt-8" aria-labelledby="gallery-title" x-data="projectGallery(@js($gallery))">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div class="flex flex-col gap-1.5">
                    <p class="eyebrow lg:text-sm">In production</p>
                    <h2 id="gallery-title" class="text-[2rem] font-semibold leading-tight text-navy lg:text-[2.5rem]">Screens from the live system</h2>
                </div>
                <button type="button" @click="open(0)" class="btn-outline shrink-0 self-start sm:self-auto">
                    <x-lucide-images class="h-[1.125rem] w-[1.125rem]" />
                    View all {{ $imageCount }} {{ Str::plural('screen', $imageCount) }}
                </button>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:gap-5 {{ $imageCount > 1 ? 'lg:h-[30rem] lg:grid-cols-[51.25rem_minmax(0,1fr)]' : '' }}">
                @foreach($gallery->take(3) as $i => $item)
                    @if($i === 1)
                        <div class="grid {{ $imageCount > 2 ? 'grid-cols-2' : 'grid-cols-1' }} gap-4 lg:flex lg:flex-col lg:gap-5">
                    @endif
                    <button type="button" @click="open({{ $i }})" class="{{ $shot }} {{ $i === 0 ? 'aspect-[820/480] lg:aspect-auto ' . ($imageCount > 1 ? 'lg:h-full' : 'lg:h-[30rem]') : 'aspect-[440/240] lg:aspect-auto lg:min-h-0 lg:flex-1' }}">
                        <img src="{{ $item['url'] }}" alt="{{ $item['title'] }}" loading="lazy" decoding="async" class="{{ $shotImg }}">
                        @if($i === 2 && $imageCount > 3)
                            <span class="absolute inset-0 flex items-center justify-center gap-2.5 bg-ink/80">
                                <span class="text-[2rem] font-bold text-white lg:text-[2.75rem]">+{{ $imageCount - 3 }}</span>
                                <span class="text-[0.9375rem] font-semibold text-white lg:text-lg">more {{ Str::plural('screen', $imageCount - 3) }}</span>
                            </span>
                        @else
                            <span class="absolute bottom-3 left-3 max-w-[calc(100%-1.5rem)] truncate rounded-xl bg-ink/85 px-3 py-1.5 text-sm font-bold text-white">{{ $item['title'] }}</span>
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
                    class="on-dark fixed inset-0 m-0 h-dvh max-h-none w-screen max-w-none bg-ink p-0 text-white backdrop:bg-ink">
                <div class="flex h-full flex-col">
                    <div class="flex items-center justify-between gap-4 px-4 py-4 sm:px-8 sm:py-5">
                        <p class="truncate text-[0.9375rem] font-medium text-mist">{{ $project->title }}</p>
                        <div class="flex shrink-0 items-center gap-4">
                            <p class="text-[0.9375rem] font-semibold tabular-nums text-white" aria-live="polite">
                                <span x-text="index + 1"></span> / {{ $imageCount }}
                            </p>
                            <button type="button" @click="close()" aria-label="Close gallery" class="{{ $roundBtn }} h-11 w-11">
                                <x-lucide-x class="h-[1.125rem] w-[1.125rem]" />
                            </button>
                        </div>
                    </div>

                    <div class="flex min-h-0 flex-1 items-center gap-6 px-4 sm:px-8"
                         @pointerdown="swipeStart($event)" @pointerup="swipeEnd($event)">
                        <button type="button" @click="step(-1)" :disabled="index === 0" aria-label="Previous screen"
                                class="{{ $roundBtn }} hidden h-12 w-12 disabled:pointer-events-none disabled:opacity-40 sm:inline-flex">
                            <x-lucide-chevron-left class="h-5 w-5" />
                        </button>

                        <div class="flex h-full min-w-0 flex-1 flex-col items-center justify-center gap-5" @click.self="close()">
                            <div class="flex min-h-0 w-full max-w-[71rem] flex-1 items-center justify-center" @click.self="close()">
                                <img :src="current.url" :alt="current.title" draggable="false"
                                     class="max-h-full max-w-full select-none rounded-[1.25rem] object-contain">
                            </div>
                            <div class="flex w-full max-w-[71rem] flex-col justify-between gap-4 sm:flex-row sm:gap-10">
                                <div class="min-w-0 flex-1">
                                    <p class="text-lg font-semibold text-white" x-text="current.title"></p>
                                    <p x-show="current.description" class="mt-1.5 text-[0.9375rem] leading-[1.375rem] text-mist" x-text="current.description"></p>
                                </div>
                                <p class="hidden shrink-0 items-center gap-2 self-start text-sm text-mist [@media(hover:hover)]:flex" aria-hidden="true">
                                    <kbd class="rounded-md border border-white/20 px-2 py-[0.1875rem] font-sans font-semibold">←</kbd>
                                    <kbd class="rounded-md border border-white/20 px-2 py-[0.1875rem] font-sans font-semibold">→</kbd>
                                    to navigate · Esc to close
                                </p>
                            </div>
                        </div>

                        <button type="button" @click="step(1)" :disabled="index === images.length - 1" aria-label="Next screen"
                                class="{{ $roundBtn }} hidden h-12 w-12 disabled:pointer-events-none disabled:opacity-40 sm:inline-flex">
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
                                             :class="index === {{ $i }} ? 'border-2 border-sky' : 'border border-white/20 opacity-60 hover:opacity-100'">
                                        <span class="mt-2 block truncate text-xs"
                                              :class="index === {{ $i }} ? 'font-bold text-white' : 'font-medium text-mist'">{{ $item['title'] }}</span>
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
        <section class="band-dark rounded-[2rem] lg:rounded-[3rem]" aria-labelledby="pipeline-title">
            <div class="page-container flex flex-col gap-6 py-12 lg:gap-9 lg:py-[4.5rem]">
                <div class="flex flex-col gap-1.5">
                    <p class="eyebrow text-sky lg:text-sm">Architecture & workflow</p>
                    <h2 id="pipeline-title" class="text-[2rem] font-semibold leading-tight text-white lg:text-[2.75rem] lg:leading-[3.1875rem]">How work moves through the pipeline</h2>
                </div>
                <ol class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-flow-col lg:auto-cols-fr lg:grid-cols-none lg:gap-5">
                    @foreach($steps as $i => $step)
                        <li class="glass-card flex flex-col gap-3 rounded-[1.25rem] p-5 lg:gap-[1.125rem] lg:p-6">
                            <span class="text-[1.375rem] font-bold tabular-nums text-sky">{{ sprintf('%02d', $i + 1) }}</span>
                            <h3 class="text-[1.375rem] font-semibold text-white lg:text-[1.625rem]">{{ $step['name'] }}</h3>
                            @if($step['description'])
                                <p class="text-[0.9375rem] leading-[1.4375rem] text-mist">{{ $step['description'] }}</p>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    @endif

    <div class="page-container flex flex-col gap-10 py-12 lg:flex-row lg:gap-[4.5rem] lg:py-[5.5rem]">
        <div class="flex min-w-0 flex-1 flex-col gap-10 lg:gap-12">
            @foreach($story as $i => $section)
                <section class="flex flex-col gap-2" aria-labelledby="story-{{ $i }}">
                    <p class="text-sm font-bold uppercase tracking-[0.0625rem] text-brand">{{ sprintf('%02d', $i + 1) }} · {{ $section['eyebrow'] }}</p>
                    <h2 id="story-{{ $i }}" class="text-[2rem] font-semibold leading-tight text-navy lg:text-[2.5rem]">{{ $section['title'] }}</h2>
                    <p class="text-[1.0625rem] leading-7 text-body lg:text-lg lg:leading-[1.875rem]">{{ $section['text'] }}</p>
                </section>
            @endforeach
        </div>

        <aside class="flex flex-col gap-4 lg:w-[23.75rem] lg:shrink-0">
            <div class="flex flex-col gap-3.5 rounded-3xl bg-panel p-6">
                <h2 class="text-[1.375rem] font-bold text-navy">Overview</h2>
                <dl class="flex flex-col gap-3.5">
                    @foreach($overview as $key => $value)
                        <div class="flex flex-col gap-0.5">
                            <dt class="text-sm font-semibold text-body">{{ $key }}</dt>
                            <dd class="text-base font-semibold leading-[1.375rem] text-navy">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
                @if(!empty($project->components))
                    <div>
                        <h3 class="text-sm font-semibold text-body">Key components</h3>
                        <ul class="mt-2 flex flex-wrap gap-1.5">
                            @foreach($project->components as $component)
                                <li class="rounded-full bg-white px-2.5 py-1 text-sm font-semibold text-navy">{{ $component }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="band-dark flex flex-col items-start gap-3 rounded-3xl p-6">
                <h2 class="text-2xl font-semibold leading-[1.8125rem] text-white">Facing a similar bottleneck?</h2>
                <p class="text-[0.9375rem] leading-[1.4375rem] text-mist">Workflow automation, WhatsApp or CRM API integration, or contact-center efficiency: tell me what you're working on.</p>
                <a href="#contact" data-open-contact aria-haspopup="dialog" class="btn-primary px-6">Contact me</a>
            </div>
        </aside>
    </div>

    @if($otherProjects->isNotEmpty())
        <section class="page-container flex flex-col gap-6 pb-12 lg:gap-7 lg:pb-[5.5rem]" aria-labelledby="more-title">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 id="more-title" class="text-[2rem] font-semibold text-navy lg:text-[2.5rem]">More case studies</h2>
                <a href="{{ route('projects.index') }}" class="link-brand shrink-0">All case studies <x-lucide-arrow-right class="h-[1.125rem] w-[1.125rem]" /></a>
            </div>
            <ul class="grid grid-cols-1 gap-5 md:grid-cols-3 lg:gap-6">
                @foreach($otherProjects as $other)
                    @php
                        $metric = $other->impact_highlights[0] ?? null;
                        $otherThumb = $other->thumbnail_url;
                    @endphp
                    <li class="group relative flex flex-col overflow-hidden rounded-[1.25rem] border border-line transition-colors hover:border-brand">
                        <div class="flex h-[12.5rem] items-center justify-center overflow-hidden bg-panel">
                            @if($otherThumb)
                                <img src="{{ $otherThumb }}" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover">
                            @else
                                <x-lucide-layers class="h-8 w-8 text-brand" aria-hidden="true" />
                            @endif
                        </div>
                        <div class="flex flex-col gap-2 p-5">
                            <p class="text-sm font-bold text-body">{{ $other->category }}</p>
                            <h3 class="text-xl font-semibold leading-[1.5625rem] text-navy">
                                <a href="{{ route('projects.show', $other->slug) }}" class="after:absolute after:inset-0">{{ $other->title }}</a>
                            </h3>
                            @if($metric)
                                <p class="flex flex-col gap-1">
                                    <span class="text-2xl font-bold text-brand">{{ $metric['metric'] }}</span>
                                    <span class="text-sm leading-[1.125rem] text-body">{{ $metric['label'] }}</span>
                                </p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </section>
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
