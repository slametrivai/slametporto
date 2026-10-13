{{-- Sort dropdown built from plain links so it works without JavaScript too. --}}
<div x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false" class="relative">
    <button type="button" @click="open = !open" :aria-expanded="open.toString()" aria-controls="sort-menu"
            class="inline-flex min-h-11 items-center gap-2 rounded-lg border border-edge px-3 text-[0.8125rem] font-medium text-content-secondary transition hover:border-content-faint hover:text-content">
        {{ $prefix ?? '' }}{{ $options[$current] }}
        <x-lucide-chevron-down class="h-3.5 w-3.5" />
    </button>
    <ul id="sort-menu" x-show="open" x-cloak x-transition.opacity.duration.100ms
        class="absolute right-0 top-full z-20 mt-2 w-48 rounded-xl border border-edge bg-surface p-1.5 shadow-[0_12px_32px_rgba(0,0,0,0.4)]">
        @foreach($options as $key => $label)
            <li>
                <a href="{{ request()->fullUrlWithQuery(['sort' => $key, 'page' => null]) }}" @if($key === $current) aria-current="true" @endif
                   class="flex min-h-10 items-center justify-between rounded-lg px-3 text-[0.8125rem] transition hover:bg-white/[0.04] {{ $key === $current ? 'font-semibold text-content' : 'text-content-secondary' }}">
                    {{ $label }}
                    @if($key === $current)<x-lucide-check class="h-3.5 w-3.5 text-accent" />@endif
                </a>
            </li>
        @endforeach
    </ul>
</div>
