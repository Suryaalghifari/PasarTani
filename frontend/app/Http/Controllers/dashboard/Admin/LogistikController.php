<?php
namespace App\Http\Controllers\dashboard\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class LogistikController extends Controller
{
    // 1. Menampilkan semua data logistik yang sudah pernah dibuat
    public function index()
    {
        $token = session('api_token');
        $user = session('user');
        $role = $user['peran'] ?? null;
        $logistics = [];

        $res = Http::withToken($token)->get('http://localhost:5000/api/logistics');
        if ($res->ok()) $logistics = $res->json();

        return view('pages.dashboard.admin.logistik.index', compact('logistics', 'role'));
    }

    // 2. Menampilkan detail logistik untuk 1 order
    public function show($id)
    {
        $token = session('api_token');
        $user = session('user');
        $role = $user['peran'] ?? null;
        $logistik = null;
        $res = Http::withToken($token)->get("http://localhost:5000/api/logistics/order/$id");
        if ($res->ok()) $logistik = $res->json();

        return view('pages.dashboard.admin.logistik.detail', compact('logistik', 'role'));
    }

    // 3. Menampilkan daftar order yang "siap-diambil" dan belum ada data logistik
    public function siapDiambil()
    {
        $token = session('api_token');
        $user = session('user');
        $role = $user['peran'] ?? null;
        $ordersSiapDiambil = [];

        // Ambil order status "siap-diambil"
        $ordersRes = Http::withToken($token)->get('http://localhost:5000/api/orders?status_pesanan=siap-diambil');
        if ($ordersRes->ok()) {
            $allOrders = $ordersRes->json();
            foreach ($allOrders as $order) {
                // Cek apakah sudah ada logistik
                $logRes = Http::withToken($token)->get('http://localhost:5000/api/logistics/order/' . $order['_id']);
                if ($logRes->notFound()) {
                    $ordersSiapDiambil[] = $order;
                }
            }
        }

        // Kirim ke blade siap_diambil.blade.php (atau sesuaikan nama filenya)
        return view('pages.dashboard.admin.logistik.siap_diambil', compact('ordersSiapDiambil', 'role'));
    }

    // 4. Tampilkan form atur jadwal pengiriman
    public function formAturJadwal($id)
    {
        $token = session('api_token');
        $user = session('user');
        $role = $user['peran'] ?? null;
        $order = null;
        $res = Http::withToken($token)->get("http://localhost:5000/api/orders/$id");
        if ($res->ok()) $order = $res->json();

        return view('pages.dashboard.admin.logistik.atur_jadwal', compact('order', 'role'));
    }

    // 5. Simpan data logistik
    public function simpanJadwal(Request $request, $id)
    {
        $token = session('api_token');
        $user = session('user');
        $role = $user['peran'] ?? null;
        $request->validate([
            'id_petani' => 'required',
            'id_titik_ambil' => 'required',
            'jadwal_pengambilan' => 'required|date',
            'jadwal_pengiriman' => 'required|date',
            'kurir' => 'required|string|max:255',
        ]);

        $res = Http::withToken($token)->post("http://localhost:5000/api/logistics", [
            'id_order' => $id,
            'id_petani' => $request->id_petani,
            'id_titik_ambil' => $request->id_titik_ambil,
            'jadwal_pengambilan' => $request->jadwal_pengambilan,
            'jadwal_pengiriman' => $request->jadwal_pengiriman,
            'kurir' => $request->kurir,
            'catatan' => $request->catatan,
        ]);
        if ($res->successful()) {
            return redirect()->route('admin.logistik')->with('success', 'Jadwal pengiriman berhasil disimpan!');
        }
        return back()->withErrors(['error' => 'Gagal simpan jadwal!'])->withInput();
    }
}
