<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KamarController;
use App\Http\Controllers\Api\AnakController;
use App\Http\Controllers\Api\DonasiController;
use App\Http\Controllers\Api\AuthController;
use App\Models\Kamar;
use App\Models\Anak;
use App\Models\Donasi;
use Illuminate\Support\Facades\DB;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/stats',function(){
    return response()->json([
        'kamar' => Kamar::count(),
        'anak' => Anak::count(),
    ]);
});
Route::get('/total-dana', function () {
    $data = DB::table('donasi')
    ->selectRaw("MONTH(created_at) as bulan, SUM(nominal) as total")
    ->where('jenis', 'dana')
    ->where('status', 'diterima')
    ->groupBy(DB::raw("MONTH(created_at)"))
    ->orderBy(DB::raw("MONTH(created_at)"))
    ->get();

    return response()->json($data);
});

Route::get('/total-barang', function () {
    $total = Donasi::where('jenis', 'barang')->where('status', 'diterima')->count();
    return response()->json(['total' => $total]);
});

Route::get('/kamar', [KamarController::class, 'index']); #bisa get kamar sama get Anak
Route::post('/kamar', [KamarController::class, 'store']);
Route::get('/kamar/{id}', [KamarController::class, 'show']);
Route::delete('/kamar/{id}', [KamarController::class, 'delete']);
Route::put('/kamar/{id}', [KamarController::class, 'update']);

Route::post('/anak', [AnakController::class, 'store']);
Route::put('/anak/{id}', [AnakController::class, 'UpdateAnak']);
Route::delete('/anak/{id}', [AnakController::class, 'hapus']);

Route::get('/donasi', [DonasiController::class, 'index']);
Route::post('/donasi', [DonasiController::class, 'store']);
Route::get('/donasi/menerima', [DonasiController::class, 'getMenerima']);
Route::get('/donasi/ditolak', [DonasiController::class, 'getDitolak']);
Route::get('/donasi/menunggu', [DonasiController::class, 'getMenunggu']);
Route::put('/donasi/{id}', [DonasiController::class, 'updateStatus']);
