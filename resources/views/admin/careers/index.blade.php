@extends('admin.layouts.app')

@section('title', 'Riwayat Karier')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h3 class="admin-card-title">Linimasa karier</h3>
            <p class="admin-card-desc">Instansi, jabatan, dan periode yang tampil di halaman resume.</p>
        </div>
        <a href="{{ route('admin.careers.create') }}" class="admin-btn-primary">Tambah riwayat</a>
    </div>
    <div class="border-t border-gray-100 p-5 sm:p-6">
        <table id="careers-table" class="w-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Instansi</th>
                    <th>Jabatan</th>
                    <th>Periode</th>
                    <th>Urutan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@push('scripts')
<script>
$(function () {
    $('#careers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.careers.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'company', name: 'company' },
            { data: 'role', name: 'role' },
            { data: 'period', name: 'period' },
            { data: 'sort_order', name: 'sort_order' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
@endsection
