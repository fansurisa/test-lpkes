@extends('layouts.auth')

@section('title', 'Daftar')

@section('content')
    <h2 class="text-2xl font-bold text-gray-900 mb-1">Buat akun baru</h2>
    <p class="text-sm text-gray-500 mb-6">Bergabung dan mulai belajar bersama LPKESolutions</p>

    {{-- Google register --}}
    <a href="{{ route('auth.google') }}"
       class="flex items-center justify-center gap-3 w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors mb-5">
        <svg class="w-5 h-5" viewBox="0 0 24 24">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        Daftar dengan Google
    </a>

    <div class="relative mb-5">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200"></div>
        </div>
        <div class="relative flex justify-center text-xs text-gray-400">
            <span class="bg-white px-3">atau daftar dengan email</span>
        </div>
    </div>

    @if($errors->any())
        <div class="alert-error mb-4">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div class="form-group">
            <label for="name" class="label">Nama Lengkap</label>
            <input type="text" id="name" name="name" class="input" value="{{ old('name') }}"
                   placeholder="Nama Lengkap" required autocomplete="name">
        </div>
        <div class="form-group">
            <label for="email" class="label">Email</label>
            <input type="email" id="email" name="email" class="input" value="{{ old('email') }}"
                   placeholder="nama@email.com" required autocomplete="email">
        </div>
        <div class="form-group">
            <label for="password" class="label">Kata Sandi</label>
            <input type="password" id="password" name="password" class="input"
                   placeholder="Minimal 8 karakter" required autocomplete="new-password">
        </div>
        <div class="form-group">
            <label for="password_confirmation" class="label">Konfirmasi Kata Sandi</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="input"
                   placeholder="Ulangi kata sandi" required autocomplete="new-password">
        </div>
        <button type="submit" class="btn-primary w-full">
            Buat Akun
        </button>
        <p class="text-center text-xs text-gray-400">
            Dengan mendaftar, Anda menyetujui <a href="#" class="text-primary-600">Syarat &amp; Ketentuan</a> kami.
        </p>
    </form>

    <p class="text-center text-sm text-gray-500 mt-5">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-primary-700">Masuk</a>
    </p>
@endsection
