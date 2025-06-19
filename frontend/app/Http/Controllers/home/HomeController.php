<?php


namespace App\Http\Controllers\Home;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;   // ← WAJIB! (supaya class Controller dikenali)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
{
    $token = session('api_token');
    $sessionUser = session('user');
    $user = null;

    if ($sessionUser) {
        $userId = $sessionUser['_id'] ?? null;
        if ($userId) {
            $userResponse = Http::withToken($token)->get("http://localhost:5000/api/users/$userId");
            if ($userResponse->ok()) {
                $user = $userResponse->json();
                session(['user' => $user]);
            } else {
                $user = $sessionUser;
            }
        } else {
            $user = $sessionUser;
        }
    }

    // Ambil produk yang statusnya tersedia (sudah diverifikasi admin)
    $products = [];
    $productsResponse = Http::get('http://localhost:5000/api/products?status=tersedia&limit=8');
    if ($productsResponse->ok()) {
        $products = $productsResponse->json();
    }

    return view('pages.home.index', compact('user', 'products'));

    
}
    // Tampilkan halaman keranjang pelanggan
    public function cart()
    {
        $token = session('api_token');
        $user = session('user');
        $cart = null;

        if ($user) {
            $cartResponse = Http::withToken($token)->get('http://localhost:5000/api/cart/me');
            if ($cartResponse->ok()) {
                $cart = $cartResponse->json();
            }
        }

        return view('pages.home.produk.cart', compact('user', 'cart'));
    }

    // (Opsi) Proses tambah barang ke keranjang
    public function tambahKeranjang(Request $request)
    {
        $token = session('api_token');
        $user = session('user');
        if (!$user) {
            return response()->json(['message' => 'Harus login!'], 401);
        }

        $produk = $request->produk;
        $jumlah = $request->jumlah ?? 1;

        $apiResponse = Http::withToken($token)->post('http://localhost:5000/api/cart/add', [
            'produk' => $produk,
            'jumlah' => $jumlah,
        ]);

        return response()->json($apiResponse->json(), $apiResponse->status());
    }
    public function hapusKeranjang(Request $request)
    {
        $token = session('api_token');
        $user = session('user');
        if (!$user) {
            return response()->json(['message' => 'Harus login!'], 401);
        }
        $produk = $request->produk;
        $apiResponse = Http::withToken($token)->delete('http://localhost:5000/api/cart/remove', [
            'produk' => $produk,
        ]);
        return response()->json($apiResponse->json(), $apiResponse->status());
    }
    // 1. Tampilkan form checkout (GET)
public function checkoutForm() {
    $token = session('api_token');
    $user = session('user');

    // Get cart + daftar pickup point aktif
    $cart = null; $pickupPoints = [];
    if ($user) {
        $cartRes = Http::withToken($token)->get('http://localhost:5000/api/cart/me');
        if ($cartRes->ok()) $cart = $cartRes->json();
        $pickupRes = Http::get('http://localhost:5000/api/pickup-points?status=aktif');
        if ($pickupRes->ok()) $pickupPoints = $pickupRes->json();
    }
    return view('pages.home.produk.checkout', compact('user', 'cart', 'pickupPoints'));
}

// 2. Proses submit checkout (POST)
public function prosesCheckout(Request $request) {
    $token = session('api_token');
$user = session('user');
$request->validate([
    'id_titik_ambil' => 'required', // pastikan ID-nya valid & pickup aktif!
    'alamat_pengiriman' => 'required|string',
    'no_hp' => 'required',
    'metode_pembayaran' => 'required',
    // 'catatan' => 'nullable|string',
]);

// Kirim request ke Express
$apiRes = Http::withToken($token)->post('http://localhost:5000/api/checkout', [
    'id_titik_ambil'      => $request->id_titik_ambil,
    'alamat_pengiriman'   => $request->alamat_pengiriman,
    'no_hp'               => $request->no_hp,
    'metode_pembayaran'   => $request->metode_pembayaran,
    'catatan'             => $request->catatan,
]);

// Cek hasil response
Log::info('Response dari /api/checkout:', $apiRes->json());

if ($apiRes->successful()) {
    // Ambil ID checkout dari response Express
    $checkoutId = $apiRes->json('checkout._id') ?? null;

    // Redirect ke halaman upload payment/order jika ada ID checkout
    if ($checkoutId) {
        // (Nanti setelah order, redirect ke payment/upload)
        return redirect()->route('payment.upload', $checkoutId)
            ->with('success', 'Checkout berhasil, silakan upload bukti pembayaran.');
    }
    // Jika tidak ada id, fallback
    return redirect()->route('home')->with('success', 'Checkout berhasil.');
}
// Jika gagal
return back()->withErrors(['error' => $apiRes->json('message') ?? 'Checkout gagal!'])->withInput();
}
}

