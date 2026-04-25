@extends('layouts.auth')

@section('title', 'Lengkapi Profil')

@section('content')
    <h2 class="text-2xl font-bold text-gray-900 mb-1">Lengkapi Profil Anda</h2>
    <p class="text-sm text-gray-500 mb-6">Informasi ini diperlukan untuk mengakses pelatihan</p>

    @if($errors->any())
        <div class="alert-error mb-4">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.complete.store') }}" class="space-y-4"
          x-data="{ userType: '{{ old('user_type', '') }}' }">
        @csrf

        <div class="form-group">
            <label for="name" class="label">Nama Lengkap</label>
            <input type="text" id="name" name="name" class="input"
                   value="{{ old('name', auth()->user()->name) }}" required>
        </div>

        <div class="form-group">
            <label for="phone" class="label">Nomor HP / WhatsApp</label>
            <input type="tel" id="phone" name="phone" class="input"
                   value="{{ old('phone') }}" placeholder="08xx-xxxx-xxxx" required>
        </div>

        <div class="form-group">
            <label class="label">Saya adalah</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="cursor-pointer">
                    <input type="radio" name="user_type" value="nakes" x-model="userType"
                           class="sr-only" {{ old('user_type') === 'nakes' ? 'checked' : '' }}>
                    <div :class="userType === 'nakes' ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-gray-200 text-gray-600 hover:border-gray-300'"
                         class="border-2 rounded-lg p-3 text-center transition-colors">
                        <div class="font-semibold text-sm">Tenaga Kesehatan</div>
                        <div class="text-xs mt-0.5">(Nakes)</div>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="user_type" value="non_nakes" x-model="userType"
                           class="sr-only" {{ old('user_type') === 'non_nakes' ? 'checked' : '' }}>
                    <div :class="userType === 'non_nakes' ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-gray-200 text-gray-600 hover:border-gray-300'"
                         class="border-2 rounded-lg p-3 text-center transition-colors">
                        <div class="font-semibold text-sm">Masyarakat Umum</div>
                        <div class="text-xs mt-0.5">(Non-Nakes)</div>
                    </div>
                </label>
            </div>
        </div>

        {{-- STR number (nakes only) --}}
        <div x-show="userType === 'nakes'" x-transition class="form-group">
            <label for="str_number" class="label">Nomor STR <span class="text-red-500">*</span></label>
            <input type="text" id="str_number" name="str_number" class="input"
                   value="{{ old('str_number') }}" placeholder="Nomor Surat Tanda Registrasi">
            <p class="text-xs text-gray-500 mt-1">Wajib diisi untuk Tenaga Kesehatan</p>
        </div>

        <div class="form-group">
            <label for="profession" class="label">Profesi</label>
            <select id="profession" name="profession" class="input" required>
                <option value="" disabled selected>Pilih profesi</option>
                <option value="dokter"       {{ old('profession') === 'dokter' ? 'selected' : '' }}>Dokter</option>
                <option value="perawat"      {{ old('profession') === 'perawat' ? 'selected' : '' }}>Perawat</option>
                <option value="bidan"        {{ old('profession') === 'bidan' ? 'selected' : '' }}>Bidan</option>
                <option value="apoteker"     {{ old('profession') === 'apoteker' ? 'selected' : '' }}>Apoteker</option>
                <option value="analis"       {{ old('profession') === 'analis' ? 'selected' : '' }}>Analis Kesehatan</option>
                <option value="radiografer"  {{ old('profession') === 'radiografer' ? 'selected' : '' }}>Radiografer</option>
                <option value="fisioterapis" {{ old('profession') === 'fisioterapis' ? 'selected' : '' }}>Fisioterapis</option>
                <option value="gizi"         {{ old('profession') === 'gizi' ? 'selected' : '' }}>Ahli Gizi</option>
                <option value="rekam_medis"  {{ old('profession') === 'rekam_medis' ? 'selected' : '' }}>Rekam Medis</option>
                <option value="lainnya"      {{ old('profession') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
        </div>

        <button type="submit" class="btn-primary w-full mt-2">
            Simpan & Lanjutkan
        </button>
    </form>
@endsection
