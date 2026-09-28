<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | {{ setting('site_name', 'Slamet Rivai') }} CMS</title>

    <link rel="icon" href="{{ setting('site_favicon') ? asset('storage/' . setting('site_favicon')) : asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    @vite(['resources/css/admin.css', 'resources/js/app.js'])
    @stack('styles')
</head>
@php
    $menu = [
        ['route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['route' => 'admin.projects.index', 'active' => 'admin.projects.*', 'label' => 'Studi Kasus', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
        ['route' => 'admin.clients.index', 'active' => 'admin.clients.*', 'label' => 'Klien', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
        ['route' => 'admin.posts.index', 'active' => 'admin.posts.*', 'label' => 'Artikel Blog', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
        ['route' => 'admin.categories.index', 'active' => 'admin.categories.*', 'label' => 'Kategori', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
        ['route' => 'admin.careers.index', 'active' => 'admin.careers.*', 'label' => 'Riwayat Karier', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
        ['route' => 'admin.certifications.index', 'active' => 'admin.certifications.*', 'label' => 'Sertifikasi', 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
    ];
    $menuOther = [
        ['route' => 'admin.inquiries.index', 'active' => 'admin.inquiries.*', 'label' => 'Pesan Masuk', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
        ['route' => 'admin.settings.index', 'active' => 'admin.settings.*', 'label' => 'Identitas & SEO', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z'],
    ];
    $user = auth()->user();
    $initials = collect(explode(' ', $user->name))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
@endphp
<body x-data="{ sidebarToggle: false }" @keydown.escape.window="sidebarToggle = false">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-99999 focus:rounded-lg focus:bg-white focus:px-4 focus:py-3 focus:text-sm focus:font-medium focus:text-brand-600 focus:shadow-theme-lg">Lewati ke konten</a>

    <div class="flex h-screen overflow-hidden">
        <aside id="admin-sidebar"
               :class="sidebarToggle ? 'translate-x-0' : '-translate-x-full'"
               class="fixed left-0 top-0 z-99999 flex h-screen w-72.5 flex-col overflow-y-auto border-r border-gray-200 bg-white px-5 transition-transform duration-300 lg:static lg:translate-x-0">
            <div class="flex items-center justify-between gap-2 pb-7 pt-8">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-lg focus:outline-none focus-visible:ring-3 focus-visible:ring-brand-500/40">
                    @if(setting('site_logo'))
                        <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="" class="h-9 w-auto object-contain">
                    @endif
                    <span class="text-lg font-semibold text-gray-800">{{ setting('site_name', 'Slamet Rivai') }}</span>
                </a>
                <button type="button" @click="sidebarToggle = false" class="admin-icon-btn lg:hidden" aria-label="Tutup menu">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <nav aria-label="Menu admin">
                @foreach(['Konten' => $menu, 'Lainnya' => $menuOther] as $group => $items)
                    <h3 class="mb-4 text-xs uppercase leading-5 text-gray-500">{{ $group }}</h3>
                    <ul class="mb-6 flex flex-col gap-1">
                        @foreach($items as $item)
                            @php $active = request()->routeIs($item['active']); @endphp
                            <li>
                                <a href="{{ route($item['route']) }}"
                                   @if($active) aria-current="page" @endif
                                   class="menu-item group {{ $active ? 'menu-item-active' : 'menu-item-inactive' }} focus:outline-none focus-visible:ring-3 focus-visible:ring-brand-500/40">
                                    <svg class="h-6 w-6 {{ $active ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                                    </svg>
                                    {{ $item['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            </nav>
        </aside>

        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            <div x-show="sidebarToggle" x-cloak @click="sidebarToggle = false" class="fixed inset-0 z-99998 bg-gray-900/50 lg:hidden"></div>

            <header class="sticky top-0 z-40 flex w-full border-b border-gray-200 bg-white">
                <div class="flex grow items-center justify-between gap-3 px-4 py-3 md:px-6 lg:py-4">
                    <button type="button" @click.stop="sidebarToggle = !sidebarToggle" :aria-expanded="sidebarToggle.toString()" aria-controls="admin-sidebar"
                            class="admin-icon-btn lg:hidden" aria-label="Buka menu">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <div class="ml-auto flex items-center gap-3">
                        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="admin-btn-outline hidden sm:inline-flex">
                            Lihat situs
                            <span class="sr-only">(tab baru)</span>
                        </a>

                        <div class="relative" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.stop="open = false; $refs.trigger.focus()">
                            <button type="button" x-ref="trigger" @click="open = !open" :aria-expanded="open.toString()"
                                    class="flex min-h-11 items-center gap-3 rounded-lg px-1 text-gray-700 focus:outline-none focus-visible:ring-3 focus-visible:ring-brand-500/40">
                                <span class="flex h-11 w-11 items-center justify-center rounded-full bg-brand-50 text-sm font-semibold text-brand-600" aria-hidden="true">{{ $initials }}</span>
                                <span class="hidden text-theme-sm font-medium sm:block">{{ $user->name }}</span>
                                <svg class="h-5 w-5 text-gray-500 transition-transform" :class="open && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open" x-cloak class="absolute right-0 mt-3 flex w-64 flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg">
                                <div class="px-3 pb-3">
                                    <span class="block text-theme-sm font-medium text-gray-700">{{ $user->name }}</span>
                                    <span class="mt-0.5 block truncate text-theme-xs text-gray-500">{{ $user->email }}</span>
                                </div>
                                <ul class="flex flex-col gap-1 border-y border-gray-200 py-3">
                                    <li>
                                        <a href="{{ route('profile.edit') }}" class="flex min-h-11 items-center gap-3 rounded-lg px-3 text-theme-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus-visible:ring-3 focus-visible:ring-brand-500/40">Profil & kata sandi</a>
                                    </li>
                                    <li class="sm:hidden">
                                        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="flex min-h-11 items-center gap-3 rounded-lg px-3 text-theme-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus-visible:ring-3 focus-visible:ring-brand-500/40">Lihat situs <span class="sr-only">(tab baru)</span></a>
                                    </li>
                                </ul>
                                <form method="POST" action="{{ route('logout') }}" class="pt-3">
                                    @csrf
                                    <button type="submit" class="flex min-h-11 w-full items-center gap-3 rounded-lg px-3 text-left text-theme-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus-visible:ring-3 focus-visible:ring-brand-500/40">Keluar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main id="main" tabindex="-1" class="focus:outline-none">
                <div class="mx-auto max-w-screen-2xl p-4 md:p-6">
                    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                        <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h2>
                        @hasSection('actions')
                            <div class="flex flex-wrap items-center gap-3">@yield('actions')</div>
                        @else
                            <nav aria-label="Breadcrumb">
                                <ol class="flex flex-wrap items-center gap-1.5 text-sm">
                                    <li class="flex items-center gap-1.5">
                                        <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-brand-600">Dashboard</a>
                                        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 17 16" aria-hidden="true"><path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </li>
                                    @hasSection('parent_label')
                                        <li class="flex items-center gap-1.5">
                                            <a href="@yield('parent_url')" class="text-gray-500 hover:text-brand-600">@yield('parent_label')</a>
                                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 17 16" aria-hidden="true"><path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </li>
                                    @endif
                                    <li class="text-gray-800" aria-current="page">@yield('title')</li>
                                </ol>
                            </nav>
                        @endif
                    </div>

                    @if(session('success'))
                        <div class="admin-alert-success mb-6" role="status">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="admin-alert-error mb-6" role="alert">
                            <p class="font-medium">Data belum tersimpan. Periksa isian berikut:</p>
                            <ul class="mt-2 list-inside list-disc space-y-1 text-gray-700">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $.extend(true, $.fn.dataTable.defaults, {
            dom: '<"mb-4 flex flex-wrap items-center justify-between gap-3"lf><"overflow-x-auto"t><"mt-4 flex flex-wrap items-center justify-between gap-3"ip>',
            language: {
                processing: 'Memuat data...',
                search: 'Cari',
                lengthMenu: 'Tampilkan _MENU_ baris',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                infoEmpty: 'Belum ada data',
                infoFiltered: '(disaring dari _MAX_ data)',
                zeroRecords: 'Tidak ada data yang cocok dengan pencarian.',
                emptyTable: 'Belum ada data. Tambahkan data baru untuk mulai.',
                paginate: { first: 'Awal', last: 'Akhir', next: 'Berikutnya', previous: 'Sebelumnya' },
            },
        });

        // Replace DataTables' window.alert with an inline error above the table.
        $.fn.dataTable.ext.errMode = function (settings) {
            const wrapper = $(settings.nTableWrapper);
            wrapper.find('.dt-error').remove();
            wrapper.prepend('<div class="dt-error admin-alert-error mb-4" role="alert">Data gagal dimuat. Muat ulang halaman, atau coba lagi beberapa saat lagi.</div>');
        };

        function confirmDelete(id, url) {
            Swal.fire({
                title: 'Hapus data ini?',
                text: 'Data yang dihapus tidak bisa dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d92d20',
                cancelButtonColor: '#475467',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                focusCancel: true,
            }).then((result) => {
                if (!result.isConfirmed) return;

                $.ajax({
                    url: url || window.location.pathname.replace(/\/$/, '') + '/' + id,
                    type: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function (response) {
                        Swal.fire({ icon: 'success', title: 'Terhapus', text: response.message || 'Data berhasil dihapus.', timer: 1500, showConfirmButton: false });
                        if ($.fn.DataTable.isDataTable('table')) {
                            $('table').DataTable().ajax.reload(null, false);
                        } else {
                            location.reload();
                        }
                    },
                    error: function () {
                        Swal.fire('Gagal menghapus', 'Terjadi kesalahan di server. Coba lagi.', 'error');
                    },
                });
            });
        }
    </script>
    <script>
        // Phone photos are 3-10 MB; the server accepts 2 MB and the production proxy drops big
        // bodies mid-upload (ERR_HTTP2_PING_FAILED). Shrink [data-shrink] images before submit.
        const UPLOAD_MAX = 2 * 1024 * 1024;
        const MAX_SIDE = 1920;
        const formatSize = (b) => b >= 1048576 ? (b / 1048576).toFixed(1) + ' MB' : Math.round(b / 1024) + ' KB';
        const canvasToBlob = (canvas, type) => new Promise((resolve) => canvas.toBlob(resolve, type, 0.85));

        async function shrinkImage(file) {
            if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) return file; // SVG/GIF/ICO as-is
            const img = await createImageBitmap(file);
            const scale = Math.min(1, MAX_SIDE / Math.max(img.width, img.height));
            if (scale === 1 && file.size <= 500 * 1024) return file;

            const canvas = document.createElement('canvas');
            canvas.width = Math.round(img.width * scale);
            canvas.height = Math.round(img.height * scale);
            canvas.getContext('2d').drawImage(img, 0, 0, canvas.width, canvas.height);

            // toBlob silently falls back to PNG when WebP encoding is unsupported (Safari).
            let blob = await canvasToBlob(canvas, 'image/webp');
            if (blob?.type !== 'image/webp' && file.type === 'image/jpeg') blob = await canvasToBlob(canvas, 'image/jpeg');
            if (!blob || blob.size >= file.size) return file;

            return new File([blob], file.name.replace(/\.[^.]+$/, '') + '.' + blob.type.split('/')[1], { type: blob.type });
        }

        document.addEventListener('change', async (e) => {
            const input = e.target;
            if (!input.matches('input[type="file"][data-shrink]') || !input.files.length) return;

            const field = input.closest('[data-upload-field]') ?? input.parentElement;
            let note = field.querySelector('[data-upload-note]');
            if (!note) {
                note = document.createElement('p');
                note.setAttribute('data-upload-note', '');
                note.setAttribute('role', 'status');
                field.appendChild(note);
            }
            const submits = input.form ? [...input.form.querySelectorAll('[type="submit"]')] : [];
            submits.forEach((b) => { b.disabled = true; });
            note.className = 'admin-help';
            note.textContent = 'Memproses gambar...';

            const original = input.files[0];
            let file = original;
            try { file = await shrinkImage(original); } catch (err) { file = original; }
            submits.forEach((b) => { b.disabled = false; });

            if (file.size > UPLOAD_MAX) {
                input.value = '';
                note.className = 'admin-error';
                note.textContent = `Gambar ${formatSize(file.size)} terlalu besar (maks. 2 MB). Pilih file yang lebih kecil.`;
                return;
            }
            if (file !== original) {
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                note.textContent = `Diperkecil otomatis dari ${formatSize(original.size)} menjadi ${formatSize(file.size)}.`;
            } else {
                note.textContent = `${formatSize(file.size)}, siap diunggah.`;
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
