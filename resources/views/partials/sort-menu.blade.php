{{-- Sort dropdown built from plain links so it works without JavaScript too. --}}
<div x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false" class="relative">
    <button type="button" @click="open = !open" :aria-expanded="open.toString()" aria-controls="sort-menu"
            class="inline-flex min-h-11 items-center gap-1.5 rounded-full text-[0.9375rem] font-semibold text-navy transition-colors hover:text-brand">
        {{ $prefix ?? '' }}{{ $options[$current] }}
        <x-lucide-chevron-down class="h-4 w-4" />
    </button>
    <ul id="sort-menu" x-show="open" x-cloak x-transition.opacity.duration.100ms
        class="absolute right-0 top-full z-20 mt-2 w-52 rounded-2xl border border-line bg-white p-1.5 shadow-[0_12px_32px_rgba(3,35,77,0.18)]">
        @foreach($options as $key => $label)
            <li>
                <a href="{{ request()->fullUrlWithQuery(['sort' => $key, 'page' => null]) }}" @if($key === $current) aria-current="true" @endif
                   class="flex min-h-10 items-center justify-between rounded-xl px-3 text-[0.9375rem] transition-colors hover:bg-panel {{ $key === $current ? 'font-bold text-navy' : 'text-body' }}">
                    {{ $label }}
                    @if($key === $current)<x-lucide-check class="h-4 w-4 text-brand" />@endif
                </a>
            </li>
        @endforeach
    </ul>
</div>
