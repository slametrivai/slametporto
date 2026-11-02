{{-- Index page header: breadcrumb, two-tone title, lead and headline counts. $accent is the blue part of the title. --}}
<header class="page-container flex flex-col gap-6 pb-6 pt-10 lg:pt-14">
    <nav aria-label="Breadcrumb">
        <ol class="flex items-center gap-2 text-[0.9375rem]">
            <li><a href="{{ route('home') }}" class="font-medium text-body transition-colors hover:text-brand">Home</a></li>
            <li aria-hidden="true"><x-lucide-chevron-right class="h-4 w-4 text-body" /></li>
            <li aria-current="page" class="font-bold text-navy">{{ $current }}</li>
        </ol>
    </nav>
    <div class="flex flex-col justify-between gap-6 lg:flex-row lg:items-end lg:gap-[3.75rem]">
        <div class="flex max-w-[56.25rem] flex-col gap-3">
            <h1 class="text-[2.75rem] font-semibold leading-[1.1] tracking-[-0.0625rem] text-navy lg:text-[4.25rem]">{{ $title }} <span class="text-brand">{{ $accent }}</span></h1>
            <p class="text-base leading-6 text-body lg:text-lg lg:leading-[1.6875rem]">{{ $lead }}</p>
        </div>
        <dl class="flex shrink-0 gap-10">
            @foreach($counts as $label => $value)
                <div class="flex flex-col-reverse gap-0.5">
                    <dt class="text-[0.9375rem] text-body">{{ $label }}</dt>
                    <dd class="text-[2.25rem] font-bold leading-tight text-brand">{{ $value }}</dd>
                </div>
            @endforeach
        </dl>
    </div>
</header>
