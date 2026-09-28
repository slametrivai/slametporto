@extends('admin.layouts.app')

@section('title', 'Profil')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <form method="post" action="{{ route('profile.update') }}" class="admin-card">
        @csrf
        @method('patch')

        <div class="admin-card-header">
            <div>
                <h3 class="admin-card-title">Informasi profil</h3>
                <p class="admin-card-desc">Nama dan email yang dipakai untuk masuk ke CMS.</p>
            </div>
        </div>

        <div class="admin-card-body">
            @if(session('status') === 'profile-updated')
                <div class="admin-alert-success" role="status">Profil tersimpan.</div>
            @endif

            <div>
                <label for="name" class="admin-label">Nama <span class="text-error-600">*</span></label>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" class="admin-input">
                @error('name')<p class="admin-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="admin-label">Email <span class="text-error-600">*</span></label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="admin-input">
                @error('email')<p class="admin-error">{{ $message }}</p>@enderror

                @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <p class="admin-help">
                        Email ini belum diverifikasi.
                        <button form="send-verification" class="admin-link">Kirim ulang email verifikasi</button>
                    </p>
                    @if(session('status') === 'verification-link-sent')
                        <p class="mt-1.5 text-theme-xs text-success-700" role="status">Link verifikasi baru sudah dikirim ke email Anda.</p>
                    @endif
                @endif
            </div>
        </div>

        <div class="flex justify-end border-t border-gray-100 p-5 sm:p-6">
            <button type="submit" class="admin-btn-primary">Simpan profil</button>
        </div>
    </form>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
        @csrf
    </form>

    <form method="post" action="{{ route('password.update') }}" class="admin-card">
        @csrf
        @method('put')

        <div class="admin-card-header">
            <div>
                <h3 class="admin-card-title">Ganti kata sandi</h3>
                <p class="admin-card-desc">Pakai kata sandi yang panjang dan tidak dipakai di layanan lain.</p>
            </div>
        </div>

        <div class="admin-card-body">
            @if(session('status') === 'password-updated')
                <div class="admin-alert-success" role="status">Kata sandi tersimpan.</div>
            @endif

            <div>
                <label for="current_password" class="admin-label">Kata sandi saat ini</label>
                <input id="current_password" type="password" name="current_password" autocomplete="current-password" class="admin-input">
                @error('current_password', 'updatePassword')<p class="admin-error">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="password" class="admin-label">Kata sandi baru</label>
                    <input id="password" type="password" name="password" autocomplete="new-password" class="admin-input">
                    @error('password', 'updatePassword')<p class="admin-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="admin-label">Ulangi kata sandi baru</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" class="admin-input">
                    @error('password_confirmation', 'updatePassword')<p class="admin-error">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end border-t border-gray-100 p-5 sm:p-6">
            <button type="submit" class="admin-btn-primary">Simpan kata sandi</button>
        </div>
    </form>

    <form method="post" action="{{ route('profile.destroy') }}" class="admin-card border-error-500">
        @csrf
        @method('delete')

        <div class="admin-card-header">
            <div>
                <h3 class="admin-card-title">Hapus akun</h3>
                <p class="admin-card-desc">Akun dan akses ke CMS ini hilang permanen. Konten situs (proyek, artikel, klien) tidak ikut terhapus.</p>
            </div>
        </div>

        <div class="admin-card-body">
            <div>
                <label for="delete_password" class="admin-label">Masukkan kata sandi untuk konfirmasi</label>
                <input id="delete_password" type="password" name="password" required autocomplete="current-password" class="admin-input sm:max-w-sm">
                @error('password', 'userDeletion')<p class="admin-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex justify-end border-t border-gray-100 p-5 sm:p-6">
            <button type="submit" class="admin-btn-danger">Hapus akun permanen</button>
        </div>
    </form>
</div>
@endsection
