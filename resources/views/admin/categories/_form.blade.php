@php
    $category ??= null;
    $robots = old('seo_robots', $category?->seo?->robots ?? 'index, follow');
@endphp

<form action="{{ $category ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST"
      class="mx-auto max-w-4xl space-y-6" x-data="categoryForm()">
    @csrf
    @if($category) @method('PUT') @endif

    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Informasi kategori</h3>
        </div>
        <div class="admin-card-body">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="md:col-span-2">
                    <label for="name" class="admin-label">Nama <span class="text-error-600">*</span></label>
                    <input id="name" type="text" name="name" x-model="name" @input="updateSlug()" required class="admin-input"
                           placeholder="Contoh: Automation & OCR">
                    @if($category && $category->type !== 'post')
                        <p class="admin-help">Mengganti nama juga memperbarui {{ $category->type === 'project' ? 'semua studi kasus' : 'semua klien' }} yang memakai kategori ini.</p>
                    @endif
                </div>
                <div>
                    <label for="type" class="admin-label">Tipe <span class="text-error-600">*</span></label>
                    <select id="type" name="type" x-model="type" required class="admin-select">
                        <option value="post">Artikel Blog</option>
                        <option value="project">Studi Kasus</option>
                        <option value="client">Industri Klien</option>
                    </select>
                </div>
            </div>

            <div>
                <div class="mb-1.5 flex flex-wrap items-center justify-between gap-2">
                    <label for="slug" class="text-sm font-medium text-gray-700">Slug URL</label>
                    <button type="button" @click="customSlug = !customSlug; updateSlug()" class="admin-link inline-flex min-h-11 items-center text-theme-sm"
                            x-text="customSlug ? 'Buat otomatis dari nama' : 'Ubah manual'"></button>
                </div>
                <input id="slug" type="text" name="slug" x-model="slug" :readonly="!customSlug" @required($category) class="admin-input read-only:bg-gray-50"
                       placeholder="slug-kategori" aria-describedby="slug-help">
                <p id="slug-help" class="admin-help">Dibuat otomatis dari nama. Pilih "Ubah manual" untuk mengetik sendiri.</p>
            </div>

            <div>
                <div class="mb-1.5 flex items-center justify-between gap-2">
                    <label for="description" class="text-sm font-medium text-gray-700">Deskripsi</label>
                    <span class="text-theme-xs text-gray-500" x-text="description.length + ' / 1000'"></span>
                </div>
                <textarea id="description" name="description" rows="3" x-model="description" maxlength="1000" class="admin-textarea"
                          placeholder="Topik yang dicakup kategori ini."></textarea>
            </div>
        </div>
    </div>

    <div class="admin-card" x-data="{ seoOpen: true }">
        <div class="admin-card-header">
            <div>
                <h3 class="admin-card-title">SEO</h3>
                <p class="admin-card-desc">Judul dan deskripsi halaman kategori di hasil pencarian. Kosongkan untuk memakai nilai otomatis.</p>
            </div>
            <button type="button" @click="seoOpen = !seoOpen" :aria-expanded="seoOpen.toString()" aria-controls="category-seo" class="admin-icon-btn">
                <span class="sr-only">Tampilkan atau sembunyikan pengaturan SEO</span>
                <svg class="h-5 w-5 transition-transform" :class="seoOpen && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </button>
        </div>

        <div id="category-seo" x-show="seoOpen" class="admin-card-body">
            <div>
                <div class="mb-1.5 flex items-center justify-between gap-2">
                    <label for="seo_title" class="text-sm font-medium text-gray-700">Meta title</label>
                    <span class="text-theme-xs" :class="seoTitlePreview.length > 60 ? 'text-warning-700' : 'text-gray-500'" x-text="seoTitlePreview.length + ' / 60'"></span>
                </div>
                <input id="seo_title" type="text" name="seo_title" x-model="seoTitle" class="admin-input" :placeholder="'Otomatis: ' + autoTitle">
            </div>

            <div>
                <div class="mb-1.5 flex items-center justify-between gap-2">
                    <label for="seo_description" class="text-sm font-medium text-gray-700">Meta description</label>
                    <span class="text-theme-xs" :class="seoDescPreview.length > 160 ? 'text-warning-700' : 'text-gray-500'" x-text="seoDescPreview.length + ' / 160'"></span>
                </div>
                <textarea id="seo_description" name="seo_description" rows="2" x-model="seoDesc" maxlength="500" class="admin-textarea"
                          placeholder="Otomatis: deskripsi kategori"></textarea>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="seo_robots" class="admin-label">Robots</label>
                    <select id="seo_robots" name="seo_robots" class="admin-select">
                        <option value="index, follow" @selected($robots === 'index, follow')>index, follow (disarankan)</option>
                        <option value="noindex, follow" @selected($robots === 'noindex, follow')>noindex, follow</option>
                        <option value="noindex, nofollow" @selected($robots === 'noindex, nofollow')>noindex, nofollow</option>
                    </select>
                </div>
                <div>
                    <label for="seo_canonical_url" class="admin-label">Canonical URL</label>
                    <input id="seo_canonical_url" type="url" name="seo_canonical_url" value="{{ old('seo_canonical_url', $category?->seo?->canonical_url) }}"
                           class="admin-input" placeholder="Otomatis: URL halaman kategori">
                </div>
            </div>

            <div>
                <p class="admin-label">Pratinjau hasil pencarian</p>
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                    <p class="truncate text-theme-xs text-gray-700" x-text="previewUrl"></p>
                    <p class="mt-1 line-clamp-1 text-base text-[#1a0dab]" x-text="seoTitlePreview"></p>
                    <p class="mt-1 line-clamp-2 text-theme-sm text-[#4d5156]" x-text="seoDescPreview"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap justify-end gap-3">
        <a href="{{ route('admin.categories.index', $category ? ['type' => $category->type] : []) }}" class="admin-btn-outline">Batal</a>
        <button type="submit" class="admin-btn-primary">{{ $category ? 'Simpan perubahan' : 'Simpan kategori' }}</button>
    </div>
</form>

@push('scripts')
<script>
function categoryForm() {
    // Mirrors Category::getDynamicSEOData() so the preview matches the real <title>.
    const typeNames = { project: 'Studi Kasus & Proyek', client: 'Ekosistem Klien & Industri', post: 'Artikel Teknis' };

    return {
        name: @js(old('name', $category?->name ?? '')),
        slug: @js(old('slug', $category?->slug ?? '')),
        type: @js(old('type', $category?->type ?? $defaultType ?? 'post')),
        description: @js(old('description', $category?->description ?? '')),
        seoTitle: @js(old('seo_title', $category?->seo?->title ?? '')),
        seoDesc: @js(old('seo_description', $category?->seo?->description ?? '')),
        customSlug: @js((bool) ($category || old('slug'))),

        updateSlug() {
            if (this.customSlug) return;
            this.slug = this.name.toLowerCase().trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        },
        get typeName() {
            return typeNames[this.type] || typeNames.post;
        },
        get autoTitle() {
            return (this.name || 'Nama kategori') + ' | ' + this.typeName;
        },
        get seoTitlePreview() {
            return this.seoTitle || this.autoTitle;
        },
        get seoDescPreview() {
            return this.seoDesc || this.description
                || 'Kumpulan ' + this.typeName + ' kategori ' + (this.name || 'ini') + ' oleh Slamet Rivai, Operations & Systems Leader.';
        },
        get previewUrl() {
            if (this.type === 'project') return @js(url('/projects')) + '?category=' + encodeURIComponent(this.name);
            if (this.type === 'client') return @js(url('/')) + '#clients';
            return @js(url('/blog')) + '?category=' + (this.slug || 'slug-kategori');
        },
    };
}
</script>
@endpush
