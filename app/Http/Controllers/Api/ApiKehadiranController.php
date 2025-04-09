<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiKehadiranController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $kehadirans = $user->hasRole('admin')
        ? Kehadiran::with('user')->get()
        : Kehadiran::where('id_user', $user->id)->with('user')->get();

        return response()->json([
            'status' => 'success',
            'data'   => $kehadirans,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'status'       => 'required|in:checkin,checkout,sakit',
            'surat_dokter' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user           = Auth::user();
        $currentTime    = Carbon::now('Asia/Jakarta');
        $tanggalHariIni = $currentTime->format('Y-m-d');

        $kehadiran = Kehadiran::where('id_user', $user->id)
            ->whereDate('tanggal', $tanggalHariIni)
            ->first();

        // STATUS: Sakit
        if ($request->status === 'sakit') {
            if ($kehadiran) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Kamu sudah absen hari ini, tidak bisa memilih absen sakit.',
                ], 409);
            }

            $filename = null;
            if ($request->hasFile('surat_dokter')) {
                $file = $request->file('surat_dokter');
                if (! $file->isValid()) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Upload surat dokter gagal.',
                    ], 500);
                }

                $filename = 'surat_dokter_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/surat_dokter', $filename);
            }

            $created = Kehadiran::create([
                'id_user'      => $user->id,
                'tanggal'      => $tanggalHariIni,
                'status'       => 'Sakit',
                'surat_dokter' => $filename,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Absen sakit berhasil.',
                'data'    => $created,
            ]);
        }

        // STATUS: Check-in
        if ($request->status === 'checkin') {
            if ($kehadiran) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Anda sudah melakukan check-in hari ini!',
                ], 409);
            }

            $isLate = $currentTime->gt(Carbon::createFromTime(7, 0, 0, 'Asia/Jakarta'));
            $status = $isLate ? 'Terlambat' : 'Hadir';

            $created = Kehadiran::create([
                'id_user'   => $user->id,
                'tanggal'   => $tanggalHariIni,
                'jam_masuk' => $currentTime->format('H:i:s'),
                'status'    => $status,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Check-in berhasil',
                'data'    => $created,
            ]);
        }

        // STATUS: Checkout
        if ($request->status === 'checkout') {
            $jamPulangBatas = Carbon::createFromTime(17, 0, 0, 'Asia/Jakarta');

            if ($currentTime->lessThan($jamPulangBatas)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Belum bisa check-out sebelum jam 17:00.',
                ], 403);
            }

            if (! $kehadiran) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Anda belum melakukan check-in.',
                ], 404);
            }

            $jamMasuk = Carbon::parse($kehadiran->jam_masuk, 'Asia/Jakarta');
            $durasi   = $jamMasuk->diff($currentTime);

            $kehadiran->update([
                'jam_keluar' => $currentTime->format('H:i:s'),
                'jam_kerja'  => $durasi->h . ' jam ' . $durasi->i . ' menit',
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Check-out berhasil.',
                'data'    => $kehadiran,
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Status tidak valid.',
        ], 422);
    }
}
