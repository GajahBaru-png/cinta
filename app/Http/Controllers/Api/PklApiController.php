<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PKL;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PklApiController extends Controller
{
    // Tampilkan list PKL dengan relasi dan pagination
    public function index(Request $request): JsonResponse
    {
        $search = $request->input('search');

        $query = PKL::with(['siswa', 'industri', 'guru']);

        if ($search) {
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            })->orWhereHas('industri', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        $pkls = $query->latest()->paginate(10);

        return response()->json($pkls);
    }

    // Simpan data PKL baru
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'industri_id' => 'required|exists:industri,id',
            'guru_id' => 'required|exists:guru,id',
            'mulai' => 'required|date',
            'selesai' => 'required|date|after_or_equal:mulai',
        ]);

        $pkl = PKL::create($validated);

        return response()->json([
            'message' => 'Data PKL berhasil ditambahkan',
            'data' => $pkl
        ], 201);
    }

    // Tampilkan detail PKL
    public function show($id): JsonResponse
    {
        $pkl = PKL::with(['siswa', 'industri', 'guru'])->find($id);

        if (!$pkl) {
            return response()->json(['message' => 'Data PKL tidak ditemukan'], 404);
        }

        return response()->json($pkl);
    }

    // Update data PKL
    public function update(Request $request, $id): JsonResponse
    {
        $pkl = PKL::find($id);

        if (!$pkl) {
            return response()->json(['message' => 'Data PKL tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'industri_id' => 'required|exists:industri,id',
            'guru_id' => 'required|exists:guru,id',
            'mulai' => 'required|date',
            'selesai' => 'required|date|after_or_equal:mulai',
        ]);

        $pkl->update($validated);

        return response()->json([
            'message' => 'Data PKL berhasil diupdate',
            'data' => $pkl
        ]);
    }

    // Hapus data PKL
    public function destroy($id): JsonResponse
    {
        $pkl = PKL::find($id);

        if (!$pkl) {
            return response()->json(['message' => 'Data PKL tidak ditemukan'], 404);
        }

        $pkl->delete();

        return response()->json(['message' => 'Data PKL berhasil dihapus']);
    }
}
