<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex h-12 items-center justify-center gap-2 rounded-[10px] bg-accent px-5 text-[15px] font-semibold text-void transition-colors hover:bg-accent-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-accent/60 focus-visible:ring-offset-2 focus-visible:ring-offset-surface disabled:opacity-60']) }}>
    {{ $slot }}
</button>
