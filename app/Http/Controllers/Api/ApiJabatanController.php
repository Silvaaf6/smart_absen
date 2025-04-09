<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiJabatanController extends Controller
{
    public function index()
    {
        $jabatan = Jabatan::orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'message' => 'Data Jabatan berhasil diambil',
            'data' => $jabatan
        ], Response::HTTP_OK);
    }
}
