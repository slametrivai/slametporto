{{-- Right-hand image of a featured case study card. It is its own link because a positioned
     panel sits above the title's full-card overlay; hidden from keyboard and screen readers,
     which get the title link instead. Params: $project, $url (Project::thumbnail_url). --}}
@if($url)
    <a href="{{ route('projects.show', $project->slug) }}" tabindex="-1" aria-hidden="true"
       class="relative z-10 block aspect-[16/10] overflow-hidden border-t border-edge bg-surface-deep lg:aspect-auto lg:flex-1 lg:border-l lg:border-t-0">
        <img src="{{ $url }}" alt="" loading="lazy" decoding="async" class="absolute inset-0 h-full w-full object-cover">
    </a>
@endif
