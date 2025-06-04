<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Industri;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class IndustriApiController extends Controller
{
    // Tampilkan list industri dengan pagination & search
    public function index(Request $request): JsonResponse
    {
        $search = $request->input('search');

        $query = Industri::query();

        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('bidang_usaha', 'like', "%{$search}%")
                  ->orWhere('kontak', 'like', "%{$search}%");
        }

        $industris = $query->latest()->paginate(10);

        return response()->json($industris);
    }

    // Simpan data industri baru
    public function store(Request $request): JsonResponse
    {
        // Contoh pengecekan role (sesuaikan sesuai implementasi role kamu)
        if ($request->user()->hasRole('guru')) {
            return response()->json(['message' => 'Hanya Siswa yang boleh menambah data industri'], 403);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string',
            'bidang_usaha' => 'nullable|string',
            'website' => 'nullable|string',
            'email' => 'nullable|string',
        ]);

        $industri = Industri::create($validated);

        return response()->json([
            'message' => 'Data industri berhasil ditambahkan',
            'data' => $industri
        ], 201);
    }

    // Tampilkan detail industri
    public function show($id): JsonResponse
    {
        $industri = Industri::find($id);

        if (!$industri) {
            return response()->json(['message' => 'Industri tidak ditemukan'], 404);
        }

        return response()->json($industri);
    }

    // Update data industri
    public function update(Request $request, $id): JsonResponse
    {
        $industri = Industri::find($id);

        if (!$industri) {
            return response()->json(['message' => 'Industri tidak ditemukan'], 404);
        }

        if ($request->user()->hasRole('guru')) {
            return response()->json(['message' => 'Hanya Siswa yang boleh mengubah data industri'], 403);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string',
            'bidang_usaha' => 'nullable|string',
            'website' => 'nullable|string',
            'email' => 'nullable|string',
        ]);

        $industri->update($validated);

        return response()->json([
            'message' => 'Data industri berhasil diupdate',
            'data' => $industri
        ]);
    }

    // Hapus data industri
    public function destroy(Request $request, $id): JsonResponse
    {
        $industri = Industri::find($id);

        if (!$industri) {
            return response()->json(['message' => 'Industri tidak ditemukan'], 404);
        }

        if ($request->user()->hasRole('guru')) {
            return response()->json(['message' => 'Hanya Siswa yang boleh menghapus data industri'], 403);
        }

        $industri->delete();

        return response()->json(['message' => 'Data industri berhasil dihapus']);
    }
}
