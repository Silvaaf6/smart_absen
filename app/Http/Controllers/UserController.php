<?php
namespace App\Http\Controllers;

use Alert;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\JabatanController;
use App\Models\Jabatan;


class UserController extends Controller
 {
    public function home()
    {
        $user = Auth::user();
        return view('home');
    }

//     public function index()
// {
//     // Ambil data pengguna yang bukan admin
//     $user = User::whereDoesntHave('roles', function ($query) {
//         $query->where('name', 'admin');
//     })->get();

//     return view('admin.user.index', compact('user'));
// }

    public function create()
    {
        $jabatan = jabatan::all();
        $user = user::all();
        return view('admin.user.create', compact('user', 'jabatan'));
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'email' => 'required|email',
        //     'password' => 'required|min:8',
        //     'nip' => 'required',
        //     'id_jabatan' => 'required',
        //     'tempat_lahir' => 'required',
        //     'tgl_lahir' => 'required',
        //     'alamat' => 'required',
        //     'jenis_kelamin' => 'required',
        //     'agama' => 'required',
        //     'no_telp' => 'required',
        //     'cover' => 'required|mimes:jpg,jpeg,png|max:65535',
        // ], [
        //     'name.required' => 'Nama wajib diisi.',
        //     'email.required' => 'Email wajib diisi',
        //     'password.required' => 'Password wajib diisi',
        //     'nip.required' => 'NIP wajib diisi',
        //     'id_jabatan.required' => 'Jabatan wajib dipilih',
        //     'tempat_lahir.required' => 'Tempat lahir wajib diisi',
        //     'tgl_lahir.required' => 'Tanggal lahir wajib diisi',
        //     'alamat.required' => 'Alamat wajib diisi',
        //     'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
        //     'agama.required' => 'Agama wajib dipilih',
        //     'no_telp.required' => 'No telepon wajib diisi',
        //     'cover.required' => 'Foto profile wajib diisi',
        // ]);

        $user                = new user();
        $user->name          = $request->name;
        $user->email         = $request->email;
        $user->password      = $request->password;
        $user->nip           = $request->nip;
        $user->id_jabatan    = $request->id_jabatan;
        $user->tempat_lahir  = $request->tempat_lahir;
        $user->tgl_lahir     = $request->tgl_lahir;
        $user->alamat        = $request->alamat;
        $user->jenis_kelamin = $request->jenis_kelamin;
        $user->agama         = $request->agama;
        $user->no_telp       = $request->no_telp;

        if ($request->hasFile('cover')) {
            $img  = $request->file('cover');
            $name = time() . '-' . $img->getClientOriginalName();
            $img->move(public_path('images/user/'), $name);
            $user->cover = $name;
        }

        $user->save();

        Alert::success('Sukses', 'Data Berhasil Ditambah!')->autoClose(1000);
        return redirect()->route('pegawai.index');

    }

}
