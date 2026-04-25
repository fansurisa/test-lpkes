@extends('layouts.app')

@section('title', 'Katalog Pelatihan')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="section-title">Katalog Pelatihan</h1>
            <p class="text-gray-500 mt-1">Event dan E-Course untuk tenaga kesehatan dan masyarakat umum</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Sidebar Filter --}}
            <aside class="w-full lg:w-64 flex-shrink-0">
                <form method="GET" action="{{ route('catalog.index') }}" id="filterForm">
                    <div class="card p-5 space-y-5">
                        {{-- Search --}}
                        <div>
                            <label class="label">Cari Pelatihan</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text" name="search" class="input pl-9" placeholder="Kata kunci..."
                                       value="{{ request('search') }}">
                            </div>
                        </div>

                        {{-- Kategori --}}
                        <div>
                            <label class="label">Kategori</label>
                            <select name="category" class="input">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tipe --}}
                        <div>
                            <label class="label">Tipe</label>
                            <div class="space-y-2">
                                @foreach(['Semua' => '', 'Event' => 'event', 'E-Course' => 'ecourse'] as $label => $value)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="type" value="{{ $value }}"
                                               {{ request('type', '') === $value ? 'checked' : '' }}
                                               class="text-primary-500 border-gray-300">
                                        <span class="text-sm text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Harga --}}
                        <div>
                            <label class="label">Harga</label>
                            <div class="space-y-2">
                                @foreach(['Semua' => '', 'Gratis' => 'free', 'Berbayar' => 'paid'] as $label => $value)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="price" value="{{ $value }}"
                                               {{ request('price', '') === $value ? 'checked' : '' }}
                                               class="text-primary-500 border-gray-300">
                                        <span class="text-sm text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- SKP --}}
                        <div>
                            <label class="label">SKP</label>
                            <div class="space-y-2">
                                @foreach(['Semua' => '', 'Ada SKP' => 'yes', 'Tanpa SKP' => 'no'] as $label => $value)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="skp" value="{{ $value }}"
                                               {{ request('skp', '') === $value ? 'checked' : '' }}
                                               class="text-primary-500 border-gray-300">
                                        <span class="text-sm text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn-primary w-full">
                            Terapkan Filter
                        </button>
                        @if(request()->anyFilled(['search', 'category', 'type', 'price', 'skp']))
                            <a href="{{ route('catalog.index') }}" class="btn-secondary w-full text-center text-sm">
                                Reset Filter
                            </a>
                        @endif
                    </div>
                </form>
            </aside>

            {{-- Grid --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm text-gray-500">
                        Menampilkan <strong>{{ $trainings->total() }}</strong> pelatihan
                    </p>
                </div>

                @if($trainings->isEmpty())
                    <div class="card p-16 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <p class="text-gray-500 font-medium">Tidak ada pelatihan ditemukan</p>
                        <p class="text-gray-400 text-sm mt-1">Coba ubah filter atau kata kunci pencarian</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                        @foreach($trainings as $training)
                            @include('components.training-card', ['training' => $training])
                        @endforeach
                    </div>
                    <div class="mt-8">
                        {{ $trainings->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
