@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
    {{-- Welcome --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Selamat datang, {{ Str::words($user->name, 2, '') }}!</h1>
        <p class="text-gray-500 text-sm mt-1">
            {{ now()->format('l, d F Y') }}
            @if($user->isNakes()) &bull; Tenaga Kesehatan @endif
        </p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 {{ $user->isNakes() ? 'md:grid-cols-4' : 'md:grid-cols-3' }} gap-4 mb-8">
        <div class="card p-4">
            <p class="text-xs text-gray-500 mb-1">Total Pelatihan</p>
            <p class="text-2xl font-bold text-gray-900">{{ $enrollments->count() }}</p>
        </div>
        <div class="card p-4">
            <p class="text-xs text-gray-500 mb-1">Aktif</p>
            <p class="text-2xl font-bold text-primary-600">{{ $enrollments->where('status', 'active')->count() }}</p>
        </div>
        <div class="card p-4">
            <p class="text-xs text-gray-500 mb-1">Selesai</p>
            <p class="text-2xl font-bold text-green-600">{{ $enrollments->where('status', 'completed')->count() }}</p>
        </div>
        @if($user->isNakes())
            <div class="card p-4">
                <p class="text-xs text-gray-500 mb-1">Total SKP</p>
                <p class="text-2xl font-bold text-purple-600">{{ $totalSkp }}</p>
            </div>
        @endif
    </div>

    {{-- Enrollments --}}
    <div class="card overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900">Pelatihan Saya</h2>
            <a href="{{ route('catalog.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                + Cari Pelatihan
            </a>
        </div>

        @if($enrollments->isEmpty())
            <div class="py-16 text-center">
                <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <p class="text-gray-500 font-medium">Belum ada pelatihan</p>
                <p class="text-gray-400 text-sm mt-1">Jelajahi katalog pelatihan kami</p>
                <a href="{{ route('catalog.index') }}" class="btn-primary mt-4 inline-flex">
                    Jelajahi Pelatihan
                </a>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($enrollments as $enrollment)
                    <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition-colors">
                        <img src="{{ $enrollment->training->thumbnail_url }}"
                             alt="{{ $enrollment->training->title }}"
                             class="w-14 h-14 rounded-lg object-cover flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 truncate">{{ $enrollment->training->title }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-xs text-gray-500">{{ $enrollment->training->category->name }}</span>
                                @if($enrollment->training->hasSkp())
                                    <span class="badge-skp">{{ $enrollment->training->skp_value }} SKP</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Didaftar: {{ $enrollment->enrolled_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            @php
                                $statusClasses = [
                                    'pending'   => 'bg-yellow-100 text-yellow-700',
                                    'active'    => 'bg-green-100 text-green-700',
                                    'completed' => 'bg-blue-100 text-blue-700',
                                ];
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$enrollment->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $enrollment->status_label }}
                            </span>
                            @if($enrollment->status === 'active' && $enrollment->training->pelataran_link)
                                <a href="{{ $enrollment->training->pelataran_link }}" target="_blank"
                                   class="text-primary-600 hover:text-primary-700 text-xs font-medium whitespace-nowrap">
                                    Buka →
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
