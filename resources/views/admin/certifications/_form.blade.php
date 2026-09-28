@php $certification ??= null; @endphp

<form action="{{ $certification ? route('admin.certifications.update', $certification) : route('admin.certifications.store') }}" method="POST" class="admin-card mx-auto max-w-3xl">
    @csrf
    @if($certification) @method('PUT') @endif

    <div class="admin-card-header">
        <div>
            <h3 class="admin-card-title">Detail sertifikasi</h3>
            <p class="admin-card-desc">Isi tautan verifikasi agar pengunjung bisa mengecek kredensial langsung ke penerbit.</p>
        </div>
    </div>

    <div class="admin-card-body">
        <div>
            <label for="title" class="admin-label">Nama sertifikasi <span class="text-error-600">*</span></label>
            <input id="title" type="text" name="title" value="{{ old('title', $certification?->title) }}" required class="admin-input"
                   placeholder="Contoh: BNSP Data Management Specialist">
        </div>

        <div>
            <label for="issuer" class="admin-label">Lembaga penerbit <span class="text-error-600">*</span></label>
            <input id="issuer" type="text" name="issuer" value="{{ old('issuer', $certification?->issuer) }}" required class="admin-input"
                   placeholder="Contoh: Badan Nasional Sertifikasi Profesi (BNSP)">
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <label for="score" class="admin-label">Skor atau tingkat</label>
                <input id="score" type="text" name="score" value="{{ old('score', $certification?->score) }}" class="admin-input"
                       placeholder="Contoh: 90 / 100">
            </div>
            <div>
                <label for="valid_period" class="admin-label">Masa berlaku</label>
                <input id="valid_period" type="text" name="valid_period" value="{{ old('valid_period', $certification?->valid_period) }}" class="admin-input"
                       placeholder="Contoh: 2025 - 2028">
            </div>
        </div>

        <div>
            <label for="credential_url" class="admin-label">URL verifikasi</label>
            <input id="credential_url" type="url" name="credential_url" value="{{ old('credential_url', $certification?->credential_url) }}" class="admin-input"
                   placeholder="https://">
        </div>
    </div>

    <div class="flex flex-wrap justify-end gap-3 border-t border-gray-100 p-5 sm:p-6">
        <a href="{{ route('admin.certifications.index') }}" class="admin-btn-outline">Batal</a>
        <button type="submit" class="admin-btn-primary">{{ $certification ? 'Simpan perubahan' : 'Simpan sertifikasi' }}</button>
    </div>
</form>
