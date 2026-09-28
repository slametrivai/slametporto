@extends('admin.layouts.app')

@section('title', 'Kategori')

@php
    $tabs = [
        'all' => ['Semua', $postCount + $projectCount + $clientCount],
        'post' => ['Artikel Blog', $postCount],
        'project' => ['Studi Kasus', $projectCount],
        'client' => ['Klien', $clientCount],
    ];
@endphp

@section('content')
<div class="admin-card" x-data="{ type: @js($selectedType) }"
     x-init="$watch('type', t => $('#categories-table').DataTable().ajax.url(@js(route('admin.categories.index')) + '?type=' + t).load())">
    <div class="admin-card-header">
        <div>
            <h3 class="admin-card-title">Kategori konten</h3>
            <p class="admin-card-desc">Kategori untuk artikel, studi kasus, dan industri klien, beserta metadata SEO halamannya.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="admin-btn-primary">Tambah kategori</a>
    </div>
    <div class="border-t border-gray-100 p-5 sm:p-6">
        <div class="mb-5 inline-flex max-w-full flex-wrap gap-0.5 rounded-lg bg-gray-100 p-0.5" role="group" aria-label="Filter tipe kategori">
            @foreach($tabs as $value => [$label, $count])
                <button type="button" @click="type = '{{ $value }}'" :aria-pressed="(type === '{{ $value }}').toString()"
                        :class="type === '{{ $value }}' ? 'bg-white text-gray-800 shadow-theme-xs' : 'text-gray-500 hover:text-gray-800'"
                        class="inline-flex min-h-11 items-center gap-2 rounded-md px-3 text-theme-sm font-medium focus:outline-none focus-visible:ring-3 focus-visible:ring-brand-500/40">
                    {{ $label }}
                    <span class="admin-badge admin-badge-gray">{{ $count }}</span>
                </button>
            @endforeach
        </div>

        <table id="categories-table" class="w-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama dan slug</th>
                    <th>Tipe</th>
                    <th>Deskripsi</th>
                    <th>Item</th>
                    <th>SEO</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@push('scripts')
<script>
$(function () {
    $('#categories-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: @js(route('admin.categories.index', ['type' => $selectedType])),
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'type', name: 'type' },
            { data: 'description', name: 'description' },
            { data: 'items_count', name: 'items_count', orderable: false, searchable: false },
            { data: 'seo_status', name: 'seo_status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']]
    });
});
</script>
@endpush
@endsection
