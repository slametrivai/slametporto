@extends('layouts.public')

@section('title', $project->title . ' | Case Study | Slamet Rivai')
@section('meta_description', Str::limit($project->challenge, 150))
@section('og_image', $project->cover_image_url)

@php
    $label = 'flex items-center gap-3 text-sm font-semibold text-accent-soft';
    $rule = '<span class="h-px w-8 bg-accent-deep" aria-hidden="true"></span>';
    $panel = 'rounded-2xl border border-edge bg-surface p-6 sm:p-8';
@endphp

@section('content')
<div class="mx-auto max-w-7xl px-4 pb-24 pt-8 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('projects.index') }}" class="inline-flex min-h-11 items-center gap-1.5 text-sm font-semibold text-content-secondary transition hover:text-accent">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to All Case Studies
        </a>
        <div class="flex items-center gap-2">
            <span class="inline-flex rounded-full border border-accent-deep/40 bg-accent/10 px-3 py-1 text-xs font-semibold text-accent-soft">{{ $project->category }}</span>
            @if($project->is_featured)
                <span class="inline-flex rounded-full bg-accent px-3 py-1 text-xs font-bold text-void">Featured System</span>
            @endif
        </div>
    </div>

    <header class="max-w-4xl">
        <p class="mb-2 text-sm text-content-secondary">Executive Operations Case Study. Role: <strong class="font-semibold text-content">{{ $project->role }}</strong></p>
        <h1 class="text-3xl font-extrabold leading-[1.2] tracking-tight text-content sm:text-4xl lg:text-5xl">{{ $project->title }}</h1>

        @if(!empty($project->impact_highlights))
            <dl class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-3">
                @foreach($project->impact_highlights as $highlight)
                    <div class="flex flex-col-reverse gap-1 rounded-xl border border-accent-deep/40 bg-accent/10 p-4">
                        <dt class="text-sm font-medium text-content-secondary">{{ $highlight['label'] ?? '' }}</dt>
                        <dd class="text-xl font-black tabular-nums text-accent">{{ $highlight['metric'] ?? '' }}</dd>
                    </div>
                @endforeach
            </dl>
        @endif
    </header>

    @if($project->cover_image)
        <div class="mt-10 overflow-hidden rounded-2xl border border-edge bg-void">
            <img src="{{ $project->cover_image_url }}" alt="{{ $project->title }}" class="h-64 w-full object-cover sm:h-96">
        </div>
    @endif

    <div class="mt-14 grid grid-cols-1 gap-12 lg:grid-cols-12">
        <div class="space-y-12 lg:col-span-8">
            @if(!empty($project->workflow_steps))
                <section class="{{ $panel }}">
                    <p class="{{ $label }}">{!! $rule !!} Architecture & Workflow</p>
                    <h2 class="mt-2 text-2xl font-bold text-content">End-to-End Pipeline Flow</h2>
                    <p class="mt-2 text-sm text-content-secondary">How data moves through each automated stage, from input to system output.</p>

                    <ol class="mt-6 flex flex-wrap items-center gap-2">
                        @foreach($project->workflow_steps as $stepIdx => $step)
                            <li class="inline-flex items-center gap-2">
                                <span class="inline-flex items-center gap-2 rounded-xl border border-edge bg-surface-elevated px-3.5 py-2 text-sm font-semibold text-content">
                                    <span class="h-2 w-2 rounded-full bg-accent-deep" aria-hidden="true"></span>
                                    {{ $step }}
                                </span>
                                @if($stepIdx < count($project->workflow_steps) - 1)
                                    <svg class="h-4 w-4 shrink-0 text-content-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </section>
            @endif

            <section class="{{ $panel }}">
                <p class="{{ $label }}">{!! $rule !!} Operational Bottlenecks</p>
                <h2 class="mt-2 text-2xl font-bold text-content">The Challenge & Problem Context</h2>
                <p class="mt-4 text-base leading-relaxed text-content-secondary">{{ $project->challenge }}</p>
            </section>

            <section class="{{ $panel }}">
                <p class="{{ $label }}">{!! $rule !!} Architecture & Implementation</p>
                <h2 class="mt-2 text-2xl font-bold text-content">The Engineered Systems Solution</h2>
                <p class="mt-4 text-base leading-relaxed text-content-secondary">{{ $project->solution }}</p>
            </section>

            <div class="flex flex-col items-start justify-between gap-6 rounded-2xl border border-accent-deep/40 bg-surface p-8 sm:flex-row sm:items-center">
                <div>
                    <p class="{{ $label }}">{!! $rule !!} Direct Consultation</p>
                    <h2 class="mt-1 text-xl font-bold text-content">Facing a similar operational bottleneck?</h2>
                    <p class="mt-2 max-w-lg text-sm leading-relaxed text-content-secondary">Talk through workflow automation, WhatsApp or CRM API integration, or contact center efficiency with Slamet Rivai.</p>
                </div>
                <a href="{{ route('home') }}#contact" data-open-contact aria-haspopup="dialog" class="inline-flex min-h-11 shrink-0 items-center rounded-lg bg-accent px-5 text-sm font-bold text-void transition hover:bg-accent-hover">Contact Slamet Rivai</a>
            </div>
        </div>

        <aside class="space-y-6 lg:col-span-4">
            <div class="rounded-2xl border border-edge bg-surface p-6">
                <h2 class="mb-4 text-sm font-semibold text-content">Case Study Overview</h2>
                <dl class="divide-y divide-edge text-sm">
                    <div class="flex justify-between gap-4 py-2.5">
                        <dt class="text-content-secondary">Domain Category</dt>
                        <dd class="text-right font-semibold text-content">{{ $project->category }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2.5">
                        <dt class="text-content-secondary">Systems Leader</dt>
                        <dd class="text-right font-semibold text-content">Slamet Rivai</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2.5">
                        <dt class="text-content-secondary">Role in Project</dt>
                        <dd class="text-right font-semibold text-content">{{ $project->role }}</dd>
                    </div>
                </dl>
            </div>

            @if($otherProjects->isNotEmpty())
                <div class="rounded-2xl border border-edge bg-surface p-6">
                    <h2 class="mb-4 text-sm font-semibold text-content">Other Featured Case Studies</h2>
                    <ul class="space-y-4">
                        @foreach($otherProjects as $other)
                            <li class="border-b border-edge pb-3 last:border-0 last:pb-0">
                                <p class="text-xs text-accent-soft">{{ $other->category }}</p>
                                <a href="{{ route('projects.show', $other->slug) }}" class="mt-0.5 block text-sm font-bold text-content transition hover:text-accent">{{ $other->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </aside>
    </div>
</div>
@endsection
