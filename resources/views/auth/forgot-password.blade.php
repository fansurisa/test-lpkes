@extends('layouts.auth')

@section('title', 'Lupa Kata Sandi')

@section('content')
    <h2 class="text-2xl font-bold text-gray-900 mb-1">Lupa kata sandi?</h2>
    <p class="text-sm text-gray-500 mb-6">
        Masukkan email Anda dan kami akan mengirimkan link untuk mereset kata sandi.
    </p>

    @if(session('status'))
        <div class="alert-success mb-4">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-error mb-4">
            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <div class="form-group">
            <label for="email" class="label">Alamat Email</label>
            <input type="email" id="email" name="email" class="input"
                   value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
        </div>
        <button type="submit" class="btn-primary w-full">Kirim Link Reset</button>
        <p class="text-center text-sm text-gray-500">
            <a href="{{ route('login') }}" class="text-primary-600 font-medium">Kembali ke halaman masuk</a>
        </p>
    </form>
@endsection
