<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0B0F17">
        <meta name="robots" content="noindex">

        <title>{{ $title ?? 'Sign in' }} · {{ setting('site_name', config('app.name', 'Slamet Rivai')) }}</title>

        <link rel="icon" href="{{ setting('site_favicon') ? asset('storage/' . setting('site_favicon')) : asset('favicon.ico') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <style>[x-cloak] { display: none !important; }</style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @php
        $siteName = setting('site_name', 'Slamet Rivai');
        $siteInitials = collect(explode(' ', (string) $siteName))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
    @endphp
    <body class="bg-void font-sans text-content antialiased [color-scheme:dark]">
        {{-- Amber glow from the top edge, as in slametrivai.pen "Admin — Login". --}}
        <div class="relative flex min-h-screen flex-col items-center justify-between bg-[radial-gradient(ellipse_60%_45%_at_50%_0%,rgba(242,184,75,0.12),transparent)] bg-no-repeat px-4 py-10 sm:px-16">
            @isset($toast)
                {{ $toast }}
            @endisset

            <span class="h-4" aria-hidden="true"></span>

            <main class="w-full max-w-[420px] rounded-2xl border border-edge bg-surface p-6 shadow-[0_24px_60px_rgba(0,0,0,0.4)] sm:p-10">
                <a href="{{ route('home') }}" class="mx-auto flex h-11 w-11 items-center justify-center rounded-[10px] border-[1.5px] border-accent text-base font-bold text-accent focus:outline-none focus-visible:ring-2 focus-visible:ring-accent/60" aria-label="{{ $siteName }} — kembali ke situs">
                    {{ $siteInitials }}
                </a>

                <div class="mt-3">
                    {{ $slot }}
                </div>
            </main>

            <p class="mt-10 text-xs text-content-faint">&copy; {{ date('Y') }} {{ $siteName }}</p>
        </div>
    </body>
</html>
