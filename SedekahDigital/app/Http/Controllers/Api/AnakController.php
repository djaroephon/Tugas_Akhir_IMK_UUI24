<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use Illuminate\Http\Request;

class AnakController extends Controller
{

    public function getAnakAll()
    {
        $anak = Anak::with('kamar')->get();

        if($anak->isEmpty()){
            return response()->json([
                'status' => 'error',
                'message' => 'Data anak tidak ditemukan.'
            ], 404);
        } else {
            return response()->json([
                'status' => 'success',
                'data' => $anak
            ], 200);
        }
    }

    public function UpdateAnak(Request $request, $id)
    {
        $anak = Anak::find($id);

        if (!$anak) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anak tidak ditemukan.'
            ], 404);
        }

        $validated = $request->validate([
            'kamar_id' => 'required|exists:kamar,id',
            'nama' => 'required|string|max:100',
            'usia' => 'required|integer|min:1',
            'latar_belakang' => 'nullable|string',
            'foto' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto-anak', 'public');
        }

        $anak->update($validated);

        return response()->json([
            'message' => 'Anak berhasil diperbarui.',
            'data' => $anak
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'kamar_id' => 'required|exists:kamar,id',
            'nama' => 'required|string|max:100',
            'usia' => 'required|integer|min:1',
            'latar_belakang' => 'nullable|string',
            'foto' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto-anak', 'public');
        }

        $anak = Anak::create($validated);

        return response()->json([
            'message' => 'Anak berhasil ditambahkan.',
            'data' => $anak
        ]);
    }

    public function hapus($id)
    {
        $anak = Anak::find($id);

        if (!$anak) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anak tidak ditemukan.'
            ], 404);
        }

        $anak->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Anak berhasil dihapus.'
        ], 200);
    }
}
