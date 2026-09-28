@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('actions')
    <a href="{{ route('admin.posts.create') }}" class="admin-btn-outline">Tulis artikel</a>
    <a href="{{ route('admin.projects.create') }}" class="admin-btn-primary">Tambah studi kasus</a>
@endsection

@php
    $metrics = [
        ['label' => 'Studi kasus', 'value' => $stats['active_projects'], 'route' => 'admin.projects.index', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ['label' => 'Klien tampil', 'value' => $stats['total_clients'], 'route' => 'admin.clients.index', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
        ['label' => 'Artikel terbit', 'value' => $stats['published_posts'], 'route' => 'admin.posts.index', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
        ['label' => 'Pesan baru', 'value' => $stats['new_inquiries'], 'route' => 'admin.inquiries.index', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
    ];
@endphp

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4">
        @foreach($metrics as $m)
            <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100">
                    <svg class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $m['icon'] }}"/></svg>
                </div>
                <div class="mt-5 flex items-end justify-between gap-3">
                    <div>
                        <span class="text-sm text-gray-500">{{ $m['label'] }}</span>
                        <p class="mt-2 text-title-sm font-bold text-gray-800">{{ $m['value'] }}</p>
                    </div>
                    <a href="{{ route($m['route']) }}" class="admin-link inline-flex min-h-11 items-center text-theme-sm">Kelola<span class="sr-only"> {{ strtolower($m['label']) }}</span></a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Pesan terbaru</h3>
                <a href="{{ route('admin.inquiries.index') }}" class="admin-link inline-flex min-h-11 items-center text-theme-sm">Semua pesan</a>
            </div>
            <ul class="divide-y divide-gray-100 border-t border-gray-100">
                @forelse($recentInquiries as $inq)
                    <li class="flex items-center justify-between gap-4 px-5 py-4 sm:px-6">
                        <div class="min-w-0">
                            <p class="flex items-center gap-2 text-theme-sm font-medium text-gray-800">
                                <span class="truncate">{{ $inq->name }}</span>
                                @if($inq->status === 'new')
                                    <span class="admin-badge admin-badge-error">Baru</span>
                                @endif
                            </p>
                            <p class="mt-0.5 truncate text-theme-sm text-gray-500">{{ $inq->subject }}</p>
                            <p class="mt-0.5 text-theme-xs text-gray-500">{{ $inq->created_at->diffForHumans() }}</p>
                        </div>
                        <a href="mailto:{{ $inq->email }}?subject={{ rawurlencode('Re: ' . $inq->subject) }}" class="admin-btn-outline shrink-0">Balas<span class="sr-only"> email ke {{ $inq->name }}</span></a>
                    </li>
                @empty
                    <li class="px-5 py-10 text-center text-theme-sm text-gray-500 sm:px-6">Belum ada pesan masuk dari form kontak.</li>
                @endforelse
            </ul>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Studi kasus terbaru</h3>
                <a href="{{ route('admin.projects.index') }}" class="admin-link inline-flex min-h-11 items-center text-theme-sm">Semua studi kasus</a>
            </div>
            <ul class="divide-y divide-gray-100 border-t border-gray-100">
                @forelse($recentProjects as $proj)
                    <li class="flex items-center justify-between gap-4 px-5 py-4 sm:px-6">
                        <div class="flex min-w-0 items-center gap-3">
                            @if($proj->cover_image)
                                <img src="{{ $proj->cover_image_url }}" alt="" class="h-11 w-16 shrink-0 rounded-lg border border-gray-200 object-cover">
                            @endif
                            <div class="min-w-0">
                                <p class="truncate text-theme-sm font-medium text-gray-800">{{ $proj->title }}</p>
                                <p class="mt-0.5 truncate text-theme-xs text-gray-500">{{ $proj->category }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.projects.edit', $proj) }}" class="admin-btn-outline shrink-0">Edit<span class="sr-only"> {{ $proj->title }}</span></a>
                    </li>
                @empty
                    <li class="px-5 py-10 text-center text-theme-sm text-gray-500 sm:px-6">
                        Belum ada studi kasus. <a href="{{ route('admin.projects.create') }}" class="admin-link">Tambah yang pertama</a>.
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
