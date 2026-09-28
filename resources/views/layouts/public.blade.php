<!DOCTYPE html>
{{-- "dark" is only for Laravel's pagination view, which ships dark: variants; the site itself is always navy. --}}
<html lang="{{ str_replace('_', '-', app()->currentLocale()) }}" class="dark scroll-smooth">
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
@php
    $siteName = setting('site_name', 'Slamet Rivai');
    $navLink = 'inline-flex min-h-11 items-center rounded-lg px-3 text-sm font-medium transition';
    $menuItem = 'group flex items-start gap-3 rounded-xl p-2.5 transition hover:bg-surface-elevated';
    $menuIcon = 'mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-surface-elevated text-accent-soft transition group-hover:bg-accent group-hover:text-void';
    $mobileLink = 'flex min-h-11 items-center rounded-lg px-3 text-content hover:bg-surface hover:text-accent';
@endphp
<body class="bg-void font-sans text-content antialiased">
    <header x-data="{ mobileMenuOpen: false }" @keydown.escape.window="mobileMenuOpen = false"
            class="sticky top-0 z-50 border-b border-edge bg-void/90 backdrop-blur-md">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <a href="{{ route('home') }}" class="group flex items-center gap-3 rounded-lg">
                    @if(setting('site_logo'))
                        <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="{{ $siteName }}" class="h-10 w-auto rounded-xl object-contain">
                    @else
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-accent text-lg font-extrabold text-void" aria-hidden="true">
                            {{ strtoupper(substr($siteName, 0, 2)) }}
                        </span>
                    @endif
                    <span>
                        <span class="block text-base font-bold tracking-tight text-content transition group-hover:text-accent">{{ $siteName }}</span>
                        <span class="block text-xs font-medium text-content-secondary">{{ setting('site_tagline', 'Operations & Systems Leader') }}</span>
                    </span>
                </a>

                <nav class="hidden items-center gap-1 md:flex lg:gap-2" aria-label="Main menu">
                    <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" @click.outside="open = false" @keydown.escape="open = false" class="relative">
                        <button type="button" @click="open = !open" :aria-expanded="open.toString()" aria-controls="menu-work"
                                class="{{ $navLink }} gap-1.5 {{ request()->routeIs('projects.*') ? 'font-semibold text-accent' : 'text-content hover:text-accent' }}"
                                :class="open && 'bg-surface text-accent'">
                            Systems & Work
                            <svg class="h-4 w-4 transition-transform" :class="open && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" class="absolute left-0 top-full h-2 w-full"></div>
                        <div id="menu-work" x-show="open" x-cloak x-transition.opacity.duration.150ms
                             class="absolute left-0 top-full z-50 mt-1.5 w-80 rounded-2xl border border-edge bg-surface p-2 shadow-lg">
                            <a href="{{ route('projects.index') }}" class="{{ $menuItem }} {{ request()->routeIs('projects.*') ? 'bg-surface-elevated' : '' }}">
                                <span class="{{ $menuIcon }}">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </span>
                                <span>
                                    <span class="block text-sm font-semibold text-content group-hover:text-accent">Case Studies Directory</span>
                                    <span class="mt-0.5 block text-xs leading-snug text-content-secondary">Engineering blueprints & production impact</span>
                                </span>
                            </a>
                            <a href="{{ route('home') }}#toolkit" class="{{ $menuItem }}">
                                <span class="{{ $menuIcon }}">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </span>
                                <span>
                                    <span class="block text-sm font-semibold text-content group-hover:text-accent">Technical Toolkit</span>
                                    <span class="mt-0.5 block text-xs leading-snug text-content-secondary">CRM stacks, automation engines & data pipeline</span>
                                </span>
                            </a>
                            <a href="{{ route('home') }}#clients" class="{{ $menuItem }}">
                                <span class="{{ $menuIcon }}">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </span>
                                <span>
                                    <span class="block text-sm font-semibold text-content group-hover:text-accent">Ecosystem & Clients</span>
                                    <span class="mt-0.5 block text-xs leading-snug text-content-secondary">Enterprise brands & live system deployments</span>
                                </span>
                            </a>
                        </div>
                    </div>

                    <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" @click.outside="open = false" @keydown.escape="open = false" class="relative">
                        <button type="button" @click="open = !open" :aria-expanded="open.toString()" aria-controls="menu-career"
                                class="{{ $navLink }} gap-1.5 text-content hover:text-accent" :class="open && 'bg-surface text-accent'">
                            Career & Profile
                            <svg class="h-4 w-4 transition-transform" :class="open && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" class="absolute left-0 top-full h-2 w-full"></div>
                        <div id="menu-career" x-show="open" x-cloak x-transition.opacity.duration.150ms
                             class="absolute left-0 top-full z-50 mt-1.5 w-80 rounded-2xl border border-edge bg-surface p-2 shadow-lg">
                            <a href="{{ route('home') }}#career" class="{{ $menuItem }}">
                                <span class="{{ $menuIcon }}">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                </span>
                                <span>
                                    <span class="block text-sm font-semibold text-content group-hover:text-accent">Career Progression</span>
                                    <span class="mt-0.5 block text-xs leading-snug text-content-secondary">9-year track record leading enterprise operations</span>
                                </span>
                            </a>
                            <a href="{{ route('home') }}#credentials" class="{{ $menuItem }}">
                                <span class="{{ $menuIcon }}">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                </span>
                                <span>
                                    <span class="block text-sm font-semibold text-content group-hover:text-accent">Certifications & Badges</span>
                                    <span class="mt-0.5 block text-xs leading-snug text-content-secondary">BNSP, Telexindo Distinction & EF SET C2</span>
                                </span>
                            </a>
                            <a href="{{ route('resume.show') }}" target="_blank" rel="noopener" class="{{ $menuItem }}">
                                <span class="{{ $menuIcon }}">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </span>
                                <span>
                                    <span class="block text-sm font-semibold text-content group-hover:text-accent">Executive Resume (CV)<span class="sr-only"> (opens in new tab)</span></span>
                                    <span class="mt-0.5 block text-xs leading-snug text-content-secondary">Printable career history and certifications</span>
                                </span>
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('blog.index') }}" class="{{ $navLink }} {{ request()->routeIs('blog.*') ? 'font-semibold text-accent' : 'text-content hover:text-accent' }}">Tech Blog</a>
                </nav>

                <div class="hidden items-center gap-3 md:flex">
                    <a href="{{ route('home') }}#contact" data-open-contact aria-haspopup="dialog"
                       class="inline-flex min-h-11 items-center rounded-lg bg-accent px-4 text-sm font-bold text-void transition hover:bg-accent-hover">Contact Me</a>
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex min-h-11 items-center rounded-lg border border-edge bg-surface px-3 text-sm font-semibold text-content transition hover:border-accent-deep hover:text-accent">Admin CMS</a>
                    @endauth
                </div>

                <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" :aria-expanded="mobileMenuOpen.toString()" aria-controls="mobile-menu"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-lg border border-edge bg-surface text-content md:hidden">
                    <span class="sr-only" x-text="mobileMenuOpen ? 'Close menu' : 'Open menu'">Open menu</span>
                    <svg x-show="!mobileMenuOpen" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" x-show="mobileMenuOpen" x-cloak x-transition.opacity.duration.150ms class="border-b border-edge bg-void px-4 pb-6 pt-3 md:hidden">
            <div class="space-y-4 text-sm font-medium">
                <div>
                    <p class="px-3 pb-1.5 text-xs font-semibold text-content-secondary">Systems & Work</p>
                    <a @click="mobileMenuOpen = false" href="{{ route('projects.index') }}" class="{{ $mobileLink }} {{ request()->routeIs('projects.*') ? 'bg-surface font-semibold text-accent' : '' }}">Case Studies Directory</a>
                    <a @click="mobileMenuOpen = false" href="{{ route('home') }}#toolkit" class="{{ $mobileLink }}">Technical Toolkit</a>
                    <a @click="mobileMenuOpen = false" href="{{ route('home') }}#clients" class="{{ $mobileLink }}">Ecosystem & Clients</a>
                </div>
                <div class="border-t border-edge pt-3">
                    <p class="px-3 pb-1.5 text-xs font-semibold text-content-secondary">Career & Profile</p>
                    <a @click="mobileMenuOpen = false" href="{{ route('home') }}#career" class="{{ $mobileLink }}">Career Timeline</a>
                    <a @click="mobileMenuOpen = false" href="{{ route('home') }}#credentials" class="{{ $mobileLink }}">Credentials & Certifications</a>
                </div>
                <div class="border-t border-edge pt-3">
                    <a @click="mobileMenuOpen = false" href="{{ route('blog.index') }}" class="{{ $mobileLink }} {{ request()->routeIs('blog.*') ? 'bg-surface font-semibold text-accent' : '' }}">Tech Blog</a>
                    <a @click="mobileMenuOpen = false" href="{{ route('resume.show') }}" target="_blank" rel="noopener" class="{{ $mobileLink }}">Executive Resume (CV)<span class="sr-only"> (opens in new tab)</span></a>
                </div>
                <div class="space-y-2 pt-2">
                    <a href="{{ route('home') }}#contact" data-open-contact aria-haspopup="dialog" @click="mobileMenuOpen = false"
                       class="flex min-h-11 w-full items-center justify-center rounded-lg bg-accent text-sm font-bold text-void transition hover:bg-accent-hover">Contact Me</a>
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="flex min-h-11 w-full items-center justify-center rounded-lg border border-edge bg-surface text-sm font-semibold text-content transition hover:border-accent-deep hover:text-accent">Admin CMS</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="mt-20 border-t border-edge bg-void">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3">
                        @if(setting('site_logo'))
                            <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="{{ $siteName }}" class="h-9 w-auto rounded-lg object-contain">
                        @else
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-accent text-base font-black text-void" aria-hidden="true">{{ strtoupper(substr($siteName, 0, 2)) }}</span>
                        @endif
                        <span class="text-base font-bold text-content">{{ $siteName }}</span>
                    </div>
                    <p class="mt-3 max-w-md text-sm leading-relaxed text-content-secondary">
                        {{ setting('meta_description', 'Operations & Systems Leader based in Bekasi, Indonesia. 9+ years scaling Customer Operations, enterprise CRM hubs, and resilient automation pipelines with verified 99.8% SLA compliance.') }}
                    </p>
                    <p class="mt-4 inline-flex items-center gap-2 text-sm text-content-secondary">
                        <span class="h-2 w-2 rounded-full bg-accent-deep" aria-hidden="true"></span>
                        Open for Advisory & Systems Leadership Roles
                    </p>
                </div>

                <div>
                    <h2 class="text-sm font-semibold text-content">Navigation</h2>
                    <ul class="mt-3 space-y-2 text-sm text-content-secondary">
                        <li><a href="{{ route('home') }}#career" class="transition hover:text-accent">Career Timeline</a></li>
                        <li><a href="{{ route('projects.index') }}" class="transition hover:text-accent {{ request()->routeIs('projects.*') ? 'font-semibold text-accent' : '' }}">Case Studies</a></li>
                        <li><a href="{{ route('home') }}#toolkit" class="transition hover:text-accent">Technical Toolkit</a></li>
                        <li><a href="{{ route('blog.index') }}" class="transition hover:text-accent">Tech Blog & Articles</a></li>
                        <li><a href="{{ route('resume.show') }}" target="_blank" rel="noopener" class="transition hover:text-accent">Executive Resume CV<span class="sr-only"> (opens in new tab)</span></a></li>
                    </ul>
                </div>

                @if(setting('contact_email') || setting('linkedin_url') || setting('whatsapp_number'))
                    <div>
                        <h2 class="text-sm font-semibold text-content">Connect</h2>
                        <ul class="mt-3 space-y-2 text-sm text-content-secondary">
                            @if(setting('contact_email'))
                                <li>
                                    <a href="mailto:{{ setting('contact_email') }}" class="inline-flex items-center gap-2 transition hover:text-accent">
                                        <svg class="h-4 w-4 text-accent-soft" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        {{ setting('contact_email') }}
                                    </a>
                                </li>
                            @endif
                            @if(setting('linkedin_url'))
                                <li>
                                    <a href="{{ setting('linkedin_url') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 transition hover:text-accent">
                                        <svg class="h-4 w-4 fill-current text-accent-soft" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 10.9v8.37H9.2V10.9H6.46M7.83 6.45a1.64 1.64 0 1 0 0 3.27 1.64 1.64 0 0 0 0-3.27Z"/></svg>
                                        LinkedIn Profile
                                    </a>
                                </li>
                            @endif
                            @if(setting('whatsapp_number'))
                                <li>
                                    <a href="https://wa.me/{{ setting('whatsapp_number') }}?text={{ rawurlencode('Hi ' . $siteName . ', I would like to discuss a collaboration.') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 transition hover:text-accent">
                                        <svg class="h-4 w-4 fill-current text-accent-soft" viewBox="0 0 24 24" aria-hidden="true"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654z"/></svg>
                                        WhatsApp Direct
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>

            <p class="mt-10 border-t border-edge pt-6 text-sm text-content-secondary">&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
        </div>
    </footer>

    @php $field = 'mt-1.5 block w-full rounded-lg border border-edge bg-void px-3.5 py-2.5 text-sm text-content placeholder:text-content-secondary focus:border-accent focus:ring-1 focus:ring-accent'; @endphp
    <dialog id="contact-dialog" aria-labelledby="contact-dialog-title" x-data="contactForm()" @close="submitted = false; errorMessage = ''"
            @click.self="$el.close()"
            class="w-[calc(100%-2rem)] max-w-lg rounded-2xl border border-edge bg-surface p-0 text-content backdrop:bg-void/80">
        <div class="max-h-[90vh] overflow-y-auto p-6 sm:p-8">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 id="contact-dialog-title" class="text-xl font-bold text-content">Let's Discuss Systems & Operations</h2>
                    <p class="mt-1 text-sm text-content-secondary">Your message goes straight to Slamet Rivai's inbox.</p>
                </div>
                <button type="button" @click="$el.closest('dialog').close()" aria-label="Close contact form"
                        class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-lg border border-edge text-content-secondary transition hover:text-accent">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div x-show="submitted" x-cloak role="status" class="mt-6 rounded-xl border border-accent-deep/40 bg-accent/10 p-5">
                <p class="text-sm font-bold text-content">Message sent</p>
                <p class="mt-1 text-sm text-content-secondary" x-text="successMessage"></p>
            </div>

            <div x-show="errorMessage" x-cloak role="alert" class="mt-6 rounded-xl border border-red-400/40 bg-red-500/10 p-4 text-sm text-red-200" x-text="errorMessage"></div>

            <form x-show="!submitted" @submit.prevent="submitForm" action="{{ route('contact.store') }}" method="POST" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label for="contact-name" class="block text-sm font-medium text-content-secondary">Full name *</label>
                    <input type="text" id="contact-name" name="name" x-model="formData.name" required autofocus autocomplete="name" class="{{ $field }}" placeholder="e.g. Pratama Wicaksana">
                </div>
                <div>
                    <label for="contact-email" class="block text-sm font-medium text-content-secondary">Work email *</label>
                    <input type="email" id="contact-email" name="email" x-model="formData.email" required autocomplete="email" class="{{ $field }}" placeholder="name@company.com">
                </div>
                <div>
                    <label for="contact-subject" class="block text-sm font-medium text-content-secondary">Topic</label>
                    <input type="text" id="contact-subject" name="subject" x-model="formData.subject" class="{{ $field }}" placeholder="e.g. Contact center SLA audit, OCR automation, or CRM migration">
                </div>
                <div>
                    <label for="contact-message" class="block text-sm font-medium text-content-secondary">What do you need? *</label>
                    <textarea id="contact-message" name="message" rows="4" x-model="formData.message" required minlength="10" class="{{ $field }}"
                              placeholder="Your current operational challenge, monthly ticket or document volume, systems in use, and target timeline."></textarea>
                </div>
                <button type="submit" :disabled="loading"
                        class="inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-lg bg-accent px-6 text-sm font-bold text-void transition hover:bg-accent-hover disabled:cursor-wait disabled:opacity-60">
                    <span x-show="!loading">Send inquiry</span>
                    <span x-show="loading" x-cloak class="flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        Sending...
                    </span>
                </button>
            </form>
        </div>
    </dialog>

    <script>
        // Any [data-open-contact] opens the dialog; its href (#contact) is the no-JS fallback.
        document.addEventListener('click', (e) => {
            if (!e.target.closest('[data-open-contact]')) return;
            e.preventDefault();
            document.getElementById('contact-dialog').showModal();
        });

        function contactForm() {
            return {
                formData: { name: '', email: '', subject: '', message: '' },
                loading: false,
                submitted: false,
                successMessage: '',
                errorMessage: '',
                submitForm() {
                    this.loading = true;
                    this.errorMessage = '';

                    fetch(@js(route('contact.store')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': @js(csrf_token()),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(this.formData),
                    })
                    .then(async (response) => {
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Your message could not be sent. Please try again.');
                        return data;
                    })
                    .then((data) => {
                        this.submitted = true;
                        this.successMessage = data.message;
                        this.formData = { name: '', email: '', subject: '', message: '' };
                    })
                    .catch((err) => { this.errorMessage = err.message; })
                    .finally(() => { this.loading = false; });
                },
            };
        }
    </script>
    @stack('scripts')
</body>
</html>
