@extends('layouts.public')

@section('title', 'Case Studies | Slamet Rivai')
@section('meta_description', 'Production systems case studies: OCR document pipelines, WhatsApp Cloud API engines, omnichannel CRM architectures, and workforce automation.')

@php
    $allActive = empty($selectedCategory) || $selectedCategory === 'All';
    $tabs = [['label' => 'All', 'count' => $totals['case studies'], 'url' => route('projects.index', array_filter(['q' => $search, 'sort' => $sort === 'featured' ? null : $sort])), 'active' => $allActive]];
    foreach ($categoriesWithCount as $catName => $count) {
        $tabs[] = ['label' => $catName, 'count' => $count, 'url' => route('projects.index', array_filter(['category' => $catName, 'q' => $search, 'sort' => $sort === 'featured' ? null : $sort])), 'active' => $selectedCategory === $catName];
    }
@endphp

@section('content')
    @include('partials.page-header', [
        'current' => 'Case Studies',
        'title' => 'Case',
        'accent' => 'studies',
        'lead' => 'Production systems behind the numbers: OCR pipelines, WhatsApp Cloud API engines, omnichannel CRM dispatch and workforce scheduling. Each one starts with an operational bottleneck and ends with a measured result.',
        'counts' => $totals,
    ])

    @include('partials.filter-bar', [
        'label' => 'Filter by category',
        'tabs' => $tabs,
        'action' => route('projects.index'),
        'hidden' => ['category' => $allActive ? null : $selectedCategory, 'sort' => $sort === 'featured' ? null : $sort],
        'search' => $search,
        'placeholder' => 'Search systems or tools…',
    ])

    @if($featuredProject)
        @php $featuredThumb = $featuredProject->thumbnail_url; @endphp
        <section class="band-dark rounded-[2rem] lg:rounded-[3rem]" aria-labelledby="featured-title">
            <div class="page-container flex flex-col gap-5 py-10 lg:gap-6 lg:py-14">
                <p class="flex items-center gap-2 text-sm font-bold uppercase tracking-[0.1em] text-sky">
                    <x-lucide-star class="h-4 w-4" />
                    Featured
                </p>
                <article class="glass-card relative flex flex-col overflow-hidden rounded-3xl transition-colors hover:bg-white/[0.11] lg:min-h-[31.25rem] lg:flex-row lg:rounded-[1.75rem]">
                    <div class="flex flex-col justify-between gap-6 p-5 lg:p-9 {{ $featuredThumb ? 'lg:w-[30.625rem] lg:shrink-0' : 'lg:flex-1' }}">
                        <div class="flex flex-col gap-3.5">
                            <p class="text-sm font-semibold text-mist lg:text-[0.9375rem]">{{ $featuredProject->category }} · {{ $featuredProject->role }}</p>
                            <h2 id="featured-title" class="text-[1.75rem] font-semibold leading-8 text-white lg:text-[2.125rem] lg:leading-[2.4375rem]">
                                <a href="{{ route('projects.show', $featuredProject->slug) }}" class="after:absolute after:inset-0">{{ $featuredProject->title }}</a>
                            </h2>
                            <p class="text-base leading-6 text-mist">{{ $featuredProject->teaser }}</p>
                        </div>
                        <div class="flex flex-col gap-[1.125rem]">
                            @if(!empty($featuredProject->impact_highlights))
                                <dl class="flex flex-wrap gap-x-8 gap-y-3">
                                    @foreach(array_slice($featuredProject->impact_highlights, 0, 2) as $h)
                                        <div class="flex flex-col-reverse gap-1">
                                            <dt class="text-sm text-mist lg:text-[0.9375rem]">{{ $h['label'] }}</dt>
                                            <dd class="text-[1.625rem] font-bold text-sky lg:text-[2rem]">{{ $h['metric'] }}</dd>
                                        </div>
                                    @endforeach
                                </dl>
                            @endif
                            @include('projects._flow', ['project' => $featuredProject])
                            <span class="inline-flex items-center gap-1.5 text-base font-bold text-white" aria-hidden="true">
                                Read full case study
                                <x-lucide-arrow-up-right class="h-[1.125rem] w-[1.125rem]" />
                            </span>
                        </div>
                    </div>
                    @include('projects._thumb', ['project' => $featuredProject, 'url' => $featuredThumb])
                </article>
            </div>
        </section>
    @endif

    <section class="page-container flex flex-col gap-6 py-12 lg:gap-8 lg:py-[4.5rem]" aria-labelledby="list-title">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 id="list-title" class="text-[2rem] font-semibold text-navy lg:text-[2.75rem]">
                {{ $featuredProject ? 'All case studies' : ($search ? 'Results for “' . $search . '”' : $selectedCategory) }}
            </h2>
            @include('partials.sort-menu', ['options' => \App\Http\Controllers\ProjectPublicController::SORTS, 'current' => $sort, 'prefix' => 'Sort: '])
        </div>

        <ul class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:gap-6">
            @forelse($projects as $project)
                @include('projects._card', ['project' => $project])
            @empty
                <li class="col-span-full rounded-3xl bg-panel px-5 py-16 text-center">
                    <p class="text-lg font-bold text-navy">No case studies match your search</p>
                    <p class="mt-1 text-base text-body">Try a different keyword or pick another category.</p>
                    <a href="{{ route('projects.index') }}" class="link-brand mt-2">Clear filters</a>
                </li>
            @endforelse
        </ul>

        @if($projects->hasPages())
            <div class="mt-4">{{ $projects->links() }}</div>
        @endif
    </section>

    <div class="px-3 pb-12 sm:px-5 lg:px-10 lg:pb-20">
        <section class="band-dark flex flex-col items-center gap-3.5 rounded-[2rem] px-5 py-10 text-center lg:rounded-[3rem] lg:px-20 lg:py-16" aria-labelledby="cta-title">
            <h2 id="cta-title" class="text-[2rem] font-semibold leading-tight text-white lg:text-5xl">Facing a similar operational bottleneck?</h2>
            <p class="text-base leading-6 text-mist lg:text-lg">Tell me where the bottleneck is. Send a message and I'll reply by email.</p>
            <a href="{{ route('home') }}#contact" data-open-contact aria-haspopup="dialog" class="btn-primary mt-1 min-h-[3.25rem] px-[1.875rem] text-[1.0625rem]">
                Send an inquiry
                <x-lucide-arrow-up-right class="h-[1.125rem] w-[1.125rem]" />
            </a>
        </section>
    </div>
@endsection
