@extends('admin.layouts.app')

@section('title', 'Sertifikasi')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h3 class="admin-card-title">Sertifikasi dan lisensi</h3>
            <p class="admin-card-desc">Lembaga penerbit, skor, dan tautan verifikasi yang tampil di halaman resume.</p>
        </div>
        <a href="{{ route('admin.certifications.create') }}" class="admin-btn-primary">Tambah sertifikasi</a>
    </div>
    <div class="border-t border-gray-100 p-5 sm:p-6">
        <table id="certifications-table" class="w-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama sertifikasi</th>
                    <th>Penerbit</th>
                    <th>Skor</th>
                    <th>Verifikasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@push('scripts')
<script>
$(function () {
    $('#certifications-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.certifications.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'issuer', name: 'issuer' },
            { data: 'score', name: 'score' },
            { data: 'credential_url', name: 'credential_url' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
@endsection
