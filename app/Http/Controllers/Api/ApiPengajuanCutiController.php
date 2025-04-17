<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\pengajuan_cuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiPengajuanCutiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->hasRole('admin')) {
            // Pastikan mengambil semua data dengan user terkait
            $pengajuan_cuti = pengajuan_cuti::with('user')->get();
        } else {
            // User hanya melihat pengajuannya sendiri
            $pengajuan_cuti = pengajuan_cuti::where('id_user', Auth::id())->get();
        }

        return response()->json([
            'status' => 'success',
            'data'   => $pengajuan_cuti,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'kategori_cuti' => 'required|in:izin,sakit,cuti',
            'tgl_mulai'     => 'required|date',
            'tgl_selesai'   => 'required|date|after_or_equal:tgl_mulai',
            'alasan'        => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors(),
            ], 422);
        }

        // Cek apakah user sudah mengajukan cuti pada hari yang sama
        $cekCuti = pengajuan_cuti::where('id_user', Auth::id())
            ->whereDate('tgl_pengajuan', now()->toDateString()) // Cek berdasarkan tanggal hari ini
            ->exists();                                         // Jika ada, akan return true

        if ($cekCuti) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda sudah mengajukan cuti hari ini!',
            ], 400);
        }

        // Simpan pengajuan cuti
        $pengajuan_cuti = pengajuan_cuti::create([
            'id_user'       => Auth::id(),
            'tgl_pengajuan' => now(),
            'kategori_cuti' => $request->kategori_cuti,
            'tgl_mulai'     => $request->tgl_mulai,
            'tgl_selesai'   => $request->tgl_selesai,
            'alasan'        => $request->alasan,
            'status'        => 'menunggu konfirmasi',
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Pengajuan berhasil diajukan.',
            'data'    => $pengajuan_cuti,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, pengajuan_cuti $pengajuan_cuti)
    {
        if (! Auth::user()->hasRole('admin')) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki izin.',
            ], 403);
        }

        // Pastikan status yang dikirim valid
        $status = $request->status;

        if (! in_array($status, ['diizinkan', 'tidak diizinkan'])) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Status tidak valid.',
            ], 400);
        }

        // Update status di database
        $pengajuan_cuti->update([
            'status' => $status,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => "Pengajuan cuti telah $status.",
            'data'    => $pengajuan_cuti,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(pengajuan_cuti $pengajuan_cuti)
    {
        // Hapus data pengajuan cuti
        $pengajuan_cuti->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Pengajuan cuti berhasil dihapus.',
        ], 200);
    }
}
