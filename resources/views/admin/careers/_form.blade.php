@php $career ??= null; @endphp

<form action="{{ $career ? route('admin.careers.update', $career) : route('admin.careers.store') }}" method="POST" class="admin-card mx-auto max-w-3xl">
    @csrf
    @if($career) @method('PUT') @endif

    <div class="admin-card-header">
        <div>
            <h3 class="admin-card-title">Detail riwayat karier</h3>
            <p class="admin-card-desc">Urutan terkecil tampil paling atas di linimasa.</p>
        </div>
    </div>

    <div class="admin-card-body">
        <div>
            <label for="company" class="admin-label">Instansi atau perusahaan <span class="text-error-600">*</span></label>
            <input id="company" type="text" name="company" value="{{ old('company', $career?->company) }}" required class="admin-input"
                   placeholder="Contoh: PT Global Tiket Network">
        </div>

        <div>
            <label for="role" class="admin-label">Jabatan <span class="text-error-600">*</span></label>
            <input id="role" type="text" name="role" value="{{ old('role', $career?->role) }}" required class="admin-input"
                   placeholder="Contoh: Customer Operations & Systems Lead">
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <label for="period" class="admin-label">Periode <span class="text-error-600">*</span></label>
                <input id="period" type="text" name="period" value="{{ old('period', $career?->period) }}" required class="admin-input"
                       placeholder="Contoh: 2021 - 2024">
            </div>
            <div>
                <label for="sort_order" class="admin-label">Urutan tampil</label>
                <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', $career?->sort_order ?? 0) }}" class="admin-input">
            </div>
        </div>

        <div>
            <label for="description" class="admin-label">Tanggung jawab dan pencapaian</label>
            <textarea id="description" name="description" rows="5" class="admin-textarea"
                      placeholder="Peran operasional, sistem yang dibangun, atau tim yang dipimpin.">{{ old('description', $career?->description) }}</textarea>
        </div>
    </div>

    <div class="flex flex-wrap justify-end gap-3 border-t border-gray-100 p-5 sm:p-6">
        <a href="{{ route('admin.careers.index') }}" class="admin-btn-outline">Batal</a>
        <button type="submit" class="admin-btn-primary">{{ $career ? 'Simpan perubahan' : 'Simpan riwayat' }}</button>
    </div>
</form>
