<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guru;

class GuruApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guru = Guru::get();
        return response()->json($guru, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $guru = new Guru(); 
        $guru->nama = $request->nama;
        $guru->nip = $request->nip;
        $guru->gender = $request->gender;
        $guru->alamat = $request->alamat;
        $guru->kontak = $request->kontak;
        $guru->email = $request->email;
        $guru->save(); // menyimpan ke database
        return response()->json($guru, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $guru = Guru::find($id); 
        return response()->json($guru, 200); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $guru = Guru::find($id);
        if (!$guru) {
            return response()->json(['message' => 'Guru Tidak Ditemukan'], 404);
        }

        $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'nip' => 'sometimes|required|numeric|unique:gurus,nip,' . $guru->id,
            'gender' => 'sometimes|required|in:Laki-laki,Perempuan',
            'alamat' => 'sometimes|required|string',
            'kontak' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:gurus,email,' . $guru->id,
        ]);

        $guru->nama = $request->nama ?? $guru->nama;
        $guru->nip = $request->nip ?? $guru->nip;
        $guru->gender = $request->gender ?? $guru->gender;
        $guru->alamat = $request->alamat ?? $guru->alamat;
        $guru->kontak = $request->kontak ?? $guru->kontak;
        $guru->email = $request->email ?? $guru->email;
        $guru->save();

        return response()->json($guru, 200); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Guru::destroy($id); // menghapus baris dengan ID yang dimaksud
        return response()->json(["message"=>"Deleted"], 200);
    }
}