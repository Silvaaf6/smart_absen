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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

        // Cek apakah user sudah mengajukan cuti pada hari yang sama
        $cekCuti = pengajuan_cuti::where('id_user', Auth::id())
            ->whereDate('tgl_pengajuan', now()->toDateString())
            ->exists();

        if ($cekCuti) {
            return redirect()->route('pengajuan_cuti.index')->with('error', 'Anda sudah mengajukan cuti hari ini!');
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
     * Display the specified resource.
     */
    public function show(pengajuan_cuti $pengajuan_cuti)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(pengajuan_cuti $pengajuan_cuti)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, pengajuan_cuti $pengajuan_cuti)
    {
        if (! Auth::user()->hasRole('admin')) {
            return redirect()->route('pengajuan_cuti.index')->with('error', 'Anda tidak memiliki izin.');
        }

        $status = $request->status;

        if (! in_array($status, ['diizinkan', 'tidak diizinkan'])) {
            return redirect()->route('pengajuan_cuti.index')->with('error', 'Status tidak valid.');
        }

        $pengajuan_cuti->update([
            'status' => $status,
        ]);

        Alert::success("Pengajuan cuti telah $status.", 'success')->autoClose(1000);
        return redirect()->route('pengajuan_cuti.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(pengajuan_cuti $pengajuan_cuti)
    {
        //
    }
}
