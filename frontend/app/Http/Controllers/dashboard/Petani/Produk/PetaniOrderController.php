<?php
namespace App\Http\Controllers\dashboard\Petani\Produk;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class PetaniOrderController extends Controller
{
    // Tampilkan semua pesanan untuk petani login
    public function index()
{
    $token = session('api_token');
    $user = session('user');
    $role = $user['peran'] ?? null;   // <-- Tambahkan baris ini

    $orders = [];
    $res = Http::withToken($token)->get('http://localhost:5000/api/orders/petani/me');
    if ($res->ok()) $orders = $res->json();

    return view('pages.dashboard.petani.orders', compact('orders', 'role')); // <-- Tambah 'role'
}

    // Update status pesanan (ex: "siap-diambil")
    public function updateStatus(Request $request, $id)
    {
        $token = session('api_token');
        $status = $request->input('status_pesanan');
        $catatan = $request->input('catatan');

        $body = [
            'status_pesanan' => $status,
        ];
        if ($catatan) $body['catatan'] = $catatan;

        $res = Http::withToken($token)->patch("http://localhost:5000/api/orders/$id/status-petani", $body);

        if ($res->ok()) {
            return back()->with('success', 'Status pesanan berhasil diupdate!');
        }
        return back()->withErrors(['error' => $res->json('message') ?? 'Gagal update status pesanan.']);
    }
}
