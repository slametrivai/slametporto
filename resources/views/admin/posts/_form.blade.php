@php $post ??= null; @endphp

<form action="{{ $post ? route('admin.posts.update', $post) : route('admin.posts.store') }}" method="POST" enctype="multipart/form-data"
      class="grid grid-cols-1 items-start gap-6 xl:grid-cols-12" x-data="postForm()" @submit="syncEditor()">
    @csrf
    @if($post) @method('PUT') @endif

    <div class="space-y-6 xl:col-span-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Konten artikel</h3>
            </div>
            <div class="admin-card-body">
                <div>
                    <label for="title" class="admin-label">Judul <span class="text-error-600">*</span></label>
                    <input id="title" type="text" name="title" x-model="title" required class="admin-input"
                           placeholder="Contoh: Membangun pipeline OCR dokumen yang tahan gagal">
                </div>

                <div>
                    <div class="mb-1.5 flex items-center justify-between gap-2">
                        <label for="excerpt" class="text-sm font-medium text-gray-700">Ringkasan <span class="text-error-600">*</span></label>
                        <span class="text-theme-xs text-gray-500" x-text="excerpt.length + ' / 500'"></span>
                    </div>
                    <textarea id="excerpt" name="excerpt" rows="3" x-model="excerpt" required maxlength="500" class="admin-textarea"
                              placeholder="1-2 kalimat untuk kartu blog dan cuplikan pencarian."></textarea>
                </div>

                <div>
                    <label for="content" class="admin-label">Isi artikel <span class="text-error-600">*</span></label>
                    <textarea id="content" name="content" rows="18">{{ old('content', $post?->content) }}</textarea>
                </div>
            </div>
        </div>

        <div class="admin-card" x-data="{ seoOpen: true }">
            <div class="admin-card-header">
                <div>
                    <h3 class="admin-card-title">SEO</h3>
                    <p class="admin-card-desc">Kosongkan untuk memakai judul dan ringkasan artikel.</p>
                </div>
                <button type="button" @click="seoOpen = !seoOpen" :aria-expanded="seoOpen.toString()" aria-controls="post-seo" class="admin-icon-btn">
                    <span class="sr-only">Tampilkan atau sembunyikan pengaturan SEO</span>
                    <svg class="h-5 w-5 transition-transform" :class="seoOpen && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
            </div>

            <div id="post-seo" x-show="seoOpen" class="admin-card-body">
                <div>
                    <div class="mb-1.5 flex items-center justify-between gap-2">
                        <label for="seo_title" class="text-sm font-medium text-gray-700">Meta title</label>
                        <span class="text-theme-xs" :class="(seoTitle || title).length > 60 ? 'text-warning-700' : 'text-gray-500'" x-text="(seoTitle || title).length + ' / 60'"></span>
                    </div>
                    <input id="seo_title" type="text" name="seo_title" x-model="seoTitle" class="admin-input" placeholder="Otomatis: judul artikel">
                </div>

                <div>
                    <div class="mb-1.5 flex items-center justify-between gap-2">
                        <label for="seo_description" class="text-sm font-medium text-gray-700">Meta description</label>
                        <span class="text-theme-xs" :class="(seoDesc || excerpt).length > 160 ? 'text-warning-700' : 'text-gray-500'" x-text="(seoDesc || excerpt).length + ' / 160'"></span>
                    </div>
                    <textarea id="seo_description" name="seo_description" rows="2" x-model="seoDesc" class="admin-textarea" placeholder="Otomatis: ringkasan artikel"></textarea>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="seo_robots" class="admin-label">Robots</label>
                        @php $robots = old('seo_robots', $post?->seo?->robots ?? 'index, follow'); @endphp
                        <select id="seo_robots" name="seo_robots" class="admin-select">
                            <option value="index, follow" @selected($robots === 'index, follow')>index, follow (disarankan)</option>
                            <option value="noindex, follow" @selected($robots === 'noindex, follow')>noindex, follow</option>
                            <option value="noindex, nofollow" @selected($robots === 'noindex, nofollow')>noindex, nofollow</option>
                        </select>
                    </div>
                    <div>
                        <label for="seo_canonical_url" class="admin-label">Canonical URL</label>
                        <input id="seo_canonical_url" type="url" name="seo_canonical_url" value="{{ old('seo_canonical_url', $post?->seo?->canonical_url) }}" class="admin-input" placeholder="Otomatis: URL artikel">
                    </div>
                </div>

                <div>
                    <p class="admin-label">Pratinjau hasil pencarian</p>
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                        <p class="truncate text-theme-xs text-gray-700">{{ url('/blog') }}/@if($post){{ $post->slug }}@else<span x-text="slugify(title)"></span>@endif</p>
                        <p class="mt-1 line-clamp-1 text-base text-[#1a0dab]" x-text="seoTitle || title || 'Judul artikel'"></p>
                        <p class="mt-1 line-clamp-2 text-theme-sm text-[#4d5156]" x-text="seoDesc || excerpt || 'Ringkasan artikel tampil di sini.'"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6 xl:col-span-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Publikasi</h3>
            </div>
            <div class="admin-card-body">
                <div>
                    <div class="mb-1.5 flex flex-wrap items-center justify-between gap-2">
                        <label for="category_id" class="text-sm font-medium text-gray-700">Kategori <span class="text-error-600">*</span></label>
                        <a href="{{ route('admin.categories.create', ['type' => 'post']) }}" target="_blank" rel="noopener" class="admin-link text-theme-sm">Tambah<span class="sr-only"> kategori (tab baru)</span></a>
                    </div>
                    <select id="category_id" name="category_id" required class="admin-select">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $post?->category_id) == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="admin-label">Status <span class="text-error-600">*</span></label>
                    <select id="status" name="status" required class="admin-select">
                        <option value="published" @selected(old('status', $post?->status) === 'published')>Terbit</option>
                        <option value="draft" @selected(old('status', $post?->status) === 'draft')>Draf</option>
                    </select>
                </div>

                <div>
                    <label for="published_at" class="admin-label">Jadwal terbit</label>
                    <input id="published_at" type="datetime-local" name="published_at" class="admin-input"
                           value="{{ old('published_at', $post?->published_at?->format('Y-m-d\TH:i')) }}">
                    <p class="admin-help">Opsional. Kosongkan untuk terbit saat disimpan.</p>
                </div>

                <div class="flex flex-col gap-3 border-t border-gray-100 pt-6">
                    <button type="submit" class="admin-btn-primary w-full">{{ $post ? 'Simpan perubahan' : 'Simpan artikel' }}</button>
                    @if($post?->status === 'published')
                        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" rel="noopener" class="admin-btn-outline w-full">Lihat di blog<span class="sr-only"> (tab baru)</span></a>
                    @endif
                    <a href="{{ route('admin.posts.index') }}" class="admin-btn-outline w-full">Batal</a>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Gambar cover</h3>
            </div>
            <div class="admin-card-body" data-upload-field>
                <template x-if="coverPreview">
                    <img :src="coverPreview" alt="Pratinjau cover baru" class="h-36 w-full rounded-lg border border-gray-200 object-cover">
                </template>
                @if($post?->cover_image)
                    <img x-show="!coverPreview" src="{{ $post->cover_image_url }}" alt="Cover saat ini" class="h-36 w-full rounded-lg border border-gray-200 object-cover">
                @endif

                <label class="flex cursor-pointer flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 text-center transition hover:border-brand-500 focus-within:border-brand-500 focus-within:ring-3 focus-within:ring-brand-500/20">
                    <span class="text-sm font-medium text-gray-800">{{ $post?->cover_image ? 'Ganti gambar' : 'Pilih gambar' }}</span>
                    <span class="mt-1 text-theme-xs text-gray-500">PNG, JPG, SVG, atau WEBP. Maks. 2 MB; foto yang lebih besar otomatis diperkecil.</span>
                    <input type="file" name="cover_image" accept="image/*,.svg" data-shrink class="sr-only" @change="previewCover($event)">
                </label>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    tinymce.init({
        selector: '#content',
        license_key: 'gpl',
        promotion: false,
        branding: false,
        height: 520,
        menubar: 'file edit view insert format tools table help',
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'codesample'
        ],
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | ' +
                 'alignleft aligncenter alignright alignjustify | ' +
                 'bullist numlist outdent indent | link image media codesample | ' +
                 'table removeformat | code fullscreen preview',
        content_style: 'body { font-family: Outfit, sans-serif; font-size: 15px; line-height: 1.7; color: #344054; padding: 12px; }',
        setup: function (editor) {
            editor.on('change keyup', function () {
                editor.save();
            });
        }
    });
});

function postForm() {
    return {
        title: @js(old('title', $post?->title ?? '')),
        excerpt: @js(old('excerpt', $post?->excerpt ?? '')),
        seoTitle: @js(old('seo_title', $post?->seo?->title ?? '')),
        seoDesc: @js(old('seo_description', $post?->seo?->description ?? '')),
        coverPreview: null,
        previewCover(event) {
            const file = event.target.files[0];
            if (file) this.coverPreview = URL.createObjectURL(file);
        },
        slugify(text) {
            if (!text) return 'slug-artikel';
            return text.toString().toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+|-+$/g, '');
        },
        syncEditor() {
            if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
                tinymce.get('content').save();
            }
        }
    };
}
</script>
@endpush
