@extends('layouts.public')

@section('title', 'Slamet Rivai | Operations & Systems Leader')

@php
    $stats = [
        ['value' => '99.8%', 'label' => 'SLA compliance', 'detail' => 'sustained across tiered support queues'],
        ['value' => '120+', 'label' => 'specialists led', 'detail' => 'hired, trained and scaled in-house'],
        ['value' => '+40%', 'label' => 'agent productivity', 'detail' => 'after CRM & routing redesign'],
        ['value' => '48h → 30m', 'label' => 'document cycle time', 'detail' => 'with Python/OCR automation'],
    ];

    $toolkit = [
        ['title' => 'CRM & Engagement', 'icon' => 'headset', 'items' => ['Zendesk Support / Chat', 'Salesforce Service Cloud', 'Freshdesk Enterprise', 'Omnichannel routing matrix', 'WhatsApp Cloud API']],
        ['title' => 'Data & Analytics', 'icon' => 'chart-column', 'items' => ['SQL / BigQuery', 'Metabase BI dashboards', 'Looker Studio reports', 'Tableau visualizations', 'Financial data modelling']],
        ['title' => 'Systems & Automation', 'icon' => 'workflow', 'items' => ['Make (Integromat)', 'Zapier custom webhooks', 'OCR Vision / Tesseract', 'Redis message queues', 'RESTful API integration']],
        ['title' => 'Ops & Collaboration', 'icon' => 'kanban', 'items' => ['Jira Service Management', 'Confluence SOP hub', 'Linear issue tracking', 'Notion enterprise ops', 'Slack Workflow Builder']],
        ['title' => 'Digital & Marketing', 'icon' => 'megaphone', 'items' => ['Google Analytics 4', 'Google Tag Manager', 'HubSpot Marketing Hub', 'Customer lifecycle flows', 'Mailchimp automation']],
    ];
    $credentialIcons = ['badge-check', 'award', 'languages'];

    $featured = $projects->firstWhere('is_featured', true) ?? $projects->first();
    $secondary = $featured ? $projects->where('id', '!=', $featured->id)->take(2) : collect();
    $featuredThumb = $featured?->thumbnail_url;

    // The three latest roles get the full entry; older ones collapse into compact rows.
    $recentRoles = $careers->take(3);
    $earlierRoles = $careers->slice(3);
    preg_match_all('/\d{4}/', $earlierRoles->pluck('period')->implode(' '), $earlierYears);
    $earlierRange = $earlierYears[0] ? min($earlierYears[0]) . ' to ' . max($earlierYears[0]) : null;
    $period = fn ($career) => str_replace([' - ', 'Present'], [' — ', 'Now'], $career->period);

    $featuredPost = $latestPosts->first();
    $sidePosts = $latestPosts->slice(1, 2);
    $postMeta = fn ($post) => collect([$post->category?->name, $post->published_at?->format('d M Y'), $post->reading_time . ' min read'])->filter()->implode(' · ');
@endphp

@section('content')
    {{-- Hero --}}
    <section id="home" class="page-container">
        <div class="flex flex-col items-center gap-10 lg:flex-row lg:items-end">
            <div class="flex-1 py-14 sm:py-20 lg:py-24">
                <p class="chip gap-2.5 bg-accent/10 px-3.5 py-2 text-accent">
                    <span class="h-2 w-2 rounded-full bg-accent" aria-hidden="true"></span>
                    Open for advisory & leadership roles · Bekasi, ID
                </p>
                <h1 class="mt-7">
                    <span class="block text-[1.375rem] font-medium text-content-secondary">Slamet Rivai</span>
                    <span class="mt-5 block text-[2.5rem] font-bold leading-[1.07] tracking-[-0.03em] text-content sm:text-5xl lg:text-[3.5rem]">I build the operations and systems that keep 120+ person support teams at 99.8% SLA.</span>
                </h1>
                <p class="mt-7 max-w-[38.75rem] text-lg leading-[1.875rem] text-content-secondary sm:text-[1.1875rem]">
                    9+ years leading Customer Operations, CRM architecture (Zendesk, Salesforce) and Python/OCR workflow automation for high-volume digital platforms.
                </p>
                <div class="mt-9 flex flex-wrap items-center gap-3">
                    <a href="{{ route('projects.index') }}" class="btn-primary min-h-12">
                        View case studies
                        <x-lucide-arrow-right class="h-[1.125rem] w-[1.125rem]" />
                    </a>
                    <a href="#contact" data-open-contact aria-haspopup="dialog" class="btn-outline min-h-12">
                        <x-lucide-message-square class="h-[1.125rem] w-[1.125rem] text-content-secondary" />
                        Contact me
                    </a>
                </div>
            </div>

            <div class="relative h-[26.25rem] w-full max-w-[32.5rem] shrink-0 sm:h-[35rem] lg:h-[38.75rem]" aria-hidden="true">
                <div class="absolute left-1/2 top-[20%] aspect-square w-[85%] -translate-x-1/2 rounded-full bg-[radial-gradient(closest-side,rgba(242,184,75,0.2),transparent)]"></div>
                <img src="{{ asset('images/slamet-rivai-cutout.png') }}" alt=""
                     class="pointer-events-none absolute inset-x-0 bottom-0 h-[94%] w-full select-none object-contain object-bottom">
            </div>
        </div>
    </section>

    {{-- Stats strip --}}
    <section class="page-container" aria-label="Track record in numbers">
        <dl class="grid grid-cols-1 divide-y divide-edge border-y border-edge lg:grid-cols-4 lg:divide-x lg:divide-y-0">
            @foreach($stats as $stat)
                <div class="flex flex-col gap-2.5 py-8 lg:px-8 lg:py-9 lg:first:pl-0">
                    <dt class="order-2 text-base font-semibold text-content">{{ $stat['label'] }}</dt>
                    <dd class="order-1 text-[2.5rem] font-bold leading-none tracking-[-0.03em] text-accent sm:text-[2.75rem]">{{ $stat['value'] }}</dd>
                    <dd class="order-3 text-sm leading-[1.3125rem] text-content-secondary">{{ $stat['detail'] }}</dd>
                </div>
            @endforeach
        </dl>
    </section>

    {{-- Case studies --}}
    @if($featured)
        <section id="projects" class="page-container py-20 sm:py-[7.5rem]" aria-labelledby="projects-title">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <p class="eyebrow">Selected work</p>
                    <h2 id="projects-title" class="section-title mt-3.5">Systems that moved the numbers</h2>
                </div>
                <a href="{{ route('projects.index') }}" class="link-accent shrink-0">All case studies <x-lucide-arrow-right class="h-4 w-4" /></a>
            </div>

            <article class="relative mt-12 flex flex-col overflow-hidden rounded-2xl border border-edge bg-surface transition hover:border-content-faint lg:min-h-[27.5rem] lg:flex-row">
                <div class="flex flex-col justify-between gap-8 p-6 sm:p-12 {{ $featuredThumb ? 'lg:w-[35rem] lg:shrink-0' : 'lg:flex-1' }}">
                    <div>
                        <p class="text-[0.8125rem] font-medium text-content-secondary">{{ $featured->category }} · {{ $featured->role }}</p>
                        <h3 class="mt-4 text-[1.625rem] font-bold leading-9 tracking-[-0.02em] text-content sm:text-[1.875rem]">
                            <a href="{{ route('projects.show', $featured->slug) }}" class="after:absolute after:inset-0">{{ $featured->title }}</a>
                        </h3>
                        <p class="mt-4 text-base leading-[1.625rem] text-content-secondary">{{ $featured->teaser }}</p>
                    </div>
                    <div class="flex flex-col gap-7">
                        @if(!empty($featured->impact_highlights))
                            <dl class="flex flex-wrap gap-x-10 gap-y-5">
                                @foreach(array_slice($featured->impact_highlights, 0, 2) as $h)
                                    <div class="flex flex-col-reverse gap-1.5">
                                        <dt class="text-[0.8125rem] text-content-secondary">{{ $h['label'] }}</dt>
                                        <dd class="text-[1.625rem] font-bold tracking-[-0.02em] text-accent">{{ $h['metric'] }}</dd>
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
                <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
                    @foreach($secondary as $case)
                        <article class="group relative flex flex-col gap-5 rounded-2xl border border-edge p-6 transition hover:border-content-faint sm:p-10">
                            <p class="text-[0.8125rem] font-medium text-content-secondary">{{ $case->category }} · {{ $case->role }}</p>
                            <h3 class="text-2xl font-bold leading-[1.875rem] tracking-[-0.017em] text-content">
                                <a href="{{ route('projects.show', $case->slug) }}" class="after:absolute after:inset-0">{{ $case->title }}</a>
                            </h3>
                            <p class="line-clamp-3 text-[0.9375rem] leading-6 text-content-secondary">{{ $case->teaser }}</p>
                            <div class="mt-auto flex flex-wrap items-end justify-between gap-4 border-t border-edge pt-5">
                                <dl class="flex gap-8">
                                    @foreach(array_slice($case->impact_highlights ?? [], 0, 2) as $h)
                                        <div class="flex flex-col-reverse gap-1">
                                            <dt class="text-[0.8125rem] text-content-secondary">{{ $h['label'] }}</dt>
                                            <dd class="text-[1.375rem] font-bold text-accent">{{ $h['metric'] }}</dd>
                                        </div>
                                    @endforeach
                                </dl>
                                <span class="inline-flex items-center gap-1.5 text-sm font-medium text-content" aria-hidden="true">
                                    Read case study
                                    <x-lucide-arrow-up-right class="h-4 w-4 text-content-secondary transition group-hover:-translate-y-0.5 group-hover:translate-x-0.5 group-hover:text-accent" />
                                </span>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    @endif

    {{-- Career --}}
    <section id="career" class="border-y border-edge bg-surface-inset" aria-labelledby="career-title">
        <div class="page-container flex flex-col gap-12 py-20 sm:py-24 lg:flex-row lg:gap-20">
            <div class="lg:w-[23.75rem] lg:shrink-0">
                <p class="eyebrow">Career</p>
                <h2 id="career-title" class="mt-4 text-[2rem] font-bold leading-[1.2] tracking-[-0.025em] text-content sm:text-4xl">9 years of making operations measurable.</h2>
                <p class="mt-4 text-base leading-[1.625rem] text-content-secondary">From process associate to operations & automation consultant — across travel, healthcare and SMEs.</p>
                <a href="#contact" data-open-contact aria-haspopup="dialog" class="link-accent mt-3">
                    <x-lucide-mail class="h-4 w-4" />
                    Discuss a role or project
                </a>
            </div>

            <ol class="flex-1">
                @foreach($recentRoles as $career)
                    <li class="flex flex-col gap-2 py-7 sm:flex-row sm:gap-8 [&:not(:first-child)]:border-t [&:not(:first-child)]:border-edge">
                        <p class="text-sm font-semibold tabular-nums sm:w-[7.5rem] sm:shrink-0 {{ $loop->first ? 'text-accent' : 'text-content-secondary' }}">{{ $period($career) }}</p>
                        <div class="flex-1">
                            <h3 class="text-[1.1875rem] font-semibold text-content">{{ $career->role }}</h3>
                            <p class="mt-1.5 text-sm font-medium text-content-secondary">{{ $career->company }}</p>
                            @if($career->description)
                                <p class="mt-1.5 text-[0.9375rem] leading-6 text-content-secondary/85">{{ $career->description }}</p>
                            @endif
                        </div>
                        <div class="sm:flex sm:w-[9.375rem] sm:shrink-0 sm:justify-end">
                            @if($career->highlight)
                                <span class="inline-block self-start rounded-md bg-accent/10 px-2.5 py-1.5 text-[0.8125rem] font-semibold text-accent">{{ $career->highlight }}</span>
                            @endif
                        </div>
                    </li>
                @endforeach

                @if($earlierRoles->isNotEmpty())
                    <li class="border-t border-edge pt-8">
                        <p class="pb-2 text-xs font-semibold tracking-[0.12em] text-content-faint"><span class="uppercase">Earlier</span>{{ $earlierRange ? ' — ' . $earlierRange : '' }}</p>
                        <ol class="divide-y divide-edge">
                        @foreach($earlierRoles as $career)
                            <li class="flex flex-col gap-1 py-3.5 sm:flex-row sm:items-center sm:gap-8">
                                <p class="text-sm font-semibold tabular-nums text-content-secondary sm:w-[7.5rem] sm:shrink-0">{{ $period($career) }}</p>
                                <p class="flex-1 text-base font-medium text-content">
                                    {{ $career->role }}
                                    <span class="ml-1 text-sm text-content-secondary">· {{ $career->company }}</span>
                                </p>
                                <div class="sm:flex sm:w-[9.375rem] sm:shrink-0 sm:justify-end">
                                    @if($career->highlight)
                                        <span class="inline-block self-start rounded-md bg-accent/10 px-2.5 py-1.5 text-[0.8125rem] font-semibold text-accent">{{ $career->highlight }}</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                        </ol>
                    </li>
                @endif
            </ol>
        </div>
    </section>

    {{-- Toolkit --}}
    <section id="toolkit" class="page-container pb-24 pt-20 sm:pt-[7.5rem]" aria-labelledby="toolkit-title">
        <div class="max-w-[45rem]">
            <p class="eyebrow">Toolkit</p>
            <h2 id="toolkit-title" class="section-title mt-3.5">The stack I run operations on</h2>
            <p class="mt-3.5 text-[1.0625rem] leading-[1.6875rem] text-content-secondary">Production-tested across live deployments — CRM platforms, queue workers, BI and team tooling.</p>
        </div>
        <div class="mt-14 grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-5">
            @foreach($toolkit as $group)
                <div>
                    <h3 class="flex items-center gap-2.5 border-b border-edge pb-4 text-[0.9375rem] font-semibold text-content">
                        <x-dynamic-component :component="'lucide-' . $group['icon']" class="h-[1.125rem] w-[1.125rem] text-accent" />
                        {{ $group['title'] }}
                    </h3>
                    <ul class="mt-[1.125rem] space-y-3 text-[0.9375rem] text-content-secondary">
                        @foreach($group['items'] as $tool)
                            <li>{{ $tool }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Credentials --}}
    @if($certifications->isNotEmpty())
        <section id="credentials" class="page-container flex flex-col gap-10 pb-20 pt-6 sm:pb-[7.5rem] lg:flex-row lg:gap-20" aria-labelledby="credentials-title">
            <div class="lg:w-[23.75rem] lg:shrink-0">
                <p class="eyebrow">Credentials</p>
                <h2 id="credentials-title" class="section-title mt-3.5">Certified, and verifiable</h2>
                <p class="mt-3.5 text-base leading-[1.625rem] text-content-secondary">National professional accreditation, contact-center leadership and C2 English — each with a public verification link.</p>
            </div>
            <ul class="flex-1 overflow-hidden rounded-2xl border border-edge">
                @foreach($certifications as $cert)
                    <li class="flex flex-wrap items-center gap-x-5 gap-y-3 px-5 py-6 sm:flex-nowrap sm:px-7 [&:not(:first-child)]:border-t [&:not(:first-child)]:border-edge">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[0.625rem] bg-accent/10 text-accent">
                            <x-dynamic-component :component="'lucide-' . ($credentialIcons[$loop->index] ?? 'badge-check')" class="h-5 w-5" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-[1.0625rem] font-semibold text-content">{{ $cert->title }}</h3>
                            <p class="mt-1 text-sm text-content-secondary">{{ $cert->issuer }}{{ $cert->valid_period ? ' · ' . $cert->valid_period : '' }}</p>
                        </div>
                        <div class="flex w-full items-center justify-between gap-4 pl-16 sm:w-auto sm:pl-0">
                            @if($cert->score)
                                <p class="text-sm font-medium text-content">{{ $cert->score }}</p>
                            @endif
                            @if($cert->credential_url)
                                <a href="{{ $cert->credential_url }}" target="_blank" rel="noopener"
                                   class="inline-flex min-h-11 items-center gap-1.5 rounded-lg border border-edge px-3 text-[0.8125rem] font-medium text-content-secondary transition hover:border-content-faint hover:text-content">
                                    Verify<span class="sr-only"> {{ $cert->title }} (opens in new tab)</span>
                                    <x-lucide-external-link class="h-3.5 w-3.5" />
                                </a>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    {{-- Clients --}}
    @if($clients->isNotEmpty())
        <section id="clients" class="border-y border-edge bg-surface-inset" aria-labelledby="clients-title">
            <div class="page-container py-16 sm:py-[4.5rem]">
                <h2 id="clients-title" class="text-[1.0625rem] font-semibold text-content">Organizations where my operations and automation work went live</h2>
                <ul class="mt-10 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($clients as $client)
                        <li class="flex items-center gap-5 rounded-[0.875rem] border border-edge p-5">
                            <span class="flex h-[4.5rem] w-[7.5rem] shrink-0 items-center justify-center overflow-hidden rounded-[0.625rem] bg-surface-logo">
                                @if($client->has_logo)
                                    <img src="{{ $client->logo_url }}" alt="" class="max-h-12 max-w-[6rem] object-contain" loading="lazy">
                                @else
                                    <span class="text-[1.375rem] font-bold tracking-[0.02em] text-[#8B95A7]" aria-hidden="true">{{ $client->initials }}</span>
                                @endif
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-[1.0625rem] font-semibold text-content">
                                    @if($client->website_url)
                                        <a href="{{ $client->website_url }}" target="_blank" rel="noopener" class="transition hover:text-accent">{{ $client->name }}<span class="sr-only"> (opens in new tab)</span></a>
                                    @else
                                        {{ $client->name }}
                                    @endif
                                </p>
                                <p class="chip mt-2.5 bg-accent/10 px-2.5 text-accent">
                                    <x-dynamic-component :component="'lucide-' . $client->industry_icon" class="h-[0.8125rem] w-[0.8125rem]" />
                                    {{ $client->industry }}
                                </p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- Writing --}}
    @if($featuredPost)
        <section id="writing" class="page-container py-20 sm:py-[7.5rem]" aria-labelledby="writing-title">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <p class="eyebrow">Writing</p>
                    <h2 id="writing-title" class="section-title mt-3.5">Field notes from the ops floor</h2>
                </div>
                <a href="{{ route('blog.index') }}" class="link-accent shrink-0">All articles <x-lucide-arrow-right class="h-4 w-4" /></a>
            </div>

            <div class="mt-12 flex flex-col gap-6 lg:flex-row">
                <article class="group relative flex flex-1 flex-col justify-between gap-10 rounded-2xl border border-edge bg-surface p-6 transition hover:border-content-faint sm:p-10 lg:min-h-[32.375rem]">
                    <div>
                        <p class="flex items-center gap-2.5 text-[0.8125rem] font-medium text-content-secondary">
                            <x-dynamic-component :component="'lucide-' . ($featuredPost->category?->icon ?? 'file-text')" class="h-4 w-4 text-accent" />
                            {{ $postMeta($featuredPost) }}
                        </p>
                        <h3 class="mt-4 text-[1.625rem] font-bold leading-[1.2] tracking-[-0.02em] text-content sm:text-[2rem]">
                            <a href="{{ route('blog.show', $featuredPost->slug) }}" class="after:absolute after:inset-0">{{ $featuredPost->title }}</a>
                        </h3>
                        <p class="mt-4 text-[0.9375rem] leading-6 text-content-secondary">{{ $featuredPost->excerpt }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-sm font-medium text-content" aria-hidden="true">
                        Read article
                        <x-lucide-arrow-up-right class="h-4 w-4 text-content-secondary transition group-hover:text-accent" />
                    </span>
                </article>

                @if($sidePosts->isNotEmpty())
                    <div class="flex flex-col gap-6 lg:w-[35rem] lg:shrink-0">
                        @foreach($sidePosts as $post)
                            <article class="group relative flex flex-1 flex-col justify-between gap-4 rounded-2xl border border-edge p-6 transition hover:border-content-faint sm:p-7">
                                <div>
                                    <p class="flex items-center gap-2.5 text-[0.8125rem] font-medium text-content-secondary">
                                        <x-dynamic-component :component="'lucide-' . ($post->category?->icon ?? 'file-text')" class="h-4 w-4 text-accent" />
                                        {{ $postMeta($post) }}
                                    </p>
                                    <h3 class="mt-4 text-[1.1875rem] font-bold leading-[1.5625rem] tracking-[-0.015em] text-content">
                                        <a href="{{ route('blog.show', $post->slug) }}" class="after:absolute after:inset-0">{{ $post->title }}</a>
                                    </h3>
                                    <p class="mt-4 line-clamp-2 text-[0.9375rem] leading-6 text-content-secondary">{{ $post->excerpt }}</p>
                                </div>
                                <span class="inline-flex items-center gap-1.5 pt-3 text-sm font-medium text-content" aria-hidden="true">
                                    Read article
                                    <x-lucide-arrow-up-right class="h-4 w-4 text-content-secondary transition group-hover:text-accent" />
                                </span>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif

    @include('partials.cta')
@endsection
