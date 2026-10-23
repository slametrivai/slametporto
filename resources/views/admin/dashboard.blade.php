@extends('admin.layouts.app')

@section('title', 'Ringkasan')

@php
    $num = fn ($n) => number_format($n, 0, ',', '.');
    $firstName = \Illuminate\Support\Str::before(trim(auth()->user()->name), ' ');

    $chartMax = max(4, (int) ceil($chart->max(fn ($w) => $w['projects'] + $w['posts']) / 4) * 4);
    $chartTotal = $chart->sum(fn ($w) => $w['projects'] + $w['posts']);
    $series = [
        'projects' => ['label' => 'Studi kasus', 'class' => 'bg-series-case'],
        'posts' => ['label' => 'Artikel', 'class' => 'bg-series-post'],
    ];

    $typeLabel = ['project' => 'Studi kasus', 'post' => 'Artikel'];
    $statusBadge = [
        'published' => ['Terbit', 'admin-badge-success'],
        'scheduled' => ['Terjadwal', 'admin-badge-info'],
        'draft' => ['Draf', 'admin-badge-gray'],
    ];
    $tabs = [
        'all' => ['Semua', $recentContent->count()],
        'project' => ['Studi kasus', $recentContent->where('type', 'project')->count()],
        'post' => ['Artikel', $recentContent->where('type', 'post')->count()],
        'draft' => ['Draf', $recentContent->where('status', 'draft')->count()],
    ];
@endphp

@section('page_header')
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div class="flex flex-col gap-1.5">
            <h1 class="text-title-sm font-bold tracking-[-0.5px] text-gray-800">Halo, {{ $firstName }}</h1>
            <p class="flex flex-wrap items-center gap-x-1.5 text-sm">
                @if($unanswered['count'])
                    <a href="{{ route('admin.inquiries.index') }}" class="font-medium text-brand-500 hover:underline">{{ $unanswered['count'] }} pesan menunggu balasan</a>
                @else
                    <span class="text-gray-500">Semua pesan sudah dibalas</span>
                @endif
                @if($lastUpdated)
                    <span class="text-gray-500">· konten terakhir diperbarui {{ $lastUpdated->locale('id')->diffForHumans() }}</span>
                @endif
            </p>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="admin-btn-outline">
            <x-admin.icon name="pen-line" class="h-4 w-4" />
            Tulis artikel
        </a>
    </div>
@endsection

@section('content')
<div class="flex flex-col gap-6">
    {{-- KPI row --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach($metrics as $m)
            @php $sparkMax = max(1, max($m['spark'])); @endphp
            <a href="{{ route($m['route']) }}" class="admin-card group flex flex-col gap-3.5 p-5 transition-colors hover:border-gray-300 focus:outline-none focus-visible:ring-3 focus-visible:ring-brand-500/40">
                <div class="flex items-center justify-between gap-2">
                    <span class="flex items-center gap-2 text-[13px] font-medium text-gray-500">
                        <x-admin.icon :name="$m['icon']" class="h-4 w-4 text-gray-400" />
                        {{ $m['label'] }}
                    </span>
                    <span class="admin-badge {{ $m['delta'] ? 'admin-badge-success' : 'admin-badge-gray' }}" title="Bertambah dalam 30 hari terakhir">
                        <x-admin.icon :name="$m['delta'] ? 'trending-up' : 'minus'" class="h-3 w-3" />
                        {{ $m['delta'] ? '+' . $num($m['delta']) : '0' }}
                        <span class="sr-only">dalam 30 hari terakhir</span>
                    </span>
                </div>
                <div class="flex items-end justify-between gap-3">
                    <span class="font-mono text-[28px] font-bold leading-none tracking-[-0.8px] text-gray-800">{{ $num($m['value']) }}</span>
                    <span class="flex h-8 items-end gap-[3px]" title="Tren {{ count($m['spark']) }} minggu terakhir" aria-hidden="true">
                        @foreach($m['spark'] as $i => $v)
                            <span class="w-[5px] rounded-[1.5px] {{ $loop->last ? 'bg-brand-500' : 'bg-gray-300' }}" style="height: {{ max(3, round($v / $sparkMax * 32)) }}px"></span>
                        @endforeach
                    </span>
                </div>
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-[minmax(0,1fr)_400px]">
        {{-- Weekly publishing chart --}}
        <section class="admin-card flex flex-col" aria-labelledby="chart-title">
            <div class="admin-card-header items-start">
                <div>
                    <h2 id="chart-title" class="admin-card-title">Konten terbit per minggu</h2>
                    <p class="admin-card-desc">{{ $chart->count() }} minggu terakhir · {{ $num($chartTotal) }} terbit</p>
                </div>
                <ul class="flex items-center gap-4 text-[13px] text-gray-500" aria-label="Legenda">
                    @foreach($series as $s)
                        <li class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-sm {{ $s['class'] }}" aria-hidden="true"></span>{{ $s['label'] }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="flex flex-1 flex-col px-5 pb-5" x-data="{ tip: null }">
                <div class="flex min-h-[240px] flex-1 gap-3" aria-hidden="true">
                    <div class="relative w-6 font-mono text-[11px] leading-none text-gray-400">
                        @for($t = 0; $t <= 4; $t++)
                            <span class="absolute right-0 translate-y-1/2" style="bottom: {{ $t * 25 }}%">{{ $chartMax / 4 * $t }}</span>
                        @endfor
                    </div>
                    <div class="relative flex-1">
                        <div class="absolute inset-0 flex flex-col justify-between">
                            @for($t = 0; $t <= 4; $t++)
                                <span class="border-t {{ $t === 4 ? 'border-gray-300' : 'border-dashed border-gray-100' }}"></span>
                            @endfor
                        </div>
                        <div class="absolute inset-0 flex items-end gap-1.5">
                            @foreach($chart as $i => $w)
                                <div class="relative flex h-full flex-1 flex-col justify-end gap-[2px] rounded-t-md px-[3px] transition-colors hover:bg-gray-50/60"
                                     @mouseenter="tip = {{ $i }}" @mouseleave="tip = null">
                                    @foreach(['posts', 'projects'] as $key)
                                        @if($w[$key])
                                            <span class="w-full {{ $series[$key]['class'] }} {{ $key === 'posts' || ! $w['posts'] ? 'rounded-t-[4px]' : '' }}"
                                                  style="height: calc({{ $w[$key] / $chartMax * 100 }}% - 1px)"></span>
                                        @endif
                                    @endforeach
                                    <div x-show="tip === {{ $i }}" x-cloak
                                         class="pointer-events-none absolute bottom-full z-10 mb-2 w-max rounded-lg bg-gray-50 px-3 py-2 text-xs shadow-theme-lg ring-1 ring-gray-200 {{ $i < 2 ? 'left-0' : ($i > $chart->count() - 3 ? 'right-0' : 'left-1/2 -translate-x-1/2') }}">
                                        <p class="mb-1 font-semibold text-gray-800">Minggu {{ $w['week']->locale('id')->translatedFormat('j M') }}</p>
                                        @foreach($series as $key => $s)
                                            <p class="flex items-center gap-2 text-gray-500">
                                                <span class="h-2 w-2 rounded-sm {{ $s['class'] }}"></span>{{ $s['label'] }}
                                                <span class="ml-auto pl-3 font-mono font-semibold text-gray-800">{{ $w[$key] }}</span>
                                            </p>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="ml-9 mt-2.5 flex gap-1.5 font-mono text-[11px] text-gray-400" aria-hidden="true">
                    @foreach($chart as $i => $w)
                        <span class="flex-1 whitespace-nowrap">{{ $i % 3 === 0 ? $w['week']->locale('id')->translatedFormat('j M') : '' }}</span>
                    @endforeach
                </div>

                <table class="sr-only">
                    <caption>Konten terbit per minggu, {{ $chart->count() }} minggu terakhir</caption>
                    <thead><tr><th scope="col">Minggu mulai</th><th scope="col">Studi kasus</th><th scope="col">Artikel</th></tr></thead>
                    <tbody>
                        @foreach($chart as $w)
                            <tr><th scope="row">{{ $w['week']->locale('id')->translatedFormat('j F Y') }}</th><td>{{ $w['projects'] }}</td><td>{{ $w['posts'] }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Unanswered inquiries --}}
        <section class="admin-card flex flex-col" aria-labelledby="inbox-title">
            <div class="admin-card-header">
                <div>
                    <h2 id="inbox-title" class="admin-card-title">Perlu dibalas</h2>
                    <p class="admin-card-desc">
                        @if($unanswered['count'])
                            {{ $unanswered['count'] }} belum dibalas · terlama {{ $unanswered['oldest']->locale('id')->diffForHumans() }}
                        @else
                            Tidak ada pesan baru
                        @endif
                    </p>
                </div>
            </div>
            <ul class="flex-1">
                @forelse($unanswered['items'] as $inq)
                    @php $initials = collect(explode(' ', $inq->name))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode(''); @endphp
                    <li class="flex gap-3 border-t border-gray-100 px-5 py-3.5">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gray-50 text-xs font-semibold text-gray-500" aria-hidden="true">{{ $initials }}</span>
                        <div class="flex min-w-0 flex-1 flex-col gap-1">
                            <div class="flex items-center justify-between gap-2">
                                <p class="truncate text-sm font-semibold text-gray-800">{{ $inq->name }}</p>
                                <time datetime="{{ $inq->created_at->toIso8601String() }}" class="shrink-0 text-xs text-gray-400">{{ $inq->created_at->locale('id')->shortRelativeDiffForHumans() }}</time>
                            </div>
                            <div class="flex min-w-0 items-center gap-2">
                                <span class="truncate text-xs text-gray-400">{{ $inq->company ?: $inq->email }}</span>
                                @if($inq->subject && $inq->subject !== 'Website Inquiry')
                                    <span class="max-w-[50%] shrink-0 truncate rounded-full px-2 py-px text-[11px] font-semibold text-brand-500 ring-1 ring-inset ring-brand-500">{{ $inq->subject }}</span>
                                @endif
                            </div>
                            <p class="line-clamp-2 text-[13px] leading-[19px] text-gray-500">{{ $inq->message }}</p>
                        </div>
                    </li>
                @empty
                    <li class="flex flex-col items-center gap-2 border-t border-gray-100 px-5 py-12 text-center">
                        <x-admin.icon name="inbox" class="h-6 w-6 text-gray-400" />
                        <p class="text-sm text-gray-500">Kotak masuk bersih. Pesan baru dari form kontak muncul di sini.</p>
                    </li>
                @endforelse
            </ul>
            <a href="{{ route('admin.inquiries.index') }}" class="flex min-h-12 items-center justify-center gap-1.5 border-t border-gray-100 text-sm font-semibold text-brand-500 hover:underline focus:outline-none focus-visible:ring-3 focus-visible:ring-inset focus-visible:ring-brand-500/40">
                Buka kotak masuk <x-admin.icon name="arrow-right" class="h-4 w-4" />
            </a>
        </section>
    </div>

    {{-- Recent content --}}
    <section class="admin-card overflow-hidden" aria-labelledby="recent-title" x-data="{ tab: 'all' }">
        <div class="admin-card-header pb-3">
            <div>
                <h2 id="recent-title" class="admin-card-title">Konten terbaru</h2>
                <p class="admin-card-desc">Studi kasus dan artikel yang terakhir diubah</p>
            </div>
        </div>
        <div class="flex gap-5 overflow-x-auto px-5" role="tablist" aria-label="Saring konten">
            @foreach($tabs as $key => [$label, $count])
                <button type="button" role="tab" class="admin-tab gap-1.5" :class="tab === '{{ $key }}' && 'admin-tab-active'"
                        :aria-selected="(tab === '{{ $key }}').toString()" @click="tab = '{{ $key }}'">
                    {{ $label }} <span class="font-mono text-[11px] font-normal text-gray-400">{{ $count }}</span>
                </button>
            @endforeach
        </div>

        @if($recentContent->isEmpty())
            <div class="border-t border-gray-100 px-5 py-12 text-center text-sm text-gray-500">
                Belum ada konten. <a href="{{ route('admin.projects.create') }}" class="admin-link">Tambah studi kasus pertama</a>.
            </div>
        @else
            <div class="overflow-x-auto border-t border-gray-100">
                <table class="w-full min-w-[720px] text-left">
                    <thead>
                        <tr class="border-b border-gray-100 text-[11px] font-semibold uppercase tracking-[0.1em] text-gray-400">
                            <th scope="col" class="px-5 py-3 font-semibold">Judul</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Tipe</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Status</th>
                            <th scope="col" class="px-4 py-3 text-right font-semibold">Dilihat</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Diperbarui</th>
                            <th scope="col" class="px-5 py-3"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentContent as $item)
                            @php [$statusLabel, $statusClass] = $statusBadge[$item['status']] ?? $statusBadge['draft']; @endphp
                            <tr x-show="tab === 'all' || tab === '{{ $item['type'] }}' || (tab === 'draft' && {{ $item['status'] === 'draft' ? 'true' : 'false' }})"
                                class="border-b border-gray-100 transition-colors last:border-b-0 hover:bg-void/40">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-200 text-gray-500" aria-hidden="true">
                                            <x-admin.icon :name="$item['type'] === 'project' ? 'briefcase' : 'pen-line'" class="h-4 w-4" />
                                        </span>
                                        <a href="{{ $item['edit_url'] }}" class="line-clamp-1 text-sm font-medium text-gray-800 hover:text-brand-500">{{ $item['title'] }}</a>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500">{{ $typeLabel[$item['type']] }}</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <span class="admin-badge admin-badge-dot {{ $statusClass }}">{{ $statusLabel }}</span>
                                    @if($item['featured'])
                                        <span class="admin-badge admin-badge-brand ml-1">Unggulan</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-right font-mono text-sm text-gray-700">{{ $item['views'] === null ? '—' : $num($item['views']) }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500">
                                    <time datetime="{{ $item['updated_at']->toIso8601String() }}">
                                        {{ $item['updated_at']->gt(now()->subDay()) ? $item['updated_at']->locale('id')->diffForHumans() : $item['updated_at']->locale('id')->translatedFormat('j M Y') }}
                                    </time>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ $item['edit_url'] }}" class="admin-icon-btn h-8 w-8 border-0" aria-label="Edit {{ $item['title'] }}" title="Edit">
                                        <x-admin.icon name="square-pen" class="h-4 w-4" />
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>
@endsection
