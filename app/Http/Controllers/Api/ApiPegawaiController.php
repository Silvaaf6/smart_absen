<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class ApiPegawaiController extends Controller
{
    public function index()
    {
        $user = User::with('jabatan')->get();
        return response()->json([
            'success' => true,
            'message' => 'Profile',
            'data'    => $user,
        ], 200);
    }

    public function show($id)
    {
        try {
            $user = User::with('jabatan')->findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Profile',
                'data'    => $user,
            ]);
        } catch (\Exception $e) {;
            return response()->json([
                'success' => true,
                'message' => 'data tidak ada',
                'errors'  => $e->getMessage(),
            ], 404);}
    }
}
