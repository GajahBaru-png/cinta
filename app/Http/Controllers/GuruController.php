<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use Inertia\Inertia;

class GuruController extends Controller
{
    public function index()
    {
        $guru = Guru::all();
        return Inertia('guru', [
            'gurus' => $guru,
        ]);
    }
}
