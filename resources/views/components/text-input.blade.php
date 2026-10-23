@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'block h-12 w-full rounded-[10px] border border-edge bg-void px-3.5 text-[15px] text-content placeholder:text-content-faint focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/30 aria-[invalid=true]:border-[#F87171]/60 disabled:opacity-60']) }}>
