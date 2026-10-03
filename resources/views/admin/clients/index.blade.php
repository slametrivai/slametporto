@extends('admin.layouts.app')

@section('title', 'Klien')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h3 class="admin-card-title">Daftar klien dan mitra</h3>
            <p class="admin-card-desc">Logo yang berstatus tampil muncul di halaman utama.</p>
        </div>
        <button type="button" data-client-new class="admin-btn-primary" aria-haspopup="dialog">Tambah klien</button>
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

<dialog id="client-dialog" aria-labelledby="client-dialog-title" class="w-[calc(100%-2rem)] max-w-[600px] rounded-3xl bg-white p-0 backdrop:bg-gray-900/50">
    <form id="client-form" method="POST" action="{{ route('admin.clients.store') }}" enctype="multipart/form-data" class="max-h-[90vh] overflow-y-auto p-6 lg:p-10">
        @csrf
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" name="_client_id" value="">
        <input type="hidden" name="_logo_url" value="">

        <div class="flex items-start justify-between gap-4">
            <h3 id="client-dialog-title" class="text-xl font-semibold text-gray-800">Tambah klien</h3>
            <button type="button" onclick="this.closest('dialog').close()" class="admin-icon-btn" aria-label="Tutup">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="mt-6 space-y-5">
            <div>
                <label for="client-name" class="admin-label">Nama perusahaan <span class="text-error-600">*</span></label>
                <input id="client-name" type="text" name="name" required class="admin-input" placeholder="Contoh: PT Global Tiket Network">
                @error('name')<p class="admin-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <div class="mb-1.5 flex flex-wrap items-center justify-between gap-2">
                    <label for="client-industry" class="text-sm font-medium text-gray-700">Industri <span class="text-error-600">*</span></label>
                    <a href="{{ route('admin.categories.create', ['type' => 'client']) }}" target="_blank" rel="noopener" class="admin-link text-theme-sm">Tambah kategori industri<span class="sr-only"> (tab baru)</span></a>
                </div>
                <select id="client-industry" name="industry" required class="admin-select">
                    @foreach($industries as $industry)
                        <option value="{{ $industry }}">{{ $industry }}</option>
                    @endforeach
                </select>
                @error('industry')<p class="admin-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="client-logo" class="admin-label">Logo <span id="client-logo-required" class="text-error-600">*</span></label>
                <div id="client-logo-current" class="mb-3 hidden h-14 w-28 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 p-1">
                    <img src="" alt="Logo saat ini" class="max-h-full max-w-full object-contain">
                </div>
                <input id="client-logo" type="file" name="logo" accept=".svg,.png,.jpg,.jpeg,.webp" data-shrink class="admin-file" aria-describedby="client-logo-help">
                <p id="client-logo-help" class="admin-help">SVG, PNG, JPG, atau WEBP. Maks. 2 MB; gambar yang lebih besar otomatis diperkecil.</p>
                @error('logo')<p class="admin-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="client-website" class="admin-label">Website</label>
                <input id="client-website" type="url" name="website_url" class="admin-input" placeholder="https://">
                @error('website_url')<p class="admin-error">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="client-sort" class="admin-label">Urutan tampil</label>
                    <input id="client-sort" type="number" name="sort_order" class="admin-input">
                    @error('sort_order')<p class="admin-error">{{ $message }}</p>@enderror
                </div>
                <label class="flex min-h-11 cursor-pointer items-center gap-3 self-end text-sm font-medium text-gray-700">
                    <input type="checkbox" name="is_active" value="1" class="admin-checkbox">
                    Tampilkan di halaman publik
                </label>
            </div>
        </div>

        <div class="mt-8 flex flex-wrap justify-end gap-3">
            <button type="button" onclick="this.closest('dialog').close()" class="admin-btn-outline">Batal</button>
            <button type="submit" id="client-submit" class="admin-btn-primary">Simpan klien</button>
        </div>
    </form>
</dialog>

@push('scripts')
<script>
const clientDialog = document.getElementById('client-dialog');
const clientForm = document.getElementById('client-form');
const clientStoreUrl = @js(route('admin.clients.store'));
const clientUpdateUrl = @js(route('admin.clients.update', '__ID__'));

function openClientModal(client = {}, keepErrors = false) {
    const editing = Boolean(client.id);
    const f = clientForm.elements;
    clientForm.action = editing ? clientUpdateUrl.replace('__ID__', client.id) : clientStoreUrl;
    f['_method'].value = editing ? 'PUT' : 'POST';
    f['_client_id'].value = client.id ?? '';
    f['_logo_url'].value = client.logo_url ?? '';
    f.name.value = client.name ?? '';
    f.website_url.value = client.website_url ?? '';
    f.sort_order.value = client.sort_order ?? 0;
    f.is_active.checked = client.is_active ?? true;
    if (client.industry && ![...f.industry.options].some((o) => o.value === client.industry)) {
        f.industry.add(new Option(client.industry, client.industry), 0);
    }
    f.industry.value = client.industry ?? f.industry.options[0]?.value;

    // A file input can never be refilled, so after a failed create the logo must be picked again.
    f.logo.value = '';
    f.logo.required = !editing;
    document.getElementById('client-logo-required').hidden = editing;
    const current = document.getElementById('client-logo-current');
    current.classList.toggle('hidden', !client.logo_url);
    current.classList.toggle('flex', Boolean(client.logo_url));
    current.querySelector('img').src = client.logo_url ?? '';
    clientDialog.querySelectorAll('[data-upload-note]').forEach((el) => el.remove());
    if (!keepErrors) clientDialog.querySelectorAll('.admin-error').forEach((el) => el.remove());

    document.getElementById('client-dialog-title').textContent = editing ? 'Edit klien' : 'Tambah klien';
    document.getElementById('client-submit').textContent = editing ? 'Simpan perubahan' : 'Simpan klien';
    clientDialog.showModal();
}

document.querySelector('[data-client-new]').addEventListener('click', () => openClientModal());
clientDialog.addEventListener('click', (e) => { if (e.target === clientDialog) clientDialog.close(); });
// Edit buttons are rendered by DataTables, so listen on the table.
document.getElementById('clients-table').addEventListener('click', (e) => {
    const btn = e.target.closest('[data-edit-modal]');
    if (btn) openClientModal(JSON.parse(btn.dataset.editModal));
});

@if($errors->any())
    openClientModal(@js([
        'id' => old('_client_id'),
        'logo_url' => old('_logo_url'),
        'name' => old('name'),
        'industry' => old('industry'),
        'website_url' => old('website_url'),
        'sort_order' => old('sort_order'),
        'is_active' => (bool) old('is_active'),
    ]), true);
@endif

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
