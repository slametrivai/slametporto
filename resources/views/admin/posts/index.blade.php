@extends('admin.layouts.app')

@section('title', 'Artikel Blog')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h3 class="admin-card-title">Semua artikel</h3>
            <p class="admin-card-desc">Artikel berstatus terbit tampil di /blog; draf hanya terlihat di sini.</p>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="admin-btn-primary">Tulis artikel</a>
    </div>
    <div class="border-t border-gray-100 p-5 sm:p-6">
        <table id="posts-table" class="w-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cover</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Tanggal terbit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@push('scripts')
<script>
$(function () {
    $('#posts-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.posts.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'cover_image', name: 'cover_image', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'category', name: 'category' },
            { data: 'status', name: 'status' },
            { data: 'published_at', name: 'published_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
@endsection
