{{-- One row of the article list: date column, topic meta, title, excerpt. --}}
<li class="group relative flex flex-col gap-3 rounded-3xl bg-panel p-5 transition-colors hover:bg-line/40 sm:flex-row sm:gap-10 lg:p-8">
    <p class="flex items-baseline gap-2 sm:w-[7.5rem] sm:shrink-0 sm:flex-col sm:gap-0">
        <time datetime="{{ $post->published_at?->toDateString() }}" class="text-2xl font-bold text-brand-dark lg:text-[1.875rem]">{{ $post->published_at?->format('d M') }}</time>
        <span class="text-base font-semibold text-body">{{ $post->published_at?->format('Y') }}</span>
    </p>
    <div class="flex min-w-0 flex-1 flex-col gap-2">
        <p class="text-sm font-semibold text-body lg:text-[0.9375rem]">{{ $post->category?->name }} · {{ $post->reading_time }} min read</p>
        <h3 class="text-[1.625rem] font-semibold leading-8 text-navy lg:text-[1.875rem] lg:leading-9">
            <a href="{{ route('blog.show', $post->slug) }}" class="after:absolute after:inset-0">{{ $post->title }}</a>
        </h3>
        <p class="text-base leading-[1.5625rem] text-body">{{ $post->excerpt }}</p>
    </div>
</li>
