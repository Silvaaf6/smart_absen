<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jabatan;
use Illuminate\Support\Facades\Validator;

class ApiJabatanController extends Controller
{
    // Menampilkan semua data jabatan
    public function index()
    {
        $jabatan = Jabatan::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar jabatan berhasil diambil',
            'data' => $jabatan
        ], 200);
    }

    // Menyimpan data baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_jabatan' => 'required|unique:jabatans,nama_jabatan',
        ], [
            'nama_jabatan.required' => 'Nama jabatan wajib diisi.',
            'nama_jabatan.unique'   => 'Nama jabatan sudah ada.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $jabatan = Jabatan::create([
            'nama_jabatan' => $request->nama_jabatan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jabatan berhasil ditambahkan',
            'data' => $jabatan
        ], 201);
    }

    // Menampilkan satu jabatan
    public function show($id)
    {
        $jabatan = Jabatan::find($id);

        if (!$jabatan) {
            return response()->json([
                'success' => false,
                'message' => 'Jabatan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail jabatan',
            'data' => $jabatan
        ], 200);
    }

    // Update data jabatan
    public function update(Request $request, $id)
    {
        $jabatan = Jabatan::find($id);

        if (!$jabatan) {
            return response()->json([
                'success' => false,
                'message' => 'Jabatan tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_jabatan' => 'required|unique:jabatans,nama_jabatan,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $jabatan->update([
            'nama_jabatan' => $request->nama_jabatan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jabatan berhasil diperbarui',
            'data' => $jabatan
        ], 200);
    }

    // Menghapus jabatan
    public function destroy($id)
    {
        $jabatan = Jabatan::find($id);

        if (!$jabatan) {
            return response()->json([
                'success' => false,
                'message' => 'Jabatan tidak ditemukan'
            ], 404);
        }

        $jabatan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jabatan berhasil dihapus'
        ], 200);
    }
}
