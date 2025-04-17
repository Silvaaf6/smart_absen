<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiKehadiranController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $kehadirans = Kehadiran::with('user')->get();
        } else {
            $kehadirans = Kehadiran::where('id_user', $user->id)->with('user')->get();
        }

        return response()->json([
            'status' => 'success',
            'data'   => $kehadirans,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'status'       => 'required|in:checkin,checkout,sakit',
            'surat_dokter' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $currentTime    = Carbon::now('Asia/Jakarta');
        $jamMasukBatas  = Carbon::createFromTime(7, 0, 0, 'Asia/Jakarta');
        $jamPulangBatas = Carbon::createFromTime(17, 0, 0, 'Asia/Jakarta');

        $user      = auth()->user();
        $kehadiran = Kehadiran::where('id_user', $user->id)
            ->whereDate('tanggal', $currentTime->format('Y-m-d'))
            ->first();

        if ($request->status === 'sakit') {
            if ($kehadiran) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Sudah absen hari ini, tidak bisa memilih absen sakit.',
                ], 400);
            }

            $filename = null;
            if ($request->hasFile('surat_dokter')) {
                $file     = $request->file('surat_dokter');
                $filename = 'surat_dokter_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/surat_dokter'), $filename);
            }

            $data = Kehadiran::create([
                'id_user'      => $user->id,
                'tanggal'      => $currentTime->format('Y-m-d'),
                'status'       => 'Sakit',
                'surat_dokter' => $filename,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Absen sakit berhasil',
                'data'    => $data,
            ]);
        }

        if ($request->status === 'checkin') {
            if ($kehadiran) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Anda sudah check-in hari ini.',
                ], 400);
            }

            $isLate = $currentTime->gt($jamMasukBatas);

            $data = Kehadiran::create([
                'id_user'   => $user->id,
                'tanggal'   => $currentTime->format('Y-m-d'),
                'jam_masuk' => $currentTime->format('H:i:s'),
                'status'    => $isLate ? 'Terlambat' : 'Hadir',
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Check-in berhasil',
                'data'    => $data,
            ]);
        }

        // STATUS CHECKOUT
        if ($request->status === 'checkout') {
            if (! $kehadiran) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Belum melakukan check-in.',
                ], 400);
            }

            if ($currentTime->lt($jamPulangBatas)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Belum bisa absen pulang sebelum jam 17:00.',
                ], 400);
            }

            $jamMasuk    = Carbon::parse($kehadiran->jam_masuk, 'Asia/Jakarta');
            $jamKeluar   = $currentTime;
            $durasiKerja = $jamMasuk->diff($jamKeluar);

            $kehadiran->update([
                'jam_keluar' => $jamKeluar->format('H:i:s'),
                'jam_kerja'  => $durasiKerja->h . ' jam ' . $durasiKerja->i . ' menit',
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Check-out berhasil',
                'data'    => $kehadiran,
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Status tidak valid.',
        ], 400);
    }
}
