@extends('layouts.public')

@section('title', 'Featured Systems & Case Studies | Slamet Rivai')
@section('meta_description', 'Production systems case studies: OCR document pipelines, WhatsApp Cloud API engines, omnichannel CRM architectures, and workforce automation.')

@php
    $chip = 'inline-flex min-h-11 items-center rounded-lg px-3.5 text-sm font-semibold transition';
    $chipOn = 'bg-accent text-void';
    $chipOff = 'border border-edge bg-surface text-content-secondary hover:border-accent-deep hover:text-accent';
    $allActive = empty($selectedCategory) || $selectedCategory === 'All';
@endphp

@section('content')
<div class="mx-auto max-w-7xl px-4 pb-16 pt-8 sm:px-6 lg:px-8">
    <div class="max-w-2xl">
        <p class="flex items-center gap-3 text-sm font-semibold text-accent-soft"><span class="h-px w-8 bg-accent-deep" aria-hidden="true"></span> Operational Engineering</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-content sm:text-4xl">Featured Systems & Case Studies</h1>
        <p class="mt-3 text-base text-content-secondary">Production operational blueprints: heuristic OCR parsing pipelines, WhatsApp Cloud API engines, omnichannel CRM dispatch, and workforce scheduling matrices.</p>
    </div>

    <div class="mt-8 flex flex-col items-stretch justify-between gap-4 border-b border-edge pb-6 md:flex-row md:items-center">
        <nav class="flex flex-wrap items-center gap-2" aria-label="Filter by category">
            <a href="{{ route('projects.index') }}" class="{{ $chip }} {{ $allActive ? $chipOn : $chipOff }}" @if($allActive) aria-current="page" @endif>All Systems</a>
            @foreach($categoriesWithCount as $catName => $count)
                <a href="{{ route('projects.index', ['category' => $catName]) }}" class="{{ $chip }} {{ $selectedCategory === $catName ? $chipOn : $chipOff }}" @if($selectedCategory === $catName) aria-current="page" @endif>
                    {{ $catName }} <span class="ml-1 tabular-nums opacity-80">({{ $count }})</span>
                </a>
            @endforeach
        </nav>

        <form action="{{ route('projects.index') }}" method="GET" role="search" class="relative w-full md:w-72">
            @if($selectedCategory)
                <input type="hidden" name="category" value="{{ $selectedCategory }}">
            @endif
            <label for="project-search" class="sr-only">Search case studies</label>
            <input id="project-search" type="search" name="q" value="{{ $search }}" placeholder="Search case studies & tools..."
                   class="h-11 w-full rounded-lg border border-edge bg-surface pl-10 pr-3 text-sm text-content placeholder:text-content-secondary focus:border-accent focus:ring-1 focus:ring-accent">
            <svg class="pointer-events-none absolute left-3 top-3.5 h-4 w-4 text-content-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </form>
    </div>

    @if(empty($selectedCategory) && empty($search) && $featuredProject)
        <div class="mt-8 overflow-hidden rounded-2xl border border-edge bg-surface">
            <div class="grid grid-cols-1 lg:grid-cols-12">
                @if($featuredProject->cover_image)
                    <div class="h-64 overflow-hidden bg-void sm:h-80 lg:col-span-7 lg:h-auto">
                        <img src="{{ $featuredProject->cover_image_url }}" alt="{{ $featuredProject->title }}" class="h-full w-full object-cover">
                    </div>
                @endif
                <div class="{{ $featuredProject->cover_image ? 'lg:col-span-5' : 'lg:col-span-12' }} flex flex-col justify-between p-6 sm:p-10">
                    <div>
                        <p class="mb-3 flex flex-wrap items-center gap-2 text-sm">
                            <span class="inline-flex rounded-full bg-accent px-2.5 py-0.5 text-xs font-bold text-void">Featured Architecture</span>
                            <span class="text-content-secondary">{{ $featuredProject->category }}</span>
                        </p>
                        <h2 class="text-xl font-bold leading-snug text-content sm:text-2xl">
                            <a href="{{ route('projects.show', $featuredProject->slug) }}" class="transition hover:text-accent">{{ $featuredProject->title }}</a>
                        </h2>
                        <p class="mt-1 text-sm text-content-secondary">Role: <strong class="font-semibold text-content">{{ $featuredProject->role }}</strong></p>
                        <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-content-secondary">{{ $featuredProject->challenge }}</p>

                        @if(!empty($featuredProject->impact_highlights))
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach(array_slice($featuredProject->impact_highlights, 0, 2) as $h)
                                    <span class="inline-flex rounded-lg border border-edge bg-surface-elevated px-2.5 py-1 text-sm font-semibold tabular-nums text-accent-soft">{{ $h['metric'] }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 border-t border-edge pt-4">
                        <a href="{{ route('projects.show', $featuredProject->slug) }}" class="inline-flex min-h-11 items-center text-sm font-semibold text-accent-soft hover:text-accent hover:underline">Read full case study</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="mt-12 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($projects as $project)
            @include('projects._card', ['project' => $project])
        @empty
            <div class="col-span-full py-16 text-center">
                <p class="text-base font-semibold text-content">No case studies match your search</p>
                <p class="mt-1 text-sm text-content-secondary">Try a different keyword or pick another category.</p>
                <a href="{{ route('projects.index') }}" class="mt-4 inline-flex min-h-11 items-center text-sm font-semibold text-accent-soft hover:text-accent hover:underline">Clear filters</a>
            </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $projects->links() }}
    </div>
</div>
@endsection
