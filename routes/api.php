<?php


use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\GuruApiController;
use App\Http\Controllers\Api\IndustriApiController;
use App\Http\Controllers\Api\PklApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $user = User::where('email', $request->email)->first();

    return response()->json([
        'token' => $user->createToken('api-token')->plainTextToken,
    ]);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('guru', 'App\Http\Controllers\Api\GuruApiController');
    Route::apiResource('siswa', SiswaController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
    
});

Route::prefix('/')->group(function () {
    Route::apiResource('guru', 'App\Http\Controllers\Api\GuruApiController');
    Route::apiResource('siswa', 'App\Http\Controllers\Api\SiswaController');
    Route::apiResource('industri', 'App\Http\Controllers\Api\IndustriApiController');
    Route::apiResource('pkl', 'App\Http\Controllers\Api\PklApiController');
});

