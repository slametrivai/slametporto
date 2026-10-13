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
                <label for="summary" class="admin-label">Ringkasan</label>
                <textarea id="summary" name="summary" rows="2" maxlength="500" class="admin-textarea" aria-describedby="summary-help"
                          placeholder="Satu atau dua kalimat: kondisi awal dan apa yang sekarang berjalan.">{{ old('summary', $project?->summary) }}</textarea>
                <p id="summary-help" class="admin-help">Tampil di bawah judul halaman studi kasus dan di kartu proyek. Jika kosong, kolom Masalah yang dipakai.</p>
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

            <div>
                <label for="result" class="admin-label">Hasil</label>
                <textarea id="result" name="result" rows="3" class="admin-textarea"
                          placeholder="Dampak yang terukur setelah sistem berjalan.">{{ old('result', $project?->result) }}</textarea>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label for="volume" class="admin-label">Volume</label>
                    <input id="volume" type="text" name="volume" value="{{ old('volume', $project?->volume) }}" maxlength="150" class="admin-input"
                           placeholder="Contoh: 5,000+ dokumen / bulan">
                </div>
                <div>
                    <label for="components" class="admin-label">Komponen utama</label>
                    <input id="components" type="text" name="components" value="{{ old('components', implode(', ', $project?->components ?? [])) }}" maxlength="500" class="admin-input"
                           placeholder="Contoh: Computer-vision OCR, REST webhooks" aria-describedby="components-help">
                    <p id="components-help" class="admin-help">Pisahkan dengan koma.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <h3 class="admin-card-title">Metrik dampak</h3>
                <p class="admin-card-desc">Isi hanya angka yang benar-benar terukur. Contoh format: "2 hari → 30 menit".</p>
            </div>
            <button type="button" @click="impactList.push({ metric: '', label: '', detail: '' })" class="admin-btn-outline">Tambah metrik</button>
        </div>
        <div class="admin-card-body">
            <template x-for="(item, index) in impactList" :key="index">
                <div class="flex items-start gap-3">
                    <div class="grid flex-1 grid-cols-1 gap-3 sm:grid-cols-3">
                        <div>
                            <label :for="'metric-' + index" class="sr-only" x-text="'Nilai metrik ' + (index + 1)"></label>
                            <input :id="'metric-' + index" type="text" name="impact_metrics[]" x-model="item.metric" required class="admin-input" placeholder="Nilai, contoh: 2 hari → 30 menit">
                        </div>
                        <div>
                            <label :for="'label-' + index" class="sr-only" x-text="'Label metrik ' + (index + 1)"></label>
                            <input :id="'label-' + index" type="text" name="impact_labels[]" x-model="item.label" required class="admin-input" placeholder="Label, contoh: Waktu proses dokumen">
                        </div>
                        <div>
                            <label :for="'detail-' + index" class="sr-only" x-text="'Keterangan metrik ' + (index + 1)"></label>
                            <input :id="'detail-' + index" type="text" name="impact_details[]" x-model="item.detail" class="admin-input" placeholder="Keterangan (opsional), contoh: dari 48 jam">
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
            <button type="button" @click="stepsList.push({ name: '', description: '' })" class="admin-btn-outline">Tambah langkah</button>
        </div>
        <div class="admin-card-body">
            <template x-for="(step, index) in stepsList" :key="index">
                <div class="flex items-start gap-3">
                    <label :for="'step-' + index" class="flex min-h-11 w-16 shrink-0 items-center text-sm font-medium text-gray-700" x-text="'Langkah ' + (index + 1)"></label>
                    <div class="grid flex-1 grid-cols-1 gap-3 sm:grid-cols-[1fr_2fr]">
                        <input :id="'step-' + index" type="text" name="workflow_steps[]" x-model="step.name" required class="admin-input" placeholder="Nama langkah">
                        <input type="text" name="step_descriptions[]" x-model="step.description" class="admin-input" placeholder="Penjelasan singkat (opsional)" :aria-label="'Penjelasan langkah ' + (index + 1)">
                    </div>
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

            <div data-upload-field x-data="{ previews: [] }"
                 @upload-ready="previews.forEach(URL.revokeObjectURL); previews = [...$event.target.files].map(f => URL.createObjectURL(f))">
                <label for="gallery_images" class="admin-label">Galeri gambar</label>

                @if($project?->images->isNotEmpty())
                    <fieldset class="mb-4">
                        <legend class="mb-2 text-theme-xs text-gray-500">{{ $project->images->count() }} gambar tersimpan. Judul dan keterangan tampil di galeri dan lightbox. Centang "Hapus" untuk membuang gambar saat disimpan.</legend>
                        <ul class="space-y-3">
                            @foreach($project->images as $image)
                                <li class="group flex flex-col gap-3 rounded-lg border border-gray-200 p-3 has-[:checked]:border-error-500 sm:flex-row">
                                    <img src="{{ $image->url }}" alt="Gambar galeri {{ $loop->iteration }}" loading="lazy"
                                         class="aspect-[4/3] w-full shrink-0 rounded-md object-cover transition-opacity group-has-[:checked]:opacity-40 sm:w-40">
                                    <div class="flex-1 space-y-2">
                                        <label for="image-title-{{ $image->id }}" class="sr-only">Judul gambar {{ $loop->iteration }}</label>
                                        <input id="image-title-{{ $image->id }}" type="text" name="image_titles[{{ $image->id }}]" maxlength="150" class="admin-input"
                                               value="{{ old('image_titles.' . $image->id, $image->title) }}" placeholder="Judul, contoh: OCR review queue">
                                        <label for="image-desc-{{ $image->id }}" class="sr-only">Keterangan gambar {{ $loop->iteration }}</label>
                                        <textarea id="image-desc-{{ $image->id }}" name="image_descriptions[{{ $image->id }}]" rows="2" maxlength="500" class="admin-textarea"
                                                  placeholder="Keterangan singkat (opsional)">{{ old('image_descriptions.' . $image->id, $image->description) }}</textarea>
                                        <label class="inline-flex min-h-11 cursor-pointer items-center gap-2 text-sm text-gray-700">
                                            <input type="checkbox" name="remove_images[]" value="{{ $image->id }}" class="admin-checkbox"
                                                   @checked(in_array($image->id, old('remove_images', [])))>
                                            Hapus<span class="sr-only"> gambar galeri {{ $loop->iteration }}</span>
                                        </label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </fieldset>
                @endif

                <input id="gallery_images" type="file" name="gallery_images[]" accept="image/jpeg,image/png,image/webp" multiple
                       data-shrink data-max-files="12" class="admin-file" aria-describedby="gallery-help">
                <p id="gallery-help" class="admin-help">Opsional. Pilih hingga 12 gambar sekaligus (JPG, PNG, atau WebP, maks. 2 MB per gambar). Gambar baru ditambahkan setelah gambar yang sudah ada; judul dan keterangannya bisa diisi setelah disimpan.</p>

                <ul x-show="previews.length" x-cloak class="mt-3 grid grid-cols-3 gap-2 sm:grid-cols-6" aria-label="Pratinjau gambar yang akan diunggah">
                    <template x-for="(src, i) in previews" :key="src">
                        <li><img :src="src" :alt="'Pratinjau gambar ' + (i + 1)" class="aspect-square w-full rounded-lg border border-gray-200 object-cover"></li>
                    </template>
                </ul>
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
    const steps = @js($project?->steps ?? []);

    return {
        impactList: highlights.length ? highlights.map(h => ({ detail: '', ...h })) : [{ metric: '', label: '', detail: '' }],
        stepsList: steps.length ? steps : [{ name: '', description: '' }],
    };
}
</script>
@endpush
