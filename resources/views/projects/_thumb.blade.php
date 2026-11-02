{{-- Image of a featured case study card: above the copy on phones, on the right from lg up. It is its own
     link because a positioned panel sits above the title's full-card overlay; hidden from keyboard and
     screen readers, which get the title link instead. Params: $project, $url (Project::thumbnail_url). --}}
@if($url)
    <a href="{{ route('projects.show', $project->slug) }}" tabindex="-1" aria-hidden="true"
       class="relative z-10 order-first block h-[12.25rem] overflow-hidden sm:h-80 lg:order-none lg:h-auto lg:flex-1">
        <img src="{{ $url }}" alt="" loading="lazy" decoding="async" class="absolute inset-0 h-full w-full object-cover">
    </a>
@endif
