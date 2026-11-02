<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->currentLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume & Curriculum Vitae | Slamet Rivai</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; font-size: 11pt; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
@php
    $h2 = 'mb-3 border-b-2 border-brand pb-1 text-sm font-bold text-navy';
    $host = parse_url(setting('canonical_url', config('app.url')), PHP_URL_HOST);
@endphp
<body class="bg-panel px-4 py-8 font-sans text-body antialiased sm:px-6">
    <div class="mx-auto max-w-4xl">
        <div class="no-print mb-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-line bg-white p-4">
            <a href="{{ route('home') }}" class="inline-flex min-h-11 items-center text-sm font-semibold text-navy transition-colors hover:text-brand">Back to Portfolio</a>
            <button type="button" onclick="window.print()" class="inline-flex min-h-11 items-center gap-2 rounded-full bg-brand px-4 text-sm font-bold text-white transition-colors hover:bg-brand-dark">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print / Save as PDF
            </button>
        </div>

        <main class="space-y-8 rounded-2xl bg-white p-8 sm:p-12">
            <header class="flex flex-col justify-between gap-4 border-b border-line pb-6 sm:flex-row sm:items-start">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-navy">Slamet Rivai</h1>
                    <p class="mt-1 text-base font-bold text-brand">Operations & Systems Leader</p>
                    <p class="mt-2 max-w-xl text-sm leading-relaxed text-body">
                        9+ years orchestrating enterprise Customer Operations, CRM Systems, and Workflow Automation pipelines. Proven track record driving 75%+ operational cycle time reductions and maintaining 99.8% SLA compliance.
                    </p>
                </div>
                <ul class="shrink-0 space-y-1 text-sm text-body">
                    <li>📍 Bekasi, Indonesia</li>
                    @if(setting('contact_email'))<li>✉️ {{ setting('contact_email') }}</li>@endif
                    @if(setting('whatsapp_number'))<li>📱 +{{ setting('whatsapp_number') }}</li>@endif
                    @if(setting('linkedin_url'))<li>🔗 {{ preg_replace('#^https?://(www\.)?#', '', rtrim(setting('linkedin_url'), '/')) }}</li>@endif
                    <li>🌐 {{ $host }}</li>
                </ul>
            </header>

            <section>
                <h2 class="{{ $h2 }}">Core Leadership Competencies</h2>
                <div class="grid grid-cols-2 gap-3 text-sm sm:grid-cols-4">
                    @foreach([
                        'Customer Operations' => 'Tiered SLA routing & Escalations',
                        'CRM Architecture' => 'Omnichannel Customer 360',
                        'Workflow Automation' => 'OCR & WhatsApp Cloud API',
                        'People Development' => '120+ Agents SOP & Training',
                    ] as $title => $text)
                        <div class="rounded border border-line p-2.5">
                            <strong class="block text-navy">{{ $title }}</strong>
                            <span class="text-body">{{ $text }}</span>
                        </div>
                    @endforeach
                </div>
            </section>

            <section>
                <h2 class="{{ $h2 }}">Professional Experience</h2>
                <div class="space-y-6">
                    @foreach($careers as $career)
                        <div>
                            <div class="flex flex-col justify-between sm:flex-row sm:items-baseline">
                                <h3 class="text-sm font-bold text-navy">{{ $career->role }}</h3>
                                <span class="text-sm tabular-nums text-body">{{ $career->period }}</span>
                            </div>
                            <p class="text-sm font-semibold text-navy">{{ $career->company }}</p>
                            <p class="mt-1 text-sm leading-relaxed text-body">{{ $career->description }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <section>
                <h2 class="{{ $h2 }}">Key Engineered Systems & Impact</h2>
                <div class="space-y-4">
                    @foreach($featuredProjects as $project)
                        <div class="rounded-lg border border-line p-4">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <h3 class="text-sm font-bold text-navy">{{ $project->title }}</h3>
                                <span class="rounded-full bg-panel px-2 py-0.5 text-xs font-semibold text-navy">{{ $project->category }}</span>
                            </div>
                            <p class="mt-1 text-sm text-body">{{ $project->solution }}</p>
                            @if(!empty($project->impact_highlights))
                                <div class="mt-2 flex flex-wrap gap-2 text-xs">
                                    @foreach($project->impact_highlights as $h)
                                        <span class="rounded border border-line px-2 py-0.5 font-semibold tabular-nums text-navy">{{ $h['metric'] ?? '' }} ({{ $h['label'] ?? '' }})</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>

            <section>
                <h2 class="{{ $h2 }}">Certifications & Accreditations</h2>
                <div class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-3">
                    @foreach($certifications as $cert)
                        <div class="rounded border border-line p-3">
                            <p class="font-bold text-navy">{{ $cert->title }}</p>
                            <p class="text-navy">{{ $cert->issuer }}</p>
                            <p class="mt-1 text-xs tabular-nums text-body">{{ collect([$cert->score, $cert->valid_period])->filter()->implode(' • ') }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <footer class="border-t border-line pt-4 text-center text-xs text-body">
                Slamet Rivai • {{ $host }}
            </footer>
        </main>
    </div>
</body>
</html>
