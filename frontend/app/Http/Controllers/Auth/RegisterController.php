<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    public function show()
    {
        return view('pages.auth.register');
    }

    public function registerUser(Request $request)
    {
        // Validasi form ringan di Laravel
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'peran' => 'required|in:admin,petani,konsumen,petugas',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string',
        ]);

        // Kirim request ke backend Express API
        $response = Http::post('http://localhost:5000/api/auth/register', [
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => $request->password,
            'peran' => $request->peran,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        if ($response->successful()) {
            return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
        } else {
            $error = $response->json('message') ?? 'Registrasi gagal.';
            return back()->withErrors(['error' => $error])->withInput();
        }
    }
}
