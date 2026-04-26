@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
    <div class="min-h-[calc(100vh-16rem)] flex items-center justify-center px-4 py-16">
        <div class="max-w-lg w-full text-center">

            {{-- Illustration --}}
            <div class="relative mx-auto mb-8 w-48 h-48">
                <div class="absolute inset-0 bg-primary-100 rounded-full blur-2xl opacity-60"></div>
                <div class="relative flex items-center justify-center w-full h-full">
                    <span class="text-8xl font-extrabold tracking-tight bg-gradient-to-br from-primary-500 to-primary-700 bg-clip-text text-transparent">
                        404
                    </span>
                </div>
            </div>

            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-3">
                Halaman tidak ditemukan
            </h1>
            <p class="text-gray-500 text-sm md:text-base mb-8 leading-relaxed">
                Maaf, halaman yang Anda cari tidak tersedia, telah dipindahkan,
                atau tautannya sudah tidak berlaku.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}"
                   class="btn-outline inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('catalog.index') }}"
                   class="btn-primary inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Jelajahi Katalog
                </a>
            </div>

            <div class="mt-10 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-400">
                    Butuh bantuan?
                    <a href="{{ route('home') }}" class="text-primary-600 hover:underline font-medium">Kembali ke beranda</a>
                </p>
            </div>
        </div>
    </div>
@endsection
