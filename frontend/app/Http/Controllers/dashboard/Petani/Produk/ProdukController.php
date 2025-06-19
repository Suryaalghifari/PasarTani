<?php

namespace App\Http\Controllers\dashboard\Petani\Produk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProdukController extends Controller
{
    // List produk petani
    public function index()
    {
        $token = session('api_token');
        $user = session('user');
        $role = $user['peran'] ?? null; // Tambahkan ini!
        $response = Http::withToken($token)->get('http://localhost:5000/api/products/petani/me');
        $products = $response->ok() ? $response->json() : [];

         // Set foto_url untuk setiap produk
        foreach ($products as &$p) {
            if (!empty($p['foto'])) {
                $p['foto_url'] = 'http://localhost:5000/' . ltrim($p['foto'], '/');
            } else {
                $p['foto_url'] = asset('default-product.png');
            }
        }
        // unset($p);
            return view('pages.dashboard.petani.produk.index', compact('products', 'role'));
    }

    // Form tambah produk
    public function create()
    {
        $user = session('user');
        $role = $user['peran'] ?? null;
        return view('pages.dashboard.petani.produk.create', compact('role'));
    }

    // Proses simpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:1',
            'kategori' => 'required|string',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $token = session('api_token');
        $response = Http::withToken($token)
            ->attach('foto', file_get_contents($request->file('gambar')->getRealPath()), $request->file('gambar')->getClientOriginalName())

            ->post('http://localhost:5000/api/products', [
                ['name' => 'nama', 'contents' => $request->nama],
                ['name' => 'harga', 'contents' => $request->harga],
                ['name' => 'stok', 'contents' => $request->stok],
                ['name' => 'kategori', 'contents' => $request->kategori],
                ['name' => 'deskripsi', 'contents' => $request->deskripsi],
            ]);
            if (!$request->hasFile('gambar')) {
                return back()->withErrors(['error' => 'File gambar tidak ditemukan di form!'])->withInput();
            }
            if ($response->successful()) {
                return redirect()->route('petani.produk.index')->with('success', 'Produk berhasil ditambah, menunggu verifikasi admin.');
            } else {
                $error = $response->json('message') ?? $response->body() ?? 'Gagal menambah produk';
                return back()->withErrors(['error' => $error])->withInput();
            }
        }
            public function edit($id)
        {
            $token = session('api_token');
            $user = session('user');
            $role = $user['peran'] ?? null;
            $response = Http::withToken($token)->get("http://localhost:5000/api/products/$id");
            if ($response->failed()) {
                return redirect()->route('petani.produk.index')->with('error', 'Produk tidak ditemukan!');
            }
            $produk = $response->json();
            return view('pages.dashboard.petani.produk.edit', compact('produk', 'role'));
        }

            public function update(Request $request, $id)
        {
            $request->validate([
                'nama' => 'required|string|max:255',
                'harga' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:1',
                'kategori' => 'required|string',
                'deskripsi' => 'nullable|string',
                'gambar' => 'nullable|image|max:2048',
            ]);

            $token = session('api_token');
            if ($request->hasFile('gambar')) {
                $response = Http::withToken($token)
                    ->attach('foto', file_get_contents($request->file('gambar')->getRealPath()), $request->file('gambar')->getClientOriginalName())
                    ->post("http://localhost:5000/api/products/$id", [ // Ganti ke .put/.patch jika API Express support!
                        ['name' => 'nama', 'contents' => $request->nama],
                        ['name' => 'harga', 'contents' => $request->harga],
                        ['name' => 'stok', 'contents' => $request->stok],
                        ['name' => 'kategori', 'contents' => $request->kategori],
                        ['name' => 'deskripsi', 'contents' => $request->deskripsi],
                    ]);
            } else {
                $response = Http::withToken($token)
                    ->put("http://localhost:5000/api/products/$id", [
                        'nama' => $request->nama,
                        'harga' => $request->harga,
                        'stok' => $request->stok,
                        'kategori' => $request->kategori,
                        'deskripsi' => $request->deskripsi,
                    ]);
            }

            if ($response->successful()) {
                return redirect()->route('petani.produk.index')->with('success', 'Produk berhasil diupdate!');
            } else {
                $error = $response->json('message') ?? $response->body() ?? 'Gagal update produk';
                return back()->withErrors(['error' => $error])->withInput();
            }
        }

            public function destroy($id)
        {
            $token = session('api_token');
            $response = Http::withToken($token)->delete("http://localhost:5000/api/products/$id");
            if ($response->successful()) {
                return redirect()->route('petani.produk.index')->with('success', 'Produk berhasil dihapus!');
            } else {
                return redirect()->route('petani.produk.index')->with('error', 'Gagal menghapus produk');
            }
        }


}
