<article class="flex flex-col justify-between overflow-hidden rounded-2xl border border-edge bg-surface transition hover:border-accent-deep">
    @if($post->cover_image)
        <a href="{{ route('blog.show', $post->slug) }}" class="block h-44 w-full overflow-hidden bg-void" tabindex="-1" aria-hidden="true">
            <img src="{{ $post->cover_image_url }}" alt="" class="h-full w-full object-cover transition duration-300 hover:scale-105">
        </a>
    @endif
    <div class="flex flex-1 flex-col justify-between p-6">
        <div>
            <p class="mb-2 flex items-center gap-2 text-xs text-content-secondary">
                <span class="font-semibold text-accent-soft">{{ $post->category?->name }}</span>
                <span aria-hidden="true">•</span>
                <span>{{ $post->reading_time }} min read</span>
            </p>
            <h3 class="line-clamp-2 text-base font-bold leading-snug text-content">
                <a href="{{ route('blog.show', $post->slug) }}" class="transition hover:text-accent">{{ $post->title }}</a>
            </h3>
            <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-content-secondary">{{ $post->excerpt }}</p>
        </div>
        <div class="mt-6 flex items-center justify-between border-t border-edge pt-4 text-sm">
            <span class="tabular-nums text-content-secondary">{{ $post->published_at?->format('d M Y') }}</span>
            <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex min-h-11 items-center font-semibold text-accent-soft hover:text-accent hover:underline">
                Read article<span class="sr-only">: {{ $post->title }}</span>
            </a>
        </div>
    </div>
</article>
