{{-- Category pills plus search. $tabs: list of [label, count, url, active]. --}}
<div class="page-container pb-8 lg:pb-10">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <nav aria-label="{{ $label }}" class="-mx-5 overflow-x-auto px-5 sm:mx-0 sm:px-0">
            <ul class="flex items-center gap-2">
                @foreach($tabs as $tab)
                    <li>
                        <a href="{{ $tab['url'] }}" @if($tab['active']) aria-current="page" @endif
                           class="pill-tab {{ $tab['active'] ? 'bg-brand font-bold text-white' : 'bg-panel font-semibold text-navy hover:bg-line/50' }}">
                            {{ $tab['label'] }}
                            <span class="text-sm font-bold tabular-nums {{ $tab['active'] ? 'text-white' : 'text-body' }}">{{ $tab['count'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <form action="{{ $action }}" method="GET" role="search" class="relative w-full lg:w-[21.25rem]"
              x-data @keydown.slash.window="if (!['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) { $event.preventDefault(); $refs.search.focus(); }">
            @foreach($hidden ?? [] as $name => $value)
                @if($value)
                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                @endif
            @endforeach
            <label for="index-search" class="sr-only">{{ $placeholder }}</label>
            <x-lucide-search class="pointer-events-none absolute left-4 top-1/2 h-[1.125rem] w-[1.125rem] -translate-y-1/2 text-body" />
            <input id="index-search" x-ref="search" type="search" name="q" value="{{ $search }}" placeholder="{{ $placeholder }}"
                   class="h-11 w-full rounded-full border-line bg-white pl-11 pr-4 text-[0.9375rem] text-navy placeholder:text-body focus:border-brand focus:ring-1 focus:ring-brand">
        </form>
    </div>
</div>
