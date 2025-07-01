<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kamar;

class KamarController extends Controller
{
    public function index(){
        $kamar = Kamar::with('anak')->get();
        return response()->json([
            'status' => 'success',
            'data' => $kamar,
        ], 200);
    }


    public function store(Request $request)
{
    $validated = $request->validate([
        'nama' => 'required|string|max:100',
        'kapasitas' => 'required|integer|min:1'
    ]);

    $kamar = Kamar::create($validated);

    return response()->json([
        "status" => "success",
        'message' => 'Kamar berhasil ditambahkan.',
        'data' => $kamar
    ],201);
}

public function show($id)
{
    $kamar = Kamar::with('anak')->find($id);

    if (!$kamar) {
        return response()->json([
            'status' => 'error',
            'message' => 'Kamar tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'data' => $kamar
    ]);
}


public function update(Request $request, $id)
{
    $kamar = Kamar::find($id);

    if (!$kamar) {
        return response()->json([
            'status' => 'error',
            'message' => 'Kamar tidak ditemukan'
        ], 404);
    }

    $validated = $request->validate([
        'nama' => 'required|string|max:100',
        'kapasitas' => 'required|integer|min:1'
    ]);

    $kamar->update($validated);

    return response()->json([
        'status' => 'success',
        'message' => 'Kamar berhasil diperbarui',
        'data' => $kamar
    ]);
}


public function delete($id)
{
    $kamar = Kamar::find($id);

    if (!$kamar) {
        return response()->json([
            'status' => 'error',
            'message' => 'Kamar tidak ditemukan'
        ], 404);
    }

    $kamar->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Kamar berhasil dihapus'
    ]);
}

}
