{{-- Article thumbnail: the cover if one is uploaded, otherwise a solid block with the post's cover word.
     Params: $post, $tone (background), $class (size and padding), $wordClass, $chipClass (arrow chip size). --}}
<div class="relative flex shrink-0 justify-between overflow-hidden {{ $tone }} {{ $class }}">
    @if($post->has_cover)
        <img src="{{ $post->cover_image_url }}" alt="" loading="lazy" class="absolute inset-0 h-full w-full object-cover">
    @else
        <span class="font-extrabold leading-none tracking-[-0.0625rem] text-white {{ $wordClass }}" aria-hidden="true">{{ $post->thumbnail_word }}</span>
    @endif
    <span class="relative ml-auto flex shrink-0 items-center justify-center self-start rounded-full bg-white text-navy {{ $chipClass }}" aria-hidden="true">
        <x-lucide-arrow-up-right class="h-1/2 w-1/2" />
    </span>
</div>
