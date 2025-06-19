<?php
namespace App\Http\Controllers\dashboard\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $token = session('api_token');
        $user = session('user');
        $role = $user['peran'] ?? null;

        // Ambil semua logistik (atau bisa filter by status_pengiriman)
        $logistics = [];
        $res = Http::withToken($token)->get('http://localhost:5000/api/logistics');
        if ($res->ok()) $logistics = $res->json();

        return view('pages.dashboard.petugas.dashboard.index', compact('logistics', 'role'));
    }

    // Detail logistik
    public function show($id)
{
    $token = session('api_token');
    $sessionUser = session('user');
    $role = $sessionUser['peran'] ?? null; // TAMBAHKAN INI!
    $logistik = null;
    $res = Http::withToken($token)->get("http://localhost:5000/api/logistics/order/$id");
    if ($res->ok()) $logistik = $res->json();

    return view('pages.dashboard.petugas.dashboard.detail', compact('logistik', 'role'));
}

    // Update status logistik
    public function updateStatus(Request $request, $id)
    {
        $token = session('api_token');
        $request->validate([
            'status_pengiriman' => 'required',
            'catatan' => 'nullable|string'
        ]);

        $res = Http::withToken($token)->patch("http://localhost:5000/api/logistics/$id/status", [
            'status_pengiriman' => $request->status_pengiriman,
            'catatan' => $request->catatan,
        ]);
        if ($res->ok()) {
            return back()->with('success', 'Status pengiriman berhasil diupdate!');
        }
        return back()->withErrors(['error' => 'Gagal update status pengiriman!']);
    }
}