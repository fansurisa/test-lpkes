@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="card p-8 text-center">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Pendaftaran Berhasil!</h1>
        <p class="text-gray-500 mb-6">Selamat belajar. Akses pelatihan dapat dilihat di Dashboard.</p>

        @php $rows = $orders->count() > 0 ? $orders : $freeEnrollments; @endphp

        @if($rows->isNotEmpty())
            <ul class="text-left bg-green-50 border border-green-100 rounded-xl p-4 mb-6 space-y-3">
                @foreach($rows as $row)
                    @php $training = $row->training; @endphp
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 text-sm leading-snug">{{ $training->title }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $training->category->name }}</p>
                        </div>
                        @if(isset($row->amount))
                            <span class="text-xs font-semibold text-gray-700 whitespace-nowrap">
                                Rp {{ number_format($row->amount, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="text-[10px] font-semibold text-green-700 bg-white px-2 py-0.5 rounded-full">GRATIS</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('dashboard') }}" class="btn-primary inline-flex items-center justify-center gap-2">
                Buka Dashboard
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
            <a href="{{ route('catalog.index') }}" class="btn-outline inline-flex items-center justify-center gap-2">
                Jelajahi Pelatihan Lain
            </a>
        </div>
    </div>
</div>
@endsection
