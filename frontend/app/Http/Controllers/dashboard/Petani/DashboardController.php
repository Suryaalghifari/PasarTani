<?php
namespace App\Http\Controllers\dashboard\Petani;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $token = session('api_token');
        $sessionUser = session('user');
        $role = $sessionUser['peran'] ?? null;
        $userId = $sessionUser['_id'] ?? null;

        // Fetch user terbaru dari API (selalu update data user di session)
        if ($userId) {
            $userResponse = Http::withToken($token)->get("http://localhost:5000/api/users/$userId");
            if ($userResponse->ok()) {
                $user = $userResponse->json();
                session(['user' => $user]); // update session!
            } else {
                $user = $sessionUser; // fallback ke session
            }
        } else {
            $user = $sessionUser; // fallback
        }

        $response = Http::withToken($token)->get('http://localhost:5000/api/dashboard/petani');
        if ($response->failed()) {
            dd('API gagal', $response->body());
        }

        $data = $response->json();
        return view('pages.dashboard.petani.dashboard.index', compact('data', 'role'));
    }
}
