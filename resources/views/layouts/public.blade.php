<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->currentLocale()) }}" class="scroll-smooth fluid-scale">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#FFFFFF">
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
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">

    <style>[x-cloak] { display: none !important; }</style>
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

    $navLink = 'inline-flex min-h-11 items-center gap-1.5 rounded-full px-[1.375rem] text-base font-medium text-white transition-colors hover:bg-white/[0.12]';
    $footLink = 'inline-flex min-h-8 items-center text-base text-white transition-colors hover:text-sky';
    $footHead = 'text-lg font-bold text-sky';
@endphp
<body class="bg-white font-sans text-body antialiased">
    <a href="#main" class="sr-only z-[60] rounded-full bg-brand px-4 py-2 font-bold text-white focus:not-sr-only focus:fixed focus:left-4 focus:top-4">Skip to content</a>

    <header x-data="{ menuOpen: false, scrolled: false }"
            x-init="scrolled = window.scrollY > 24"
            x-effect="document.documentElement.classList.toggle('overflow-hidden', menuOpen)"
            @scroll.window.passive="scrolled = window.scrollY > 24"
            @keydown.escape.window="menuOpen = false"
            class="on-dark pointer-events-none sticky top-0 z-50 pt-4 lg:pt-6">
        <div class="pointer-events-auto mx-4 transition-[margin] duration-200 lg:mx-20" :class="scrolled && 'lg:!mx-[7.5rem]'">
            <div class="flex items-center justify-between rounded-[2rem] bg-ink py-2 pl-3.5 pr-2 transition-[background-color,box-shadow,padding] duration-200 lg:grid lg:grid-cols-[1fr_auto_1fr] lg:rounded-[2.5rem] lg:px-3 lg:py-2.5"
                 :class="scrolled && 'bg-ink/[0.85] shadow-[0_12px_32px_rgba(3,35,77,0.25)] backdrop-blur-md lg:!py-1.5'">
                <nav class="hidden items-center gap-2 lg:flex" aria-label="Main menu">
                    @foreach(['Work' => $workMenu, 'About' => $aboutMenu] as $label => $items)
                        @php $groupOn = $label === 'Work' && request()->routeIs('projects.*'); @endphp
                        <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" @click.outside="open = false" @keydown.escape="open = false" class="relative">
                            <button type="button" @click="open = !open" :aria-expanded="open.toString()" aria-controls="menu-{{ Str::lower($label) }}"
                                    class="{{ $navLink }} {{ $groupOn ? 'bg-white/[0.12]' : '' }}" :class="open && 'bg-white/[0.12]'">
                                {{ $label }}
                                <x-lucide-chevron-down class="h-4 w-4 transition-transform" ::class="open && 'rotate-180'" />
                            </button>
                            {{-- Hover bridge so the menu stays open while the pointer crosses the gap. --}}
                            <div x-show="open" class="absolute left-0 top-full h-5 w-full"></div>
                            <div id="menu-{{ Str::lower($label) }}" x-show="open" x-cloak
                                 x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                 class="absolute left-0 top-full z-50 mt-5 flex w-[22.5rem] flex-col gap-1 rounded-3xl border border-line bg-white p-2.5 shadow-[0_12px_32px_rgba(3,35,77,0.18)]">
                                @foreach($items as $item)
                                    <a href="{{ $item['href'] }}" class="flex items-center gap-3.5 rounded-2xl p-3 transition-colors hover:bg-panel focus-visible:outline-brand {{ ($item['active'] ?? false) ? 'bg-panel' : '' }}" @if($item['active'] ?? false) aria-current="page" @endif>
                                        <span class="icon-chip">
                                            <x-dynamic-component :component="'lucide-' . $item['icon']" class="h-5 w-5" />
                                        </span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block text-[1.0625rem] font-bold text-navy">{{ $item['title'] }}</span>
                                            <span class="mt-0.5 block text-[0.9375rem] text-body">{{ $item['desc'] }}</span>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </nav>

                <a href="{{ route('home') }}" class="flex items-center gap-2.5 rounded-full">
                    @if(setting('site_logo'))
                        <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="" class="h-9 w-auto rounded-full object-contain">
                    @else
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand text-sm font-extrabold text-white" aria-hidden="true">{{ $initials }}</span>
                    @endif
                    <span class="text-base font-extrabold uppercase text-white lg:text-lg lg:tracking-[0.03em]">{{ $siteName }}</span>
                </a>

                <div class="hidden items-center justify-end gap-2 lg:flex">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="{{ $navLink }}">Admin</a>
                    @endauth
                    <a href="{{ route('blog.index') }}" class="{{ $navLink }} {{ request()->routeIs('blog.*') ? 'bg-white/[0.12]' : '' }}" @if(request()->routeIs('blog.*')) aria-current="page" @endif>Writing</a>
                    <a href="{{ route('home') }}#contact" data-open-contact aria-haspopup="dialog"
                       class="inline-flex min-h-11 items-center gap-1.5 rounded-full bg-brand px-[1.375rem] text-base font-bold text-white transition-colors hover:bg-brand-dark">
                        Let's talk
                        <x-lucide-arrow-up-right class="h-4 w-4" />
                    </a>
                </div>

                <button type="button" @click="menuOpen = true" :aria-expanded="menuOpen.toString()" aria-controls="mobile-menu" aria-label="Open menu"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/[0.12] text-white lg:hidden">
                    <x-lucide-menu class="h-5 w-5" />
                </button>
            </div>
        </div>

        {{-- Sibling of the pill: a blurred ancestor would trap this fixed overlay inside it. --}}
        <div id="mobile-menu" x-show="menuOpen" x-cloak x-transition.opacity.duration.150ms role="dialog" aria-modal="true" aria-label="Menu"
             class="pointer-events-auto fixed inset-0 z-50 flex flex-col gap-4 overflow-y-auto bg-ink p-4 lg:hidden">
            <div class="flex items-center justify-between pb-2 pl-1.5">
                <a href="{{ route('home') }}" @click="menuOpen = false" class="flex items-center gap-2.5 rounded-full">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand text-sm font-extrabold text-white" aria-hidden="true">{{ $initials }}</span>
                    <span class="text-base font-extrabold uppercase text-white">{{ $siteName }}</span>
                </a>
                <button type="button" @click="menuOpen = false" aria-label="Close menu" class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/[0.12] text-white">
                    <x-lucide-x class="h-5 w-5" />
                </button>
            </div>

            @foreach(['Work' => $workMenu, 'About' => $aboutMenu] as $label => $items)
                <div class="flex flex-col gap-0.5 border-b border-white/20 px-1.5 pb-4 pt-2">
                    <p class="text-sm font-bold uppercase tracking-[0.1em] text-sky">{{ $label }}</p>
                    @foreach($items as $item)
                        <a @click="menuOpen = false" href="{{ $item['href'] }}" class="flex h-12 items-center justify-between text-2xl font-semibold text-white">
                            {{ $item['title'] }}
                            <x-lucide-arrow-up-right class="h-5 w-5" />
                        </a>
                    @endforeach
                </div>
            @endforeach
            <div class="flex flex-col px-1.5">
                <a @click="menuOpen = false" href="{{ route('blog.index') }}" class="flex h-12 items-center justify-between text-2xl font-semibold text-white">
                    Writing
                    <x-lucide-arrow-up-right class="h-5 w-5" />
                </a>
                @auth
                    <a @click="menuOpen = false" href="{{ route('admin.dashboard') }}" class="flex h-12 items-center justify-between text-2xl font-semibold text-white">
                        Admin
                        <x-lucide-arrow-up-right class="h-5 w-5" />
                    </a>
                @endauth
            </div>
            <a href="{{ route('home') }}#contact" data-open-contact aria-haspopup="dialog" @click="menuOpen = false" class="btn-primary mt-auto min-h-[3.375rem] w-full text-[1.0625rem]">
                Let's talk
                <x-lucide-arrow-up-right class="h-5 w-5" />
            </a>
        </div>
    </header>

    <main id="main">
        @yield('content')
    </main>

    <footer class="on-dark rounded-t-[2rem] bg-ink lg:rounded-t-[3rem]">
        <div class="page-container flex flex-col gap-7 pb-7 pt-12 lg:gap-12 lg:pb-9 lg:pt-[4.5rem]">
            <div class="flex flex-col gap-7 border-b border-white/20 pb-7 lg:flex-row lg:items-center lg:justify-between lg:pb-12">
                <p class="text-[2.75rem] font-semibold leading-[2.875rem] tracking-[-0.0625rem] text-white lg:text-[4rem] lg:leading-tight">Let's Connect there</p>
                <a href="{{ route('home') }}#contact" data-open-contact aria-haspopup="dialog" class="btn-primary min-h-[3.25rem] w-full px-8 text-[1.0625rem] lg:min-h-[3.5rem] lg:w-auto lg:text-xl">
                    Let's talk
                    <x-lucide-arrow-up-right class="h-5 w-5" />
                </a>
            </div>

            <div class="flex flex-col gap-7 lg:flex-row lg:gap-16">
                <div class="flex flex-1 flex-col gap-4 lg:gap-5">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-brand text-[0.9375rem] font-extrabold text-white" aria-hidden="true">{{ $initials }}</span>
                        <span class="text-xl font-extrabold uppercase text-white">{{ $siteName }}</span>
                    </div>
                    <p class="max-w-[33rem] text-base leading-[1.5625rem] text-mist">Operations & Systems Leader based in Bekasi, Indonesia. Customer operations, CRM hubs and automation pipelines that hold their SLA.</p>
                </div>

                <div class="grid grid-cols-2 gap-7 lg:flex lg:gap-16">
                    <div class="lg:w-[11.25rem]">
                        <h2 class="{{ $footHead }}">Work</h2>
                        <ul class="mt-2.5 space-y-1">
                            <li><a href="{{ route('projects.index') }}" class="{{ $footLink }}">Case studies</a></li>
                            <li><a href="{{ route('home') }}#career" class="{{ $footLink }}">Career</a></li>
                            <li><a href="{{ route('home') }}#toolkit" class="{{ $footLink }}">Toolkit</a></li>
                            <li><a href="{{ route('home') }}#credentials" class="{{ $footLink }}">Credentials</a></li>
                        </ul>
                    </div>
                    <div class="lg:w-[11.25rem]">
                        <h2 class="{{ $footHead }}">Writing</h2>
                        <ul class="mt-2.5 space-y-1">
                            <li><a href="{{ route('blog.index') }}" class="{{ $footLink }}">All articles</a></li>
                            @foreach($footerTopics as $topic)
                                <li><a href="{{ route('blog.index', ['category' => $topic->slug]) }}" class="{{ $footLink }}">{{ $topic->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="lg:w-[11.25rem]">
                        <h2 class="{{ $footHead }}">Contact</h2>
                        <ul class="mt-2.5 space-y-1">
                            <li><a href="{{ route('home') }}#contact" data-open-contact aria-haspopup="dialog" class="{{ $footLink }}">Contact form</a></li>
                            @if(setting('contact_email'))
                                <li><a href="mailto:{{ setting('contact_email') }}" class="{{ $footLink }}">Email</a></li>
                            @endif
                            @if(setting('linkedin_url'))
                                <li><a href="{{ setting('linkedin_url') }}" target="_blank" rel="noopener" class="{{ $footLink }}">LinkedIn<span class="sr-only"> (opens in new tab)</span></a></li>
                            @endif
                            @if(setting('whatsapp_number'))
                                <li><a href="https://wa.me/{{ setting('whatsapp_number') }}?text={{ rawurlencode('Hi ' . $siteName . ', I would like to discuss a collaboration.') }}" target="_blank" rel="noopener" class="{{ $footLink }}">WhatsApp<span class="sr-only"> (opens in new tab)</span></a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <p class="text-[0.9375rem] text-mist">&copy; {{ date('Y') }} {{ $siteName }}</p>
        </div>
    </footer>

    @include('partials.contact-dialog')
    @stack('scripts')
</body>
</html>
