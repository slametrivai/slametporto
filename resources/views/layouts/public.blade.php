<!DOCTYPE html>
{{-- "dark" is only for Laravel's pagination view, which ships dark: variants; the site itself is always navy. --}}
<html lang="{{ str_replace('_', '-', app()->currentLocale()) }}" class="dark scroll-smooth fluid-scale">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if(setting('site_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . setting('site_favicon')) }}">
        <link rel="apple-touch-icon" href="{{ asset('storage/' . setting('site_favicon')) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    @php
        $pageTitle = html_entity_decode(trim($__env->yieldContent('title', '')), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $pageDesc = html_entity_decode(trim($__env->yieldContent('meta_description', '')), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $pageImage = trim($__env->yieldContent('og_image', ''));

        if (request()->routeIs('blog.show') && isset($post) && $post instanceof \Illuminate\Database\Eloquent\Model) {
            $seoInstance = seo()->for($post);
        } elseif (request()->routeIs('projects.show') && isset($project) && $project instanceof \Illuminate\Database\Eloquent\Model) {
            $seoInstance = seo()->for($project);
        } elseif ($pageTitle !== '' || $pageDesc !== '') {
            $seoInstance = seo(new \RalphJSmit\Laravel\SEO\Support\SEOData(
                title: $pageTitle !== '' ? $pageTitle : setting('site_title'),
                description: $pageDesc !== '' ? $pageDesc : setting('meta_description'),
                author: setting('site_author', 'Slamet Rivai'),
                url: url()->current(),
            ));
        } else {
            $seoInstance = seo();
        }
    @endphp
    {!! $seoInstance !!}

    @if(setting('meta_keywords'))
        <meta name="keywords" content="{{ setting('meta_keywords') }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
@php
    $siteName = setting('site_name', 'Slamet Rivai');
    $nameWords = preg_split('/\s+/', trim($siteName));
    $initials = strtoupper(mb_substr($nameWords[0], 0, 1) . mb_substr(end($nameWords), count($nameWords) > 1 ? 0 : 1, 1));
    $caseCount = \App\Models\Project::count();
    $footerTopics = \App\Models\Category::forPosts()->orderBy('id')->take(2)->get();

    $workMenu = [
        ['href' => route('projects.index'), 'icon' => 'layers', 'title' => 'Case studies', 'desc' => $caseCount . ' systems with measured results', 'active' => request()->routeIs('projects.*')],
        ['href' => route('home') . '#toolkit', 'icon' => 'wrench', 'title' => 'Toolkit', 'desc' => 'CRM, automation & data tools', 'active' => false],
        ['href' => route('home') . '#clients', 'icon' => 'building-2', 'title' => 'Clients', 'desc' => 'Where the work went live', 'active' => false],
    ];
    $aboutMenu = [
        ['href' => route('home') . '#career', 'icon' => 'route', 'title' => 'Career', 'desc' => '9 years, six organizations'],
        ['href' => route('home') . '#credentials', 'icon' => 'badge-check', 'title' => 'Credentials', 'desc' => 'BNSP, Telexindo, EF SET C2'],
    ];

    $navLink = 'inline-flex min-h-11 items-center gap-1.5 rounded-lg px-3.5 text-[0.9375rem] transition';
    $navIdle = 'font-medium text-content-secondary hover:text-content';
    $navOn = 'bg-white/5 font-semibold text-content';
    $menuPanel = 'absolute left-1/2 top-full z-50 mt-2 w-[18.75rem] -translate-x-1/2 rounded-xl border border-edge bg-surface p-1.5 shadow-[0_12px_32px_rgba(0,0,0,0.4)]';
    $menuItem = 'group flex items-center gap-3 rounded-lg px-3 py-2.5 transition hover:bg-white/[0.04]';
@endphp
<body class="bg-void font-sans text-content antialiased">
    <a href="#main" class="sr-only z-[60] rounded-lg bg-accent px-4 py-2 font-semibold text-void focus:not-sr-only focus:fixed focus:left-4 focus:top-4">Skip to content</a>

    <header x-data="{ mobileMenuOpen: false }" @keydown.escape.window="mobileMenuOpen = false"
            class="sticky top-0 z-50 border-b border-edge bg-void/95 backdrop-blur-md">
        <div class="page-container flex h-[4.75rem] items-center justify-between gap-6">
            <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-lg">
                @if(setting('site_logo'))
                    <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="" class="h-9 w-auto rounded-lg object-contain">
                @else
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg border-[1.5px] border-accent text-sm font-bold text-accent" aria-hidden="true">
                        {{ $initials }}
                    </span>
                @endif
                <span class="text-base font-semibold text-content">{{ $siteName }}</span>
            </a>

            <nav class="hidden items-center gap-2 md:flex" aria-label="Main menu">
                @foreach(['Work' => $workMenu, 'About' => $aboutMenu] as $label => $items)
                    @php $groupOn = $label === 'Work' && request()->routeIs('projects.*'); @endphp
                    <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" @click.outside="open = false" @keydown.escape="open = false" class="relative">
                        <button type="button" @click="open = !open" :aria-expanded="open.toString()" aria-controls="menu-{{ Str::lower($label) }}"
                                class="{{ $navLink }} {{ $groupOn ? $navOn : $navIdle }}" :class="open && 'bg-white/5 text-content'">
                            {{ $label }}
                            <x-lucide-chevron-down class="h-[0.9375rem] w-[0.9375rem] text-content-faint transition-transform" ::class="open && 'rotate-180'" />
                        </button>
                        {{-- Hover bridge so the menu stays open while the pointer crosses the gap. --}}
                        <div x-show="open" class="absolute left-0 top-full h-2 w-full"></div>
                        <div id="menu-{{ Str::lower($label) }}" x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                             class="{{ $menuPanel }}">
                            @foreach($items as $item)
                                <a href="{{ $item['href'] }}" class="{{ $menuItem }} {{ ($item['active'] ?? false) ? 'bg-white/[0.04]' : '' }}" @if($item['active'] ?? false) aria-current="page" @endif>
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg transition {{ ($item['active'] ?? false) ? 'bg-accent/10 text-accent' : 'bg-surface-raised text-content-secondary group-hover:bg-accent/10 group-hover:text-accent' }}">
                                        <x-dynamic-component :component="'lucide-' . $item['icon']" class="h-4 w-4" />
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block text-sm font-semibold text-content">{{ $item['title'] }}</span>
                                        <span class="mt-0.5 block text-[0.8125rem] leading-[1.1875rem] text-content-secondary">{{ $item['desc'] }}</span>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <a href="{{ route('blog.index') }}" class="{{ $navLink }} {{ request()->routeIs('blog.*') ? $navOn : $navIdle }}" @if(request()->routeIs('blog.*')) aria-current="page" @endif>Writing</a>
            </nav>

            <div class="hidden items-center gap-3 md:flex">
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex min-h-11 items-center px-2 text-sm font-medium text-content-secondary transition hover:text-content">Admin</a>
                @endauth
                <a href="{{ route('home') }}#contact" data-open-contact aria-haspopup="dialog"
                   class="inline-flex min-h-11 items-center gap-2 rounded-lg border border-edge px-[1.125rem] text-sm font-medium text-content transition hover:border-content-faint">
                    Let's talk
                    <x-lucide-arrow-up-right class="h-4 w-4 text-accent" />
                </a>
            </div>

            <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" :aria-expanded="mobileMenuOpen.toString()" aria-controls="mobile-menu"
                    class="inline-flex h-11 w-11 items-center justify-center rounded-lg border border-edge text-content md:hidden">
                <span class="sr-only" x-text="mobileMenuOpen ? 'Close menu' : 'Open menu'">Open menu</span>
                <x-lucide-menu x-show="!mobileMenuOpen" class="h-5 w-5" />
                <x-lucide-x x-show="mobileMenuOpen" x-cloak class="h-5 w-5" />
            </button>
        </div>

        <div id="mobile-menu" x-show="mobileMenuOpen" x-cloak x-transition.opacity.duration.150ms class="border-t border-edge bg-void md:hidden">
            <div class="page-container space-y-5 pb-6 pt-4">
                @foreach(['Work' => $workMenu, 'About' => $aboutMenu] as $label => $items)
                    <div>
                        <p class="eyebrow px-1 pb-1 !text-xs">{{ $label }}</p>
                        @foreach($items as $item)
                            <a @click="mobileMenuOpen = false" href="{{ $item['href'] }}" class="{{ $menuItem }}">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-surface-raised text-content-secondary">
                                    <x-dynamic-component :component="'lucide-' . $item['icon']" class="h-4 w-4" />
                                </span>
                                <span class="text-[0.9375rem] font-semibold text-content">{{ $item['title'] }}</span>
                            </a>
                        @endforeach
                    </div>
                @endforeach
                <a @click="mobileMenuOpen = false" href="{{ route('blog.index') }}" class="flex min-h-11 items-center rounded-lg px-3 text-[0.9375rem] font-semibold text-content hover:bg-white/[0.04]">Writing</a>
                <div class="flex flex-col gap-2 border-t border-edge pt-5">
                    <a href="{{ route('home') }}#contact" data-open-contact aria-haspopup="dialog" @click="mobileMenuOpen = false" class="btn-primary w-full">Let's talk</a>
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="btn-outline w-full">Admin</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main id="main">
        @yield('content')
    </main>

    <footer class="border-t border-edge">
        <div class="page-container pb-10 pt-14">
            <div class="flex flex-col justify-between gap-10 lg:flex-row">
                <div class="max-w-[25rem]">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg border-[1.5px] border-accent text-sm font-bold text-accent" aria-hidden="true">{{ $initials }}</span>
                        <span class="text-base font-semibold text-content">{{ $siteName }}</span>
                    </div>
                    <p class="mt-4 text-sm leading-[1.375rem] text-content-secondary">Operations & Systems Leader based in Bekasi, Indonesia. Customer operations, CRM hubs and automation pipelines that hold their SLA.</p>
                </div>

                <div class="grid grid-cols-2 gap-10 sm:flex sm:gap-20">
                    <div>
                        <h2 class="text-[0.8125rem] font-semibold text-content">Work</h2>
                        <ul class="mt-3.5 space-y-1 text-sm text-content-secondary">
                            <li><a href="{{ route('projects.index') }}" class="inline-flex min-h-8 items-center transition hover:text-content">Case studies</a></li>
                            <li><a href="{{ route('home') }}#career" class="inline-flex min-h-8 items-center transition hover:text-content">Career</a></li>
                            <li><a href="{{ route('home') }}#toolkit" class="inline-flex min-h-8 items-center transition hover:text-content">Toolkit</a></li>
                            <li><a href="{{ route('home') }}#credentials" class="inline-flex min-h-8 items-center transition hover:text-content">Credentials</a></li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="text-[0.8125rem] font-semibold text-content">Writing</h2>
                        <ul class="mt-3.5 space-y-1 text-sm text-content-secondary">
                            <li><a href="{{ route('blog.index') }}" class="inline-flex min-h-8 items-center transition hover:text-content">All articles</a></li>
                            @foreach($footerTopics as $topic)
                                <li><a href="{{ route('blog.index', ['category' => $topic->slug]) }}" class="inline-flex min-h-8 items-center transition hover:text-content">{{ $topic->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <h2 class="text-[0.8125rem] font-semibold text-content">Contact</h2>
                        <ul class="mt-3.5 space-y-1 text-sm text-content-secondary">
                            <li><a href="{{ route('home') }}#contact" data-open-contact aria-haspopup="dialog" class="inline-flex min-h-8 items-center transition hover:text-content">Contact form</a></li>
                            @if(setting('contact_email'))
                                <li><a href="mailto:{{ setting('contact_email') }}" class="inline-flex min-h-8 items-center transition hover:text-content">Email</a></li>
                            @endif
                            @if(setting('linkedin_url'))
                                <li><a href="{{ setting('linkedin_url') }}" target="_blank" rel="noopener" class="inline-flex min-h-8 items-center transition hover:text-content">LinkedIn<span class="sr-only"> (opens in new tab)</span></a></li>
                            @endif
                            @if(setting('whatsapp_number'))
                                <li><a href="https://wa.me/{{ setting('whatsapp_number') }}?text={{ rawurlencode('Hi ' . $siteName . ', I would like to discuss a collaboration.') }}" target="_blank" rel="noopener" class="inline-flex min-h-8 items-center transition hover:text-content">WhatsApp<span class="sr-only"> (opens in new tab)</span></a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex flex-col justify-between gap-3 border-t border-edge pt-6 sm:flex-row sm:items-center">
                <p class="text-[0.8125rem] text-content-faint">&copy; {{ date('Y') }} {{ $siteName }}</p>
                <p class="flex items-center gap-2 text-[0.8125rem] text-content-secondary">
                    <span class="h-2 w-2 rounded-full bg-accent" aria-hidden="true"></span>
                    Open for advisory & systems leadership roles
                </p>
            </div>
        </div>
    </footer>

    @include('partials.contact-dialog')
    @stack('scripts')
</body>
</html>
