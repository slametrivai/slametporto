@extends('admin.layouts.app')

@section('title', 'Identitas & SEO')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data"
      class="grid grid-cols-1 items-start gap-6 xl:grid-cols-12"
      x-data="settingsForm({
          siteName: @js(old('site_name', $settings['site_name'] ?? 'Slamet Rivai')),
          siteTitle: @js(old('site_title', $settings['site_title'] ?? '')),
          metaDesc: @js(old('meta_description', $settings['meta_description'] ?? '')),
          canonicalUrl: @js(old('canonical_url', $settings['canonical_url'] ?? 'https://slametrivai.host')),
      })">
    @csrf

    <div class="space-y-6 xl:col-span-7">
        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h3 class="admin-card-title">Identitas situs</h3>
                    <p class="admin-card-desc">Nama, tagline, logo, dan favicon yang dipakai di header, footer, dan tab browser.</p>
                </div>
            </div>
            <div class="admin-card-body">
                <div>
                    <label for="site_name" class="admin-label">Nama situs <span class="text-error-600">*</span></label>
                    <input id="site_name" type="text" name="site_name" x-model="siteName" required class="admin-input">
                </div>

                <div>
                    <label for="site_tagline" class="admin-label">Tagline</label>
                    <input id="site_tagline" type="text" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline'] ?? 'Operations & Systems Leader') }}" class="admin-input">
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="rounded-xl border border-gray-200 p-4">
                        <p class="admin-label">Logo</p>
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-24 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-gray-200 bg-gray-50 p-1">
                                <template x-if="logoPreview"><img :src="logoPreview" alt="Pratinjau logo baru" class="max-h-full max-w-full object-contain"></template>
                                @if(!empty($settings['site_logo']))
                                    <img x-show="!logoPreview" src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo saat ini" class="max-h-full max-w-full object-contain">
                                @else
                                    <span x-show="!logoPreview" class="text-theme-xs text-gray-500">Belum ada</span>
                                @endif
                            </div>
                            <label class="admin-btn-outline cursor-pointer focus-within:ring-3 focus-within:ring-brand-500/40">
                                Pilih logo
                                <input type="file" name="site_logo" accept="image/*" class="sr-only" @change="logoPreview = preview($event)">
                            </label>
                        </div>
                        @if(!empty($settings['site_logo']))
                            <label class="mt-3 flex min-h-11 cursor-pointer items-center gap-3 text-theme-sm text-gray-700">
                                <input type="checkbox" name="remove_logo" value="1" class="admin-checkbox">
                                Hapus logo saat ini
                            </label>
                        @endif
                        <p class="admin-help">PNG, SVG, atau WEBP. Maks. 2 MB.</p>
                    </div>

                    <div class="rounded-xl border border-gray-200 p-4">
                        <p class="admin-label">Favicon</p>
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-gray-200 bg-gray-50 p-1">
                                <template x-if="faviconPreview"><img :src="faviconPreview" alt="Pratinjau favicon baru" class="h-8 w-8 object-contain"></template>
                                @if(!empty($settings['site_favicon']))
                                    <img x-show="!faviconPreview" src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon saat ini" class="h-8 w-8 object-contain">
                                @else
                                    <span x-show="!faviconPreview" class="text-theme-xs text-gray-500">Belum ada</span>
                                @endif
                            </div>
                            <label class="admin-btn-outline cursor-pointer focus-within:ring-3 focus-within:ring-brand-500/40">
                                Pilih favicon
                                <input type="file" name="site_favicon" accept=".ico,.png,.svg" class="sr-only" @change="faviconPreview = preview($event)">
                            </label>
                        </div>
                        @if(!empty($settings['site_favicon']))
                            <label class="mt-3 flex min-h-11 cursor-pointer items-center gap-3 text-theme-sm text-gray-700">
                                <input type="checkbox" name="remove_favicon" value="1" class="admin-checkbox">
                                Hapus favicon saat ini
                            </label>
                        @endif
                        <p class="admin-help">ICO, PNG, atau SVG, 32 atau 64 px.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h3 class="admin-card-title">Kontak</h3>
                    <p class="admin-card-desc">Tampil di footer, section kontak, dan resume. Kosongkan untuk menyembunyikan tautannya.</p>
                </div>
            </div>
            <div class="admin-card-body">
                <div>
                    <label for="contact_email" class="admin-label">Email</label>
                    <input id="contact_email" type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" class="admin-input"
                           placeholder="nama@domain.com">
                </div>

                <div>
                    <label for="whatsapp_number" class="admin-label">Nomor WhatsApp</label>
                    <input id="whatsapp_number" type="tel" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '') }}" class="admin-input"
                           inputmode="tel" placeholder="0812xxxxxxxx" aria-describedby="whatsapp-help">
                    <p id="whatsapp-help" class="admin-help">Boleh ditulis 0812..., +62 812..., atau dengan tanda hubung. Disimpan sebagai 62812... untuk tautan wa.me.</p>
                </div>

                <div>
                    <label for="linkedin_url" class="admin-label">URL profil LinkedIn</label>
                    <input id="linkedin_url" type="url" name="linkedin_url" value="{{ old('linkedin_url', $settings['linkedin_url'] ?? '') }}" class="admin-input"
                           placeholder="https://www.linkedin.com/in/username">
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h3 class="admin-card-title">SEO global</h3>
                    <p class="admin-card-desc">Dipakai halaman yang tidak punya pengaturan SEO sendiri.</p>
                </div>
            </div>
            <div class="admin-card-body">
                <div>
                    <div class="mb-1.5 flex items-center justify-between gap-2">
                        <label for="site_title" class="text-sm font-medium text-gray-700">Meta title <span class="text-error-600">*</span></label>
                        <span class="text-theme-xs" :class="siteTitle.length > 60 ? 'text-warning-700' : 'text-gray-500'" x-text="siteTitle.length + ' / 60'"></span>
                    </div>
                    <input id="site_title" type="text" name="site_title" x-model="siteTitle" required class="admin-input" aria-describedby="site-title-help">
                    <p id="site-title-help" class="admin-help">Judul biru di hasil pencarian Google dan teks di tab browser.</p>
                </div>

                <div>
                    <div class="mb-1.5 flex items-center justify-between gap-2">
                        <label for="meta_description" class="text-sm font-medium text-gray-700">Meta description <span class="text-error-600">*</span></label>
                        <span class="text-theme-xs" :class="metaDesc.length > 160 ? 'text-warning-700' : 'text-gray-500'" x-text="metaDesc.length + ' / 160'"></span>
                    </div>
                    <textarea id="meta_description" name="meta_description" rows="3" x-model="metaDesc" class="admin-textarea" aria-describedby="meta-desc-help"></textarea>
                    <p id="meta-desc-help" class="admin-help">Cuplikan di bawah judul hasil pencarian. Idealnya 140 sampai 160 karakter.</p>
                </div>

                <div>
                    <label for="meta_keywords" class="admin-label">Meta keywords</label>
                    <input id="meta_keywords" type="text" name="meta_keywords" value="{{ old('meta_keywords', $settings['meta_keywords'] ?? '') }}" class="admin-input" aria-describedby="keywords-help">
                    <p id="keywords-help" class="admin-help">Pisahkan dengan koma.</p>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="canonical_url" class="admin-label">Canonical URL</label>
                        <input id="canonical_url" type="url" name="canonical_url" x-model="canonicalUrl" class="admin-input">
                    </div>
                    <div>
                        <label for="site_author" class="admin-label">Penulis</label>
                        <input id="site_author" type="text" name="site_author" value="{{ old('site_author', $settings['site_author'] ?? 'Slamet Rivai') }}" class="admin-input">
                    </div>
                </div>

                <div>
                    <label for="twitter_handle" class="admin-label">Akun X (Twitter)</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-sm text-gray-500" aria-hidden="true">@</span>
                        <input id="twitter_handle" type="text" name="twitter_handle" value="{{ ltrim(old('twitter_handle', $settings['twitter_handle'] ?? 'slametrivai'), '@') }}" class="admin-input pl-9">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="admin-btn-primary">Simpan pengaturan</button>
        </div>
    </div>

    <div class="space-y-6 xl:sticky xl:top-24 xl:col-span-5">
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Pratinjau hasil pencarian</h3>
            </div>
            <div class="admin-card-body">
                <div class="rounded-xl border border-gray-200 bg-white p-4">
                    <p class="text-theme-sm text-gray-800" x-text="siteName"></p>
                    <p class="truncate text-theme-xs text-gray-700" x-text="canonicalUrl"></p>
                    <p class="mt-2 line-clamp-2 text-base text-[#1a0dab]" x-text="siteTitle || 'Isi meta title untuk melihat pratinjau'"></p>
                    <p class="mt-1 line-clamp-3 text-theme-sm text-[#4d5156]" x-text="metaDesc || 'Isi meta description untuk melihat pratinjau.'"></p>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Pratinjau teks saat dibagikan</h3>
            </div>
            <div class="admin-card-body">
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                    <p class="text-theme-xs uppercase text-gray-500" x-text="hostname"></p>
                    <p class="mt-1 truncate text-theme-sm font-medium text-gray-800" x-text="siteTitle || siteName"></p>
                    <p class="mt-1 line-clamp-2 text-theme-sm text-gray-500" x-text="metaDesc"></p>
                </div>
                <p class="admin-help">Judul di atas 60 karakter biasanya terpotong di Google. Gabungkan peran dan nama agar tetap terbaca utuh.</p>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
function settingsForm(initial) {
    return {
        ...initial,
        logoPreview: null,
        faviconPreview: null,
        preview(event) {
            const file = event.target.files[0];
            return file ? URL.createObjectURL(file) : null;
        },
        get hostname() {
            try { return new URL(this.canonicalUrl).hostname; } catch (e) { return this.canonicalUrl; }
        },
    };
}
</script>
@endpush
@endsection
