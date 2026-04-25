<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileCompletionController extends Controller
{
    public function show()
    {
        if (Auth::user()->isProfileCompleted()) {
            return redirect()->route('dashboard');
        }
        return view('auth.complete-profile');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'phone'      => ['required', 'string', 'max:20'],
            'user_type'  => ['required', 'in:nakes,non_nakes'],
            'str_number' => ['required_if:user_type,nakes', 'nullable', 'string', 'max:50'],
            'profession' => ['required', 'string'],
        ], [
            'name.required'       => 'Nama lengkap wajib diisi.',
            'phone.required'      => 'Nomor telepon wajib diisi.',
            'user_type.required'  => 'Tipe pengguna wajib dipilih.',
            'str_number.required_if' => 'Nomor STR wajib diisi untuk Tenaga Kesehatan.',
            'profession.required' => 'Profesi wajib dipilih.',
        ]);

        $user = Auth::user();
        $user->update([
            'name'                 => $request->name,
            'phone'                => $request->phone,
            'user_type'            => $request->user_type,
            'str_number'           => $request->user_type === 'nakes' ? $request->str_number : null,
            'profession'           => $request->profession,
            'profile_completed_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Profil berhasil dilengkapi. Selamat datang di LPKESolutions!');
    }
}
