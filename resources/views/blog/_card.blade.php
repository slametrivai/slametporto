{{-- One row of the article list: date column, topic meta, title, excerpt. --}}
<li class="group relative flex flex-col gap-4 border-t border-edge py-9 sm:flex-row sm:gap-12">
    <p class="flex items-baseline gap-2 sm:w-[7.5rem] sm:shrink-0 sm:flex-col sm:gap-0.5">
        <time datetime="{{ $post->published_at?->toDateString() }}" class="text-xl font-bold tracking-[-0.015em] text-content">{{ $post->published_at?->format('d M') }}</time>
        <span class="text-[0.8125rem] text-content-faint">{{ $post->published_at?->format('Y') }}</span>
    </p>
    <div class="min-w-0 flex-1">
        <p class="flex items-center gap-2 text-[0.8125rem] font-medium text-content-secondary">
            <x-dynamic-component :component="'lucide-' . ($post->category?->icon ?? 'file-text')" class="h-3.5 w-3.5 text-accent" />
            {{ $post->category?->name }} · {{ $post->reading_time }} min read
        </p>
        <h3 class="mt-3 max-w-[51.25rem] text-[1.375rem] font-bold leading-[1.27] tracking-[-0.02em] text-content sm:text-[1.625rem]">
            <a href="{{ route('blog.show', $post->slug) }}" class="after:absolute after:inset-0">{{ $post->title }}</a>
        </h3>
        <p class="mt-3 line-clamp-2 max-w-[51.25rem] text-base leading-[1.625rem] text-content-secondary">{{ $post->excerpt }}</p>
    </div>
    <span class="hidden h-12 w-12 shrink-0 items-center justify-center rounded-full border border-edge text-content-secondary transition group-hover:border-accent group-hover:text-accent sm:inline-flex" aria-hidden="true">
        <x-lucide-arrow-up-right class="h-[1.125rem] w-[1.125rem]" />
    </span>
</li>
