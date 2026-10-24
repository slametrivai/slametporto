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
        'title' => 'Case studies',
        'lead' => 'Production systems behind the numbers — OCR pipelines, WhatsApp Cloud API engines, omnichannel CRM dispatch and workforce scheduling. Each one starts with an operational bottleneck and ends with a measured result.',
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
        <section class="page-container pt-12" aria-labelledby="featured-title">
            <p class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.12em] text-accent">
                <x-lucide-star class="h-3.5 w-3.5" />
                Featured
            </p>
            <article class="relative mt-5 flex flex-col overflow-hidden rounded-2xl border border-edge bg-surface transition hover:border-content-faint lg:min-h-[28.75rem] lg:flex-row">
                <div class="flex flex-col justify-center gap-7 p-6 sm:p-12 {{ $featuredThumb ? 'lg:w-[38.75rem] lg:shrink-0' : 'lg:flex-1' }}">
                    <div>
                        <p class="text-[0.8125rem] font-medium text-content-secondary">{{ $featuredProject->category }} · {{ $featuredProject->role }}</p>
                        <h2 id="featured-title" class="mt-4 text-[1.625rem] font-bold leading-9 tracking-[-0.02em] text-content sm:text-[1.875rem]">
                            <a href="{{ route('projects.show', $featuredProject->slug) }}" class="after:absolute after:inset-0">{{ $featuredProject->title }}</a>
                        </h2>
                        <p class="mt-4 text-base leading-[1.625rem] text-content-secondary">{{ $featuredProject->teaser }}</p>
                    </div>
                    @if(!empty($featuredProject->impact_highlights))
                        <dl class="flex flex-wrap gap-x-8 gap-y-5">
                            @foreach(array_slice($featuredProject->impact_highlights, 0, 3) as $h)
                                <div class="flex flex-col-reverse gap-1.5">
                                    <dt class="text-[0.8125rem] text-content-secondary">{{ $h['label'] }}</dt>
                                    <dd class="text-[1.625rem] font-bold tracking-[-0.02em] text-accent">{{ $h['metric'] }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    @endif
                    @include('projects._flow', ['project' => $featuredProject])
                    <span class="inline-flex items-center gap-2 text-[0.9375rem] font-semibold text-content" aria-hidden="true">
                        Read full case study
                        <x-lucide-arrow-right class="h-4 w-4" />
                    </span>
                </div>
                @include('projects._thumb', ['project' => $featuredProject, 'url' => $featuredThumb])
            </article>
        </section>
    @endif

    <section class="page-container pb-20 pt-16 sm:pb-[7.5rem] sm:pt-[4.5rem]" aria-labelledby="list-title">
        <div class="flex items-center justify-between gap-4 pb-4">
            <h2 id="list-title" class="text-[1.375rem] font-bold tracking-[-0.018em] text-content">
                {{ $featuredProject ? 'All case studies' : ($search ? 'Results for “' . $search . '”' : $selectedCategory) }}
            </h2>
            @include('partials.sort-menu', ['options' => \App\Http\Controllers\ProjectPublicController::SORTS, 'current' => $sort, 'prefix' => 'Sort: '])
        </div>

        <ul>
            @forelse($projects as $project)
                @include('projects._card', ['project' => $project])
            @empty
                <li class="border-t border-edge py-16 text-center">
                    <p class="text-base font-semibold text-content">No case studies match your search</p>
                    <p class="mt-1 text-sm text-content-secondary">Try a different keyword or pick another category.</p>
                    <a href="{{ route('projects.index') }}" class="link-accent mt-2">Clear filters</a>
                </li>
            @endforelse
        </ul>

        @if($projects->hasPages())
            <div class="mt-10">{{ $projects->links() }}</div>
        @endif
    </section>

    @include('partials.cta', ['title' => 'Facing a similar operational bottleneck?'])
@endsection
