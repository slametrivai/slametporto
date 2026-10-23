<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0B0F17">
    <title>@yield('title', 'Dashboard') | {{ setting('site_name', 'Slamet Rivai') }} CMS</title>

    <link rel="icon" href="{{ setting('site_favicon') ? asset('storage/' . setting('site_favicon')) : asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    @vite(['resources/css/admin.css', 'resources/js/app.js'])
    @stack('styles')
</head>
@php
    $menuGroups = [
        'Konten' => [
            ['route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'label' => 'Ringkasan', 'icon' => 'layout-dashboard'],
            ['route' => 'admin.projects.index', 'active' => 'admin.projects.*', 'label' => 'Studi kasus', 'icon' => 'briefcase'],
            ['route' => 'admin.posts.index', 'active' => 'admin.posts.*', 'label' => 'Artikel', 'icon' => 'pen-line'],
            ['route' => 'admin.inquiries.index', 'active' => 'admin.inquiries.*', 'label' => 'Pesan masuk', 'icon' => 'inbox'],
            ['route' => 'admin.clients.index', 'active' => 'admin.clients.*', 'label' => 'Klien', 'icon' => 'building-2'],
            ['route' => 'admin.categories.index', 'active' => 'admin.categories.*', 'label' => 'Kategori', 'icon' => 'tag'],
            ['route' => 'admin.careers.index', 'active' => 'admin.careers.*', 'label' => 'Riwayat karier', 'icon' => 'history'],
            ['route' => 'admin.certifications.index', 'active' => 'admin.certifications.*', 'label' => 'Sertifikasi', 'icon' => 'award'],
        ],
        'Situs' => [
            ['route' => 'admin.settings.index', 'active' => 'admin.settings.*', 'label' => 'Identitas & SEO', 'icon' => 'settings'],
            ['route' => 'profile.edit', 'active' => 'profile.*', 'label' => 'Profil & kata sandi', 'icon' => 'user'],
        ],
    ];
    $initialsOf = fn ($name) => collect(explode(' ', (string) $name))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
    $user = auth()->user();
    $siteName = setting('site_name', 'Slamet Rivai');
@endphp
<body x-data="{ sidebarToggle: false }" @keydown.escape.window="sidebarToggle = false">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-99999 focus:rounded-lg focus:bg-brand-500 focus:px-4 focus:py-3 focus:text-sm focus:font-semibold focus:text-void">Lewati ke konten</a>

    <div class="flex h-screen overflow-hidden">
        <aside id="admin-sidebar"
               :class="sidebarToggle ? 'translate-x-0' : '-translate-x-full'"
               class="fixed left-0 top-0 z-99999 flex h-screen w-64 shrink-0 flex-col justify-between gap-6 overflow-y-auto border-r border-gray-200 bg-rail px-4 py-5 transition-transform duration-300 lg:static lg:translate-x-0">
            <div class="flex flex-col gap-6">
                <div class="flex items-center justify-between gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 rounded-lg px-2 py-1 focus:outline-none focus-visible:ring-3 focus-visible:ring-brand-500/40">
                        @if(setting('site_logo'))
                            <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="" class="h-8 w-8 rounded-lg object-contain">
                        @else
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg border-[1.5px] border-brand-500 text-[13px] font-bold text-brand-500" aria-hidden="true">{{ $initialsOf($siteName) }}</span>
                        @endif
                        <span class="flex flex-col gap-0.5">
                            <span class="text-[15px] font-semibold leading-tight text-gray-800">{{ $siteName }}</span>
                            <span class="text-xs leading-tight text-gray-400">Studio admin</span>
                        </span>
                    </a>
                    <button type="button" @click="sidebarToggle = false" class="admin-icon-btn border-0 lg:hidden" aria-label="Tutup menu">
                        <x-admin.icon name="x" class="h-5 w-5" />
                    </button>
                </div>

                <nav aria-label="Menu admin" class="flex flex-col gap-6">
                    @foreach($menuGroups as $group => $items)
                        <div>
                            <h3 class="mb-2 px-0 text-[11px] font-semibold uppercase tracking-[0.11em] text-gray-400">{{ $group }}</h3>
                            <ul class="flex flex-col gap-0.5">
                                @foreach($items as $item)
                                    @php $active = request()->routeIs($item['active']); @endphp
                                    <li>
                                        <a href="{{ route($item['route']) }}"
                                           @if($active) aria-current="page" @endif
                                           class="menu-item group {{ $active ? 'menu-item-active' : 'menu-item-inactive' }} focus:outline-none focus-visible:ring-3 focus-visible:ring-brand-500/40">
                                            <x-admin.icon :name="$item['icon']" class="{{ $active ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}" />
                                            {{ $item['label'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </nav>
            </div>

            <div class="flex flex-col gap-3">
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="group flex min-h-10 items-center gap-2.5 rounded-lg border border-gray-200 px-3 text-theme-sm font-medium text-gray-500 transition-colors hover:bg-gray-50 hover:text-gray-800 focus:outline-none focus-visible:ring-3 focus-visible:ring-brand-500/40">
                    <x-admin.icon name="globe" class="text-gray-400 group-hover:text-gray-500" />
                    <span class="flex-1">Lihat situs</span>
                    <x-admin.icon name="arrow-up-right" class="h-4 w-4 text-brand-500" />
                    <span class="sr-only">(tab baru)</span>
                </a>

                <div class="flex items-center gap-2.5 border-t border-gray-200 px-2 pt-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gray-50 text-[13px] font-semibold text-brand-500 ring-1 ring-brand-500" aria-hidden="true">{{ $initialsOf($user->name) }}</span>
                    <a href="{{ route('profile.edit') }}" class="flex min-w-0 flex-1 flex-col gap-0.5 rounded focus:outline-none focus-visible:ring-3 focus-visible:ring-brand-500/40">
                        <span class="truncate text-sm font-semibold text-gray-800">{{ $user->name }}</span>
                        <span class="truncate text-xs text-gray-400">{{ $user->email }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition-colors hover:bg-gray-50 hover:text-gray-800 focus:outline-none focus-visible:ring-3 focus-visible:ring-brand-500/40" aria-label="Keluar" title="Keluar">
                            <x-admin.icon name="log-out" />
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="relative flex min-w-0 flex-1 flex-col overflow-y-auto overflow-x-hidden">
            <div x-show="sidebarToggle" x-cloak @click="sidebarToggle = false" class="fixed inset-0 z-99998 bg-gray-900/70 lg:hidden"></div>

            <header class="sticky top-0 z-40 flex h-[68px] w-full shrink-0 items-center justify-between gap-3 border-b border-gray-200 bg-void/90 px-4 backdrop-blur md:px-8">
                <div class="flex min-w-0 items-center gap-3">
                    <button type="button" @click.stop="sidebarToggle = !sidebarToggle" :aria-expanded="sidebarToggle.toString()" aria-controls="admin-sidebar"
                            class="admin-icon-btn lg:hidden" aria-label="Buka menu">
                        <x-admin.icon name="menu" class="h-5 w-5" />
                    </button>

                    <nav aria-label="Breadcrumb" class="min-w-0">
                        <ol class="flex items-center gap-2 text-sm">
                            <li class="hidden items-center gap-2 sm:flex">
                                <a href="{{ route('admin.dashboard') }}" class="text-gray-400 transition-colors hover:text-gray-800">Studio</a>
                                <x-admin.icon name="chevron-right" class="h-4 w-4 text-gray-400" />
                            </li>
                            @hasSection('parent_label')
                                <li class="hidden items-center gap-2 sm:flex">
                                    <a href="@yield('parent_url')" class="text-gray-400 transition-colors hover:text-gray-800">@yield('parent_label')</a>
                                    <x-admin.icon name="chevron-right" class="h-4 w-4 text-gray-400" />
                                </li>
                            @endif
                            <li class="truncate font-semibold text-gray-800" aria-current="page">
                                @hasSection('breadcrumb') @yield('breadcrumb') @else @yield('title', 'Dashboard') @endif
                            </li>
                        </ol>
                    </nav>
                </div>

                <div class="flex shrink-0 items-center gap-3">
                    <a href="{{ route('admin.inquiries.index') }}" class="admin-icon-btn relative"
                       aria-label="Pesan masuk{{ $newInquiries ? ', ' . $newInquiries . ' belum dibaca' : '' }}">
                        <x-admin.icon name="bell" />
                        @if($newInquiries)
                            <span class="absolute right-[9px] top-[9px] h-2 w-2 rounded-full bg-brand-500 ring-2 ring-void" aria-hidden="true"></span>
                        @endif
                    </a>
                    <a href="{{ route('admin.projects.create') }}" class="admin-btn-primary hidden md:inline-flex">
                        <x-admin.icon name="plus" class="h-4 w-4" />
                        Studi kasus baru
                    </a>
                </div>
            </header>

            <main id="main" tabindex="-1" class="focus:outline-none">
                <div class="mx-auto flex max-w-screen-2xl flex-col gap-6 p-4 md:px-8 md:pb-8 md:pt-7">
                    @hasSection('page_header')
                        @yield('page_header')
                    @else
                        <div class="flex flex-wrap items-end justify-between gap-3">
                            <div class="flex flex-col gap-1.5">
                                <h1 class="text-title-sm font-bold tracking-[-0.5px] text-gray-800">@yield('title', 'Dashboard')</h1>
                                @hasSection('subtitle')
                                    <p class="text-sm text-gray-500">@yield('subtitle')</p>
                                @endif
                            </div>
                            @hasSection('actions')
                                <div class="flex flex-wrap items-center gap-3">@yield('actions')</div>
                            @endif
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="admin-alert-success" role="status">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="admin-alert-error" role="alert">
                            <p class="font-semibold">Data belum tersimpan. Periksa isian berikut:</p>
                            <ul class="mt-2 list-inside list-disc space-y-1 text-gray-700">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div>
                        @yield('content')
                    </div>
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
                confirmButtonColor: '#F87171',
                cancelButtonColor: '#18202E',
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

            if (input.multiple) {
                await shrinkMany(input, note);
            } else {
                await shrinkOne(input, note);
            }
            submits.forEach((b) => { b.disabled = false; });
            input.dispatchEvent(new CustomEvent('upload-ready', { bubbles: true }));
        });

        async function shrinkOne(input, note) {
            const original = input.files[0];
            let file = original;
            try { file = await shrinkImage(original); } catch (err) { file = original; }

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
        }

        async function shrinkMany(input, note) {
            const maxFiles = Number(input.dataset.maxFiles) || Infinity;
            const picked = [...input.files];
            const kept = [];
            const tooBig = [];

            for (const original of picked.slice(0, maxFiles)) {
                let file = original;
                try { file = await shrinkImage(original); } catch (err) { file = original; }
                (file.size > UPLOAD_MAX ? tooBig : kept).push(file);
            }

            const dt = new DataTransfer();
            kept.forEach((file) => dt.items.add(file));
            input.files = dt.files;

            const problems = [];
            if (picked.length > maxFiles) problems.push(`${picked.length - maxFiles} gambar dilewati (maks. ${maxFiles} per unggahan)`);
            if (tooBig.length) problems.push(`${tooBig.map((f) => f.name).join(', ')} terlalu besar (maks. 2 MB)`);

            const total = kept.reduce((sum, f) => sum + f.size, 0);
            const ready = kept.length ? `${kept.length} gambar siap diunggah (${formatSize(total)}).` : 'Tidak ada gambar yang bisa diunggah.';
            note.className = problems.length ? 'admin-error' : 'admin-help';
            note.textContent = [ready, ...problems.map((p) => p + '.')].join(' ');
        }
    </script>
    @stack('scripts')
</body>
</html>
