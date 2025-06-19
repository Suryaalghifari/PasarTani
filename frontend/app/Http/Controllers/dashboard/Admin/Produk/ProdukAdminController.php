<?php

namespace App\Http\Controllers\dashboard\Admin\Produk;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProdukAdminController extends Controller
{


    public function verifikasiIndex()
    {
        $token = session('api_token');
        $user = session('user');
        $role = $user['peran'] ?? null; // Tambahkan ini!
        $response = Http::withToken($token)->get('http://localhost:5000/api/products?status=tunggu-verifikasi');
        $products = $response->ok() ? $response->json() : [];
        return view('pages.dashboard.admin.produk.verifikasi', compact('products', 'role'));
    }

    public function show($id)
    {
        $token = session('api_token');
        $user = session('user');
        $role = $user['peran'] ?? null;
        $response = Http::withToken($token)->get("http://localhost:5000/api/products/$id");
        $produk = $response->ok() ? $response->json() : null;
        return view('pages.dashboard.admin.produk.detail', compact('produk', 'role'));
    }

public function verifikasi(Request $request, $id)
{
    $token = session('api_token');
    $request->validate([
        'status' => 'required|in:tersedia,ditolak',
    ]);
    $response = Http::withToken($token)->patch("http://localhost:5000/api/products/$id/verify", [
        'status' => $request->status,
    ]);
    if ($response->successful()) {
        return redirect()->route('admin.produk.verifikasi')->with('success', 'Status produk berhasil diubah!');
    } else {
        return back()->withErrors(['error' => 'Gagal memverifikasi produk']);
    }
}
}