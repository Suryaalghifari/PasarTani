<?php

namespace App\Http\Controllers\dashboard\Petani;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    public function edit()
    {
        $token = session('api_token');
        $user = session('user');
        $role = $user['peran'] ?? null;
        $userId = $user['_id'] ?? null;

        if (!$userId) {
            return redirect()->route('petani.dashboard')->with('error', 'ID user tidak ditemukan!');
        }

        $response = Http::withToken($token)->get("http://localhost:5000/api/users/$userId");
        if ($response->failed()) {
            return redirect()->route('petani.dashboard')->with('error', 'Gagal mengambil data user: ' . $response->body());
        }
        $user = $response->json();

        // Set foto_url di sini!
        if (!empty($user['foto'])) {
            $user['foto_url'] = 'http://localhost:5000/uploads/' . ltrim($user['foto'], '/');
        } else {
            $user['foto_url'] = asset('default-avatar.png');
        }

        return view('pages.dashboard.petani.profile.edit', compact('user', 'role'));
    }


    public function update(Request $request)
    {
        $token = session('api_token');
        $user = session('user');
        $userId = $user['_id'] ?? null;

        if (!$userId) {
            return back()->with('error', 'ID user tidak ditemukan!');
        }

        $data = [
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'password' => $request->filled('password') ? $request->password : null, // biar tidak kirim string kosong
        ];

        // Hapus password dari data jika kosong/tidak diisi
        if (empty($data['password'])) {
            unset($data['password']);
        }

        // Kirim file jika ada foto baru
        if ($request->hasFile('foto')) {
            $response = Http::withToken($token)
                ->attach('foto', file_get_contents($request->file('foto')), $request->file('foto')->getClientOriginalName())
                ->post("http://localhost:5000/api/users/$userId", $data);
        } else {
            $response = Http::withToken($token)
                ->put("http://localhost:5000/api/users/$userId", $data);
        }

        if ($response->failed()) {
            return back()->with('error', 'Gagal update profil: ' . $response->body());
        }

        return redirect()->route('petani.profile.edit')->with('success', 'Profil berhasil diupdate!');
    }
}
