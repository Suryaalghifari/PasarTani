<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    // Form upload bukti pembayaran (GET)
    public function show($id_checkout)
    {
        $token = session('api_token');
        $user = session('user');
        $checkout = null;

        // Ambil data checkout dari backend Express
        $checkoutRes = Http::withToken($token)->get("http://localhost:5000/api/checkout/$id_checkout");
        if ($checkoutRes->ok()) {
            $checkout = $checkoutRes->json();
        } else {
            return redirect()->route('home')->withErrors(['error' => 'Data checkout tidak ditemukan.']);
        }

        return view('pages.home.produk.upload_payment', compact('checkout', 'user'));
    }

    // Proses upload bukti pembayaran (POST)
   // PaymentController@upload
public function upload(Request $request, $id_checkout)
{
    $token = session('api_token');
    $user = session('user');

    $request->validate([
        'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
    ]);

    $bukti = $request->file('bukti_pembayaran');

    // Kirim file ke Express endpoint CHECKOUT (bukan payments)
    $fileRes = Http::withToken($token)
        ->attach('bukti_pembayaran', file_get_contents($bukti->getRealPath()), $bukti->getClientOriginalName())
        ->post("http://localhost:5000/api/checkout/{$id_checkout}/upload-bukti");

    if ($fileRes->successful()) {
        return redirect()->route('pesanan.saya')->with('success', 'Bukti pembayaran berhasil di-upload.');
    }
    return back()->withErrors(['error' => $fileRes->json('message') ?? 'Gagal upload bukti'])->withInput();
}

}