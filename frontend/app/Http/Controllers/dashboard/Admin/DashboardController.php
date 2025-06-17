<?php

namespace App\Http\Controllers\dashboard\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request; // Ini yang benar

class DashboardController extends Controller
{
    public function index()
    {
        $token = session('api_token');
        $role = session('user')['peran'] ?? null;
    
        $response = Http::withToken($token)->get('http://localhost:5000/api/dashboard/admin');
    
        if ($response->failed()) {
            dd('API gagal', $response->body());
        }
    
        $data = $response->json();
        return view('pages.dashboard.admin.dashboard.index', compact('data', 'role'));
    }

    public function logout(Request $request)
    {
        // Hapus semua data session yang berhubungan dengan user
        $request->session()->flush(); // Menghapus semua session
        // Atau jika ingin hapus session tertentu saja
        // $request->session()->forget(['api_token', 'user']);

        // Redirect ke halaman login
        return redirect()->route('login')->with('success', 'Anda berhasil logout!');
    }

    
}
