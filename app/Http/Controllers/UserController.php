<?php

namespace App\Http\Controllers;

use Alert;
use App\Models\Jabatan;
use App\Models\Kehadiran;
use App\Models\Pengajuan_cuti;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function home(Request $request)
    {
        $jumlahJabatan = Jabatan::count();

        $jumlahPegawai = User::whereHas('roles', function ($query) {
            $query->where('name', 'user');
        })->count();
        // $jumlahPegawai = User::role('user')->count();

        // dd($jumlahPegawai);
        $tanggal = $request->input('tanggal') ?: Carbon::now('Asia/Jakarta')->format('Y-m-d');

        $jumlahHadir = Kehadiran::whereDate('tanggal', $tanggal)
            ->whereIn('status', ['Hadir', 'Terlambat'])
            ->distinct('id_user')
            ->count('id_user');

        $jumlahCuti = Pengajuan_cuti::whereDate('tgl_pengajuan', $tanggal)
            ->whereIn('status', ['menunggu konfirmasi'])
            ->distinct('id_user')
            ->count('id_user');

        $userId = Auth::id();
        $absenHariIni = Kehadiran::where('id_user', $userId)
            ->whereDate('tanggal', $tanggal)
            ->exists();

        return view('home', compact(
            'jumlahPegawai',
            'jumlahJabatan',
            'jumlahHadir',
            'jumlahCuti',
            'absenHariIni'
        ));
    }

    public function index()
    {
        $pegawai = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->with('jabatan')->get();

        $jabatan     = Jabatan::all();
        $jumlahNotif = Pengajuan_cuti::where('status', 'menunggu konfirmasi')->count();
        $daftarNotif = Pengajuan_cuti::where('status', 'menunggu konfirmasi')->latest()->take(5)->get();

        return view('admin.user.index', compact('pegawai', 'jabatan', 'jumlahNotif', 'daftarNotif'));
    }

    public function indexapi()
    {
        $pegawai = User::with(['kehadiran', 'jadwal_piket', 'absen_piket', 'jabatan', 'pengajuan_cuti'])->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar User',
            'users'   => $user,
        ], 200);
    }

    public function create()
    {
        $jabatan = Jabatan::all();
        $pegawai    = User::all();
        return view('admin.user.index', compact('pegawai', 'jabatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'email'         => 'required|email',
            'password'      => 'required|min:8',
            'nip'           => 'required|min:16',
            'id_jabatan'    => 'required',
            'tempat_lahir'  => 'required',
            'tgl_lahir'     => 'required|date|before_or_equal:' . now()->subYears(17)->format('Y-m-d'),
            'alamat'        => 'required',
            'jenis_kelamin' => 'required',
            'agama'         => 'required',
            'no_telp'       => 'required',
            'cover'         => 'required|image|mimes:jpg,jpeg,png|max:65535',
        ]);

        $pegawai                = new User();
        $pegawai->name          = $request->name;
        $pegawai->email         = $request->email;
        $pegawai->password      = bcrypt($request->password);
        $pegawai->nip           = $request->nip;
        $pegawai->id_jabatan    = $request->id_jabatan;
        $pegawai->tempat_lahir  = $request->tempat_lahir;
        $pegawai->tgl_lahir     = $request->tgl_lahir;
        $pegawai->alamat        = $request->alamat;
        $pegawai->jenis_kelamin = $request->jenis_kelamin;
        $pegawai->agama         = $request->agama;
        $pegawai->no_telp       = $request->no_telp;

        $coverName = time() . '.' . $request->cover->extension();
        $request->cover->move(public_path('uploads'), $coverName);
        $coverPath = 'uploads/' . $coverName;

        $pegawai->cover = $coverPath;
        $pegawai->save();
        $pegawai->assignRole('user');

        Alert::success('Sukses', 'Data Berhasil Ditambah!')->autoClose(1000);
        return redirect()->route('pegawai.index');
    }

    public function show($id)
    {
        $pegawai    = User::with('jabatan')->where('id', $id)->first();
        $jabatan = Jabatan::all();
        return view('admin.user.show', compact('pegawai', 'jabatan'));
    }

    public function edit($id)
    {
        $pegawai    = User::findOrFail($id);
        $jabatan = Jabatan::all();
        return view('admin.user.edit', compact('pegawai', 'jabatan'));
    }

    public function update(Request $request, $id)
    {
        $pegawai = User::findOrFail($id);

        $pegawai->update([
            'name'          => $request->name,
            'email'         => $request->email,
            'nip'           => $request->nip,
            'id_jabatan'    => $request->id_jabatan,
            'tempat_lahir'  => $request->tempat_lahir,
            'tgl_lahir'     => $request->tgl_lahir,
            'alamat'        => $request->alamat,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama'         => $request->agama,
            'no_telp'       => $request->no_telp,
        ]);

        if ($request->filled('password')) {
            $pegawai->password = Hash::make($request->password);
        }

        if ($request->hasFile('cover')) {
            $img  = $request->file('cover');
            $name = time() . '.' . $img->getClientOriginalExtension();
            $img->move(public_path('uploads'), $name);

            // Hapus cover lama jika ada
            if ($pegawai->cover && file_exists(public_path($pegawai->cover))) {
                unlink(public_path($pegawai->cover));
            }

            $pegawai->cover = 'uploads/' . $name;
        }

        $pegawai->save();

        Alert::success('Sukses', 'Data Berhasil Diubah!')->autoClose(1000);
        return redirect()->route('pegawai.show', ['pegawai' => $pegawai->id]);
    }
}
