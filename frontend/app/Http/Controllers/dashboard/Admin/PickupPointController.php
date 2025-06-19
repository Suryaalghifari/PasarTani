<?php

namespace App\Http\Controllers\dashboard\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PickupPointController extends Controller
{
    // 1. List semua pickup point
    public function index()
    {
        $token = session('api_token');
        $user = session('user');
        $role = $user['peran'] ?? null;

        $pickupPoints = [];
        $res = Http::withToken($token)->get('http://localhost:5000/api/pickup-points');
        if ($res->ok()) $pickupPoints = $res->json();

        return view('pages.dashboard.admin.pickup_point.index', compact('pickupPoints', 'role'));
    }

    // 2. Tampilkan form tambah
    public function create()
    {
        $user = session('user');
        $role = $user['peran'] ?? null;
        return view('pages.dashboard.admin.pickup_point.create', compact('role'));
    }

    // 3. Proses tambah pickup point
    public function store(Request $request)
    {
        $token = session('api_token');
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'jam_operasional' => 'required|string|max:64',
            'kontak' => 'required|string|max:64',
            'status' => 'required|in:aktif,non-aktif',
            'keterangan' => 'nullable|string'
        ]);
        $res = Http::withToken($token)->post('http://localhost:5000/api/pickup-points', $data);

        if ($res->successful()) {
            return redirect()->route('admin.pickup_point.index')->with('success', 'Pickup point berhasil ditambah');
        }

        // Kirim juga role biar view create dapat $role kalau balik error
        $user = session('user');
        $role = $user['peran'] ?? null;
        return back()->withErrors(['error' => $res->json('message') ?? 'Gagal tambah pickup point'])->withInput()->with(compact('role'));
    }

    // 4. Tampilkan form edit pickup point
    public function edit($id)
    {
        $token = session('api_token');
        $user = session('user');
        $role = $user['peran'] ?? null;
        $point = null;
        $res = Http::withToken($token)->get("http://localhost:5000/api/pickup-points/$id");
        if ($res->ok()) $point = $res->json();

        return view('pages.dashboard.admin.pickup_point.edit', compact('point', 'role'));
    }

    // 5. Proses update pickup point
    public function update(Request $request, $id)
    {
        $token = session('api_token');
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'jam_operasional' => 'required|string|max:64',
            'kontak' => 'required|string|max:64',
            'status' => 'required|in:aktif,non-aktif',
            'keterangan' => 'nullable|string'
        ]);
        $res = Http::withToken($token)->put("http://localhost:5000/api/pickup-points/$id", $data);

        if ($res->successful()) {
            return redirect()->route('admin.pickup_point.index')->with('success', 'Pickup point berhasil diupdate');
        }

        // Kirim juga $role kalau update error
        $user = session('user');
        $role = $user['peran'] ?? null;
        $point = $data;
        $point['_id'] = $id; // agar form edit tetap terisi
        return back()->withErrors(['error' => $res->json('message') ?? 'Gagal update pickup point'])->withInput()->with(compact('point', 'role'));
    }

    // 6. Hapus pickup point
    public function destroy($id)
    {
        $token = session('api_token');
        $user = session('user');
        $role = $user['peran'] ?? null;
        $res = Http::withToken($token)->delete("http://localhost:5000/api/pickup-points/$id");

        if ($res->successful()) {
            return redirect()->route('admin.pickup_point.index')->with('success', 'Pickup point berhasil dihapus');
        }
        // Kirim role juga kalau gagal hapus
        return back()->withErrors(['error' => $res->json('message') ?? 'Gagal hapus pickup point'])->with(compact('role'));
    }
}
