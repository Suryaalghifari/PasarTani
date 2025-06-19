<?php
   namespace App\Http\Controllers\dashboard\Admin;


    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Http;

    class VerifikasiPesananController extends Controller
    {
        // Tampilkan daftar checkout yang menunggu verifikasi
        public function index()
        {
            $token = session('api_token');
            $user = session('user');
            $role = $user['peran'] ?? null; // <-- Ambil role dari session user

            $checkouts = [];
            $res = Http::withToken($token)->get('http://localhost:5000/api/checkout');
            if ($res->ok()) {
                $checkouts = collect($res->json())
                    ->where('status_checkout', 'menunggu-verifikasi')
                    ->values()->all();
            }

            return view('pages.dashboard.admin.verifikasi_pesanan', compact('checkouts', 'role'));
        }

        // Proses verifikasi (POST)
        public function verif(Request $request, $id)
        {
            $token = session('api_token');
            $action = $request->input('aksi'); // 'paid' atau 'gagal'

            $res = Http::withToken($token)->patch("http://localhost:5000/api/checkout/{$id}/verify", [
                'status_checkout' => $action,
            ]);
            if ($res->ok()) {
                return back()->with('success', 'Verifikasi berhasil!');
            }
            return back()->withErrors(['error' => 'Gagal verifikasi!']);
        }
    }
