<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->currentLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#FFFFFF">
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

        // Same figures as the home stats.
        $proof = [
            ['value' => '+40%', 'label' => 'travelers booked', 'size' => 'text-2xl'],
            ['value' => '+30%', 'label' => 'sales, paperless', 'size' => 'text-2xl'],
            ['value' => '2d → ±30m', 'label' => 'data processing', 'size' => 'text-lg'],
        ];

        $featuredThumb = $featured?->thumbnail_url;
        $featuredMetric = $featured?->impact_highlights[0] ?? null;

        // "Doctor Trip – End-to-End Travel Booking System" reads as a short name plus what it is.
        $projectLink = fn ($project) => [
            'href' => route('projects.show', $project->slug),
            'icon' => 'layers',
            'title' => Str::of($project->title)->before(' – ')->trim()->toString(),
            'sub' => Str::contains($project->title, ' – ') ? Str::after($project->title, ' – ') : 'Case study · ' . $project->category,
        ];

        $links = [
            ['href' => route('home'), 'icon' => 'globe', 'title' => 'Portfolio website', 'sub' => 'All case studies & career history'],
            ...$moreProjects->map($projectLink)->all(),
        ];
        if ($latestPost) {
            $links[] = ['href' => route('blog.show', $latestPost->slug), 'icon' => 'pen-line', 'title' => 'Latest article', 'sub' => $latestPost->title];
        }
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
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>[x-cloak] { display: none !important; }</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans text-body antialiased">
    <main class="mx-auto flex w-full max-w-[26rem] flex-col gap-[1.125rem] px-5 pb-7 pt-4">
        <div class="relative flex justify-end" x-data="{ copied: false, async share() {
                const data = { title: document.title, url: @js(route('linkbio')) };
                if (navigator.share) { try { await navigator.share(data); } catch (e) {} return; }
                await navigator.clipboard.writeText(data.url);
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            } }">
            <button type="button" @click="share()" aria-label="Share this page"
                    class="flex h-11 w-11 items-center justify-center rounded-full bg-panel text-navy transition-colors hover:bg-line/50">
                <x-lucide-share-2 class="h-5 w-5" />
            </button>
            <p x-show="copied" x-cloak x-transition.opacity role="status"
               class="absolute right-0 top-full z-10 mt-2 whitespace-nowrap rounded-xl bg-ink px-3 py-1.5 text-sm font-semibold text-white">Link copied</p>
        </div>

        <header class="flex flex-col items-center gap-2.5 text-center">
            <span class="flex h-[7.5rem] w-[7.5rem] items-center justify-center rounded-full bg-brand">
                <img src="{{ asset('images/slamet-rivai-avatar.jpg') }}" alt="{{ $siteName }}" width="160" height="160" class="h-[6.75rem] w-[6.75rem] rounded-full object-cover">
            </span>
            <h1 class="text-[1.75rem] font-bold text-navy">{{ $siteName }}</h1>
            <p class="text-[1.1875rem] font-semibold leading-[1.5625rem] text-brand">I build the systems your business runs on.</p>
        </header>

        @if($socials)
            <ul class="flex justify-center gap-3" aria-label="Profiles and contact">
                @foreach($socials as $s)
                    <li>
                        <a href="{{ $s['href'] }}" @if($s['external']) target="_blank" rel="noopener" @endif
                           class="flex h-12 w-12 items-center justify-center rounded-full border border-line text-navy transition-colors hover:bg-panel">
                            <x-dynamic-component :component="'lucide-' . $s['icon']" class="h-5 w-5" aria-hidden="true" />
                            <span class="sr-only">{{ $s['label'] }}</span>@if($s['external']){!! $newTab !!}@endif
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        <a href="{{ route('home') }}#contact" data-open-contact aria-haspopup="dialog" class="btn-primary min-h-14 w-full gap-2.5 text-lg">
            <x-lucide-message-square class="h-5 w-5" />
            Contact me
        </a>

        <dl class="grid grid-cols-3 border-y border-line py-3.5" aria-label="Results in numbers">
            @foreach($proof as $p)
                <div class="flex flex-col items-center justify-center gap-0.5 border-r border-line px-1 text-center last:border-r-0">
                    <dt class="order-2 text-sm text-body">{{ $p['label'] }}</dt>
                    <dd class="order-1 font-bold text-brand {{ $p['size'] }}">{{ $p['value'] }}</dd>
                </div>
            @endforeach
        </dl>

        @if($featured)
            <a href="{{ route('projects.show', $featured->slug) }}" class="on-dark flex flex-col overflow-hidden rounded-3xl bg-ink transition-opacity hover:opacity-95">
                @if($featuredThumb)
                    <img src="{{ $featuredThumb }}" alt="" loading="lazy" class="h-[11.25rem] w-full object-cover">
                @endif
                <span class="flex flex-col gap-2 p-[1.125rem]">
                    <span class="flex items-center justify-between gap-3">
                        <span class="flex items-center gap-1.5 text-sm font-bold text-sky">
                            <x-lucide-star class="h-4 w-4" />
                            Featured case study
                        </span>
                        <x-lucide-arrow-up-right class="h-5 w-5 text-white" />
                    </span>
                    <span class="text-[1.375rem] font-semibold leading-[1.625rem] text-white">{{ $featured->title }}</span>
                    @if($featuredMetric)
                        <span class="text-[1.375rem] font-bold text-sky">{{ $featuredMetric['metric'] }}</span>
                        <span class="text-sm text-mist">{{ $featuredMetric['label'] }}</span>
                    @endif
                </span>
            </a>
        @endif

        <section class="flex flex-col gap-2.5" aria-labelledby="more-title">
            <h2 id="more-title" class="text-sm font-bold uppercase tracking-[0.1em] text-body">More from me</h2>
            <ul class="flex flex-col gap-2.5">
                @foreach($links as $link)
                    <li>
                        <a href="{{ $link['href'] }}" class="flex items-center gap-3 rounded-[1.125rem] bg-panel p-3.5 transition-colors hover:bg-line/50">
                            <span class="icon-chip" aria-hidden="true">
                                <x-dynamic-component :component="'lucide-' . $link['icon']" class="h-5 w-5" />
                            </span>
                            <span class="flex min-w-0 flex-1 flex-col gap-0.5">
                                <span class="text-[1.0625rem] font-bold text-navy">{{ $link['title'] }}</span>
                                <span class="line-clamp-2 text-sm leading-[1.125rem] text-body">{{ $link['sub'] }}</span>
                            </span>
                            <x-lucide-chevron-right class="h-5 w-5 shrink-0 text-navy" />
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>

        <footer class="pt-2">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-1.5 text-[0.9375rem] font-semibold text-body transition-colors hover:text-brand">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand text-sm font-extrabold text-white" aria-hidden="true">{{ $initials }}</span>
                {{ $host }}
            </a>
        </footer>
    </main>

    @include('partials.contact-dialog', ['closeLabel' => 'Back to links'])
</body>
</html>
