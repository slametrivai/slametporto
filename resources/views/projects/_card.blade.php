{{-- One row of the case study list: thumbnail, identity, story with pipeline flow, two metrics. --}}
@php
    $steps = array_slice($project->steps, 0, 4);
    $thumb = $project->thumbnail_url;
@endphp
<li class="group relative flex flex-col gap-6 border-t border-edge py-9 lg:flex-row lg:gap-10">
    {{-- Not positioned, so the title's full-row overlay makes the image clickable too. --}}
    <div class="flex aspect-[16/10] w-full shrink-0 items-center justify-center overflow-hidden rounded-xl border border-edge bg-surface-deep sm:w-64 lg:w-44">
        @if($thumb)
            <img src="{{ $thumb }}" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover transition duration-300 group-hover:opacity-90">
        @else
            <x-lucide-layers class="h-6 w-6 text-content-secondary" aria-hidden="true" />
        @endif
    </div>
    <div class="lg:w-[20rem] lg:shrink-0">
        <p class="flex flex-wrap items-center gap-2">
            <span class="chip border border-edge px-2.5 py-1 text-xs text-content-secondary">{{ $project->category }}</span>
            @if($project->is_featured)
                <span class="chip gap-[0.3125rem] bg-accent/10 px-2.5 py-1 text-xs font-semibold text-accent">
                    <x-lucide-star class="h-[0.6875rem] w-[0.6875rem]" />
                    Featured
                </span>
            @endif
        </p>
        <h3 class="mt-3 text-[1.375rem] font-bold leading-7 tracking-[-0.018em] text-content">
            <a href="{{ route('projects.show', $project->slug) }}" class="after:absolute after:inset-0">{{ $project->title }}</a>
        </h3>
        <p class="mt-3 text-sm text-content-secondary">{{ $project->role }}</p>
    </div>

    <div class="min-w-0 flex-1">
        <p class="line-clamp-3 text-[0.9375rem] leading-6 text-content-secondary">{{ $project->teaser }}</p>
        @if($steps)
            <p class="mt-4 flex flex-wrap items-center gap-x-1.5 gap-y-1 text-xs font-medium">
                @foreach($steps as $step)
                    <span class="{{ $loop->index === 1 ? 'text-accent' : 'text-content-body' }}">{{ $step['name'] }}</span>
                    @unless($loop->last)
                        <x-lucide-arrow-right class="h-3 w-3 text-content-faint" />
                    @endunless
                @endforeach
            </p>
        @endif
    </div>

    <dl class="flex gap-6 lg:w-[16.25rem] lg:shrink-0">
        @foreach(array_slice($project->impact_highlights ?? [], 0, 2) as $h)
            <div class="flex flex-1 flex-col-reverse justify-end gap-1">
                <dt class="text-[0.8125rem] text-content-secondary">{{ $h['label'] }}</dt>
                <dd class="text-[1.625rem] font-bold tracking-[-0.023em] text-accent">{{ $h['metric'] }}</dd>
            </div>
        @endforeach
    </dl>

    <span class="hidden h-11 w-11 shrink-0 items-center justify-center rounded-full border border-edge text-content-secondary transition group-hover:border-accent group-hover:text-accent lg:inline-flex" aria-hidden="true">
        <x-lucide-arrow-up-right class="h-[1.125rem] w-[1.125rem]" />
    </span>
</li>
