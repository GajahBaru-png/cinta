<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Industri;
use App\Models\User;
use Illuminate\Http\Request;

class IndustriController extends Controller
{
    public function index(Request $request): Response
{
    $search = $request->input('search');

    $query = Industri::query();

    if ($search) {
        $query->where('nama', 'like', "%{$search}%")
              ->orWhere('alamat', 'like', "%{$search}%")
              ->orWhere('bidang_usaha', 'like', "%{$search}%")
              ->orWhere('kontak', 'like', "%{$search}%");
    }

    $industri = $query->latest()->paginate(9)->withQueryString();

    return Inertia::render('industri', [
        'industri' => $industri,
        'filters' => [
            'search' => $search,
        ],
        'userRole' => auth()->user()->getRoleNames()->first(),
    ]);
    }
    
    public function create()
    {
        return Inertia::render('IndustriCreatePage');
    }

    // Simpan data industri baru
    public function store(Request $request)
    {
        if (auth()->user()->hasRole('guru')) {
        abort(403, 'Hanya Siswa Yang Boleh');
    }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string',
            'bidang_usaha' => 'nullable|string',
            'website' => 'nullable|string',
            'email' => 'nullable|string',
        ]);


        Industri::create($validated);

        return redirect()->route('industri.index')->with('success', 'Data industri berhasil ditambahkan');
    }
}