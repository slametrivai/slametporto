@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-sm font-medium text-[#34D399]']) }} role="status">
        {{ $status }}
    </div>
@endif
