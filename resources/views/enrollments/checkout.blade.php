@extends('layouts.app')

@section('title', 'Checkout - ' . $training->title)

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Checkout Pendaftaran</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Order summary --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Training info --}}
                <div class="card p-5">
                    <h2 class="font-semibold text-gray-900 mb-4">Detail Pelatihan</h2>
                    <div class="flex gap-4">
                        <img src="{{ $training->thumbnail_url }}" alt="{{ $training->title }}"
                             class="w-20 h-20 rounded-lg object-cover flex-shrink-0">
                        <div>
                            <div class="flex gap-1.5 mb-1">
                                <span class="{{ $training->type === 'event' ? 'badge-event' : 'badge-ecourse' }}">{{ $training->type_label }}</span>
                                @if($training->hasSkp())<span class="badge-skp">{{ $training->skp_value }} SKP</span>@endif
                            </div>
                            <p class="font-semibold text-gray-900">{{ $training->title }}</p>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $training->category->name }}</p>
                        </div>
                    </div>
                </div>

                {{-- User info --}}
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

            {{-- Payment sidebar --}}
            <div>
                <div class="card p-5 sticky top-24">
                    <h2 class="font-semibold text-gray-900 mb-4">Ringkasan Pembayaran</h2>
                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Harga pelatihan</span>
                            <span class="font-medium">{{ $training->formatted_price }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Biaya admin</span>
                            <span class="font-medium text-green-600">Rp 0</span>
                        </div>
                        <div class="border-t border-gray-100 pt-2 flex justify-between font-bold">
                            <span>Total</span>
                            <span class="text-primary-600">{{ $training->formatted_price }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('enrollments.pay', $training) }}">
                        @csrf
                        <button type="submit" id="payBtn" class="btn-primary w-full">
                            Bayar Sekarang
                        </button>
                    </form>

                    <div class="mt-4 text-center">
                        <p class="text-xs text-gray-400">Pembayaran aman diproses oleh</p>
                        <p class="text-xs font-semibold text-gray-600 mt-0.5">Midtrans</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
