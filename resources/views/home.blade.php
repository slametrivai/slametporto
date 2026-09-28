@extends('layouts.public')

@section('title', 'Slamet Rivai | Operations & Systems Leader')
@section('meta_description', 'Operations & Systems Leader with 9+ years experience architecting customer operations, CRM hubs, OCR document pipelines, and scalable enterprise workflows.')

@php
    // Section eyebrow: a short accent rule that repeats the baseline under the hero portrait.
    $eyebrow = 'flex items-center gap-3 text-sm font-semibold text-accent-soft';
    $rule = '<span class="h-px w-8 bg-accent-deep" aria-hidden="true"></span>';
    $h2 = 'mt-2 text-3xl font-extrabold tracking-tight text-content sm:text-4xl';
    $lead = 'mt-3 text-base text-content-secondary';
    $card = 'rounded-2xl border border-edge bg-surface';

    $pillars = [
        ['title' => 'Customer Operations', 'text' => 'Tiered escalation hierarchies, SLA containment (99.8%), real-time agent dispatch, and operational incident governance.', 'metric' => '99.8% SLA Containment',
         'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
        ['title' => 'CRM Architecture', 'text' => 'Customer 360 profiling, Zendesk and Salesforce administration, webhook routing, and cross-channel ticket sync.', 'metric' => '↑40% Agent Productivity',
         'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
        ['title' => 'Workflow Automation', 'text' => 'Document OCR extraction, WhatsApp Cloud API dispatch, asynchronous webhook queues, and zero-touch ticket deflection.', 'metric' => '48h → 30m Cycle Time',
         'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z'],
        ['title' => 'People Development', 'text' => 'Hiring, training, and operational enablement for 120+ customer operations specialists, SOP formulation, and QA audits.', 'metric' => 'Telexindo Distinction 90/100',
         'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
    ];

    $toolkit = [
        'CRM & Engagement' => ['Zendesk Support / Chat', 'Salesforce Service Cloud', 'Freshdesk Enterprise', 'Omnichannel Routing Matrix', 'WhatsApp Cloud API'],
        'Data & Analytics' => ['SQL / BigQuery', 'Metabase BI Dashboards', 'Looker Studio Reports', 'Tableau Visualizations', 'Financial Data Modelling'],
        'Systems & Automation' => ['Make (Integromat)', 'Zapier Custom Webhooks', 'OCR Vision / Tesseract', 'Redis Message Queues', 'RESTful API Integration'],
        'Ops & Collaboration' => ['Jira Service Management', 'Confluence SOP Hub', 'Linear Issue Tracking', 'Notion Enterprise Ops', 'Slack Workflow Builder'],
        'Digital & Marketing' => ['Google Analytics 4 (GA4)', 'Google Tag Manager', 'HubSpot Marketing Hub', 'Customer Lifecycle Flow', 'Mailchimp Automation'],
    ];
@endphp

@section('content')
<div class="space-y-24 md:space-y-32">

    {{-- Hero --}}
    <section id="home" class="relative flex items-center overflow-hidden pb-4 pt-6 sm:pb-6 sm:pt-10 lg:pb-8 lg:pt-12">
        <div class="relative mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 items-center gap-10 lg:grid-cols-12 lg:gap-8">
                <div class="self-center py-6 sm:py-10 lg:col-span-7">
                    <p class="mb-5 inline-flex max-w-full flex-wrap items-center gap-x-2 gap-y-1 rounded-2xl border border-edge bg-surface px-3.5 py-1.5 text-xs font-medium text-content sm:rounded-full">
                        <span class="inline-flex shrink-0 items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-accent-deep" aria-hidden="true"></span>
                            Bekasi, Indonesia
                        </span>
                        <span class="hidden text-content-secondary sm:inline" aria-hidden="true">•</span>
                        <span class="font-semibold text-content-secondary">9+ Years Operations & Systems Leadership</span>
                    </p>

                    <div class="my-6 block sm:my-8 lg:hidden">
                        <div class="mx-auto flex w-full max-w-[320px] justify-center border-b-2 border-accent-deep sm:max-w-[420px]">
                            <img src="{{ asset('images/slamet-rivai-cutout.png') }}" alt="Slamet Rivai, Operations & Systems Leader"
                                 class="pointer-events-none h-auto max-h-[380px] w-full select-none object-contain sm:max-h-[480px]">
                        </div>
                    </div>

                    <h1 class="mb-2 text-base font-semibold text-accent-soft sm:text-lg">
                        Hey There 👋 I am
                        <span class="mt-1.5 block text-3xl font-extrabold leading-[1.12] tracking-tight text-content sm:text-4xl lg:text-5xl xl:text-6xl">Slamet Rivai</span>
                    </h1>

                    <h2 class="mb-5 mt-3 text-lg font-medium text-content-secondary sm:text-xl lg:text-2xl">
                        Professional <span class="font-bold text-content">Operations & Systems Leader</span>
                    </h2>

                    <p class="mb-8 max-w-xl text-base leading-relaxed text-content-secondary">
                        Directing enterprise Customer Operations, CRM architectures (Zendesk, Salesforce), and Python/OCR workflow automations. 9+ years scaling operational units up to 120+ specialists with verified 99.8% SLA compliance.
                    </p>

                    <div class="flex flex-wrap items-center gap-4">
                        <a href="#contact" data-open-contact aria-haspopup="dialog" class="inline-flex min-h-11 items-center justify-center rounded-lg bg-accent px-7 text-sm font-bold text-void transition hover:bg-accent-hover">Contact Me</a>
                        <a href="#projects" class="inline-flex min-h-11 items-center justify-center rounded-lg border border-edge bg-surface px-7 text-sm font-semibold text-content transition hover:border-accent-deep hover:text-accent">Case Studies</a>
                        <!-- <a href="{{ route('resume.show') }}" target="_blank" rel="noopener" class="inline-flex min-h-11 items-center gap-1.5 text-sm font-semibold text-content-secondary transition hover:text-accent">
                            <svg class="h-4 w-4 text-accent-soft" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Executive Resume<span class="sr-only"> (opens in new tab)</span>
                        </a> -->
                    </div>
                </div>

                <div class="relative -mt-10 hidden justify-end self-center lg:col-span-5 lg:flex" aria-hidden="true">
                    <div class="flex w-full max-w-[540px] justify-end border-b-2 border-accent-deep xl:max-w-[580px]">
                        <img src="{{ asset('images/slamet-rivai-cutout.png') }}" alt=""
                             class="pointer-events-none h-auto max-h-[660px] w-full select-none object-contain xl:max-h-[720px]">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Competency pillars --}}
    <section id="pillars" class="mx-auto !mt-6 max-w-7xl px-4 sm:!mt-8 sm:px-6 lg:!mt-10 lg:px-8">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($pillars as $p)
                <div class="{{ $card }} p-6 transition hover:border-accent-deep">
                    <span class="mb-4 flex h-11 w-11 items-center justify-center rounded-lg bg-surface-elevated text-accent-soft">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $p['icon'] }}"/></svg>
                    </span>
                    <h3 class="text-base font-bold text-content">{{ $p['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-content-secondary">{{ $p['text'] }}</p>
                    <p class="mt-4 text-sm font-bold tabular-nums text-accent-soft">{{ $p['metric'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Career timeline --}}
    <section id="career" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="careerTimeline()">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div class="max-w-2xl">
                <p class="{{ $eyebrow }}">{!! $rule !!} Track Record & Leadership</p>
                <h2 class="{{ $h2 }}">Career Progression Timeline</h2>
                <p class="{{ $lead }}">A chronological 9-year progression leading customer operations, CRM transformations, and automation engineering across high-volume digital platforms.</p>
            </div>

            <div class="flex shrink-0 items-center gap-3 self-start sm:self-end">
                <span class="mr-1 hidden text-sm text-content-secondary md:inline">Scroll sideways</span>
                <button type="button" @click="scrollPrev" :disabled="!canScrollLeft" aria-label="Previous milestone"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-edge bg-surface text-content transition hover:border-accent-deep hover:text-accent disabled:cursor-not-allowed disabled:opacity-40">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" @click="scrollNext" :disabled="!canScrollRight" aria-label="Next milestone"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-edge bg-surface text-content transition hover:border-accent-deep hover:text-accent disabled:cursor-not-allowed disabled:opacity-40">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>

        <div class="relative mt-10">
            <div class="pointer-events-none absolute bottom-6 left-0 top-0 z-20 w-8 bg-gradient-to-r from-void to-transparent" x-show="canScrollLeft" x-transition.opacity></div>
            <div class="pointer-events-none absolute bottom-6 right-0 top-0 z-20 w-8 bg-gradient-to-l from-void to-transparent" x-show="canScrollRight" x-transition.opacity></div>

            <div x-ref="track" tabindex="0" role="region" aria-label="Career timeline, scroll with the arrow keys"
                 @scroll.passive="checkScroll()" @mousedown="handleMouseDown($event)" @mouseleave="handleMouseLeave()" @mouseup="handleMouseUp()" @mousemove="handleMouseMove($event)"
                 class="flex cursor-grab select-none snap-x snap-mandatory gap-6 overflow-x-auto scroll-smooth rounded-2xl pb-6 pt-2 active:cursor-grabbing"
                 style="scrollbar-width: thin;">
                @foreach($careers as $index => $career)
                    <div class="group flex w-[300px] shrink-0 snap-start flex-col sm:w-[360px] lg:w-[400px]">
                        <div class="relative mb-6 flex items-center justify-center">
                            <div class="absolute left-0 right-1/2 top-1/2 h-0.5 -translate-y-1/2 bg-edge group-first:hidden"></div>
                            <div class="absolute left-1/2 right-0 top-1/2 h-0.5 -translate-y-1/2 bg-edge group-last:hidden"></div>
                            <div class="relative z-10 flex h-9 w-9 items-center justify-center rounded-full border-2 border-accent-deep bg-surface transition-colors group-hover:bg-accent">
                                <div class="h-2.5 w-2.5 rounded-full bg-accent-deep transition-colors group-hover:bg-void"></div>
                            </div>
                        </div>

                        <div class="{{ $card }} flex flex-1 flex-col p-6 transition hover:border-accent-deep">
                            <div class="flex items-center justify-between gap-2">
                                <span class="inline-flex rounded-full border border-accent-deep/40 bg-accent/10 px-3 py-1 text-xs font-semibold tabular-nums text-accent-soft">{{ $career->period }}</span>
                                <span class="text-xs font-semibold tabular-nums text-content-secondary">#{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <h3 class="mt-4 text-base font-bold text-content">{{ $career->role }}</h3>
                            <p class="mt-1 text-sm font-semibold text-accent-soft">{{ $career->company }}</p>
                            <p class="mt-3 text-sm leading-relaxed text-content-secondary">{{ $career->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Featured case studies --}}
    <section id="projects" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div class="max-w-2xl">
                <p class="{{ $eyebrow }}">{!! $rule !!} Operational Engineering</p>
                <h2 class="{{ $h2 }}">Featured Systems & Case Studies</h2>
                <p class="{{ $lead }}">Production operational blueprints: OCR parsing pipelines, high-throughput WhatsApp notification engines, and unified CRM dispatch hubs.</p>
            </div>
            <a href="{{ route('projects.index') }}" class="inline-flex min-h-11 shrink-0 items-center text-sm font-semibold text-accent-soft hover:text-accent hover:underline">View all case studies</a>
        </div>

        <div class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($projects->take(3) as $project)
                @include('projects._card', ['project' => $project])
            @endforeach
        </div>
    </section>

    {{-- Toolkit --}}
    <section id="toolkit" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <p class="{{ $eyebrow }}">{!! $rule !!} Stack & Competencies</p>
            <h2 class="{{ $h2 }}">Technical Toolkit & Platforms</h2>
            <p class="{{ $lead }}">Production-tested software, asynchronous queue workers, CRM platforms, and developer tooling utilized across live deployments.</p>
        </div>

        <div class="mt-12 grid grid-cols-1 gap-5 md:grid-cols-3 lg:grid-cols-5">
            @foreach($toolkit as $group => $tools)
                <div class="{{ $card }} p-5">
                    <h3 class="mb-4 text-sm font-semibold text-accent-soft">{{ $group }}</h3>
                    <ul class="space-y-2.5 text-sm text-content">
                        @foreach($tools as $tool)
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-accent-deep" aria-hidden="true"></span>
                                {{ $tool }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Credentials --}}
    <section id="credentials" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <p class="{{ $eyebrow }}">{!! $rule !!} Official Accreditations</p>
            <h2 class="{{ $h2 }}">Credentials & Certifications</h2>
            <p class="{{ $lead }}">Verified national professional accreditations (BNSP), contact center management distinctions (Telexindo), and professional English proficiency.</p>
        </div>

        <div class="mt-12 grid grid-cols-1 gap-6 md:grid-cols-3">
            @foreach($certifications as $cert)
                <div class="{{ $card }} flex flex-col justify-between p-6">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-surface-elevated text-accent-soft">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            </span>
                            @if($cert->valid_period)
                                <span class="text-xs tabular-nums text-content-secondary">{{ $cert->valid_period }}</span>
                            @endif
                        </div>
                        <h3 class="mt-4 text-base font-bold text-content">{{ $cert->title }}</h3>
                        <p class="mt-1 text-sm font-medium text-accent-soft">{{ $cert->issuer }}</p>
                        @if($cert->score)
                            <p class="mt-4 inline-flex rounded-full border border-accent-deep/40 bg-accent/10 px-3 py-1 text-xs font-semibold text-accent-soft">{{ $cert->score }}</p>
                        @endif
                    </div>

                    @if($cert->credential_url)
                        <div class="mt-6 border-t border-edge pt-4">
                            <a href="{{ $cert->credential_url }}" target="_blank" rel="noopener" class="inline-flex min-h-11 items-center gap-1.5 text-sm font-semibold text-content-secondary transition hover:text-accent">
                                Verify Official Credential<span class="sr-only"> (opens in new tab)</span>
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    {{-- Clients --}}
    <section id="clients" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="{{ $eyebrow }} justify-center">{!! $rule !!} Industry Footprint</p>
            <h2 class="{{ $h2 }}">Clients & Organizational Ecosystem</h2>
            <p class="{{ $lead }}">Companies, healthcare networks, and scale-ups where operational architectures and automation workflows have been deployed.</p>
        </div>

        <div class="mt-12 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
            @foreach($clients as $client)
                <div class="group {{ $card }} flex flex-col items-center justify-center p-4 text-center transition hover:border-accent-deep">
                    <div class="flex h-16 w-full items-center justify-center grayscale transition group-hover:grayscale-0">
                        <img src="{{ $client->logo_url }}" alt="{{ $client->name }}" class="max-h-12 max-w-full object-contain">
                    </div>
                    <p class="mt-3 w-full truncate text-sm font-bold text-content">{{ $client->name }}</p>
                    <p class="mt-0.5 w-full truncate text-xs text-content-secondary">{{ $client->industry }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Latest posts --}}
    @if($latestPosts->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <p class="{{ $eyebrow }}">{!! $rule !!} Knowledge Base</p>
                    <h2 class="{{ $h2 }}">Technical Blog & Architecture Notes</h2>
                    <p class="{{ $lead }}">Engineering field notes, queue reliability patterns, and real-world operational post-mortems.</p>
                </div>
                <a href="{{ route('blog.index') }}" class="inline-flex min-h-11 shrink-0 items-center text-sm font-semibold text-accent-soft hover:text-accent hover:underline">View all articles</a>
            </div>

            <div class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-3">
                @foreach($latestPosts as $post)
                    @include('blog._card', ['post' => $post])
                @endforeach
            </div>
        </section>
    @endif

    {{-- Contact --}}
    <section id="contact" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="{{ $card }} px-6 py-12 text-center sm:px-12 sm:py-16">
            <div class="mx-auto max-w-2xl">
                <p class="{{ $eyebrow }} justify-center">{!! $rule !!} Direct Inquiries</p>
                <h2 class="{{ $h2 }}">Let's Discuss Systems & Operations</h2>
                <p class="mt-4 text-base leading-relaxed text-content-secondary">
                    Planning an operations restructuring, CRM overhaul, document OCR automation, or high-volume WhatsApp Cloud API integration? Send a direct message or schedule a consultation.
                </p>

                <a href="#contact" data-open-contact aria-haspopup="dialog"
                   class="mt-8 inline-flex min-h-11 items-center justify-center rounded-lg bg-accent px-8 text-sm font-bold text-void transition hover:bg-accent-hover">
                    Send an inquiry
                </a>

                @if(setting('contact_email') || setting('whatsapp_number'))
                <dl class="mt-10 flex flex-wrap justify-center gap-x-8 gap-y-4 border-t border-edge pt-8 text-left">
                        @if(setting('contact_email'))
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-surface-elevated text-accent-soft">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </span>
                                <div>
                                    <dt class="text-xs font-medium text-content-secondary">Email Address</dt>
                                    <dd><a href="mailto:{{ setting('contact_email') }}" class="text-sm font-semibold text-content transition hover:text-accent">{{ setting('contact_email') }}</a></dd>
                                </div>
                            </div>
                        @endif

                        @if(setting('whatsapp_number'))
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-surface-elevated text-accent-soft">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                </span>
                                <div>
                                    <dt class="text-xs font-medium text-content-secondary">Direct WhatsApp</dt>
                                    <dd><a href="https://wa.me/{{ setting('whatsapp_number') }}?text={{ rawurlencode('Hi ' . setting('site_name', 'Slamet Rivai') . ', I would like to discuss a project.') }}" target="_blank" rel="noopener" class="text-sm font-semibold tabular-nums text-content transition hover:text-accent">+{{ setting('whatsapp_number') }}</a></dd>
                                </div>
                            </div>
                        @endif
                </dl>
                @endif
            </div>
        </div>
    </section>

</div>

@push('scripts')
<script>
function careerTimeline() {
    return {
        canScrollLeft: false,
        canScrollRight: true,
        init() {
            this.$nextTick(() => this.checkScroll());
        },
        scrollPrev() {
            this.$refs.track.scrollBy({ left: -380, behavior: 'smooth' });
        },
        scrollNext() {
            this.$refs.track.scrollBy({ left: 380, behavior: 'smooth' });
        },
        checkScroll() {
            const el = this.$refs.track;
            if (!el) return;
            this.canScrollLeft = el.scrollLeft > 15;
            this.canScrollRight = el.scrollLeft < (el.scrollWidth - el.clientWidth - 15);
        },
        isDown: false,
        startX: 0,
        scrollLeftPos: 0,
        handleMouseDown(e) {
            this.isDown = true;
            this.startX = e.pageX - this.$refs.track.offsetLeft;
            this.scrollLeftPos = this.$refs.track.scrollLeft;
        },
        handleMouseLeave() {
            this.isDown = false;
        },
        handleMouseUp() {
            this.isDown = false;
        },
        handleMouseMove(e) {
            if (!this.isDown) return;
            e.preventDefault();
            const x = e.pageX - this.$refs.track.offsetLeft;
            this.$refs.track.scrollLeft = this.scrollLeftPos - (x - this.startX) * 1.5;
        }
    }
}
</script>
@endpush
@endsection
