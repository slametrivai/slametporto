<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->currentLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0B0F17">
    @php
        $siteName = setting('site_name', 'Slamet Rivai');
        $nameWords = preg_split('/\s+/', trim($siteName));
        $initials = strtoupper(mb_substr($nameWords[0], 0, 1) . mb_substr(end($nameWords), count($nameWords) > 1 ? 0 : 1, 1));
        $host = parse_url(setting('canonical_url', config('app.url')), PHP_URL_HOST);
        $newTab = '<span class="sr-only"> (opens in new tab)</span>';

        $socials = array_filter([
            setting('linkedin_url') ? ['label' => 'LinkedIn', 'icon' => 'linkedin', 'href' => setting('linkedin_url'), 'external' => true] : null,
            setting('github_url') ? ['label' => 'GitHub', 'icon' => 'github', 'href' => setting('github_url'), 'external' => true] : null,
            setting('contact_email') ? ['label' => 'Email', 'icon' => 'mail', 'href' => 'mailto:' . setting('contact_email'), 'external' => false] : null,
            setting('whatsapp_number') ? ['label' => 'WhatsApp', 'icon' => 'message-circle', 'href' => 'https://wa.me/' . setting('whatsapp_number') . '?text=' . rawurlencode('Hi ' . $siteName . ', I would like to discuss a collaboration.'), 'external' => true] : null,
        ]);

        // Same figures as the home stats strip (confirmed by the owner, anti-slop audit 003 #9).
        $proof = [
            ['value' => '99.8%', 'label' => 'SLA kept'],
            ['value' => '120+', 'label' => 'agents led'],
            ['value' => '48h→30m', 'label' => 'doc cycle'],
        ];

        $links = array_filter([
            ['href' => route('home'), 'icon' => 'globe', 'title' => 'Portfolio website', 'sub' => 'All case studies & career history'],
            $nextProject ? ['href' => route('projects.show', $nextProject->slug), 'icon' => 'layers', 'title' => $nextProject->title, 'sub' => 'Case study · ' . $nextProject->category] : null,
            $latestPost ? ['href' => route('blog.show', $latestPost->slug), 'icon' => 'pen-line', 'title' => 'Latest article', 'sub' => $latestPost->title] : null,
            ['href' => route('resume.show'), 'icon' => 'file-text', 'title' => 'Resume', 'sub' => 'Experience, skills and credentials'],
        ]);
    @endphp

    {!! seo(new \RalphJSmit\Laravel\SEO\Support\SEOData(
        title: $siteName . ' | Links',
        description: 'Contact ' . $siteName . ', read the latest case study and article, or open the full portfolio.',
        author: setting('site_author', $siteName),
        url: route('linkbio'),
    )) !!}

    @if(setting('site_favicon'))
        <link rel="icon" href="{{ asset('storage/' . setting('site_favicon')) }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@700&display=swap" rel="stylesheet">

    <style>[x-cloak] { display: none !important; }</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-void font-sans text-content antialiased">
    {{-- One amber wash behind the profile, as in the "Link in Bio — Mobile" frame. --}}
    <div class="min-h-screen bg-[radial-gradient(ellipse_80%_25rem_at_50%_0%,rgba(242,184,75,0.16),transparent)] bg-no-repeat">
        <main class="relative mx-auto flex w-full max-w-[26rem] flex-col items-center gap-6 px-5 pb-8 pt-14">
            <div class="absolute right-4 top-4" x-data="{ copied: false, async share() {
                    const data = { title: document.title, url: @js(route('linkbio')) };
                    if (navigator.share) { try { await navigator.share(data); } catch (e) {} return; }
                    await navigator.clipboard.writeText(data.url);
                    this.copied = true;
                    setTimeout(() => this.copied = false, 2000);
                } }">
                <button type="button" @click="share()" aria-label="Share this page"
                        class="flex h-11 w-11 items-center justify-center rounded-full border border-edge bg-surface/70 text-content transition hover:border-content-faint">
                    <x-lucide-share-2 class="h-[1.125rem] w-[1.125rem]" />
                </button>
                <p x-show="copied" x-cloak x-transition.opacity role="status"
                   class="absolute right-0 top-full mt-2 whitespace-nowrap rounded-lg border border-edge bg-surface px-3 py-1.5 text-[0.8125rem] font-medium text-content">Link copied</p>
            </div>

            <header class="flex w-full flex-col items-center gap-3.5 text-center">
                <div class="h-[7.5rem] w-[7.5rem] rounded-full p-1 ring-2 ring-inset ring-accent">
                    <img src="{{ asset('images/slamet-rivai-avatar.jpg') }}" alt="{{ $siteName }}" width="160" height="160"
                         class="h-full w-full rounded-full bg-surface-raised object-cover">
                </div>
                <div class="flex flex-col gap-1.5">
                    <h1 class="text-[1.625rem] font-bold tracking-[-0.02em] text-content">{{ $siteName }}</h1>
                    <p class="text-sm leading-[1.3125rem] text-content-secondary">Customer Ops · CRM Architecture · Automation</p>
                </div>
                <p class="chip gap-2 bg-accent/10 py-1.5 text-xs font-semibold text-accent">
                    <span class="h-2 w-2 rounded-full bg-accent" aria-hidden="true"></span>
                    Open for advisory roles · Bekasi, ID
                </p>
            </header>

            @if($socials)
                <ul class="flex gap-3" aria-label="Profiles and contact">
                    @foreach($socials as $s)
                        <li>
                            <a href="{{ $s['href'] }}" @if($s['external']) target="_blank" rel="noopener" @endif
                               class="flex h-12 w-12 items-center justify-center rounded-xl border border-edge bg-surface text-content transition hover:border-content-faint">
                                <x-dynamic-component :component="'lucide-' . $s['icon']" class="h-5 w-5" aria-hidden="true" />
                                <span class="sr-only">{{ $s['label'] }}</span>@if($s['external']){!! $newTab !!}@endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif

            <a href="{{ route('home') }}#contact" data-open-contact aria-haspopup="dialog"
               class="flex h-14 w-full items-center justify-center gap-2.5 rounded-[0.875rem] bg-accent text-base font-bold text-void transition hover:bg-accent-hover">
                <x-lucide-message-square class="h-5 w-5" />
                Contact me
            </a>

            <dl class="grid w-full grid-cols-3 divide-x divide-edge rounded-[0.875rem] border border-edge px-2 py-4" aria-label="Track record">
                @foreach($proof as $p)
                    <div class="flex flex-col items-center gap-1 text-center">
                        <dt class="order-2 text-xs text-content-secondary">{{ $p['label'] }}</dt>
                        <dd class="order-1 font-mono text-lg font-bold tracking-[-0.02em] {{ $loop->first ? 'text-accent' : 'text-content' }}">{{ $p['value'] }}</dd>
                    </div>
                @endforeach
            </dl>

            @if($featured)
                <a href="{{ route('projects.show', $featured->slug) }}"
                   class="glow-corner group flex w-full flex-col gap-3.5 rounded-2xl border border-accent/30 bg-surface p-5 transition hover:border-accent/60">
                    <span class="flex items-center justify-between gap-3">
                        <span class="chip gap-1.5 bg-accent/10 px-2.5 py-1 text-xs font-semibold text-accent">
                            <x-lucide-star class="h-3.5 w-3.5" />
                            Featured case study
                        </span>
                        <x-lucide-arrow-up-right class="h-[1.125rem] w-[1.125rem] text-accent transition group-hover:-translate-y-0.5 group-hover:translate-x-0.5" />
                    </span>
                    <span class="text-lg font-bold leading-6 text-content">{{ $featured->title }}</span>
                    <span class="line-clamp-3 text-sm leading-[1.375rem] text-content-secondary">{{ $featured->teaser }}</span>
                </a>
            @endif

            <section class="flex w-full flex-col gap-2.5" aria-labelledby="more-title">
                <h2 id="more-title" class="text-xs font-semibold uppercase tracking-[0.12em] text-content-secondary">More from me</h2>
                <ul class="flex flex-col gap-2.5">
                    @foreach($links as $link)
                        <li>
                            <a href="{{ $link['href'] }}" class="group flex items-center gap-3 rounded-[0.875rem] border border-edge bg-surface py-3 pl-3 pr-3.5 transition hover:border-content-faint">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[0.625rem] bg-surface-raised text-accent" aria-hidden="true">
                                    <x-dynamic-component :component="'lucide-' . $link['icon']" class="h-[1.125rem] w-[1.125rem]" />
                                </span>
                                <span class="flex min-w-0 flex-1 flex-col gap-0.5">
                                    <span class="line-clamp-2 text-[0.9375rem] font-semibold text-content">{{ $link['title'] }}</span>
                                    <span class="line-clamp-2 text-[0.8125rem] text-content-secondary">{{ $link['sub'] }}</span>
                                </span>
                                <x-lucide-chevron-right class="h-[1.125rem] w-[1.125rem] shrink-0 text-content-secondary transition group-hover:translate-x-0.5" />
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>

            <footer class="flex flex-col items-center gap-2.5 pt-2">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2.5 text-xs text-content-secondary transition hover:text-content">
                    <span class="flex h-7 w-7 items-center justify-center rounded-[0.4375rem] border-[1.5px] border-accent text-[0.6875rem] font-bold text-accent" aria-hidden="true">{{ $initials }}</span>
                    {{ $host }}
                </a>
            </footer>
        </main>
    </div>

    @include('partials.contact-dialog', ['closeLabel' => 'Back to links'])
</body>
</html>
