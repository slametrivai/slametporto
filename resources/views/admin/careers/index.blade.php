@extends('admin.layouts.app')

@section('title', 'Riwayat Karier')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h3 class="admin-card-title">Linimasa karier</h3>
            <p class="admin-card-desc">Seret pegangan di kiri, atau pakai tombol naik/turun, untuk mengatur urutan tampil di halaman utama dan resume.</p>
        </div>
        <button type="button" data-career-new class="admin-btn-primary">Tambah riwayat</button>
    </div>

    <p id="career-order-status" class="admin-help mt-0 px-5 pb-3 sm:px-6" role="status"></p>

    @if($careers->isEmpty())
        <div class="border-t border-gray-100 px-5 py-12 text-center sm:px-6">
            <p class="text-theme-sm text-gray-500">Belum ada riwayat karier.</p>
            <button type="button" data-career-new class="admin-link mt-2 inline-flex min-h-11 items-center text-theme-sm">Tambah yang pertama</button>
        </div>
    @else
        <ol id="career-list" class="border-t border-gray-100">
            @foreach($careers as $career)
                <li data-id="{{ $career->id }}" data-career="{{ json_encode($career->only(['id', 'company', 'role', 'period', 'description'])) }}"
                    class="group flex flex-wrap items-start gap-3 border-t border-gray-100 bg-white px-5 py-4 first:border-t-0 sm:flex-nowrap sm:px-6">
                    <span class="career-handle flex h-11 w-8 shrink-0 cursor-grab touch-none items-center justify-center text-gray-500 active:cursor-grabbing" title="Seret untuk mengurutkan" aria-hidden="true">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M7 4a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm0 6a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm-1.5 7.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM16 4a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm-1.5 7.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM16 16a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/></svg>
                    </span>

                    <div class="min-w-0 flex-1 py-1">
                        <p class="font-medium text-gray-800">{{ $career->role }}</p>
                        <p class="mt-0.5 flex flex-wrap items-center gap-2 text-theme-sm text-gray-500">
                            {{ $career->company }}
                            <span class="admin-badge admin-badge-gray">{{ $career->period }}</span>
                        </p>
                        @if($career->description)
                            <p class="mt-1.5 line-clamp-2 text-theme-sm text-gray-500">{{ $career->description }}</p>
                        @endif
                    </div>

                    <div class="flex w-full shrink-0 items-center justify-end gap-2 sm:w-auto">
                        <button type="button" data-move="-1" class="admin-icon-btn group-first:invisible" aria-label="Naikkan {{ $career->company }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                        </button>
                        <button type="button" data-move="1" class="admin-icon-btn group-last:invisible" aria-label="Turunkan {{ $career->company }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <button type="button" data-edit class="admin-icon-btn" aria-label="Edit {{ $career->company }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button type="button" onclick="confirmDelete({{ $career->id }})" class="admin-icon-btn admin-icon-btn-danger" aria-label="Hapus {{ $career->company }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </li>
            @endforeach
        </ol>
    @endif
</div>

<dialog id="career-dialog" aria-labelledby="career-dialog-title" class="w-[calc(100%-2rem)] max-w-[600px] rounded-3xl bg-white p-0 backdrop:bg-gray-900/50">
    <form id="career-form" method="POST" action="{{ route('admin.careers.store') }}" class="max-h-[90vh] overflow-y-auto p-6 lg:p-10">
        @csrf
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" name="_career_id" value="">

        <div class="flex items-start justify-between gap-4">
            <h3 id="career-dialog-title" class="text-xl font-semibold text-gray-800">Tambah riwayat karier</h3>
            <button type="button" onclick="this.closest('dialog').close()" class="admin-icon-btn" aria-label="Tutup">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="mt-6 space-y-5">
            <div>
                <label for="career-company" class="admin-label">Instansi atau perusahaan <span class="text-error-600">*</span></label>
                <input id="career-company" type="text" name="company" required class="admin-input" placeholder="Contoh: PT Global Tiket Network">
                @error('company')<p class="admin-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="career-role" class="admin-label">Jabatan <span class="text-error-600">*</span></label>
                <input id="career-role" type="text" name="role" required class="admin-input" placeholder="Contoh: Customer Operations & Systems Lead">
                @error('role')<p class="admin-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="career-period" class="admin-label">Periode <span class="text-error-600">*</span></label>
                <input id="career-period" type="text" name="period" required class="admin-input" placeholder="Contoh: 2021 - 2024">
                @error('period')<p class="admin-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="career-description" class="admin-label">Tanggung jawab dan pencapaian</label>
                <textarea id="career-description" name="description" rows="5" class="admin-textarea" placeholder="Peran operasional, sistem yang dibangun, atau tim yang dipimpin."></textarea>
                @error('description')<p class="admin-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-8 flex flex-wrap justify-end gap-3">
            <button type="button" onclick="this.closest('dialog').close()" class="admin-btn-outline">Batal</button>
            <button type="submit" id="career-submit" class="admin-btn-primary">Simpan riwayat</button>
        </div>
    </form>
</dialog>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15/Sortable.min.js"></script>
<script>
const careerDialog = document.getElementById('career-dialog');
const careerForm = document.getElementById('career-form');
const storeUrl = @js(route('admin.careers.store'));
const updateUrl = @js(route('admin.careers.update', '__ID__'));

function openCareerModal(career = {}, keepErrors = false) {
    const editing = Boolean(career.id);
    careerForm.action = editing ? updateUrl.replace('__ID__', career.id) : storeUrl;
    careerForm.elements['_method'].value = editing ? 'PUT' : 'POST';
    careerForm.elements['_career_id'].value = career.id ?? '';
    ['company', 'role', 'period', 'description'].forEach((f) => { careerForm.elements[f].value = career[f] ?? ''; });
    if (!keepErrors) careerDialog.querySelectorAll('.admin-error').forEach((el) => el.remove());
    document.getElementById('career-dialog-title').textContent = editing ? 'Edit riwayat karier' : 'Tambah riwayat karier';
    document.getElementById('career-submit').textContent = editing ? 'Simpan perubahan' : 'Simpan riwayat';
    careerDialog.showModal();
}

document.querySelectorAll('[data-career-new]').forEach((btn) => btn.addEventListener('click', () => openCareerModal()));
careerDialog.addEventListener('click', (e) => { if (e.target === careerDialog) careerDialog.close(); });

@if($errors->any())
    openCareerModal(@js([
        'id' => old('_career_id'),
        'company' => old('company'),
        'role' => old('role'),
        'period' => old('period'),
        'description' => old('description'),
    ]), true);
@endif

const careerList = document.getElementById('career-list');
const orderStatus = document.getElementById('career-order-status');

function saveCareerOrder() {
    orderStatus.className = 'admin-help mt-0 px-5 pb-3 sm:px-6';
    orderStatus.textContent = 'Menyimpan urutan...';
    fetch(@js(route('admin.careers.reorder')), {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ ids: [...careerList.children].map((li) => Number(li.dataset.id)) }),
    })
        .then((res) => {
            if (!res.ok) throw new Error();
            orderStatus.textContent = 'Urutan tersimpan dan langsung dipakai di halaman utama dan resume.';
        })
        .catch(() => {
            orderStatus.className = 'admin-error mt-0 px-5 pb-3 sm:px-6';
            orderStatus.textContent = 'Urutan gagal disimpan. Muat ulang halaman, lalu coba lagi.';
        });
}

if (careerList) {
    Sortable.create(careerList, {
        handle: '.career-handle',
        animation: 150,
        ghostClass: 'bg-brand-50',
        onEnd: (e) => { if (e.oldIndex !== e.newIndex) saveCareerOrder(); },
    });

    // Keyboard alternative to dragging, plus the edit button.
    careerList.addEventListener('click', (e) => {
        const move = e.target.closest('[data-move]');
        if (move) {
            const item = move.closest('li');
            const sibling = move.dataset.move === '-1' ? item.previousElementSibling : item.nextElementSibling;
            if (!sibling) return;
            move.dataset.move === '-1' ? sibling.before(item) : sibling.after(item);
            move.focus();
            saveCareerOrder();
            return;
        }
        if (e.target.closest('[data-edit]')) {
            openCareerModal(JSON.parse(e.target.closest('li').dataset.career));
        }
    });
}
</script>
@endpush
@endsection
