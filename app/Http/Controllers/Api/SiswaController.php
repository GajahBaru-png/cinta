<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;


class SiswaController extends Controller
{
     public function index()
    {
        return Siswa::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|unique:siswa,nis',
            'gender' => 'required|in:L,P',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:255',
            'email' => 'required|email|unique:siswa,email',
            'foto' => 'nullable|string|max:255',  // Handle foto as optional
            'status_pkl' => 'required|boolean',  // Ensure status_pkl is a boolean (0 or 1)
        ]);

        return response()->json(Siswa::create($validated), 201);
    }

    public function show($id)
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json(['message' => 'Siswa tidak ditemukan'], 404);
        }

        return response()->json($siswa);
    }

    public function update(Request $request, $id)
    {
        try {
            $siswa = Siswa::findOrFail($id);

            $validated = $request->validate([
                'nama' => 'sometimes|required|string|max:255',
                'nis' => 'sometimes|required|string|max:255|unique:siswa,nis,' . $id,
                'gender' => 'sometimes|required|in:L,P',
                'alamat' => 'sometimes|required|string',
                'kontak' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:siswa,email,' . $id,
                'foto' => 'nullable|string|max:255',
                'status_pkl' => 'sometimes|required|boolean',
            ]);

            $siswa->fill($validated)->save();

            return response()->json($siswa);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server Error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return response()->json(null, 204);
    }

}
