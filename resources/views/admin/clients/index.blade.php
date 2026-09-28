@extends('admin.layouts.app')

@section('title', 'Klien')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h3 class="admin-card-title">Daftar klien dan mitra</h3>
            <p class="admin-card-desc">Logo yang berstatus tampil muncul di halaman utama.</p>
        </div>
        <a href="{{ route('admin.clients.create') }}" class="admin-btn-primary">Tambah klien</a>
    </div>
    <div class="border-t border-gray-100 p-5 sm:p-6">
        <table id="clients-table" class="w-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Logo</th>
                    <th>Nama klien</th>
                    <th>Industri</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@push('scripts')
<script>
$(function () {
    $('#clients-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.clients.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'logo', name: 'logo', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'industry', name: 'industry' },
            { data: 'sort_order', name: 'sort_order' },
            { data: 'is_active', name: 'is_active' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
@endsection
