<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function show()
    {
        return view('pages.auth.login');
    }

    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    
        $response = Http::post('http://localhost:5000/api/auth/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);
    
        if ($response->successful()) {
            $data = $response->json();
            session([
                'api_token' => $data['token'],
                'user' => $data['user'],
            ]);
            $role = $data['user']['peran'] ?? $data['user']['role'] ?? null;
    
            // Debug sementara:
            // dd($role, session()->all());
    
            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'petani') {
                return redirect()->route('petani.dashboard');
            } else {
                return redirect('/home');
            }
        } else {
            $errorMessage = $response->json('message') ?? 'Login gagal';
            return back()->withErrors(['error' => $errorMessage])->withInput();
        }
    }

    public function logout(Request $request)
    {
        // Menghapus semua session yang berhubungan dengan user
        $request->session()->flush();

        // Redirect ke halaman login (atau halaman lain sesuai kebutuhan)
        return redirect()->route('login')->with('success', 'Anda berhasil logout!');
    }
    
}
