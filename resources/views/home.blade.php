@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    {{-- Hero --}}
    <section class="bg-gradient-to-br from-primary-600 via-primary-500 to-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 text-white/90 text-sm font-medium mb-6">
                    <div class="w-2 h-2 rounded-full bg-green-300 animate-pulse"></div>
                    Platform Pelatihan Kesehatan Indonesia
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                    Tingkatkan Kompetensi<br>
                    <span class="text-yellow-300">Tenaga Kesehatan</span>
                </h1>
                <p class="text-lg text-white/80 mb-8 leading-relaxed max-w-xl">
                    Akses ratusan pelatihan, event, dan e-course berkualitas untuk tenaga kesehatan dan masyarakat umum. Dapatkan SKP langsung dari Pelataran Kemenkes.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white text-primary-600 font-semibold hover:bg-gray-50 transition-colors shadow-md">
                        Jelajahi Pelatihan
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border-2 border-white/50 text-white font-semibold hover:bg-white/10 transition-colors">
                            Daftar Gratis
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-3 md:grid-cols-3 gap-6 md:gap-10">
                <div class="text-center">
                    <div class="text-3xl font-extrabold text-primary-600">500+</div>
                    <div class="text-sm text-gray-500 mt-1">Pelatihan Tersedia</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-extrabold text-primary-600">10K+</div>
                    <div class="text-sm text-gray-500 mt-1">Peserta Terdaftar</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-extrabold text-primary-600">100%</div>
                    <div class="text-sm text-gray-500 mt-1">Bersertifikat SKP</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Recent trainings --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="section-title">Pelatihan Terbaru</h2>
                <p class="text-gray-500 text-sm mt-1">Pelatihan dan event terbaru yang baru saja ditambahkan</p>
            </div>
            <a href="{{ route('catalog.index') }}" class="btn-outline text-sm hidden sm:inline-flex">
                Lihat Semua
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($trainings as $training)
                @include('components.training-card', ['training' => $training])
            @empty
                <div class="col-span-full text-center py-16 text-gray-400">
                    Belum ada pelatihan tersedia.
                </div>
            @endforelse
        </div>
        <div class="mt-6 text-center sm:hidden">
            <a href="{{ route('catalog.index') }}" class="btn-outline">Lihat Semua Pelatihan</a>
        </div>
    </section>

    {{-- Features / Why --}}
    <section class="bg-primary-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="section-title">Kenapa LPKESolutions?</h2>
                <p class="text-gray-500 mt-2">Platform terpercaya untuk pengembangan kompetensi kesehatan</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach([
                    ['icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'title' => 'SKP Terakreditasi', 'desc' => 'Pelatihan kami terhubung langsung dengan Pelataran Kemenkes untuk perolehan SKP resmi.'],
                    ['icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'title' => 'Pembayaran Aman', 'desc' => 'Didukung Midtrans dengan berbagai metode pembayaran: QRIS, transfer bank, dan lainnya.'],
                    ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'title' => 'Event & E-Course', 'desc' => 'Tersedia dalam format event tatap muka maupun e-course online sesuai kebutuhan Anda.'],
                ] as $feature)
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
