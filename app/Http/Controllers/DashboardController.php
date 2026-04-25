<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\SkpRecord;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $enrollments = Enrollment::with('training.category')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $skpRecords = null;
        $totalSkp   = 0;
        if ($user->isNakes()) {
            $skpRecords = SkpRecord::with('training')
                ->where('user_id', $user->id)
                ->orderByDesc('completed_at')
                ->get();
            $totalSkp = $skpRecords->sum('skp_earned');
        }

        return view('dashboard.index', compact('user', 'enrollments', 'skpRecords', 'totalSkp'));
    }

    public function profile()
    {
        return view('dashboard.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'phone'      => ['required', 'string', 'max:20'],
            'str_number' => ['nullable', 'string', 'max:50'],
            'profession' => ['required', 'string'],
        ], [
            'name.required'       => 'Nama lengkap wajib diisi.',
            'phone.required'      => 'Nomor telepon wajib diisi.',
            'profession.required' => 'Profesi wajib dipilih.',
        ]);

        $user->update($request->only('name', 'phone', 'str_number', 'profession'));

        return redirect()->route('dashboard.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
