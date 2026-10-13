{{-- Vertical pipeline used as the visual of a featured case study. The last step is the live output. --}}
@php
    $steps = array_slice($project->steps, 0, 4);
    $stepIcons = ['file-scan', 'scan-text', 'shield-check', 'database'];
@endphp
<ol class="w-full max-w-[26.25rem]" aria-label="{{ $project->title }} pipeline">
    @foreach($steps as $i => $step)
        @php $last = $loop->last; @endphp
        <li>
            @unless($loop->first)
                <span class="ml-[2.0625rem] block h-[1.125rem] w-[0.0938rem] bg-edge" aria-hidden="true"></span>
            @endunless
            <div class="flex items-center gap-3.5 rounded-xl border px-4 py-3.5 {{ $last ? 'border-accent/30 bg-accent/10' : 'border-edge bg-surface' }}">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $last ? 'bg-accent/15 text-accent' : 'bg-surface-raised text-content-secondary' }}">
                    <x-dynamic-component :component="'lucide-' . $stepIcons[$i]" class="h-[1.125rem] w-[1.125rem]" />
                </span>
                <span class="min-w-0 flex-1">
                    <span class="block text-sm font-semibold text-content">{{ $step['name'] }}</span>
                    @if($step['description'])
                        <span class="mt-[0.1875rem] block truncate text-xs text-content-secondary">{{ $step['description'] }}</span>
                    @endif
                </span>
                <span class="text-xs font-semibold tabular-nums {{ $last ? 'text-accent' : 'text-content-faint' }}">{{ $last ? 'Live' : sprintf('%02d', $i + 1) }}</span>
            </div>
        </li>
    @endforeach
</ol>
