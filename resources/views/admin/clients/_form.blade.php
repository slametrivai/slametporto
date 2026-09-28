@php $client ??= null; @endphp

<form action="{{ $client ? route('admin.clients.update', $client) : route('admin.clients.store') }}" method="POST" enctype="multipart/form-data" class="admin-card mx-auto max-w-3xl">
    @csrf
    @if($client) @method('PUT') @endif

    <div class="admin-card-header">
        <div>
            <h3 class="admin-card-title">Detail klien</h3>
            <p class="admin-card-desc">Klien berstatus tampil muncul di bagian klien halaman utama.</p>
        </div>
    </div>

    <div class="admin-card-body">
        <div>
            <label for="name" class="admin-label">Nama perusahaan <span class="text-error-600">*</span></label>
            <input id="name" type="text" name="name" value="{{ old('name', $client?->name) }}" required class="admin-input"
                   placeholder="Contoh: PT Global Tiket Network">
        </div>

        <div>
            <div class="mb-1.5 flex flex-wrap items-center justify-between gap-2">
                <label for="industry" class="text-sm font-medium text-gray-700">Industri <span class="text-error-600">*</span></label>
                <a href="{{ route('admin.categories.create', ['type' => 'client']) }}" target="_blank" rel="noopener" class="admin-link text-theme-sm">Tambah kategori industri<span class="sr-only"> (tab baru)</span></a>
            </div>
            <select id="industry" name="industry" required class="admin-select">
                @forelse($categories as $cat)
                    <option value="{{ $cat }}" @selected(old('industry', $client?->industry) == $cat)>{{ $cat }}</option>
                @empty
                    <option value="{{ $client?->industry ?? 'General Ecosystem' }}">{{ $client?->industry ?? 'General Ecosystem' }}</option>
                @endforelse
            </select>
        </div>

        <div>
            <label for="logo" class="admin-label">
                Logo
                @unless($client)<span class="text-error-600">*</span>@endunless
            </label>
            @if($client?->logo)
                <div class="mb-3 flex h-14 w-28 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 p-1">
                    <img src="{{ $client->logo_url }}" alt="Logo saat ini" class="max-h-full max-w-full object-contain">
                </div>
            @endif
            <input id="logo" type="file" name="logo" accept=".svg,.png,.jpg,.jpeg,.webp" @required(!$client) data-shrink class="admin-file" aria-describedby="logo-help">
            <p id="logo-help" class="admin-help">SVG, PNG, JPG, atau WEBP. Maks. 2 MB; gambar yang lebih besar otomatis diperkecil.{{ $client ? ' Kosongkan jika tidak mengganti logo.' : '' }}</p>
        </div>

        <div>
            <label for="website_url" class="admin-label">Website</label>
            <input id="website_url" type="url" name="website_url" value="{{ old('website_url', $client?->website_url) }}" class="admin-input"
                   placeholder="https://">
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <label for="sort_order" class="admin-label">Urutan tampil</label>
                <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', $client?->sort_order ?? 0) }}" class="admin-input">
            </div>
            <label class="flex min-h-11 cursor-pointer items-center gap-3 self-end text-sm font-medium text-gray-700">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $client?->is_active ?? true)) class="admin-checkbox">
                Tampilkan di halaman publik
            </label>
        </div>
    </div>

    <div class="flex flex-wrap justify-end gap-3 border-t border-gray-100 p-5 sm:p-6">
        <a href="{{ route('admin.clients.index') }}" class="admin-btn-outline">Batal</a>
        <button type="submit" class="admin-btn-primary">{{ $client ? 'Simpan perubahan' : 'Simpan klien' }}</button>
    </div>
</form>
