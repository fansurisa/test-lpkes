@extends('layouts.auth')

@section('title', 'Konfirmasi Kata Sandi')

@section('content')
    <h2 class="text-2xl font-bold text-gray-900 mb-3">Konfirmasi Kata Sandi</h2>
    <p class="text-gray-500 text-sm mb-6">
        Untuk keamanan, harap konfirmasi kata sandi Anda sebelum melanjutkan.
    </p>
    @if($errors->any())
        <div class="alert-error mb-4">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
    @endif
    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf
        <div class="form-group">
            <label for="password" class="label">Kata Sandi</label>
            <input type="password" id="password" name="password" class="input" required>
        </div>
        <button type="submit" class="btn-primary w-full">Konfirmasi</button>
    </form>
@endsection
