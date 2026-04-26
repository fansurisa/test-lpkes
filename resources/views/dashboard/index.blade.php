@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
    {{-- Welcome + CTA --}}
    <div class="flex items-start justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Selamat datang, {{ Str::words($user->name, 2, '') }}!</h1>
            <p class="text-gray-500 text-sm mt-1">
                {{ now()->format('l, d F Y') }}
                @if($user->isNakes()) &bull; Tenaga Kesehatan @endif
            </p>
        </div>
        <a href="{{ route('catalog.index') }}"
           class="flex-shrink-0 inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Cari Pelatihan
        </a>
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
    <div class="card overflow-hidden" x-data="{ tab: 'all' }">
        {{-- Header + Tabs --}}
        <div class="px-6 pt-5 pb-0 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900 mb-4">Pelatihan Saya</h2>
            <div class="flex gap-1 -mb-px overflow-x-auto">
                @php
                    $tabs = [
                        'all'       => ['label' => 'Semua',                 'count' => $enrollments->count()],
                        'pending'   => ['label' => 'Menunggu Pembayaran',   'count' => $enrollments->where('status', 'pending')->count()],
                        'active'    => ['label' => 'Sedang Berlangsung',    'count' => $enrollments->where('status', 'active')->count()],
                        'completed' => ['label' => 'Selesai',               'count' => $enrollments->where('status', 'completed')->count()],
                    ];
                @endphp
                @foreach($tabs as $key => $tab)
                    <button @click="tab = '{{ $key }}'"
                            :class="tab === '{{ $key }}'
                                ? 'border-b-2 border-primary-600 text-primary-600 font-semibold'
                                : 'text-gray-500 hover:text-gray-700'"
                            class="flex items-center gap-1.5 px-3 py-2.5 text-sm whitespace-nowrap transition-colors">
                        {{ $tab['label'] }}
                        @if($tab['count'] > 0)
                            <span :class="tab === '{{ $key }}' ? 'bg-primary-100 text-primary-700' : 'bg-gray-100 text-gray-500'"
                                  class="text-xs px-1.5 py-0.5 rounded-full font-medium transition-colors">
                                {{ $tab['count'] }}
                            </span>
                        @endif
                    </button>
                @endforeach
            </div>
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
                    @php
                        $statusClasses = [
                            'pending'   => 'bg-yellow-100 text-yellow-700',
                            'active'    => 'bg-green-100 text-green-700',
                            'completed' => 'bg-blue-100 text-blue-700',
                        ];
                    @endphp
                    <div x-show="tab === 'all' || tab === '{{ $enrollment->status }}'"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100">
                        <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition-colors group select-none cursor-pointer"
                             @click="window.location='{{ route('catalog.show', $enrollment->training->slug) }}'">
                            <img src="{{ $enrollment->training->thumbnail_url }}"
                                 alt="{{ $enrollment->training->title }}"
                                 class="w-14 h-14 rounded-lg object-cover flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 group-hover:text-primary-600 truncate transition-colors">
                                    {{ $enrollment->training->title }}
                                </p>
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
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$enrollment->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $enrollment->status_label }}
                                </span>
                                @if(in_array($enrollment->status, ['active', 'completed']))
                                    @if($enrollment->training->pelataran_link)
                                        <a href="{{ $enrollment->training->pelataran_link }}" target="_blank"
                                           @click.stop
                                           class="inline-flex items-center gap-1 text-xs font-medium text-primary-600 hover:text-primary-700 whitespace-nowrap border border-primary-200 hover:border-primary-400 rounded-lg px-2.5 py-1 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            Buka Link
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400 italic whitespace-nowrap" @click.stop>Link belum tersedia</span>
                                    @endif
                                @endif
                                <svg class="w-4 h-4 text-gray-300 group-hover:text-primary-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Empty state per tab --}}
                <div x-show="tab !== 'all' && {{ $enrollments->count() }} > 0"
                     x-cloak>
                    @foreach(['pending' => 'menunggu pembayaran', 'active' => 'sedang berlangsung', 'completed' => 'selesai'] as $status => $label)
                        <div x-show="tab === '{{ $status }}' && {{ $enrollments->where('status', $status)->count() }} === 0"
                             class="py-12 text-center text-gray-400 text-sm">
                            Tidak ada pelatihan {{ $label }}
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
