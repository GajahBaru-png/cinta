<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\PKL;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $siswa = Siswa::where('email', $user->email)->first();
        $mulai = PKL::where('siswa_id', $siswa?->id)->value('mulai');
        $selesai = PKL::where('siswa_id', $siswa?->id)->value('selesai');

        $pkl = null;
        if ($siswa) {
            $pkl = PKL::with(['guru', 'industri'])
                ->where('siswa_id', $siswa->id)
                ->latest()
                ->first();
        }

        return Inertia::render('dashboard', [
            'mulai' => $mulai,
            'selesai' => $selesai,
            'user' => $user,
            'siswa' => $siswa,
            'status_pkl' => $siswa?->status_pkl,
            'pkl' => $pkl,
        ]);
    }
}
