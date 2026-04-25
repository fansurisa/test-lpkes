@extends('layouts.auth')

@section('title', 'Reset Kata Sandi')

@section('content')
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Reset Kata Sandi</h2>

    @if($errors->any())
        <div class="alert-error mb-4">
            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="form-group">
            <label for="email" class="label">Email</label>
            <input type="email" id="email" name="email" class="input" value="{{ old('email', $request->email) }}" required>
        </div>
        <div class="form-group">
            <label for="password" class="label">Kata Sandi Baru</label>
            <input type="password" id="password" name="password" class="input" required placeholder="Minimal 8 karakter">
        </div>
        <div class="form-group">
            <label for="password_confirmation" class="label">Konfirmasi Kata Sandi Baru</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="input" required>
        </div>
        <button type="submit" class="btn-primary w-full">Simpan Kata Sandi Baru</button>
    </form>
@endsection
