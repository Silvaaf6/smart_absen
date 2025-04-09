<?php
namespace App\Http\Controllers;

use Alert;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function home()
    {
        $user = Auth::user();
        return view('home');
    }

    public function index()
    {
        // Ambil data pengguna yang bukan admin beserta jabatan
        $user = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->with('jabatan')->get();

        $jabatan = Jabatan::all();

        return view('admin.user.index', compact('user', 'jabatan'));
    }

    public function indexapi()
    {
        $user = User::with(['kehadiran', 'jadwal_piket', 'absen_piket', 'jabatan', 'pengajuan_cuti'])->get();
        $res  = [
            'success' => true,
            'message' => 'Daftar User',
            'users'   => $user,
        ];
        return response()->json($res, 200);
    }

    public function create()
    {
        $jabatan = jabatan::all();
        $user    = user::all();
        return view('admin.user.index', compact('user', 'jabatan'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        // $request->validate([
        //     'name'          => 'required',
        //     'email'         => 'required|email',
        //     'password'      => 'required|min:8',
        //     'nip'           => 'required|min:16',
        //     'id_jabatan'    => 'required',
        //     'tempat_lahir'  => 'required',
        //     'tgl_lahir'     => 'required|date|before_or_equal:' . now()->subYears(17)->format('Y-m-d'),
        //     'alamat'        => 'required',
        //     'jenis_kelamin' => 'required',
        //     'agama'         => 'required',
        //     'no_telp'       => 'required',
        //     'cover'         => 'required|image|mimes:jpg,jpeg,png|max:65535',
        // ], [
        //     'name.required'          => 'Nama wajib diisi.',
        //     'email.required'         => 'Email wajib diisi',
        //     'password.required'      => 'Password wajib diisi',
        //     'nip.required'           => 'NIP wajib diisi',
        //     'id_jabatan.required'    => 'Jabatan wajib dipilih',
        //     'tempat_lahir.required'  => 'Tempat lahir wajib diisi',
        //     'tgl_lahir.required'     => 'Tanggal lahir wajib diisi',
        //     'alamat.required'        => 'Alamat wajib diisi',
        //     'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
        //     'agama.required'         => 'Agama wajib dipilih',
        //     'no_telp.required'       => 'No telepon wajib diisi',
        //     'cover.required'         => 'Foto profile wajib diisi',
        // ]);

        $user                = new User();
        $user->name          = $request->name;
        $user->email         = $request->email;
        $user->password      = bcrypt($request->password);
        $user->nip           = $request->nip;
        $user->id_jabatan    = $request->id_jabatan;
        $user->tempat_lahir  = $request->tempat_lahir;
        $user->tgl_lahir     = $request->tgl_lahir;
        $user->alamat        = $request->alamat;
        $user->jenis_kelamin = $request->jenis_kelamin;
        $user->agama         = $request->agama;
        $user->no_telp       = $request->no_telp;

// Cek apakah user bukan admin sebelum menyimpan cover
        if ($request->hasFile('cover') && ! $request->user()->hasRole('admin')) {
            $img  = $request->file('cover');
            $name = 'cover_' . time() . '.' . $img->getClientOriginalExtension();
            $img->move(public_path('admin/images/cover'), $name);
            $user->cover = $name;
        }

        $user->save();

        Alert::success('Sukses', 'Data Berhasil Ditambah!')->autoClose(1000);
        return redirect()->route('pegawai.index');
    }

    public function show($id)
    {
        $user    = User::with('jabatan')->where('id', $id)->first();
        $jabatan = Jabatan::all(); // Ambil semua data jabatan

        return view('admin.user.show', compact('user', 'jabatan'));
    }

    public function edit($id)
    {
        $user    = User::findOrFail($id);
        $jabatan = Jabatan::all();
        return view('admin.user.edit', compact('user', 'jabatan'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi Input
        // $request->validate([
        //     'name'          => 'required|string|max:255',
        //     'email'         => 'required|email|unique:users,email,' . $id,
        //     'nip'           => 'required|numeric',
        //     'id_jabatan'    => 'required|exists:jabatan,id',
        //     'tempat_lahir'  => 'required|string|max:255',
        //     'tgl_lahir'     => 'required|date',
        //     'alamat'        => 'required|string|max:255',
        //     'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        //     'agama'         => 'required|string|max:50',
        //     'no_telp'       => 'required|numeric',
        //     'cover'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        // ]);

        // Update data kecuali password
        $user->update([
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

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Upload cover jika ada
        if ($request->hasFile('cover')) {
            $img  = $request->file('cover');
            $name = time() . '-' . $img->getClientOriginalName();
            $img->move(public_path('images/user/'), $name);

            // Hapus cover lama jika ada
            if ($user->cover && file_exists(public_path('images/user/' . $user->cover))) {
                unlink(public_path('images/user/' . $user->cover));
            }

            $user->cover = $name;
        }

        $user->save();

        // Notifikasi sukses
        Alert::success('Sukses', 'Data Berhasil Diubah!')->autoClose(1000);
        return redirect()->route('pegawai.show', ['pegawai' => $user->id]);
    }

}
