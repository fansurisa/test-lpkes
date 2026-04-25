@extends('layouts.dashboard')

@section('title', 'Profil Saya')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola informasi pribadi Anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Avatar card --}}
        <div class="card p-6 text-center">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                 class="w-24 h-24 rounded-full object-cover mx-auto mb-3 ring-4 ring-primary-100">
            <h3 class="font-semibold text-gray-900">{{ $user->name }}</h3>
            <p class="text-sm text-gray-500">{{ $user->email }}</p>
            <div class="mt-2">
                @if($user->isNakes())
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                        Tenaga Kesehatan
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                        Masyarakat Umum
                    </span>
                @endif
            </div>
            @if($user->isNakes())
                <div class="mt-4 p-3 bg-gray-50 rounded-lg text-left">
                    <p class="text-xs text-gray-500">Nomor STR</p>
                    <p class="font-mono text-sm font-medium text-gray-900">{{ $user->str_number ?: '-' }}</p>
                </div>
            @endif
            <div class="mt-3 p-3 bg-gray-50 rounded-lg text-left">
                <p class="text-xs text-gray-500">Total SKP</p>
                <p class="font-bold text-lg text-purple-600">{{ $user->totalSkp() }}</p>
            </div>
        </div>

        {{-- Edit form --}}
        <div class="lg:col-span-2 card p-6">
            <h2 class="font-semibold text-gray-900 mb-6">Edit Informasi</h2>

            <form method="POST" action="{{ route('dashboard.profile.update') }}" class="space-y-4">
                @csrf
                @method('PATCH')

                @if($errors->any())
                    <div class="alert-error">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="name" class="label">Nama Lengkap</label>
                        <input type="text" id="name" name="name" class="input"
                               value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="phone" class="label">Nomor HP</label>
                        <input type="tel" id="phone" name="phone" class="input"
                               value="{{ old('phone', $user->phone) }}" placeholder="08xx-xxxx-xxxx" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="label">Email</label>
                    <input type="email" class="input bg-gray-50 cursor-not-allowed"
                           value="{{ $user->email }}" disabled>
                    <p class="text-xs text-gray-400 mt-1">Email tidak dapat diubah</p>
                </div>

                <div class="form-group">
                    <label for="profession" class="label">Profesi</label>
                    <select id="profession" name="profession" class="input" required>
                        <option value="" disabled>Pilih profesi</option>
                        @foreach([
                            'dokter' => 'Dokter',
                            'perawat' => 'Perawat',
                            'bidan' => 'Bidan',
                            'apoteker' => 'Apoteker',
                            'analis' => 'Analis Kesehatan',
                            'radiografer' => 'Radiografer',
                            'fisioterapis' => 'Fisioterapis',
                            'gizi' => 'Ahli Gizi',
                            'rekam_medis' => 'Rekam Medis',
                            'lainnya' => 'Lainnya',
                        ] as $val => $label)
                            <option value="{{ $val }}" {{ old('profession', $user->profession) === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($user->isNakes())
                    <div class="form-group">
                        <label for="str_number" class="label">Nomor STR</label>
                        <input type="text" id="str_number" name="str_number" class="input"
                               value="{{ old('str_number', $user->str_number) }}"
                               placeholder="Nomor Surat Tanda Registrasi">
                    </div>
                @endif

                <div class="pt-2">
                    <button type="submit" class="btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
