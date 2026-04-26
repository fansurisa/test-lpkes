@extends('layouts.app')

@section('title', $training->title)
@section('description', Str::limit(strip_tags($training->description), 160))

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col lg:flex-row gap-10">

            {{-- Main content --}}
            <div class="flex-1 min-w-0">
                {{-- Breadcrumb --}}
                <nav class="text-sm text-gray-500 mb-5 flex items-center gap-1.5">
                    <a href="{{ route('catalog.index') }}" class="hover:text-primary-600">Katalog</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <a href="{{ route('catalog.index') }}?category={{ $training->category_id }}" class="hover:text-primary-600">{{ $training->category->name }}</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-gray-900 font-medium truncate">{{ $training->title }}</span>
                </nav>

                {{-- Badges --}}
                <div class="flex flex-wrap gap-2 mb-3">
                    <span class="{{ $training->type === 'event' ? 'badge-event' : 'badge-ecourse' }} text-xs">
                        {{ $training->type_label }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                        {{ $training->category->name }}
                    </span>
                    @if($training->hasSkp())
                        <span class="badge-skp text-xs">{{ $training->skp_value }} SKP</span>
                    @endif
                </div>

                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-4 leading-tight">{{ $training->title }}</h1>

                {{-- Thumbnail --}}
                <div class="rounded-xl overflow-hidden mb-8 bg-gray-100 aspect-video">
                    <img src="{{ $training->thumbnail_url }}" alt="{{ $training->title }}"
                         class="w-full h-full object-cover">
                </div>

                {{-- Meta --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    @if($training->schedule)
                        <div class="card p-4 text-center">
                            <svg class="w-5 h-5 text-primary-500 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs text-gray-500">Tanggal</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $training->schedule->format('d M Y') }}</p>
                        </div>
                    @endif
                    @if($training->duration)
                        <div class="card p-4 text-center">
                            <svg class="w-5 h-5 text-primary-500 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-xs text-gray-500">Durasi</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $training->duration }}</p>
                        </div>
                    @endif
                    <div class="card p-4 text-center">
                        <svg class="w-5 h-5 text-primary-500 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <p class="text-xs text-gray-500">Peserta</p>
                        <p class="text-sm font-semibold text-gray-800">{{ number_format($training->enrollments_count) }}</p>
                    </div>
                    @if($training->hasSkp())
                        <div class="card p-4 text-center">
                            <svg class="w-5 h-5 text-green-500 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            <p class="text-xs text-gray-500">SKP</p>
                            <p class="text-sm font-semibold text-green-700">{{ $training->skp_value }} SKP</p>
                        </div>
                    @endif
                </div>

                {{-- Description --}}
                <div class="prose prose-sm prose-gray max-w-none mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">Deskripsi</h2>
                    {!! nl2br(e($training->description)) !!}
                </div>

                @if($training->objectives)
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">Tujuan Pembelajaran</h2>
                        <div class="prose prose-sm max-w-none text-gray-600">
                            {!! nl2br(e($training->objectives)) !!}
                        </div>
                    </div>
                @endif

                @if($training->hasSkp())
                    <div class="bg-green-50 border border-green-200 rounded-xl p-5 mb-8">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <div>
                                <h3 class="font-semibold text-green-800 mb-1">Informasi SKP</h3>
                                <p class="text-sm text-green-700">
                                    Pelatihan ini memberikan <strong>{{ $training->skp_value }} SKP</strong> bagi tenaga kesehatan.
                                    SKP akan diterima setelah menyelesaikan pelatihan melalui Pelataran Kemenkes.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Trainer --}}
                @if($training->trainer_name)
                    <div class="card p-5 mb-8">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Fasilitator / Narasumber</h2>
                        <div class="flex items-start gap-4">
                            @if($training->trainer_avatar)
                                <img src="{{ $training->trainer_avatar }}" alt="{{ $training->trainer_name }}"
                                     class="w-16 h-16 rounded-full object-cover flex-shrink-0">
                            @else
                                <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                    <span class="text-primary-600 font-bold text-lg">{{ substr($training->trainer_name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <p class="font-semibold text-gray-900">{{ $training->trainer_name }}</p>
                                @if($training->trainer_title)
                                    <p class="text-sm text-gray-500">{{ $training->trainer_title }}</p>
                                @endif
                                @if($training->trainer_bio)
                                    <p class="text-sm text-gray-600 mt-2">{{ $training->trainer_bio }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Related --}}
                @if($related->isNotEmpty())
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Pelatihan Terkait</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($related as $rel)
                                @include('components.training-card', ['training' => $rel])
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sticky sidebar --}}
            <div class="w-full lg:w-80 flex-shrink-0">
                <div class="card p-6 sticky top-24">
                    {{-- Price --}}
                    <div class="mb-5">
                        @if($training->is_free)
                            <p class="text-3xl font-extrabold text-green-600">GRATIS</p>
                        @else
                            <p class="text-3xl font-extrabold text-gray-900">{{ $training->formatted_price }}</p>
                        @endif
                    </div>

                    {{-- CTA --}}
                    @if($enrolled)
                        <div class="alert-success mb-4 text-center font-medium">
                            ✓ Anda sudah terdaftar
                        </div>
                        @if($training->pelataran_link)
                            <a href="{{ $training->pelataran_link }}" target="_blank"
                               class="btn-primary w-full mb-3">
                                Buka di Pelataran Kemenkes
                            </a>
                        @endif
                    @elseif($training->isFull())
                        <div class="alert-error mb-3 text-center">Kuota sudah penuh</div>
                    @else
                        @auth
                            @if($training->is_free)
                                <form method="POST" action="{{ route('enrollments.free', $training) }}">
                                    @csrf
                                    <button type="submit" class="btn-primary w-full mb-3">
                                        Daftar Gratis Sekarang
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('enrollments.checkout', $training) }}" class="btn-primary w-full mb-3 block text-center">
                                    Daftar Sekarang
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-primary w-full mb-3 block text-center">
                                Masuk untuk Mendaftar
                            </a>
                            <a href="{{ route('register') }}" class="btn-outline w-full block text-center">
                                Buat Akun Gratis
                            </a>
                        @endauth
                    @endif

                    {{-- Info --}}
                    <ul class="space-y-2 mt-4 text-sm text-gray-600">
                        @if($training->max_participants)
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Kuota: {{ number_format($training->max_participants) }} peserta
                            </li>
                        @endif
                        @if($training->hasSkp())
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-green-700 font-medium">{{ $training->skp_value }} SKP untuk Nakes</span>
                            </li>
                        @endif
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Pembayaran aman via Midtrans
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
@endsection
