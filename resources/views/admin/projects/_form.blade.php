@php $project ??= null; @endphp

<form action="{{ $project ? route('admin.projects.update', $project) : route('admin.projects.store') }}" method="POST" enctype="multipart/form-data"
      class="mx-auto max-w-4xl space-y-6" x-data="projectForm()">
    @csrf
    @if($project) @method('PUT') @endif

    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <h3 class="admin-card-title">Ringkasan proyek</h3>
                <p class="admin-card-desc">Masalah dan solusi tampil sebagai dua bagian utama di halaman studi kasus.</p>
            </div>
        </div>
        <div class="admin-card-body">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="md:col-span-2">
                    <label for="title" class="admin-label">Judul <span class="text-error-600">*</span></label>
                    <input id="title" type="text" name="title" value="{{ old('title', $project?->title) }}" required class="admin-input"
                           placeholder="Contoh: Document OCR & ERP Pipeline">
                </div>
                <div>
                    <div class="mb-1.5 flex flex-wrap items-center justify-between gap-2">
                        <label for="category" class="text-sm font-medium text-gray-700">Kategori <span class="text-error-600">*</span></label>
                        <a href="{{ route('admin.categories.create', ['type' => 'project']) }}" target="_blank" rel="noopener" class="admin-link text-theme-sm">Tambah<span class="sr-only"> kategori (tab baru)</span></a>
                    </div>
                    <select id="category" name="category" required class="admin-select">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" @selected(old('category', $project?->category) == $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="role" class="admin-label">Peran <span class="text-error-600">*</span></label>
                <input id="role" type="text" name="role" value="{{ old('role', $project?->role) }}" required class="admin-input"
                       placeholder="Contoh: Lead Systems Architect">
            </div>

            <div>
                <label for="challenge" class="admin-label">Masalah <span class="text-error-600">*</span></label>
                <textarea id="challenge" name="challenge" rows="4" required class="admin-textarea"
                          placeholder="Kendala operasional, bottleneck, atau tingkat kesalahan sebelum proyek.">{{ old('challenge', $project?->challenge) }}</textarea>
            </div>

            <div>
                <label for="solution" class="admin-label">Solusi <span class="text-error-600">*</span></label>
                <textarea id="solution" name="solution" rows="4" required class="admin-textarea"
                          placeholder="Integrasi sistem, modul yang dibangun, dan alur otomasi data.">{{ old('solution', $project?->solution) }}</textarea>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <h3 class="admin-card-title">Metrik dampak</h3>
                <p class="admin-card-desc">Isi hanya angka yang benar-benar terukur. Contoh format: "2 hari → 30 menit".</p>
            </div>
            <button type="button" @click="impactList.push({ metric: '', label: '' })" class="admin-btn-outline">Tambah metrik</button>
        </div>
        <div class="admin-card-body">
            <template x-for="(item, index) in impactList" :key="index">
                <div class="flex items-start gap-3">
                    <div class="grid flex-1 grid-cols-1 gap-3 sm:grid-cols-2">
                        <div>
                            <label :for="'metric-' + index" class="sr-only" x-text="'Nilai metrik ' + (index + 1)"></label>
                            <input :id="'metric-' + index" type="text" name="impact_metrics[]" x-model="item.metric" required class="admin-input" placeholder="Nilai, contoh: 2 hari → 30 menit">
                        </div>
                        <div>
                            <label :for="'label-' + index" class="sr-only" x-text="'Label metrik ' + (index + 1)"></label>
                            <input :id="'label-' + index" type="text" name="impact_labels[]" x-model="item.label" required class="admin-input" placeholder="Label, contoh: Waktu proses dokumen">
                        </div>
                    </div>
                    <button type="button" @click="impactList.length > 1 && impactList.splice(index, 1)" :disabled="impactList.length === 1"
                            class="admin-icon-btn admin-icon-btn-danger disabled:cursor-not-allowed disabled:opacity-50" :aria-label="'Hapus metrik ' + (index + 1)">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </template>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <h3 class="admin-card-title">Alur pipeline</h3>
                <p class="admin-card-desc">Langkah alur data dari kiri ke kanan, contoh: Dokumen pelanggan, OCR, Data terstruktur, Sistem operasional.</p>
            </div>
            <button type="button" @click="stepsList.push({ name: '' })" class="admin-btn-outline">Tambah langkah</button>
        </div>
        <div class="admin-card-body">
            <template x-for="(step, index) in stepsList" :key="index">
                <div class="flex items-center gap-3">
                    <label :for="'step-' + index" class="w-16 shrink-0 text-sm font-medium text-gray-700" x-text="'Langkah ' + (index + 1)"></label>
                    <input :id="'step-' + index" type="text" name="workflow_steps[]" x-model="step.name" required class="admin-input" placeholder="Nama langkah">
                    <button type="button" @click="stepsList.length > 1 && stepsList.splice(index, 1)" :disabled="stepsList.length === 1"
                            class="admin-icon-btn admin-icon-btn-danger disabled:cursor-not-allowed disabled:opacity-50" :aria-label="'Hapus langkah ' + (index + 1)">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </template>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Tampilan</h3>
        </div>
        <div class="admin-card-body">
            <div>
                <label for="cover_image" class="admin-label">Gambar cover</label>
                @if($project?->cover_image)
                    <img src="{{ $project->cover_image_url }}" alt="Cover saat ini" class="mb-3 h-24 w-40 rounded-lg border border-gray-200 object-cover">
                @endif
                <input id="cover_image" type="file" name="cover_image" accept="image/*,.svg" data-shrink class="admin-file" aria-describedby="cover-help">
                <p id="cover-help" class="admin-help">Opsional. Maks. 2 MB; foto yang lebih besar otomatis diperkecil sebelum diunggah.{{ $project ? ' Kosongkan jika tidak mengganti cover.' : '' }}</p>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="sort_order" class="admin-label">Urutan tampil</label>
                    <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', $project?->sort_order ?? 0) }}" class="admin-input">
                </div>
                <label class="flex min-h-11 cursor-pointer items-center gap-3 self-end text-sm font-medium text-gray-700">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $project?->is_featured)) class="admin-checkbox">
                    Jadikan studi kasus unggulan
                </label>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap justify-end gap-3">
        <a href="{{ route('admin.projects.index') }}" class="admin-btn-outline">Batal</a>
        <button type="submit" class="admin-btn-primary">{{ $project ? 'Simpan perubahan' : 'Simpan studi kasus' }}</button>
    </div>
</form>

@push('scripts')
<script>
function projectForm() {
    const highlights = @js($project?->impact_highlights ?? []);
    const steps = @js($project?->workflow_steps ?? []);

    return {
        impactList: highlights.length ? highlights : [{ metric: '', label: '' }],
        stepsList: steps.length ? steps.map(name => ({ name })) : [{ name: '' }],
    };
}
</script>
@endpush
