{{-- Pipeline pills under a featured case study's metrics, on a dark card. The last step is the live output. --}}
@php
    $steps = array_slice($project->steps, 0, 4);
    $stepIcons = ['file-text', 'bot', 'shield-check', 'database'];
@endphp
@if($steps)
    <ol class="flex flex-wrap items-center gap-2" aria-label="{{ $project->title }} pipeline">
        @foreach($steps as $i => $step)
            <li class="flex items-center gap-2">
                <span class="inline-flex items-center gap-2 rounded-xl px-3.5 py-[0.5625rem] text-[0.9375rem] font-semibold text-white {{ $loop->last ? 'bg-brand' : 'bg-white/[0.12]' }}">
                    <x-dynamic-component :component="'lucide-' . $stepIcons[$i]" class="h-4 w-4" />
                    {{ $step['name'] }}
                    @if($loop->last)<span class="sr-only">(live)</span>@endif
                </span>
                @unless($loop->last)
                    <x-lucide-arrow-right class="h-4 w-4 text-mist" aria-hidden="true" />
                @endunless
            </li>
        @endforeach
    </ol>
@endif
