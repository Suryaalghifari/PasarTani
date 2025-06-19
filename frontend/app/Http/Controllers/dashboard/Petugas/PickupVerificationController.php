<?Php
namespace App\Http\Controllers\dashboard\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PickupVerificationController extends Controller
{
    // Tampilkan form upload pickup verification
    public function form($id_order)
    {
        $token = session('api_token');
    $user = session('user');
    $role = $user['peran'] ?? null;
        $order = null;
        $res = Http::withToken($token)->get("http://localhost:5000/api/logistics/order/{$id_order}");
        if ($res->ok()) $order = $res->json();
        return view('pages.dashboard.petugas.pickup_verification.form', compact('order', 'role'));
    }

    // Proses upload pickup verification
    public function upload(Request $request, $id_order)
    {
        $token = session('api_token');
        $request->validate([
            'foto_bukti' => 'required|image|max:4096',
            'waktu_verifikasi' => 'required',
            'keterangan' => 'nullable|string',
        ]);
    
        // Semua data
        $data = [
            ['name' => 'id_order', 'contents' => $id_order],
            ['name' => 'id_konsumen', 'contents' => $request->id_konsumen],
            ['name' => 'id_titik_ambil', 'contents' => $request->id_titik_ambil],
            ['name' => 'waktu_verifikasi', 'contents' => $request->waktu_verifikasi],
            ['name' => 'keterangan', 'contents' => $request->keterangan],
        ];
    
        // Gabungkan foto
        if ($request->hasFile('foto_bukti')) {
            $file = $request->file('foto_bukti');
            $data[] = [
                'name' => 'foto_bukti',
                'contents' => fopen($file->getRealPath(), 'r'),
                'filename' => $file->getClientOriginalName(),
            ];
        }
    
        // Kirim multipart ke Express
        $response = Http::withToken($token)
            ->asMultipart()
            ->post('http://localhost:5000/api/pickup-verification', $data);
    
        if ($response->successful()) {
            return redirect()->route('petugas.logistik')->with('success', 'Pickup berhasil diverifikasi!');
        }
        return back()->withErrors(['error' => $response->json('message') ?? 'Upload gagal!'])->withInput();
    }
}    