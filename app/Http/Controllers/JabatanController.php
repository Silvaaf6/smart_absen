<?php
namespace App\Http\Controllers;

use Alert;
use App\Models\jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
    {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jabatan = jabatan::orderBy('created_at', 'desc')->get(); // Mengambil data terbaru di atas
        return view('admin.jabatan.index', compact('jabatan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jabatan = jabatan::all();
        return view('admin.jabatan.index', compact('jabatan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|unique:jabatans,nama_jabatan',
        ], [
            'nama_jabatan.required' => 'Nama jabatan wajib diisi.',
            'nama_jabatan.unique'   => 'Nama jabatan sudah ada.',
        ]);

        $jabatan               = new jabatan();
        $jabatan->nama_jabatan = $request->nama_jabatan;

        $jabatan->save();

        Alert::success('Sukses', 'Data Berhasil Ditambah!')->autoClose(1000);
        return redirect()->route('jabatan.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(jabatan $jabatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(jabatan $jabatan)
    {
        //
    }

    // /**
    //  * Update the specified resource in storage.
    //  */
    public function update(Request $request, jabatan $jabatan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jabatan = jabatan::findOrFail($id);
        $jabatan->delete();

        Alert::success('Sukses', 'Data Berhasil Dihapus!')->autoClose(1000);
        return redirect()->route('jabatan.index');
    }
}
