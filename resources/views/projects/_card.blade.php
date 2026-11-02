{{-- One card of the case study grid: thumbnail, identity, story with pipeline flow, two metrics. --}}
@php
    $steps = array_slice($project->steps, 0, 4);
    $thumb = $project->thumbnail_url;
@endphp
<li class="group relative flex flex-col overflow-hidden rounded-3xl border border-line bg-white transition-colors hover:border-brand">
    {{-- Not positioned, so the title's full-card overlay makes the image clickable too. --}}
    <div class="flex h-[12.5rem] w-full shrink-0 items-center justify-center overflow-hidden bg-panel sm:h-64 lg:h-[21.25rem]">
        @if($thumb)
            <img src="{{ $thumb }}" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover">
        @else
            <x-lucide-layers class="h-10 w-10 text-brand" aria-hidden="true" />
        @endif
    </div>
    <div class="flex flex-1 flex-col items-start gap-2.5 p-5 lg:p-7">
        <p class="chip">{{ $project->category }}</p>
        <h3 class="text-[1.5rem] font-semibold leading-[1.8125rem] text-navy lg:text-[1.625rem] lg:leading-[1.9375rem]">
            <a href="{{ route('projects.show', $project->slug) }}" class="after:absolute after:inset-0">{{ $project->title }}</a>
        </h3>
        <p class="text-[0.9375rem] font-bold text-brand">{{ $project->role }}</p>
        <p class="text-[0.9375rem] leading-[1.4375rem] text-body">{{ $project->teaser }}</p>
        @if($steps)
            <p class="text-sm font-semibold leading-5 text-navy">{{ collect($steps)->pluck('name')->implode(' → ') }}</p>
        @endif
        @if(!empty($project->impact_highlights))
            <dl class="mt-auto flex w-full gap-4 border-t border-line pt-3">
                @foreach(array_slice($project->impact_highlights, 0, 2) as $h)
                    <div class="flex flex-1 flex-col-reverse gap-0.5">
                        <dt class="text-sm leading-[1.125rem] text-body">{{ $h['label'] }}</dt>
                        <dd class="text-[1.375rem] font-bold text-brand">{{ $h['metric'] }}</dd>
                    </div>
                @endforeach
            </dl>
        @endif
    </div>
</li>
