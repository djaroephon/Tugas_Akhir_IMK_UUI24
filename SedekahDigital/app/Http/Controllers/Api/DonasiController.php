<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class DonasiController extends Controller
{
    public function index()
    {
        return response()->json(
            Donasi::with('kamar')->latest()->get()
        );
    }

     public function getMenerima()
    {
        $data = Donasi::with('kamar')->where('status', 'diterima')->latest()->get();
        return response()->json($data);
    }

    public function getDitolak()
    {
        $data = Donasi::with('kamar')->where('status', 'ditolak')->latest()->get();
        return response()->json($data);
    }

    public function getMenunggu()
    {
        $data = Donasi::with('kamar')->where('status', 'menunggu')->latest()->get();
        return response()->json($data);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak,menunggu',
        ]);

        $donasi = Donasi::findOrFail($id);
        $donasi->status = $request->status;
        $donasi->save();

        return response()->json([
            'status'  => 'Success',
            'message' => 'Status donasi berhasil diperbarui.',
            'data'    => $donasi
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'kamar_id'      => 'required|exists:kamar,id',
                'nama_donatur'  => 'required|string|max:100',
                'asal'          => 'required|string|max:100',
                'jenis'         => 'required|in:dana,barang',
                'keterangan'    => 'nullable|string',
                'bukti'         => 'nullable|image|max:2048',
                'nominal'       => 'nullable|numeric|min:1000',
                'nama_barang'   => 'nullable|string|max:100',
            ]);

            if ($request->jenis === 'dana' && !$request->filled('nominal')) {
                return response()->json([
                    'status'  => 'Error',
                    'message' => 'Nominal wajib diisi jika jenis donasi adalah dana.'
                ], 422);
            }

            if ($request->jenis === 'barang' && !$request->filled('nama_barang')) {
                return response()->json([
                    'status'  => 'Error',
                    'message' => 'Nama barang wajib diisi jika jenis donasi adalah barang.'
                ], 422);
            }

            // Upload file bukti jika ada
            if ($request->hasFile('bukti')) {
                $validated['bukti'] = $request->file('bukti')->store('bukti-donasi', 'public');
            }

            DB::beginTransaction();

            $donasi = Donasi::create($validated);

            DB::commit();

            return response()->json([
                "status"  => "Success",
                "message" => "Donasi berhasil dikirim! Akan diverifikasi oleh admin.",
                "data"    => $donasi
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status'  => 'Error',
                'message' => 'Validasi gagal.',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan donasi: ' . $e->getMessage());

            return response()->json([
                'status'  => 'Error',
                'message' => 'Terjadi kesalahan saat memproses donasi. Silakan coba lagi nanti.'
            ], 500);
        }
    }
}
