{{-- Index page header: breadcrumb, title, lead and headline counts. --}}
<header class="page-container flex flex-col justify-between gap-10 pb-12 pt-14 sm:pt-[5.5rem] lg:flex-row lg:items-end">
    <div class="max-w-[47.5rem]">
        <nav aria-label="Breadcrumb">
            <ol class="flex items-center gap-2 text-[0.8125rem]">
                <li><a href="{{ route('home') }}" class="text-content-secondary transition hover:text-content">Home</a></li>
                <li aria-hidden="true"><x-lucide-chevron-right class="h-3.5 w-3.5 text-content-faint" /></li>
                <li aria-current="page" class="font-medium text-content">{{ $current }}</li>
            </ol>
        </nav>
        <h1 class="mt-4 text-[2.5rem] font-bold leading-[1.07] tracking-[-0.03em] text-content sm:text-[3.5rem]">{{ $title }}</h1>
        <p class="mt-4 text-[1.0625rem] leading-[1.8125rem] text-content-secondary sm:text-lg">{{ $lead }}</p>
    </div>
    <dl class="flex gap-10">
        @foreach($counts as $label => $value)
            <div class="flex flex-col-reverse gap-1">
                <dt class="text-[0.8125rem] text-content-secondary">{{ $label }}</dt>
                <dd class="text-4xl font-bold tracking-[-0.03em] text-content">{{ $value }}</dd>
            </div>
        @endforeach
    </dl>
</header>
