{{-- Compact one-row pipeline under a featured case study's metrics. The last step is the live output. --}}
@php
    $steps = array_slice($project->steps, 0, 4);
    $stepIcons = ['file-scan', 'scan-text', 'shield-check', 'database'];
@endphp
@if($steps)
    <ol class="flex flex-wrap items-center gap-2 border-t border-edge pt-6" aria-label="{{ $project->title }} pipeline">
        @foreach($steps as $i => $step)
            <li class="flex items-center gap-2">
                <span class="inline-flex min-h-8 items-center gap-2 rounded-lg border px-2.5 text-[0.8125rem] font-medium {{ $loop->last ? 'border-accent/30 bg-accent/10 text-accent' : 'border-edge bg-surface-raised text-content' }}">
                    <x-dynamic-component :component="'lucide-' . $stepIcons[$i]" class="h-3.5 w-3.5 {{ $loop->last ? '' : 'text-content-secondary' }}" />
                    {{ $step['name'] }}
                    @if($loop->last)<span class="sr-only">(live)</span>@endif
                </span>
                @unless($loop->last)
                    <x-lucide-arrow-right class="h-3.5 w-3.5 text-content-secondary" aria-hidden="true" />
                @endunless
            </li>
        @endforeach
    </ol>
@endif
