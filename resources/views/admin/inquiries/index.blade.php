@extends('admin.layouts.app')

@section('title', 'Pesan Masuk')

@section('content')
<div id="inquiry-manager" class="admin-card" x-data="inquiryManager()" @keydown.escape.window="close()">
    <div class="admin-card-header">
        <div>
            <h3 class="admin-card-title">Pesan dari form kontak</h3>
            <p class="admin-card-desc">Membuka detail pesan baru otomatis mengubah statusnya menjadi dibaca.</p>
        </div>
        <div class="flex flex-wrap items-end gap-3">
            <div>
                <label for="status-filter" class="admin-label">Status</label>
                <select id="status-filter" x-model="filter" @change="reload()" class="admin-select w-48">
                    <option value="all">Semua status</option>
                    <option value="new">Baru</option>
                    <option value="read">Dibaca</option>
                    <option value="responded">Dibalas</option>
                </select>
            </div>
            <a :href="@js(route('admin.inquiries.export')) + '?status=' + filter" class="admin-btn-outline">Ekspor CSV</a>
        </div>
    </div>

    <div class="border-t border-gray-100 p-5 sm:p-6">
        <table id="inquiries-table" class="w-full" tabindex="-1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Subjek</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>

    <div x-show="modalOpen" x-cloak @click.self="close()" class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto bg-gray-900/50 p-4">
        <div role="dialog" aria-modal="true" aria-labelledby="inquiry-title" class="relative w-full max-w-[600px] rounded-3xl bg-white p-6 lg:p-10">
            <button type="button" x-ref="closeBtn" @click="close()" class="admin-icon-btn absolute right-4 top-4" aria-label="Tutup detail pesan">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <h3 id="inquiry-title" class="pr-14 text-xl font-semibold text-gray-800" x-text="active.subject || 'Detail pesan'"></h3>
            <p class="mt-1 text-theme-sm text-gray-500" x-text="active.created_at"></p>

            <dl class="mt-6 grid grid-cols-1 gap-4 text-theme-sm sm:grid-cols-2">
                <div>
                    <dt class="text-gray-500">Pengirim</dt>
                    <dd class="mt-1 font-medium text-gray-800" x-text="active.name"></dd>
                </div>
                <div>
                    <dt class="text-gray-500">Email</dt>
                    <dd class="mt-1"><a :href="'mailto:' + active.email" class="admin-link break-all" x-text="active.email"></a></dd>
                </div>
            </dl>

            <div class="mt-6">
                <p class="admin-label">Isi pesan</p>
                {{-- message is escaped server-side: nl2br(e(...)) in InquiryController::show --}}
                <div class="max-h-60 overflow-y-auto break-words rounded-xl border border-gray-200 bg-gray-50 p-4 text-theme-sm leading-relaxed text-gray-800" x-html="active.message"></div>
            </div>

            <div class="mt-6">
                <label for="inquiry-status" class="admin-label">Status pesan</label>
                <select id="inquiry-status" x-model="active.status" @change="updateStatus()" class="admin-select sm:w-56">
                    <option value="new">Baru</option>
                    <option value="read">Dibaca</option>
                    <option value="responded">Dibalas</option>
                </select>
            </div>

            <div class="mt-8 flex flex-wrap justify-end gap-3">
                <button type="button" @click="close()" class="admin-btn-outline">Tutup</button>
                <a :href="'mailto:' + active.email + '?subject=' + encodeURIComponent('Re: ' + (active.subject || ''))" class="admin-btn-primary">Balas via email</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const inquiriesUrl = @js(route('admin.inquiries.index'));
let inquiriesTable = null;

function inquiryManager() {
    return {
        filter: 'all',
        modalOpen: false,
        active: {},
        returnFocus: null,
        reload() {
            inquiriesTable?.ajax.reload();
        },
        open(data, trigger) {
            this.active = data;
            this.returnFocus = trigger;
            this.modalOpen = true;
            this.$nextTick(() => this.$refs.closeBtn.focus());
        },
        close() {
            if (!this.modalOpen) return;
            this.modalOpen = false;
            // The trigger row may have been redrawn by the table reload.
            const target = this.returnFocus && document.body.contains(this.returnFocus) ? this.returnFocus : document.getElementById('inquiries-table');
            target?.focus();
        },
        updateStatus() {
            $.ajax({
                url: inquiriesUrl + '/' + this.active.id + '/status',
                type: 'PATCH',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: { status: this.active.status },
                success: () => inquiriesTable?.ajax.reload(null, false),
                error: () => Swal.fire('Status gagal disimpan', 'Terjadi kesalahan di server. Coba lagi.', 'error'),
            });
        },
    };
}

function viewInquiry(id, trigger) {
    $.getJSON(inquiriesUrl + '/' + id)
        .done(function (response) {
            if (!response.success) return;
            Alpine.$data(document.getElementById('inquiry-manager')).open(response.data, trigger);
            inquiriesTable?.ajax.reload(null, false);
        })
        .fail(function () {
            Swal.fire('Pesan gagal dimuat', 'Terjadi kesalahan di server. Coba lagi.', 'error');
        });
}

$(function () {
    inquiriesTable = $('#inquiries-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: inquiriesUrl,
            data: d => { d.status_filter = $('#status-filter').val(); },
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'subject', name: 'subject' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
@endsection
