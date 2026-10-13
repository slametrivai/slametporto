{{-- Category pills plus search. $tabs: list of [label, count, url, active]. --}}
<div class="page-container">
    <div class="flex flex-col gap-4 border-y border-edge py-4 lg:flex-row lg:items-center lg:justify-between">
        <nav aria-label="{{ $label }}" class="-mx-4 overflow-x-auto px-4 sm:mx-0 sm:px-0">
            <ul class="flex items-center gap-2">
                @foreach($tabs as $tab)
                    <li>
                        <a href="{{ $tab['url'] }}" @if($tab['active']) aria-current="page" @endif
                           class="pill-tab {{ $tab['active'] ? 'border-content bg-content font-semibold text-void' : 'border-edge font-medium text-content-secondary hover:border-content-faint hover:text-content' }}">
                            {{ $tab['label'] }}
                            <span class="text-xs font-semibold tabular-nums {{ $tab['active'] ? 'text-void/60' : 'text-content-faint' }}">{{ $tab['count'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <form action="{{ $action }}" method="GET" role="search" class="relative w-full lg:w-[18.75rem]"
              x-data @keydown.slash.window="if (!['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) { $event.preventDefault(); $refs.search.focus(); }">
            @foreach($hidden ?? [] as $name => $value)
                @if($value)
                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                @endif
            @endforeach
            <label for="index-search" class="sr-only">{{ $placeholder }}</label>
            <x-lucide-search class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-content-faint" />
            <input id="index-search" x-ref="search" type="search" name="q" value="{{ $search }}" placeholder="{{ $placeholder }}"
                   class="h-11 w-full rounded-[0.625rem] border-edge bg-surface-inset pl-10 pr-9 text-sm text-content placeholder:text-content-faint focus:border-accent focus:ring-1 focus:ring-accent">
            <kbd class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 font-sans text-xs text-content-faint" aria-hidden="true">/</kbd>
        </form>
    </div>
</div>
