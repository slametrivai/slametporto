@extends('layouts.public')

@section('title', 'Slamet Rivai | Operations & Systems Leader')

@php
    $competencies = ['Process Efficiency', 'Workflow Automation', 'Customer Experience', 'Data-Driven Decisions', 'Digital Product Delivery'];

    $stats = [
        ['value' => '+40%', 'label' => 'more travelers booked', 'detail' => 'PT. Wisata Impian Universal'],
        ['value' => '+30%', 'label' => 'sales after going paperless', 'detail' => 'WhatsApp results, PT. Swabku Indonesia'],
        ['value' => '2d → ±30m', 'label' => 'manual data processing', 'detail' => 'OCR automation, PT. Baitussalam Mandiri'],
        ['value' => '+30%', 'label' => 'customer satisfaction', 'detail' => 'PT. Global Tiket Network'],
    ];

    $toolkit = [
        ['title' => 'CRM & Engagement', 'icon' => 'headset', 'items' => ['Zendesk Support / Chat', 'Salesforce Service Cloud', 'Freshdesk Enterprise', 'Omnichannel routing matrix', 'WhatsApp Cloud API']],
        ['title' => 'Data & Analytics', 'icon' => 'chart-column', 'items' => ['SQL / BigQuery', 'Metabase BI dashboards', 'Looker Studio reports', 'Tableau visualizations', 'Financial data modelling']],
        ['title' => 'Systems & Automation', 'icon' => 'workflow', 'items' => ['Make (Integromat)', 'Zapier custom webhooks', 'OCR Vision / Tesseract', 'Redis message queues', 'RESTful API integration']],
        ['title' => 'Ops & Collaboration', 'icon' => 'kanban', 'items' => ['Jira Service Management', 'Confluence SOP hub', 'Linear issue tracking', 'Notion enterprise ops', 'Slack Workflow Builder']],
        ['title' => 'Digital & Marketing', 'icon' => 'megaphone', 'items' => ['Google Analytics 4', 'Google Tag Manager', 'HubSpot Marketing Hub', 'Customer lifecycle flows', 'Mailchimp automation']],
    ];

    $ribbon = ['Zendesk', 'Salesforce', 'WhatsApp Cloud API', 'OCR Pipelines', 'Metabase', 'BigQuery', 'Make', 'Jira Service Management'];

    $featured = $projects->firstWhere('is_featured', true) ?? $projects->first();
    $secondary = $featured ? $projects->where('id', '!=', $featured->id)->take(2) : collect();
    $featuredThumb = $featured?->thumbnail_url;

    // The three latest roles get the full entry; older ones collapse into compact rows.
    $recentRoles = $careers->take(3);
    $earlierRoles = $careers->slice(3);
    preg_match_all('/\d{4}/', $earlierRoles->pluck('period')->implode(' '), $earlierYears);
    $earlierRange = $earlierYears[0] ? min($earlierYears[0]) . ' to ' . max($earlierYears[0]) : null;
    $period = fn ($career) => str_replace([' - ', ' — ', 'Present'], [' – ', ' – ', 'Now'], $career->period);

    // "Skor 90 / 100 (Distinction)" reads as a big "90 / 100" with a small "Distinction"; "Certified Specialist" splits on the first word.
    $scoreParts = function (?string $score) {
        if (preg_match('/(\d+\s*\/\s*\d+)\s*\(?([^)]*)\)?/', (string) $score, $m)) {
            return [$m[1], $m[2]];
        }

        return array_pad(explode(' ', trim((string) $score), 2), 2, null);
    };
    $certIcon = fn ($title) => match (true) {
        (bool) preg_match('/data|bnsp/i', $title) => 'database',
        (bool) preg_match('/contact|center/i', $title) => 'headset',
        (bool) preg_match('/english|ef set/i', $title) => 'languages',
        default => 'badge-check',
    };

    $homePosts = $latestPosts->take(2);
    $postMeta = fn ($post) => collect([$post->category?->name, $post->published_at?->format('d M Y'), $post->reading_time . ' min read'])->filter()->implode(' · ');
    $industries = $clients->pluck('industry')->unique()->values();
@endphp

@section('content')
    {{-- Hero --}}
    <section id="home" class="relative overflow-hidden lg:h-[51.5625rem]">
        <div class="relative flex flex-col items-center gap-3.5 px-5 pt-9 text-center lg:gap-3 lg:pt-11">
            <h1 class="text-[3rem] font-semibold leading-[3.125rem] tracking-[-0.0625rem] sm:text-[4rem] sm:leading-[4.25rem] lg:text-[5.5rem] lg:leading-[5.8125rem] lg:tracking-[-0.125rem]">
                <span class="block text-navy">I build the systems</span>
                <span class="block text-brand">your business runs on.</span>
            </h1>
            <p class="text-[0.9375rem] font-bold leading-[1.4375rem] text-navy lg:text-lg">{{ implode(' · ', $competencies) }}</p>
        </div>

        <div class="relative mt-0 h-[25rem] sm:h-[32rem] lg:absolute lg:inset-0 lg:mt-0 lg:h-auto">
            <div class="absolute left-1/2 top-[4.375rem] h-[25.625rem] w-[25.625rem] -translate-x-1/2 rounded-full bg-brand sm:h-[33rem] sm:w-[33rem] lg:top-[18.75rem] lg:h-[53.75rem] lg:w-[53.75rem]" aria-hidden="true"></div>
            <img src="{{ asset('images/slamet-rivai-cutout.png') }}" alt="{{ setting('site_name', 'Slamet Rivai') }}" width="1254" height="1254" fetchpriority="high"
                 class="pointer-events-none absolute left-1/2 top-2 h-[26.875rem] w-[26.875rem] max-w-none -translate-x-1/2 select-none object-contain sm:h-[34rem] sm:w-[34rem] lg:top-[12.8125rem] lg:h-[45rem] lg:w-[45rem]">
            <div class="absolute inset-x-0 top-[20.375rem] flex justify-center sm:top-[26.5rem] lg:top-[46.3125rem]">
                <div class="on-dark flex items-center gap-0.5 rounded-full bg-ink/65 p-1.5 ring-1 ring-inset ring-white/40 backdrop-blur-[6px] lg:gap-1 lg:p-2">
                    <a href="{{ route('projects.index') }}" class="inline-flex min-h-11 items-center gap-1.5 rounded-full bg-brand px-[1.125rem] py-3.5 text-base font-bold text-white transition-colors hover:bg-brand-dark lg:gap-2 lg:px-7 lg:text-lg">
                        View case studies
                        <x-lucide-arrow-up-right class="h-4 w-4 lg:h-5 lg:w-5" />
                    </a>
                    <a href="#contact" data-open-contact aria-haspopup="dialog" class="inline-flex min-h-11 items-center rounded-full px-4 py-3.5 text-base font-semibold text-white transition-colors hover:bg-white/10 lg:px-7 lg:text-lg">Contact me</a>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="page-container pb-12 pt-8 lg:pb-16 lg:pt-0" aria-label="Results in numbers">
        <dl class="grid grid-cols-2 gap-x-4 gap-y-6 lg:grid-cols-4 lg:gap-0 lg:border-b lg:border-line lg:py-9">
            @foreach($stats as $stat)
                <div class="flex flex-col gap-1 lg:gap-1.5 lg:border-r lg:border-line lg:px-6 lg:first:pl-0 lg:last:border-r-0">
                    <dt class="order-2 text-base font-bold leading-5 text-navy lg:text-lg lg:leading-normal">{{ $stat['label'] }}</dt>
                    <dd class="order-1 text-[1.875rem] font-bold tracking-[-0.03125rem] text-brand lg:text-[2.75rem] lg:tracking-[-0.0625rem]">{{ $stat['value'] }}</dd>
                    <dd class="order-3 text-sm leading-[1.1875rem] text-body lg:text-[0.9375rem] lg:leading-[1.3125rem]">{{ $stat['detail'] }}</dd>
                </div>
            @endforeach
        </dl>
    </section>

    {{-- Selected work --}}
    @if($featured)
        <section id="projects" class="band-dark rounded-[2rem] lg:rounded-[3rem]" aria-labelledby="projects-title">
            <div class="page-container flex flex-col gap-6 py-12 lg:gap-12 lg:py-[4.5rem]">
                <div class="flex items-end justify-between gap-4">
                    <div class="flex flex-col gap-2 lg:gap-2.5">
                        <p class="eyebrow text-sky">Selected work</p>
                        <h2 id="projects-title" class="section-title text-white">Systems that moved <span class="text-sky">the numbers</span></h2>
                    </div>
                    <a href="{{ route('projects.index') }}" class="btn-primary hidden shrink-0 lg:inline-flex">All case studies <x-lucide-arrow-right class="h-[1.125rem] w-[1.125rem]" /></a>
                </div>

                <article class="glass-card relative flex flex-col overflow-hidden rounded-3xl transition-colors hover:bg-white/[0.11] lg:min-h-[31.25rem] lg:flex-row lg:rounded-[1.75rem]">
                    <div class="flex flex-col justify-between gap-6 p-5 lg:p-9 {{ $featuredThumb ? 'lg:w-[30.625rem] lg:shrink-0' : 'lg:flex-1' }}">
                        <div class="flex flex-col gap-3.5">
                            <p class="text-sm font-semibold text-mist lg:text-[0.9375rem]">{{ $featured->category }} · {{ $featured->role }}</p>
                            <h3 class="text-[1.75rem] font-semibold leading-8 text-white lg:text-[2.125rem] lg:leading-[2.4375rem]">
                                <a href="{{ route('projects.show', $featured->slug) }}" class="after:absolute after:inset-0">{{ $featured->title }}</a>
                            </h3>
                            <p class="text-base leading-6 text-mist">{{ $featured->teaser }}</p>
                        </div>
                        <div class="flex flex-col gap-[1.125rem]">
                            @if(!empty($featured->impact_highlights))
                                <dl class="flex flex-wrap gap-x-8 gap-y-3">
                                    @foreach(array_slice($featured->impact_highlights, 0, 2) as $h)
                                        <div class="flex flex-col-reverse gap-1">
                                            <dt class="text-sm text-mist lg:text-[0.9375rem]">{{ $h['label'] }}</dt>
                                            <dd class="text-[1.625rem] font-bold text-sky lg:text-[2rem]">{{ $h['metric'] }}</dd>
                                        </div>
                                    @endforeach
                                </dl>
                            @endif
                            @include('projects._flow', ['project' => $featured])
                        </div>
                    </div>
                    @include('projects._thumb', ['project' => $featured, 'url' => $featuredThumb])
                </article>

                @if($secondary->isNotEmpty())
                    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2 lg:gap-6">
                        @foreach($secondary as $case)
                            <article class="glass-card relative flex flex-col gap-3.5 rounded-3xl p-5 transition-colors hover:bg-white/[0.11] lg:rounded-[1.75rem] lg:p-8">
                                <p class="text-sm font-semibold text-mist lg:text-[0.9375rem]">{{ $case->category }} · {{ $case->role }}</p>
                                <h3 class="text-[1.5rem] font-semibold leading-[1.8125rem] text-white lg:text-[1.625rem] lg:leading-[1.9375rem]">
                                    <a href="{{ route('projects.show', $case->slug) }}" class="after:absolute after:inset-0">{{ $case->title }}</a>
                                </h3>
                                <p class="text-base leading-6 text-mist">{{ $case->teaser }}</p>
                                <div class="mt-auto flex flex-wrap items-end justify-between gap-4 border-t border-white/20 pt-[1.125rem]">
                                    <dl class="flex flex-wrap gap-x-7 gap-y-3">
                                        @foreach(array_slice($case->impact_highlights ?? [], 0, 2) as $h)
                                            <div class="flex flex-col-reverse gap-1">
                                                <dt class="text-sm text-mist lg:text-[0.9375rem]">{{ $h['label'] }}</dt>
                                                <dd class="text-[1.375rem] font-bold text-sky lg:text-2xl">{{ $h['metric'] }}</dd>
                                            </div>
                                        @endforeach
                                    </dl>
                                    <span class="inline-flex items-center gap-1.5 text-base font-bold text-white" aria-hidden="true">
                                        Read case study
                                        <x-lucide-arrow-up-right class="h-[1.125rem] w-[1.125rem]" />
                                    </span>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif

                <a href="{{ route('projects.index') }}" class="btn-primary w-full lg:hidden">All case studies <x-lucide-arrow-right class="h-[1.125rem] w-[1.125rem]" /></a>
            </div>
        </section>
    @endif

    {{-- Career --}}
    <section id="career" class="page-container flex flex-col gap-7 py-14 lg:flex-row lg:gap-[4.5rem] lg:py-[6.5rem]" aria-labelledby="career-title">
        <div class="flex flex-col items-start gap-3 lg:w-[24.375rem] lg:shrink-0 lg:gap-[1.125rem]">
            <p class="eyebrow">Career</p>
            <h2 id="career-title" class="text-[2.25rem] font-semibold leading-10 text-navy lg:text-[2.75rem] lg:leading-[3.0625rem]">9 years of making operations <span class="text-brand">measurable.</span></h2>
            <p class="text-base leading-6 text-body lg:text-[1.0625rem] lg:leading-[1.625rem]">From process associate to operations & automation consultant, across travel, healthcare and SMEs.</p>
            <a href="#contact" data-open-contact aria-haspopup="dialog" class="link-brand lg:text-[1.0625rem]">
                <x-lucide-mail class="h-[1.125rem] w-[1.125rem]" />
                Discuss a role or project
            </a>
        </div>

        <ol class="min-w-0 flex-1">
            @foreach($recentRoles as $career)
                <li class="flex flex-col gap-1.5 border-b border-line py-6 first:pt-0 lg:flex-row lg:gap-6 lg:py-7">
                    <p class="text-base font-bold tabular-nums lg:w-[7.5rem] lg:shrink-0 {{ $loop->first ? 'text-brand' : 'text-body' }}">{{ $period($career) }}</p>
                    <div class="flex min-w-0 flex-1 flex-col gap-1.5">
                        <h3 class="text-[1.375rem] font-semibold text-navy lg:text-2xl">{{ $career->role }}</h3>
                        <p class="text-base font-semibold text-navy">{{ $career->company }}</p>
                        @if($career->description)
                            <p class="text-base leading-[1.5625rem] text-body">{{ $career->description }}</p>
                        @endif
                        @if($career->highlight)
                            <span class="chip mt-1 self-start">{{ $career->highlight }}</span>
                        @endif
                    </div>
                </li>
            @endforeach

            @if($earlierRoles->isNotEmpty())
                <li class="pt-7">
                    <p class="text-sm font-bold uppercase tracking-[0.0625rem] text-body">Earlier{{ $earlierRange ? ', ' . $earlierRange : '' }}</p>
                    <ol>
                        @foreach($earlierRoles as $career)
                            <li class="flex flex-col gap-1 border-b border-line py-3.5 last:border-b-0 lg:flex-row lg:items-center lg:gap-6">
                                <p class="text-base font-semibold tabular-nums text-body lg:w-[7.5rem] lg:shrink-0">{{ $period($career) }}</p>
                                <p class="min-w-0 flex-1 text-[1.0625rem] font-semibold text-navy">
                                    {{ $career->role }}
                                    <span class="ml-1 block text-[0.9375rem] font-normal text-body sm:inline">· {{ $career->company }}</span>
                                </p>
                                @if($career->highlight)
                                    <span class="chip shrink-0 self-start lg:self-auto">{{ $career->highlight }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </li>
            @endif
        </ol>
    </section>

    {{-- Toolkit --}}
    <div class="px-3 pb-12 sm:px-5 lg:px-10 lg:pb-[4.5rem]">
        <section id="toolkit" class="flex flex-col gap-7 rounded-[2rem] bg-panel px-5 py-10 lg:gap-12 lg:rounded-[3rem] lg:px-12 lg:py-[4.5rem]" aria-labelledby="toolkit-title">
            <div class="flex max-w-[47.5rem] flex-col gap-2 lg:gap-3">
                <p class="eyebrow text-brand-dark">Toolkit</p>
                <h2 id="toolkit-title" class="section-title">The stack I run <span class="text-brand">operations on</span></h2>
                <p class="text-base leading-6 text-body lg:text-lg lg:leading-[1.6875rem]">Production-tested across live deployments: CRM platforms, queue workers, BI and team tooling.</p>
            </div>
            <div class="grid grid-cols-1 gap-7 sm:grid-cols-2 lg:grid-cols-5">
                @foreach($toolkit as $group)
                    <div class="flex flex-col gap-2 lg:gap-3">
                        <h3 class="flex items-center gap-2 border-b border-line pb-2.5 text-[1.0625rem] font-bold text-navy lg:pb-3">
                            <x-dynamic-component :component="'lucide-' . $group['icon']" class="h-[1.125rem] w-[1.125rem] shrink-0 text-brand" />
                            {{ $group['title'] }}
                        </h3>
                        <ul class="flex flex-col gap-2 text-[0.9375rem] leading-5 text-body lg:gap-3">
                            @foreach($group['items'] as $tool)
                                <li>{{ $tool }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    {{-- Credentials --}}
    @if($certifications->isNotEmpty())
        <section id="credentials" class="band-dark-top rounded-[2rem] lg:rounded-[3rem]" aria-labelledby="credentials-title">
            <div class="page-container flex flex-col gap-5 py-12 lg:items-center lg:gap-14 lg:py-[5.5rem]">
                <div class="flex max-w-[47.5rem] flex-col gap-2 lg:items-center lg:gap-4 lg:text-center">
                    <p class="eyebrow text-sky">Credentials</p>
                    <h2 id="credentials-title" class="section-title text-white">Certified, and <span class="text-sky">verifiable</span></h2>
                    <p class="text-base leading-6 text-mist lg:text-lg lg:leading-[1.6875rem]">National professional accreditation, contact-center leadership and C2 English, each with a public verification link.</p>
                </div>
                <ul class="grid w-full grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-6">
                    @foreach($certifications as $cert)
                        @php [$scoreValue, $scoreLabel] = $scoreParts($cert->score); @endphp
                        <li class="glass-card flex flex-col gap-3 rounded-3xl p-5 lg:gap-5 lg:p-8">
                            <div class="flex items-center justify-between gap-3">
                                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-white text-brand lg:h-14 lg:w-14">
                                    <x-dynamic-component :component="'lucide-' . $certIcon($cert->title)" class="h-6 w-6" />
                                </span>
                                @if($cert->credential_url)
                                    <a href="{{ $cert->credential_url }}" target="_blank" rel="noopener" class="inline-flex min-h-11 items-center gap-1.5 text-base font-bold text-sky transition-colors hover:text-white">
                                        Verify<span class="sr-only"> {{ $cert->title }} (opens in new tab)</span>
                                        <x-lucide-arrow-up-right class="h-[1.125rem] w-[1.125rem]" />
                                    </a>
                                @endif
                            </div>
                            @if($scoreValue)
                                <p class="flex flex-wrap items-end gap-x-2.5">
                                    <span class="text-[2rem] font-bold leading-tight text-white lg:text-[2.5rem]">{{ $scoreValue }}</span>
                                    @if($scoreLabel)
                                        <span class="pb-1 text-[0.9375rem] font-bold text-sky lg:text-[1.0625rem]">{{ $scoreLabel }}</span>
                                    @endif
                                </p>
                            @endif
                            <h3 class="text-xl font-semibold leading-[1.625rem] text-white lg:text-[1.375rem] lg:leading-7">{{ $cert->title }}</h3>
                            <p class="text-[0.9375rem] leading-[1.4375rem] text-mist lg:text-base">{{ $cert->issuer }}{{ $cert->valid_period ? ' · ' . $cert->valid_period : '' }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- Clients --}}
    @if($clients->isNotEmpty())
        <section id="clients" class="mx-auto flex max-w-page flex-col gap-5 pb-10 pl-5 pt-14 sm:pl-8 lg:gap-8 lg:pb-16 lg:pl-20 lg:pt-24" aria-labelledby="clients-title" x-data="clientCarousel()">
            <h2 id="clients-title" class="max-w-[80rem] pr-5 text-[1.75rem] font-semibold leading-[2.125rem] text-navy lg:text-[2.25rem] lg:leading-[2.6875rem]">Organizations where my operations and automation work went live</h2>

            <div class="flex items-center justify-between gap-4 pr-5 lg:pr-20">
                <div class="-my-1 flex gap-2 overflow-x-auto py-1 lg:gap-2.5" role="group" aria-label="Filter by industry">
                    <button type="button" @click="setFilter('all')" :aria-pressed="(filter === 'all').toString()" class="pill-tab lg:text-base"
                            :class="filter === 'all' ? 'bg-brand font-bold text-white' : 'bg-panel font-semibold text-navy hover:bg-line/50'">All</button>
                    @foreach($industries as $industry)
                        <button type="button" @click="setFilter(@js($industry))" :aria-pressed="(filter === @js($industry)).toString()" class="pill-tab lg:text-base"
                                :class="filter === @js($industry) ? 'bg-brand font-bold text-white' : 'bg-panel font-semibold text-navy hover:bg-line/50'">{{ $industry }}</button>
                    @endforeach
                </div>
                <div class="hidden shrink-0 gap-2.5 lg:flex">
                    <button type="button" @click="scrollPage(-1)" aria-label="Previous organizations" class="flex h-12 w-12 items-center justify-center rounded-full border border-line text-navy transition-colors hover:bg-panel">
                        <x-lucide-arrow-left class="h-5 w-5" />
                    </button>
                    <button type="button" @click="scrollPage(1)" aria-label="Next organizations" class="flex h-12 w-12 items-center justify-center rounded-full bg-brand text-white transition-colors hover:bg-brand-dark">
                        <x-lucide-arrow-right class="h-5 w-5" />
                    </button>
                </div>
            </div>

            <ul x-ref="track" @scroll.passive="syncPage()" class="relative flex snap-x gap-4 overflow-x-auto pb-2 pr-5 [scrollbar-width:none] lg:gap-5 lg:pr-20 [&::-webkit-scrollbar]:hidden">
                @foreach($clients as $client)
                    <li x-show="filter === 'all' || filter === @js($client->industry)" class="flex h-[18.5rem] w-[16.25rem] shrink-0 snap-start flex-col gap-3.5 rounded-[1.25rem] border border-line p-3 lg:h-[19.75rem] lg:w-[18.125rem]">
                        <span class="flex h-40 w-full shrink-0 items-center justify-center overflow-hidden rounded-xl bg-panel">
                            @if($client->has_logo)
                                <img src="{{ $client->logo_url }}" alt="" loading="lazy" class="h-full w-full object-contain p-4">
                            @else
                                <span class="text-[2.25rem] font-bold text-navy" aria-hidden="true">{{ $client->initials }}</span>
                            @endif
                        </span>
                        <div class="flex min-h-0 flex-1 flex-col items-start justify-between gap-2 px-1 pb-1">
                            <p class="line-clamp-2 text-[1.1875rem] font-semibold leading-[1.4375rem] text-navy">
                                @if($client->website_url)
                                    <a href="{{ $client->website_url }}" target="_blank" rel="noopener" class="transition-colors hover:text-brand">{{ $client->name }}<span class="sr-only"> (opens in new tab)</span></a>
                                @else
                                    {{ $client->name }}
                                @endif
                            </p>
                            <p class="chip font-semibold">
                                <x-dynamic-component :component="'lucide-' . $client->industry_icon" class="h-3.5 w-3.5 text-brand" />
                                {{ $client->industry }}
                            </p>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="flex items-center justify-center gap-2 pr-5 lg:pr-20" aria-hidden="true" x-show="pages > 1">
                <template x-for="i in pages" :key="i">
                    <span class="h-3 rounded-full transition-all" :class="i - 1 === page ? 'w-12 bg-brand' : 'w-3 bg-line'"></span>
                </template>
            </div>
        </section>
    @endif

    {{-- Tool ribbon --}}
    <div class="relative h-[8.125rem] overflow-hidden lg:h-[13.125rem]" aria-hidden="true">
        <div class="absolute -left-10 top-[1.875rem] h-[3.125rem] w-[130%] origin-top-left rotate-[4deg] bg-line lg:-left-[3.75rem] lg:top-[2.875rem] lg:h-[4.75rem] lg:rotate-[2.5deg]"></div>
        <div class="absolute -left-10 top-[5.5rem] flex h-[3.125rem] w-[130%] origin-top-left -rotate-[4deg] items-center gap-3.5 whitespace-nowrap bg-brand px-4 text-2xl font-semibold text-white lg:-left-[3.75rem] lg:top-32 lg:h-[4.75rem] lg:-rotate-[2.5deg] lg:gap-6 lg:px-8 lg:text-[2.25rem]">
            @foreach($ribbon as $tool)
                <span>{{ $tool }}</span>
                <x-lucide-sparkle class="h-5 w-5 shrink-0 fill-white lg:h-7 lg:w-7" />
            @endforeach
        </div>
    </div>

    {{-- Writing --}}
    @if($homePosts->isNotEmpty())
        <section id="writing" class="page-container flex flex-col gap-7 pb-12 pt-6 lg:gap-12 lg:pb-28 lg:pt-14" aria-labelledby="writing-title">
            <div class="flex items-center justify-between gap-4">
                <div class="flex flex-col gap-1 lg:gap-0">
                    <p class="eyebrow">Writing</p>
                    <h2 id="writing-title" class="section-title">Field notes from <span class="block text-brand">the ops floor</span></h2>
                </div>
                <a href="{{ route('blog.index') }}" class="btn-primary hidden shrink-0 lg:inline-flex">All articles</a>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 lg:gap-6">
                @foreach($homePosts as $post)
                    <article class="group relative flex flex-col items-start gap-3 lg:gap-4">
                        @include('blog._thumb', ['post' => $post, 'tone' => $loop->first ? 'bg-brand' : 'bg-navy', 'class' => 'h-[11.25rem] w-full rounded-3xl p-5 lg:h-[17.5rem] lg:rounded-[1.75rem] lg:p-7', 'wordClass' => 'text-[2.75rem] lg:text-[4rem]', 'chipClass' => 'h-12 w-12 lg:h-16 lg:w-16'])
                        <p class="text-sm font-semibold text-body lg:text-[0.9375rem]">{{ $postMeta($post) }}</p>
                        <h3 class="text-[1.625rem] font-semibold leading-8 text-navy lg:text-[1.875rem] lg:leading-9">
                            <a href="{{ route('blog.show', $post->slug) }}" class="after:absolute after:inset-0">{{ $post->title }}</a>
                        </h3>
                        <p class="text-base leading-[1.5625rem] text-body">{{ $post->excerpt }}</p>
                        <span class="inline-flex items-center gap-1.5 text-base font-bold text-navy" aria-hidden="true">
                            Read article
                            <x-lucide-arrow-up-right class="h-[1.125rem] w-[1.125rem]" />
                        </span>
                    </article>
                @endforeach
            </div>

            <a href="{{ route('blog.index') }}" class="btn-primary w-full lg:hidden">All articles</a>
        </section>
    @endif

    @include('partials.cta')
@endsection

@push('scripts')
<script>
function clientCarousel() {
    return {
        filter: 'all',
        page: 0,
        pages: 1,
        init() {
            this.$nextTick(() => this.syncPage());
            window.addEventListener('resize', () => this.syncPage(), { passive: true });
        },
        setFilter(value) {
            this.filter = value;
            this.$refs.track.scrollLeft = 0;
            this.$nextTick(() => this.syncPage());
        },
        scrollPage(direction) {
            const track = this.$refs.track;
            track.scrollBy({ left: direction * track.clientWidth * 0.9, behavior: 'smooth' });
        },
        syncPage() {
            const track = this.$refs.track;
            const step = track.clientWidth * 0.9 || 1;
            this.pages = Math.max(1, Math.ceil((track.scrollWidth - track.clientWidth) / step) + 1);
            this.page = Math.min(this.pages - 1, Math.round(track.scrollLeft / step));
        },
    };
}
</script>
@endpush
