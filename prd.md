# Product Requirements Document (PRD)

**Project Name:** Interactive Web Portfolio, Knowledge Base & Operations CMS  
**Owner / Profile:** Slamet Rivai (Operations & Systems Leader)  
**Tech Stack:** Laravel 12, Tailwind CSS, TailAdmin Dashboard, Yajra DataTables  
**Status:** Final Draft  

---

## 1. Executive Summary & Goals

### 1.1. Latar Belakang
Mentransformasikan portofolio presentasi statis menjadi aplikasi web modern berbasis dua modul utama:
1. **Public Web Experience:** Showcase interaktif untuk profil kepemimpinan sistem, linimasa riwayat karier, studi kasus sistem otomatisasi berbasis pipeline visual, katalog klien, sertifikasi resmi, artikel blog teknis, dan formulir kontak langsung.
2. **Back-Office Management System (CMS):** Dashboard internal berbasis **TailAdmin** dan tabel data **Yajra DataTables** untuk mengelola seluruh data proyek, artikel, mitra klien, kredensial, dan pesan masuk (*inquiries*) secara *server-side*.

### 1.2. Tujuan Proyek
* **Personal Branding & Kredibilitas:** Menampilkan rekam jejak 9+ tahun di bidang *Customer Operations*, *CRM*, *Workflow Automation*, dan *Business Systems* secara terukur[cite: 1].
* **Pengelolaan Konten Mandiri:** Memudahkan pembaruan studi kasus, alur diagram proses, status publikasi artikel blog, dan logo klien secara dinamis tanpa perlu mengubah kode sumber[cite: 1].
* **Lead Conversion:** Mengonversi pengunjung (klien atau rekruter) menjadi peluang kerja sama nyata melalui formulir kontak terintegrasi[cite: 1].

---

## 2. Tech Stack Architecture

| Layer | Teknologi | Keterangan Penggunaan |
| :--- | :--- | :--- |
| **Backend Framework** | Laravel 12 (PHP 8.2+) | MVC, Routing, Eloquent ORM, Validasi Request, File Storage |
| **Authentication** | Laravel Breeze | Keamanan sesi login admin |
| **DataTables Engine** | `yajra/laravel-datatables-oracle` | Server-side processing, pagination, pencarian, dan filtering |
| **Admin Template** | TailAdmin (Tailwind-based) | UI Back-office, layout sidebar/navbar, dark/light toggle |
| **Frontend Styling** | Tailwind CSS + `@tailwindcss/typography` + `@tailwindcss/forms` | Styling landing page & pembaca blog |
| **Markdown / Text Editor** | TipTap / EasyMDE | Penulisan artikel blog teknis |
| **Asset Pipeline** | Vite | Asset bundling & hot-module reloading |
| **Database Engine** | MySQL 8.0+ / PostgreSQL 15+ | Penyimpanan relasional |

---

## 3. Matriks Peran Pengguna & Hak Akses

| Fitur / Modul | Pengunjung Publik | Super Admin (Slamet Rivai)[cite: 1] |
| :--- | :---: | :---: |
| Akses Landing Page & Unduh CV[cite: 1] | View / Download | Kelola Konten via CMS |
| Baca & Cari Artikel Blog | View, Search, Filter | Full CRUD (Draft / Published) |
| Showcase Proyek & Visual Pipeline[cite: 1] | View & Filter Kategori | Full CRUD & Sort Order |
| Showcase Mitra Klien & Industri[cite: 1] | View Logo Grid & Hover | Full CRUD (Upload Logo, Toggle Aktif) |
| Riwayat Karier & Kredensial[cite: 1] | View Only | Full CRUD |
| Formulir Kontak / Inquiry[cite: 1] | Submit Pesan | View, Filter, Export, Balas |

---

## 4. Design System & Panduan Gaya Frontend

### 4.1. Filosofi Visual
* **Arah Gaya:** *Clean Editorial & Modern Enterprise SaaS*. Terstruktur, presisi, minimalis, dan berbasis data[cite: 1].
* **Elemen Kartu:** Sudut membulat modern (`rounded-xl`), pembatas tipis (`border border-slate-200 dark:border-slate-800`), dan bayangan lembut (`shadow-sm`).
* **Visualisasi Pipeline:** Menggunakan *node-flow badge* horizontal dengan ikon panah transisi (contoh: *Customer Doc → OCR Engine → Structured Data → Ops System*)[cite: 1].

### 4.2. Palet Warna (Tailwind Configuration)
* **Primary / Interactive (Cobalt & Indigo):**
  * `brand-50`: `#EEF2FF` (Aksen badge & background hover)
  * `brand-500`: `#4F46E5` (Warna hover & aksen)
  * `brand-600`: `#4338CA` (Tombol utama & status aktif)
  * `brand-700`: `#3730A3`
* **Warna Netral (Slate Shades):**
  * Light Background: `#F8FAFC` (`bg-slate-50`)
  * Light Card/Surface: `#FFFFFF` (`bg-white`)
  * Dark Background: `#0F172A` (`dark:bg-slate-900`)
  * Dark Card/Surface: `#1E293B` (`dark:bg-slate-800`)
  * Garis Pembatas: `#E2E8F0` / `#334155` (`border-slate-200` / `dark:border-slate-700`)
* **Warna Semantik & Aksen Metrik:**
  * Metrik Sukses: `#10B981` (`emerald-500`) untuk efisiensi seperti `2 DAYS → ±30 MINS` atau `↑40% Productivity`[cite: 1].
  * Indikator Alur Proses: `#F59E0B` (`amber-500`).

### 4.3. Tipografi
* **UI Utama & Judul:** `Inter` atau `Plus Jakarta Sans`.
* **Data Metrik, Angka, Snippet Kode & Pipeline:** `JetBrains Mono` atau `Fira Code`.

---

## 5. Rincian Fitur & Modul Fungsional

### Modul A: Public Portfolio (Frontend)
1. **Hero & Professional Summary:**
   * Nama Slamet Rivai, headline "Operations & Systems Leader", domisili Bekasi, Indonesia, dan pernyataan proposisi nilai[cite: 1].
   * Tombol CTA: *"Let's Connect"* & *"Download Resume"*.
   * Statistik 4 pilar kompetensi utama: *Customer Operations*, *CRM*, *Workflow Automation*, *People Development*[cite: 1].
2. **Career Timeline Section:**
   * Menampilkan perjalanan karier secara kronologis: PT Global Tiket Network, PT Wisata Universal, PT Baitussalam Mandiri, PT Cipta Optima, PT Sumber Rezeki Exata, dan Consultant[cite: 1].
3. **Interactive Project Showcase:**
   * Filter tab: *All*, *Automation & OCR*, *CRM & Sales*, *WhatsApp API*, *HR Systems*, *Digital Experience*[cite: 1].
   * Kartu proyek memuat peran (*Role*), latar masalah (*The Challenge*), metrik hasil (*Project Impact*), dan diagram alur visual (*Workflow Steps*)[cite: 1].
4. **Interactive Technical Toolkit:**
   * Pengelompokan keahlian: *CRM & Engagement*, *Data & Analytics*, *Systems & Automation*, *Operations & Collaboration*, *Digital & Marketing*[cite: 1].
5. **Credentials & Certifications:**
   * Menampilkan lisensi BNSP Data Management (2025–2028), Certified Contact Center Team Leader Telexindo (Skor 90/100), dan EF SET English C2 Proficient (Skor 76/100) beserta tautan verifikasi[cite: 1].
6. **Clients & Ecosystem:**
   * Grid logo mitra/klien dari sektor Travel & Hospitality, Healthcare, SMEs, dan Education & NGO[cite: 1].
   * Efek interaktif: Monokrom (*grayscale*) berubah menjadi full-color saat cursor hover.
7. **Contact Form:**
   * Formulir pesan (Nama, Email, Subjek, Pesan) dengan rate-limiter dan validasi backend.

### Modul B: Tech Blog & Knowledge Base
1. **Blog Index (`/blog`):**
   * Direktori artikel dengan search bar, filter kategori, kartu artikel unggulan (*Featured Post*), dan estimasi waktu baca.
2. **Article Reader (`/blog/{slug}`):**
   * Format tipografi terstruktur dengan Tailwind Prose.
   * Blok kode teknis dengan pewarnaan sintaks (*syntax highlighting*).
   * Daftar isi dinamis (*Table of Contents*) dan tombol bagikan langsung ke LinkedIn/X.

### Modul C: Admin Back-Office (TailAdmin + Yajra DataTables)
1. **Dashboard Overview:** Metrik jumlah proyek aktif, total klien, artikel terpublikasi, dan pesan baru.
2. **Kelola Proyek:** CRUD studi kasus sistem dengan input dinamis untuk JSON metrik dampak dan JSON langkah diagram workflow[cite: 1].
3. **Kelola Klien:** CRUD mitra/klien meliputi input nama instansi, sektor industri, unggah file logo, dan toggle status aktif[cite: 1].
4. **Kelola Blog:** Editor teks kaya, upload cover image, slug generator otomatis, status (`draft`/`published`), dan isian meta SEO.
5. **Kelola Karier & Sertifikasi:** Form CRUD linimasa pekerjaan dan lisensi kompetensi[cite: 1].
6. **Kelola Pesan Masuk (Inquiries):** Yajra DataTables dengan filter status (`new`, `responded`), fitur ekspor data, dan tombol aksi balas via WhatsApp atau Email.

---

## 6. Skema Basis Data Lengkap (Migrations)

```sql
-- 1. Tabel Riwayat Karier
CREATE TABLE careers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    period VARCHAR(100) NOT NULL,
    role VARCHAR(150) NOT NULL,
    company VARCHAR(150) NOT NULL,
    description TEXT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- 2. Tabel Studi Kasus Proyek
CREATE TABLE projects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    category VARCHAR(100) NOT NULL,
    role VARCHAR(150) NOT NULL,
    challenge TEXT NOT NULL,
    solution TEXT NOT NULL,
    impact_highlights JSON NOT NULL, -- Format: [{"metric": "2 DAYS → ±30 MINS", "label": "Document Processing"}]
    workflow_steps JSON NOT NULL,    -- Format: ["Customer Doc", "OCR Engine", "Structured Data", "Ops System"]
    cover_image VARCHAR(255) NULL,
    is_featured BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- 3. Tabel Klien & Partner
CREATE TABLE clients (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    industry VARCHAR(100) NOT NULL, -- Travel & Hospitality, Healthcare, SMEs, Education & NGO
    logo VARCHAR(255) NOT NULL,      -- Path file di storage
    website_url VARCHAR(255) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- 4. Tabel Kategori Blog
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- 5. Tabel Artikel Blog
CREATE TABLE posts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    excerpt TEXT NOT NULL,
    content LONGTEXT NOT NULL,
    cover_image VARCHAR(255) NULL,
    status ENUM('draft', 'published') DEFAULT 'draft',
    views_count INT UNSIGNED DEFAULT 0,
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- 6. Tabel Sertifikasi & Kredensial
CREATE TABLE certifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    issuer VARCHAR(150) NOT NULL,
    score VARCHAR(50) NULL,
    valid_period VARCHAR(100) NULL,
    credential_url VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- 7. Tabel Pesan Masuk
CREATE TABLE inquiries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(255) NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'responded') DEFAULT 'new',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
7. Cetak Biru Implementasi Kode (Yajra & TailAdmin)
7.1. Controller Pattern Yajra DataTables (ClientController.php)
PHP
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Client::select(['id', 'name', 'industry', 'logo', 'is_active', 'sort_order']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('logo', function ($row) {
                    $url = asset('storage/' . $row->logo);
                    return '<div class="h-10 w-16 flex items-center justify-center p-1 bg-slate-50 dark:bg-slate-800 rounded border border-slate-200 dark:border-slate-700">
                                <img src="'.$url.'" alt="'.$row->name.'" class="max-h-full max-w-full object-contain">
                            </div>';
                })
                ->editColumn('is_active', function ($row) {
                    return $row->is_active 
                        ? '<span class="inline-flex rounded-full bg-emerald-500/10 px-2.5 py-1 text-xs font-medium text-emerald-500">Active</span>'
                        : '<span class="inline-flex rounded-full bg-slate-500/10 px-2.5 py-1 text-xs font-medium text-slate-400">Hidden</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="flex items-center space-x-3 text-sm">
                                <a href="'.route('admin.clients.edit', $row->id).'" class="text-brand-600 hover:underline">Edit</a>
                                <button onclick="deleteClient('.$row->id.')" class="text-rose-600 hover:underline">Delete</button>
                            </div>';
                })
                ->rawColumns(['logo', 'is_active', 'action'])
                ->make(true);
        }

        return view('admin.clients.index');
    }
}
7.2. Tampilan Blade TailAdmin (resources/views/admin/clients/index.blade.php)
HTML
@extends('admin.layouts.app')

@section('content')
<div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Partner & Client Directory</h3>
            <p class="text-xs text-slate-500">Kelola daftar logo klien yang tampil pada halaman utama publik.</p>
        </div>
        <a href="{{ route('admin.clients.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700 transition">
            + Tambah Klien
        </a>
    </div>

    <div class="overflow-x-auto">
        <table id="clients-table" class="w-full text-left text-sm text-slate-600 dark:text-slate-400">
            <thead>
                <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-xs uppercase tracking-wider text-slate-700 dark:text-slate-300">
                    <th class="py-3.5 px-4">#</th>
                    <th class="py-3.5 px-4">Logo</th>
                    <th class="py-3.5 px-4">Nama Klien</th>
                    <th class="py-3.5 px-4">Sektor Industri</th>
                    <th class="py-3.5 px-4">Urutan</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800"></tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#clients-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.clients.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'logo', name: 'logo', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'industry', name: 'industry' },
            { data: 'sort_order', name: 'sort_order' },
            { data: 'is_active', name: 'is_active' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
@endsection
8. Rencana Kerja Eksekusi (Sprint Breakdown)
Sprint 1 (Fondasi & Setup):

Inisialisasi framework Laravel 12, Vite, Tailwind CSS, dan paket dependensi (yajra/laravel-datatables-oracle, Laravel Breeze).

Konfigurasi layout dasar TailAdmin (Sidebar, Header, Dark Mode toggle).

Sprint 2 (Migrasi Database & Seeding Awal):

Eksekusi seluruh migrasi database (7 tabel).

Pembuatan seeder data riil portofolio (linimasa karier, 6 studi kasus otomatisasi, sertifikasi BNSP/Telexindo/EF SET, dan daftar sektor industri)[cite: 1].

Sprint 3 (Implementasi CMS & Yajra DataTables):

Implementasi modul CRUD Projects, Clients, Blog, Careers, dan Inquiries[cite: 1].

Pembuatan komponen form dinamis untuk array metrik hasil dan alur diagram proses[cite: 1].

Sprint 4 (Perakitan Public Frontend):

Pembuatan halaman landing page responsif dengan visualisasi pipeline alur sistem[cite: 1].

Pembuatan halaman direktori dan pembaca artikel blog menggunakan Tailwind Prose.

Sprint 5 (Pengujian, Optimasi & Peluncuran):

Optimasi kueri basis data (eager loading untuk mencegah N+1 query).

Konfigurasi metadata OpenGraph SEO (pratinjau share WhatsApp/LinkedIn).

Deployment ke server produksi (VPS Linux/Nginx) dan pembuatan symlink storage (php artisan storage:link).