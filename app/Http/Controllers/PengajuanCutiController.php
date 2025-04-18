<?php
namespace App\Http\Controllers;

use Alert;
use App\Models\pengajuan_cuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanCutiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->hasRole('admin')) {
            $pengajuan_cuti = pengajuan_cuti::with('user')->get();
        } else {
            $pengajuan_cuti = pengajuan_cuti::where('id_user', Auth::id())->get();
        }

        return view('admin.pengajuan.index', compact('pengajuan_cuti'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori_cuti' => 'required|in:izin,sakit,cuti',
            'tgl_mulai'     => 'required|date',
            'tgl_selesai'   => 'required|date|after_or_equal:tgl_mulai',
            'alasan'        => 'required',
        ]);

        // Cek total pengajuan cuti dalam tahun ini
        $jumlahCutiTahunIni = pengajuan_cuti::where('id_user', Auth::id())
            ->whereYear('tgl_pengajuan', now()->year)
            ->count();

        if ($jumlahCutiTahunIni >= 12) {
            return redirect()->route('pengajuan_cuti.index')->with('error', 'Pengajuan cuti sudah mencapai batas maksimal 12 kali dalam 1 tahun.');
        }

        // Simpan pengajuan cuti
        pengajuan_cuti::create([
            'id_user'       => Auth::id(),
            'tgl_pengajuan' => now(),
            'kategori_cuti' => $request->kategori_cuti,
            'tgl_mulai'     => $request->tgl_mulai,
            'tgl_selesai'   => $request->tgl_selesai,
            'alasan'        => $request->alasan,
            'status'        => 'menunggu konfirmasi',
        ]);

        Alert::success('Pengajuan berhasil diajukan.', 'success')->autoClose(1000);
        return redirect()->route('pengajuan_cuti.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, pengajuan_cuti $pengajuan_cuti)
{
    if (! Auth::user()->hasRole('admin')) {
        return redirect()->route('pengajuan.index')->with('error', 'Anda tidak memiliki izin.');
    }

    $status = $request->status;

    if (! in_array($status, ['diizinkan', 'tidak diizinkan'])) {
    return redirect()->route('pengajuan.index')->with('error', 'Status tidak valid.');
}
// Update status pengajuan cuti
    $pengajuan_cuti->update([
        'status' => $status,
    ]);

    \Alert::success("Pengajuan cuti telah $status.", 'success')->autoClose(1000);
    return redirect()->route('pengajuan_cuti.index');
}

}
