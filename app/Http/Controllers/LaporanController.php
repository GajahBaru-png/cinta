<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PKL;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Industri;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman utama Laporan PKL (daftar laporan).
     * Merender komponen LaporanMain.tsx.
     */
    public function index(Request $request)
    {
        $laporans = PKL::with(['siswa', 'guru', 'industri'])
            ->latest()
            ->paginate(10)
            ->through(function ($laporan) {
                $mulai = \Carbon\Carbon::parse($laporan->mulai);
                $selesai = \Carbon\Carbon::parse($laporan->selesai);
                $durasi = $mulai->diffInDays($selesai);

                return [
                    'id' => $laporan->id,
                    'mulai' => $laporan->mulai,
                    'selesai' => $laporan->selesai,
                    'siswa' => ['nama' => $laporan->siswa->nama],
                    'guru' => ['nama' => $laporan->guru->nama],
                    'industri' => ['nama' => $laporan->industri->nama],
                    'durasi' => $durasi . ' hari',
                ];
            })
            ->withQueryString();

        return Inertia::render('LaporanMain', [
            'laporans' => $laporans,
            'userRole' => auth()->user()->getRoleNames()->first(),
        ]);
    }


    /**
     * Menampilkan form untuk membuat laporan PKL baru.
     * Merender komponen laporan.tsx.
     */
    public function create()
    {
        $authUser = Auth::user();
        $siswasData = collect();
        

        if ($authUser) {
            $userRole = $authUser->getRoleNames()->first();
            if ($userRole === 'siswa') {
                $loggedInSiswa = Siswa::where("email", $authUser->email)->first();
                if ($loggedInSiswa) {
                    // CEK JIKA SISWA SUDAH PUNYA LAPORAN PKL AKTIF
                    if ($loggedInSiswa->status_pkl) {
                        // Jika sudah punya, redirect atau tampilkan pesan error
                        return redirect()->route('laporan.index')
                                         ->with('error', 'Anda sudah memiliki laporan PKL aktif dan tidak dapat membuat laporan baru.');
                    }
                    $siswasData = new Collection([$loggedInSiswa]);
                } else {
                    // Handle jika user siswa tidak punya record di tabel siswa
                    return redirect()->back()->with('error', 'Data siswa tidak ditemukan untuk akun Anda.');
                }
            } elseif ($userRole === 'admin' || $userRole === 'super-admin') {
                $siswasData = Siswa::where('status_pkl', false)->orderBy('nama')->get();
            }
        }

        $gurus = Guru::all();
        $industris = Industri::all();

        return Inertia::render('laporan', [
            'siswas' => $siswasData,
            'gurus' => $gurus,
            'industris' => $industris,
            'userRole' => $userRole, // Kirim userRole yang sudah diambil
            'user' => $authUser ? ['name' => $authUser->name, 'email' => $authUser->email] : null,
        ]);
    }

    /**
     * Menyimpan laporan PKL baru.
     */
    public function store(Request $request): RedirectResponse
{
    $authUser = Auth::user();
    $userRole = $authUser ? $authUser->getRoleNames()->first() : null;

    if ($userRole === 'guru') {
        abort(403, 'Guru tidak diizinkan membuat laporan PKL.');
    }

    // Validasi input
    $validated = $request->validate([
        'siswa_id' => 'required|exists:siswa,id',
        'guru_id' => 'required|exists:guru,id',
        'industri_id' => 'required|exists:industri,id',
        'mulai' => 'required|date',
        'selesai' => 'required|date|after_or_equal:mulai',
    ]);

    // Hitung durasi
    $mulai = Carbon::parse($validated['mulai']);
    $selesai = Carbon::parse($validated['selesai']);
    $durasi = $mulai->diffInDays($selesai);

    // Pastikan durasi minimal 90 hari
    if ($durasi < 90) {
        return redirect()->route('laporan.create')
                         ->with('error', 'Durasi PKL minimal adalah 90 hari.')
                         ->withInput();
    }

    $siswa = Siswa::find($validated['siswa_id']);

    // Validasi tambahan jika user adalah siswa
    if ($userRole === 'siswa') {
        $loggedInSiswa = Siswa::where("email", $authUser->email)->first();

        if (!$loggedInSiswa || $loggedInSiswa->id != $validated['siswa_id']) {
            return redirect()->route('laporan.create')
                             ->with('error', 'Anda tidak diizinkan membuat laporan untuk siswa lain.')
                             ->withInput();
        }

        if ($siswa && $siswa->status_pkl) {
            return redirect()->route('laporan.index')
                             ->with('error', 'Anda sudah memiliki laporan PKL aktif.');
        }
    }

    // Validasi tambahan jika admin membuat laporan
    if (($userRole === 'admin' || $userRole === 'super-admin') && $siswa && $siswa->status_pkl) {
        return redirect()->route('laporan.create')
                         ->with('error', 'Siswa yang dipilih sudah memiliki laporan PKL aktif.')
                         ->withInput();
    }

    DB::beginTransaction();

    try {
        // Simpan data laporan
        PKL::create([
            'siswa_id' => $validated['siswa_id'],
            'guru_id' => $validated['guru_id'],
            'industri_id' => $validated['industri_id'],
            'mulai' => $validated['mulai'],
            'selesai' => $validated['selesai'],
            // 'durasi' => $durasi, // Un-comment jika kolom durasi tersedia di tabel
        ]);

        // Update status PKL siswa
        if ($siswa) {
            $siswa->update(['status_pkl' => true]);
        }

        DB::commit();

        return redirect()->route('laporan.index')
                         ->with('success', 'Laporan PKL berhasil ditambahkan!');
    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()->route('laporan.create')
                         ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                         ->withInput();
    }
}

}