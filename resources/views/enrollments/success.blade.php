@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil')

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="card p-8 text-center">
            {{-- Success icon --}}
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <h1 class="text-2xl font-extrabold text-gray-900 mb-2">
                {{ $training->is_free ? 'Pendaftaran Berhasil!' : 'Pembayaran Berhasil!' }}
            </h1>

            <p class="text-gray-500 mb-6">
                @if($training->is_free)
                    Anda telah berhasil mendaftar. Selamat belajar!
                @else
                    Pembayaran berhasil. Anda sudah mendapat akses ke pelatihan ini.
                @endif
            </p>

            {{-- Training info --}}
            <div class="bg-gray-50 rounded-xl p-5 text-left mb-8">
                <div class="flex gap-4 items-start">
                    <img src="{{ $training->thumbnail_url }}" alt="{{ $training->title }}"
                         class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $training->title }}</p>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $training->category->name }}</p>
                        <span class="inline-flex items-center mt-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            Status: Aktif
                        </span>
                    </div>
                </div>
            </div>

            {{-- Instructions --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 text-left mb-8">
                <h3 class="font-semibold text-blue-900 mb-2">Cara Memulai Pelatihan</h3>
                <ol class="text-sm text-blue-800 space-y-1.5 list-decimal list-inside">
                    <li>Login ke Pelataran Kemenkes menggunakan akun Anda</li>
                    <li>Cari pelatihan <strong>{{ $training->title }}</strong></li>
                    <li>Ikuti pelatihan hingga selesai</li>
                    @if($training->hasSkp())
                        <li>SKP akan dikreditkan setelah pelatihan selesai</li>
                    @endif
                </ol>
                <p class="text-xs text-blue-600 mt-3">
                    Catatan: Silakan login ke Pelataran Kemenkes dan cari pelatihan ini untuk memulai.
                </p>
            </div>

            {{-- CTA --}}
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                @if($training->pelataran_link)
                    <a href="{{ $training->pelataran_link }}" target="_blank" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Buka Pelataran Kemenkes
                    </a>
                @endif
                <a href="{{ route('dashboard') }}" class="btn-outline">
                    Dashboard Saya
                </a>
            </div>
        </div>
    </div>
@endsection
