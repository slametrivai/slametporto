<article class="group flex flex-col justify-between rounded-2xl border border-edge bg-surface p-5 transition hover:border-accent-deep">
    <div>
        @if($project->cover_image)
            <a href="{{ route('projects.show', $project->slug) }}" class="mb-4 block h-44 w-full overflow-hidden rounded-xl border border-edge bg-void" tabindex="-1" aria-hidden="true">
                <img src="{{ $project->cover_image_url }}" alt="" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
            </a>
        @endif

        <div class="mb-2.5 flex items-center justify-between gap-2">
            <span class="inline-flex rounded-md bg-accent/10 px-2.5 py-0.5 text-xs font-semibold text-accent-soft">{{ $project->category }}</span>
            @if($project->is_featured)
                <span class="text-xs font-semibold text-accent">Featured</span>
            @endif
        </div>

        <h3 class="line-clamp-2 text-base font-bold leading-snug text-content">
            <a href="{{ route('projects.show', $project->slug) }}" class="transition hover:text-accent">{{ $project->title }}</a>
        </h3>
        <p class="mt-1 text-xs text-content-secondary">Role: <strong class="font-semibold text-content">{{ $project->role }}</strong></p>
        <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-content-secondary">{{ $project->challenge }}</p>

        @if(!empty($project->impact_highlights))
            <div class="mt-4 flex flex-wrap gap-1.5">
                @foreach(array_slice($project->impact_highlights, 0, 2) as $h)
                    <span class="inline-flex rounded-md border border-edge bg-surface-elevated px-2 py-0.5 text-xs font-semibold tabular-nums text-accent-soft">{{ $h['metric'] }}</span>
                @endforeach
            </div>
        @endif

        @if(!empty($project->workflow_steps))
            <p class="mt-4 flex items-center gap-1 overflow-hidden border-t border-edge pt-3 text-xs text-content-secondary">
                <span class="truncate font-semibold text-content">{{ $project->workflow_steps[0] }}</span>
                <span aria-hidden="true">→</span>
                <span class="truncate font-semibold text-accent-soft">{{ $project->workflow_steps[1] ?? 'Engine' }}</span>
                <span aria-hidden="true">→</span>
                <span class="truncate font-semibold text-content">{{ $project->workflow_steps[count($project->workflow_steps) - 1] }}</span>
            </p>
        @endif
    </div>

    <div class="mt-5 border-t border-edge pt-3.5">
        <a href="{{ route('projects.show', $project->slug) }}" class="inline-flex min-h-11 items-center text-sm font-semibold text-accent-soft hover:text-accent hover:underline">
            Read case study<span class="sr-only">: {{ $project->title }}</span>
        </a>
    </div>
</article>
