<?php

namespace App\Http\Controllers\dashboard\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserAdminController extends Controller
{
    public function index()
    {
        $token = session('api_token');
        $response = Http::withToken($token)->get('http://localhost:5000/api/users');
        $users = $response->json();

        // Kirim $role dari session user
        $role = session('user')['peran'] ?? null;

        return view('pages.dashboard.admin.user.index', compact('users', 'role'));
    }

    public function create()
    {
        // Kirim $role dari session user
        $role = session('user')['peran'] ?? null;

        return view('pages.dashboard.admin.user.create', compact('role'));
    }

    public function store(Request $request)
    {
        $token = session('api_token');

        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'peran' => 'required|in:admin,petani,petugas,pelanggan',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->only(['nama', 'email', 'password', 'peran', 'alamat', 'no_hp']);

        if ($request->hasFile('foto')) {
            $response = Http::withToken($token)
                ->attach('foto', file_get_contents($request->file('foto')), $request->file('foto')->getClientOriginalName())
                ->post('http://localhost:5000/api/users', $data);
        } else {
            $response = Http::asForm()->withToken($token)
                ->post('http://localhost:5000/api/users', $data);
        }

        if ($response->failed()) {
            $error = $response->json('message') ?? 'Gagal menambah user';
            return back()->with('error', $error)->withInput();
        }

        return redirect()->route('admin.user.index')->with('success', 'User berhasil ditambahkan!');
    }
    public function edit($id)
{
    $token = session('api_token');
    $response = Http::withToken($token)->get("http://localhost:5000/api/users/$id");
    $user = $response->json();
    $role = $user['peran'] ?? null;
    return view('pages.dashboard.admin.user.edit', compact('user', 'role'));
}

public function update(Request $request, $id)
{
    $token = session('api_token');

    $request->validate([
        'nama' => 'required|string|max:100',
        'email' => 'required|email',
        'password' => 'nullable|min:6',
        'peran' => 'required|in:admin,petani,petugas,pelanggan',
        'alamat' => 'nullable|string|max:255',
        'no_hp' => 'nullable|string|max:20',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $data = $request->only(['nama', 'email', 'password', 'peran', 'alamat', 'no_hp']);
    if (empty($data['password'])) unset($data['password']);

    if ($request->hasFile('foto')) {
        $response = Http::withToken($token)
            ->attach('foto', file_get_contents($request->file('foto')), $request->file('foto')->getClientOriginalName())
            ->put("http://localhost:5000/api/users/$id", $data);
    } else {
        $response = Http::asForm()->withToken($token)
            ->put("http://localhost:5000/api/users/$id", $data);
    }

    if ($response->failed()) {
        $error = $response->json('message') ?? 'Gagal mengupdate user';
        return back()->with('error', $error)->withInput();
    }

    return redirect()->route('admin.user.index')->with('success', 'User berhasil diupdate!');
    }
    public function destroy($id)
{
    $token = session('api_token');
    $response = Http::withToken($token)
        ->delete("http://localhost:5000/api/users/$id");

    if ($response->failed()) {
        $error = $response->json('message') ?? 'Gagal menghapus user';
        return back()->with('error', $error);
    }

    return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus!');
}


}
