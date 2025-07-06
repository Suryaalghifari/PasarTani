<?php
namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function index()
{
    $token = session('api_token');
    $user = session('user');

    $orders = [];
    $checkouts = [];

    if ($user) {
        // Ambil orders yang sudah diverifikasi/admin approve
        $ordersRes = Http::withToken($token)->get('http://localhost:5000/api/orders/me');
        if ($ordersRes->ok()) $orders = $ordersRes->json();

        // Ambil checkouts user (yang belum diverifikasi)
        $checkoutsRes = Http::withToken($token)->get('http://localhost:5000/api/checkout/me');
        if ($checkoutsRes->ok()) {
            $checkouts = collect($checkoutsRes->json())
                ->whereIn('status_checkout', ['pending', 'menunggu-verifikasi', 'gagal', 'berhasil']) // tambahkan 'gagal'
                ->values()->all();;
        }   
    }

    // Gabungkan kedua data, urutkan berdasarkan tanggal (createdAt)
    $allOrders = array_merge($orders, $checkouts);
    usort($allOrders, function($a, $b) {
        return strtotime($b['createdAt']) <=> strtotime($a['createdAt']);
    });

    return view('pages.home.produk.orders', [
        'orders' => $allOrders,
        'user' => $user,
    ]);
}

}
