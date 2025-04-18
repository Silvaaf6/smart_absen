<?php
namespace App\Http\Controllers;

use Alert;
use App\Models\kehadiran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KehadiranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            $kehadirans = Kehadiran::with('user')->get();
        } else {
            $kehadirans = Kehadiran::where('id_user', auth()->user()->id)->with('user')->get();
        }

        $check_kehadiran = Kehadiran::whereDate('tanggal', Carbon::now('Asia/Jakarta')->format('Y-m-d'))
            ->where('id_user', auth()->user()->id)
            ->first();

        $hasKehadiran = $kehadirans->isNotEmpty();

        return view('admin.kehadiran.index', compact('kehadirans', 'check_kehadiran', 'hasKehadiran'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $check_kehadiran = Kehadiran::whereDate('tanggal', Carbon::now('Asia/Jakarta')->format('Y-m-d'))
            ->where('id_user', auth()->user()->id)
            ->first();

        return view('admin.kehadiran.index', compact('check_kehadiran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'surat_dokter' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $currentTime = Carbon::now('Asia/Jakarta');
        $jamMasukBatas = Carbon::createFromTime(7, 0, 0, 'Asia/Jakarta');
        $jamPulangBatas = Carbon::createFromTime(9, 15, 0, 'Asia/Jakarta');

        $kehadiran = Kehadiran::where('id_user', auth()->user()->id)
            ->whereDate('tanggal', $currentTime->format('Y-m-d'))
            ->first();

        if ($request->status == 'sakit' && $kehadiran) {
            Alert::error('Kamu sudah absen hari ini, tidak bisa memilih absen sakit.')->autoClose(1000);
            return redirect()->back();
        }

        if ($request->status == 'sakit') {
            if ($request->hasFile('surat_dokter')) {
                $file     = $request->file('surat_dokter');
                $filename = 'surat_dokter_' . auth()->user()->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/surat_dokter'), $filename);
            }

            $kehadiran              = new Kehadiran;
            $kehadiran->id_user     = auth()->user()->id;
            $kehadiran->tanggal     = $currentTime->format('Y-m-d');
            $kehadiran->status      = 'Sakit';
            $kehadiran->surat_dokter = $filename ?? null;
            $kehadiran->save();

            Alert::success('Absen sakit berhasil.', 'success')->autoClose(1000);
            return redirect()->route('kehadiran.index');
        }

        // **STATUS CHECK-IN**
        if ($request->status == 'checkin') {
            if ($kehadiran) {
                Alert::error('Gagal', 'Anda sudah melakukan check-in hari ini!')->autoClose(2000);
                return redirect()->back();
            }

            $isLate = $currentTime->gt(Carbon::createFromTime(7, 0, 0, 'Asia/Jakarta'));

            $kehadiran            = new Kehadiran;
            $kehadiran->id_user   = auth()->user()->id;
            $kehadiran->tanggal   = $currentTime->format('Y-m-d');
            $kehadiran->jam_masuk = $currentTime->format('H:i:s');
            $kehadiran->status    = $isLate ? 'Terlambat' : 'Hadir';
            $kehadiran->save();

            Alert::success('Sukses', 'Check-in berhasil')->autoClose(1000);
            return redirect()->back();
        } else {
            if ($currentTime->lessThan($jamPulangBatas)) {
            Alert::success('Anda belum bisa absen pulang sebelum jam 17:00.', 'error');
            return redirect()->back();
            }
        }

        if ($kehadiran) {
            $jamMasuk    = Carbon::parse($kehadiran->jam_masuk, 'Asia/Jakarta');
            $jamKeluar   = $currentTime;
            $durasiKerja = $jamMasuk->diff($jamKeluar);

            $kehadiran->jam_keluar = $jamKeluar->format('H:i:s');
            $kehadiran->jam_kerja  = $durasiKerja->h . ' jam ' . $durasiKerja->i . ' menit';
            $kehadiran->save();

            Alert::success('Check-out berhasil.', 'success');
            return redirect()->back();
        }

        return redirect()->back();
    }

    // public function sakit(Request $request)
    // {
    //     $request->validate([
    //         'surat_dokter' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
    //     ]);

    //     $currentTime = Carbon::now('Asia/Jakarta');
    //     $userId      = auth()->user()->id;

    //     // **Cek apakah user sudah absen hari ini**
    //     $kehadiranHariIni = Kehadiran::whereDate('tanggal', $currentTime->format('Y-m-d'))
    //         ->where('id_user', $userId)
    //         ->first();

    //     if ($kehadiranHariIni) {
    //         Alert::error('Gagal', 'Anda sudah melakukan absensi hari ini!')->autoClose(2000);
    //         return redirect()->back();
    //     }

    //     // Simpan file surat dokter
    //     if ($request->hasFile('surat_dokter')) {
    //         $file     = $request->file('surat_dokter');
    //         $fileName = time() . '_' . $file->getClientOriginalName();
    //         $filePath = $file->storeAs('surat_dokter', $fileName, 'public');
    //     }

    //     // Simpan kehadiran dengan status "Sakit"
    //     Kehadiran::create([
    //         'id_user'      => $userId,
    //         'status'       => 'Sakit',
    //         'tanggal'      => $currentTime->format('Y-m-d'),
    //         'jam_masuk'    => null,
    //         'jam_keluar'   => null,
    //         'jam_kerja'    => null,
    //         'surat_dokter' => $filePath ?? null,
    //     ]);

    //     Alert::success('Sukses', 'Status sakit telah disimpan!')->autoClose(1000);
    //     return redirect()->back();
    // }

    /**
     * Display the specified resource.
     */
    public function show(kehadiran $kehadiran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(kehadiran $kehadiran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, kehadiran $kehadiran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(kehadiran $kehadiran)
    {
        //
    }
}
