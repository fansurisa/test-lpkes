@extends('layouts.app')

@section('title', 'Keranjang Pelatihan')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Keranjang Saya</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $items->count() }} pelatihan tersimpan
            </p>
        </div>
        @if($items->isNotEmpty())
            <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Kosongkan semua keranjang?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="text-sm text-gray-500 hover:text-red-600 transition-colors inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                    </svg>
                    Kosongkan keranjang
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if($items->isEmpty())
        <div class="card p-16 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-1">Keranjang masih kosong</h3>
            <p class="text-sm text-gray-500 mb-6">Temukan pelatihan yang sesuai dan tambahkan ke keranjang.</p>
            <a href="{{ route('catalog.index') }}" class="btn-primary inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Jelajahi Katalog
            </a>
        </div>
    @else
        @php
            $payableCount = 0; $payableTotal = 0; $freeCount = 0;
            foreach ($items as $it) {
                $t = $it->training;
                $blocked = in_array($t->id, $enrolledIds)
                    || $t->isFull()
                    || in_array($t->event_status, ['selesai','ditutup'], true);
                if ($blocked) continue;
                if ($t->is_free) { $freeCount++; }
                else { $payableCount++; $payableTotal += (int) $t->price; }
            }
            $checkoutCount = $payableCount + $freeCount;
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Item list --}}
            <div class="lg:col-span-2 space-y-3">
                @foreach($items as $item)
                    @php
                        $training = $item->training;
                        $alreadyEnrolled = in_array($training->id, $enrolledIds);
                        $eventStatus     = $training->event_status;
                        $isFull          = $training->isFull();

                        $blocked   = $alreadyEnrolled || $isFull || in_array($eventStatus, ['selesai','ditutup'], true);
                        $blockMsg  = $alreadyEnrolled ? 'Sudah terdaftar'
                                   : ($isFull ? 'Kuota penuh'
                                   : ($eventStatus === 'selesai' ? 'Pelatihan selesai'
                                   : ($eventStatus === 'ditutup' ? 'Pendaftaran ditutup' : null)));
                    @endphp
                    <div class="card p-4 sm:p-5 flex gap-4 items-start {{ $blocked ? 'opacity-60' : '' }}">
                        {{-- Thumbnail --}}
                        <a href="{{ route('catalog.show', $training->slug) }}"
                           class="flex-shrink-0 block w-24 h-24 sm:w-28 sm:h-28 rounded-lg overflow-hidden bg-gray-100">
                            <img src="{{ $training->thumbnail_url }}" alt="{{ $training->title }}"
                                 class="w-full h-full object-cover">
                        </a>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-1.5 mb-1.5">
                                <span class="{{ $training->type === 'event' ? 'badge-event' : 'badge-ecourse' }} text-[10px]">
                                    {{ $training->type_label }}
                                </span>
                                @if($training->hasSkp())
                                    <span class="badge-skp text-[10px]">{{ $training->skp_value }} SKP</span>
                                @endif
                                <span class="text-[11px] font-medium text-primary-600">
                                    {{ $training->category->name }}
                                </span>
                            </div>

                            <a href="{{ route('catalog.show', $training->slug) }}"
                               class="font-semibold text-gray-900 hover:text-primary-600 transition-colors line-clamp-2 text-sm sm:text-base leading-snug">
                                {{ $training->title }}
                            </a>

                            <div class="mt-1.5 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-500">
                                @if($training->schedule)
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $training->schedule->format('d M Y') }}
                                    </span>
                                @endif
                                @if($training->duration)
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $training->duration }}
                                    </span>
                                @endif
                            </div>

                            <div class="mt-3 flex items-center justify-between gap-3 flex-wrap">
                                <span class="{{ $training->is_free ? 'text-green-600' : 'text-gray-900' }} font-bold">
                                    {{ $training->formatted_price }}
                                </span>

                                <div class="flex items-center gap-2">
                                    @if($blocked)
                                        <span class="text-[11px] font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-lg">
                                            {{ $blockMsg }}
                                        </span>
                                    @endif
                                    <form method="POST" action="{{ route('cart.remove', $training) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                title="Hapus dari keranjang"
                                                class="text-gray-400 hover:text-red-500 transition-colors p-1.5 -m-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Summary sidebar --}}
            <aside class="lg:col-span-1">
                <div class="card p-5 sticky top-24">
                    <h3 class="font-bold text-gray-900 mb-4">Ringkasan Pesanan</h3>

                    <dl class="space-y-2 text-sm mb-4">
                        <div class="flex justify-between text-gray-600">
                            <dt>Pelatihan dapat dibayar</dt>
                            <dd class="font-medium text-gray-900">{{ $payableCount }}</dd>
                        </div>
                        @if($freeCount > 0)
                            <div class="flex justify-between text-gray-600">
                                <dt>Pelatihan gratis</dt>
                                <dd class="font-medium text-green-600">{{ $freeCount }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between text-gray-600">
                            <dt>Subtotal</dt>
                            <dd class="font-medium text-gray-900">
                                {{ $payableTotal > 0 ? 'Rp ' . number_format($payableTotal, 0, ',', '.') : 'Rp 0' }}
                            </dd>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <dt>Biaya admin</dt>
                            <dd class="font-medium text-green-600">Rp 0</dd>
                        </div>
                    </dl>

                    <div class="border-t border-gray-100 pt-3 mb-5">
                        <div class="flex justify-between items-baseline font-bold text-gray-900">
                            <span>Total</span>
                            <span class="text-lg">
                                {{ $payableTotal > 0 ? 'Rp ' . number_format($payableTotal, 0, ',', '.') : 'GRATIS' }}
                            </span>
                        </div>
                    </div>

                    @if($checkoutCount === 0)
                        <button disabled
                                class="w-full bg-gray-100 text-gray-400 font-semibold py-2.5 px-4 rounded-xl cursor-not-allowed">
                            Tidak ada pelatihan yang dapat dibayar
                        </button>
                    @else
                        <a href="{{ route('cart.checkout') }}"
                           class="btn-primary w-full text-center inline-flex items-center justify-center gap-2 py-3">
                            Lanjut ke Pembayaran
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    @endif

                    <a href="{{ route('catalog.index') }}"
                       class="block text-center text-sm text-gray-500 hover:text-primary-600 transition-colors mt-4">
                        + Tambah pelatihan lain
                    </a>

                    <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-center gap-2 text-xs text-gray-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Pembayaran aman via Midtrans
                    </div>
                </div>
            </aside>

        </div>
    @endif
</div>
@endsection
