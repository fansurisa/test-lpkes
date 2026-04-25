@extends('layouts.auth')

@section('title', 'Verifikasi Email')

@section('content')
    <div class="text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-3">Verifikasi Email</h2>
        <p class="text-gray-500 text-sm mb-6">
            Kami telah mengirimkan link verifikasi ke email Anda. Silakan cek inbox Anda.
        </p>
        @if(session('status') === 'verification-link-sent')
            <div class="alert-success mb-4">Link verifikasi baru telah dikirimkan.</div>
        @endif
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-primary w-full">Kirim Ulang Email Verifikasi</button>
        </form>
        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">Keluar</button>
        </form>
    </div>
@endsection
