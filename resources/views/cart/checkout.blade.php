@extends('layouts.app')

@section('title', 'Checkout Keranjang')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <nav class="text-sm text-gray-500 mb-5 flex items-center gap-1.5">
        <a href="{{ route('cart.index') }}" class="hover:text-primary-600">Keranjang</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-900 font-medium">Checkout</span>
    </nav>

    <h1 class="text-2xl font-extrabold text-gray-900 mb-1">Checkout Pendaftaran</h1>
    <p class="text-sm text-gray-500 mb-8">{{ $items->count() }} pelatihan akan diproses bersamaan.</p>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Items + user --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="card p-5">
                <h2 class="font-semibold text-gray-900 mb-4">Detail Pelatihan</h2>
                <ul class="divide-y divide-gray-100">
                    @foreach($items as $item)
                        @php $training = $item->training; @endphp
                        <li class="py-3 first:pt-0 last:pb-0 flex gap-3 items-start">
                            <img src="{{ $training->thumbnail_url }}" alt=""
                                 class="w-14 h-14 rounded-lg object-cover flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap gap-1 mb-1">
                                    <span class="{{ $training->type === 'event' ? 'badge-event' : 'badge-ecourse' }} text-[10px]">
                                        {{ $training->type_label }}
                                    </span>
                                    @if($training->hasSkp())
                                        <span class="badge-skp text-[10px]">{{ $training->skp_value }} SKP</span>
                                    @endif
                                </div>
                                <p class="font-semibold text-gray-900 text-sm leading-snug line-clamp-2">{{ $training->title }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $training->category->name }}</p>
                            </div>
                            <span class="font-semibold text-sm whitespace-nowrap {{ $training->is_free ? 'text-green-600' : 'text-gray-900' }}">
                                {{ $training->formatted_price }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="card p-5">
                <h2 class="font-semibold text-gray-900 mb-4">Data Peserta</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Nama</p>
                        <p class="font-medium text-gray-900">{{ auth()->user()->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Email</p>
                        <p class="font-medium text-gray-900">{{ auth()->user()->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">No. HP</p>
                        <p class="font-medium text-gray-900">{{ auth()->user()->phone ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Tipe</p>
                        <p class="font-medium text-gray-900">{{ auth()->user()->isNakes() ? 'Tenaga Kesehatan' : 'Masyarakat Umum' }}</p>
                    </div>
                </div>
                <a href="{{ route('dashboard.profile') }}" class="text-xs text-primary-600 hover:underline mt-3 inline-block">
                    Perbarui profil &rarr;
                </a>
            </div>
        </div>

        {{-- Sidebar --}}
        <aside>
            <div class="card p-5 sticky top-24">
                <h2 class="font-semibold text-gray-900 mb-4">Ringkasan Pembayaran</h2>

                <dl class="space-y-2 text-sm mb-4">
                    <div class="flex justify-between text-gray-600">
                        <dt>Pelatihan berbayar</dt>
                        <dd class="font-medium text-gray-900">{{ $payable->count() }}</dd>
                    </div>
                    @if($freeOnly->count() > 0)
                        <div class="flex justify-between text-gray-600">
                            <dt>Pelatihan gratis</dt>
                            <dd class="font-medium text-green-600">{{ $freeOnly->count() }}</dd>
                        </div>
                    @endif
                    <div class="flex justify-between text-gray-600">
                        <dt>Subtotal</dt>
                        <dd class="font-medium text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <dt>Biaya admin</dt>
                        <dd class="font-medium text-green-600">Rp 0</dd>
                    </div>
                </dl>

                <div class="border-t border-gray-100 pt-3 mb-5 flex justify-between items-baseline">
                    <span class="font-bold text-gray-900">Total</span>
                    <span class="font-extrabold text-primary-600 text-lg">
                        {{ $total > 0 ? 'Rp ' . number_format($total, 0, ',', '.') : 'GRATIS' }}
                    </span>
                </div>

                <form method="POST" action="{{ route('cart.pay') }}">
                    @csrf
                    <button type="submit" class="btn-primary w-full py-3">
                        {{ $total > 0 ? 'Bayar Sekarang' : 'Daftar Semua' }}
                    </button>
                </form>

                <p class="text-[11px] text-gray-400 text-center mt-3">
                    Dengan melanjutkan, Anda akan diarahkan ke halaman pembayaran Midtrans yang aman.
                </p>
            </div>
        </aside>

    </div>
</div>
@endsection
