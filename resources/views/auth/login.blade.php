<x-guest-layout>
    @if ($errors->any() || session('status'))
        <x-slot:toast>
            @php $failed = $errors->any(); @endphp
            <div x-data="{ open: true }" x-show="open" x-transition.opacity
                 role="{{ $failed ? 'alert' : 'status' }}"
                 class="absolute left-1/2 top-8 z-10 flex w-[calc(100%-2rem)] max-w-[400px] -translate-x-1/2 items-center gap-3 rounded-xl border bg-[#18202E] py-3.5 pl-4 pr-3.5 shadow-[0_12px_32px_rgba(0,0,0,0.5)] {{ $failed ? 'border-[#F87171]/35' : 'border-[#34D399]/35' }}">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $failed ? 'bg-[#F87171]/10 text-[#F87171]' : 'bg-[#34D399]/10 text-[#34D399]' }}" aria-hidden="true">
                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        @if ($failed)
                            <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>
                        @else
                            <circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>
                        @endif
                    </svg>
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-content">{{ $failed ? 'Sign in failed' : 'Done' }}</p>
                    <p class="text-[13px] leading-[19px] text-content-secondary">{{ $failed ? $errors->first() : session('status') }}</p>
                </div>
                <button type="button" @click="open = false" class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md text-content-faint transition-colors hover:bg-white/5 hover:text-content" aria-label="Dismiss">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
        </x-slot:toast>
    @endif

    <div class="text-center">
        <h1 class="text-[26px] font-bold tracking-[-0.6px] text-content">Sign in</h1>
        <p class="mt-2 text-[15px] leading-[23px] text-content-secondary">{{ setting('site_name', 'Slamet Rivai') }} · Studio admin</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="mt-7 flex flex-col gap-5" x-data="{ show: false }">
        @csrf

        <div class="flex flex-col gap-2">
            <x-input-label for="email" value="Email" />
            <div class="relative">
                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-[18px] w-[18px] -translate-y-1/2 text-content-faint" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                <x-text-input id="email" class="pl-[42px]" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                              :aria-invalid="$errors->has('email') ? 'true' : null" />
            </div>
        </div>

        <div class="flex flex-col gap-2">
            <div class="flex items-center justify-between gap-3">
                <x-input-label for="password" value="Password" />
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="rounded text-[13px] font-medium text-content-secondary transition-colors hover:text-accent focus:outline-none focus-visible:ring-2 focus-visible:ring-accent/60">Forgot password?</a>
                @endif
            </div>
            <div class="relative">
                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-[18px] w-[18px] -translate-y-1/2 text-content-faint" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <x-text-input id="password" class="pl-[42px] pr-12" type="password" x-bind:type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                              :aria-invalid="$errors->has('password') ? 'true' : null" />
                <button type="button" @click="show = !show" class="absolute right-1.5 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-lg text-content-faint transition-colors hover:text-content focus:outline-none focus-visible:ring-2 focus-visible:ring-accent/60"
                        :aria-label="show ? 'Hide password' : 'Show password'" :aria-pressed="show.toString()" aria-label="Show password">
                    <svg x-show="!show" class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg x-show="show" x-cloak class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/></svg>
                </button>
            </div>
        </div>

        <label for="remember_me" class="inline-flex w-fit cursor-pointer items-center gap-2.5 text-sm text-content-secondary">
            <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-edge bg-void text-accent focus:ring-2 focus:ring-accent/40 focus:ring-offset-0">
            Keep me signed in
        </label>

        <x-primary-button class="mt-1 w-full">
            Sign in
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </x-primary-button>
    </form>
</x-guest-layout>
