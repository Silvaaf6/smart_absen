<?php
namespace App\Http\Controllers;

use App\Models\kehadiran;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tanggalMulai   = $request->get('tanggal_mulai');
        $tanggalSelesai = $request->get('tanggal_selesai');
        $idUser         = $request->get('id_user');

        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        if (auth()->user()->hasRole('admin')) {
            $kehadirans = Kehadiran::when($tanggalMulai && $tanggalSelesai, function ($query) use ($tanggalMulai, $tanggalSelesai) {
                return $query->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
            })
                ->when($idUser, function ($query) use ($idUser) {
                    return $query->where('id_user', $idUser);
                })
                ->get();
        } else {
            $kehadirans = Kehadiran::where('id_user', auth()->user()->id)
                ->when($tanggalMulai && $tanggalSelesai, function ($query) use ($tanggalMulai, $tanggalSelesai) {
                    return $query->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
                })
                ->get();
        }

        $totalJamKerja = $kehadirans->filter(function ($kehadiran) {
            return $kehadiran->status !== 'Sakit' && $kehadiran->jam_masuk && $kehadiran->jam_keluar;
        })->reduce(function ($carry, $kehadiran) {
            $jamMasuk = Carbon::parse($kehadiran->jam_masuk);

            if ($kehadiran->jam_keluar) {
                $jamKeluar   = Carbon::parse($kehadiran->jam_keluar);
                $durasiKerja = $jamMasuk->diffInMinutes($jamKeluar);

                // Format durasi ke dalam "X jam Y menit"
                $kehadiran->jam_kerja = floor($durasiKerja / 60) . ' jam ' . ($durasiKerja % 60) . ' menit';

                return $carry + $durasiKerja;
            }

            return $carry;
        }, 0);

// Ubah format total jam kerja menjadi "X jam Y menit"
        $totalJamKerja = floor($totalJamKerja / 60) . ' jam ' . ($totalJamKerja % 60) . ' menit';

        return view('admin.laporan.index', compact('kehadirans', 'tanggalMulai', 'tanggalSelesai', 'totalJamKerja', 'users', 'idUser'));
    }

    public function exportPdf(Request $request)
    {
        $tanggalMulai   = $request->get('tanggal_mulai');
        $tanggalSelesai = $request->get('tanggal_selesai');
        $idUser         = $request->get('id_user');

        // Ambil data kehadiran berdasarkan filter yang dipilih
        $kehadiransQuery = Kehadiran::query();

        if ($tanggalMulai && $tanggalSelesai) {
            $kehadiransQuery->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
        }

        if (auth()->user()->hasRole('admin')) {
            if ($idUser) {
                $kehadiransQuery->where('id_user', $idUser);
            }
        } else {
            // Jika bukan admin, hanya bisa melihat datanya sendiri
            $kehadiransQuery->where('id_user', auth()->user()->id);
        }

        $kehadirans = $kehadiransQuery->get();

        // Hitung total jam kerja
        $totalMenitKerja = $kehadirans->filter(function ($kehadiran) {
            return $kehadiran->status !== 'Sakit' && $kehadiran->jam_masuk && $kehadiran->jam_keluar;
        })->reduce(function ($carry, $kehadiran) {
            $jamMasuk  = Carbon::parse($kehadiran->jam_masuk);
            $jamKeluar = Carbon::parse($kehadiran->jam_keluar);

            return $carry + $jamMasuk->diffInMinutes($jamKeluar);
        }, 0);

        // Konversi total menit ke format "X jam Y menit"
        $totalJamKerja = floor($totalMenitKerja / 60) . ' jam ' . ($totalMenitKerja % 60) . ' menit';

        $waktuDibuat = now('Asia/Jakarta')->format('d-m-Y H:i:s');

        // Generate PDF
        $pdf = Pdf::loadView('admin.laporan.pdf', compact('kehadirans', 'tanggalMulai', 'tanggalSelesai', 'waktuDibuat', 'totalJamKerja'))
            ->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);

        // Download PDF
        return $pdf->download('laporan_absen_' . now()->format('YmdHis') . '.pdf');
    }

}
