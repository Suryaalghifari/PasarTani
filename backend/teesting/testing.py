import requests
import json

BASE = "http://localhost:5000/api"
LOG_FILE = "log_testing_pasar_tani.txt"

def log(title, res):
    try:
        body = res.json()
    except:
        body = res.text
    logline = f"\n=== {title} ===\nSTATUS: {res.status_code}\nRESPONSE: {json.dumps(body, indent=2) if isinstance(body, dict) else body}\n"
    print(logline)
    with open(LOG_FILE, "a", encoding="utf-8") as f:
        f.write(logline)

def test_register(email, password, nama, peran):
    res = requests.post(f"{BASE}/auth/register", json={
        "nama": nama,
        "email": email,
        "password": password,
        "peran": peran
    })
    log(f"REGISTER ({peran})", res)
    return res

def test_login(email, password, peran):
    res = requests.post(f"{BASE}/auth/login", json={
        "email": email,
        "password": password
    })
    log(f"LOGIN ({peran})", res)
    if res.status_code == 200 and "token" in res.json():
        return res.json()["token"]
    return None

def test_profile(token, peran):
    res = requests.get(f"{BASE}/users/me", headers={"Authorization": f"Bearer {token}"})
    log(f"GET PROFILE ({peran})", res)

def test_add_product(token):
    # Ubah path file sesuai foto yang ada
    files = {'foto': open('foto_tomat.jpg', 'rb')}
    data = {
        "nama": "Tomat Test",
        "deskripsi": "Tomat Segar Automation",
        "kategori": "sayur",
        "harga": 20000,
        "stok": 40
    }
    res = requests.post(f"{BASE}/products", headers={"Authorization": f"Bearer {token}"}, data=data, files=files)
    log("TAMBAH PRODUK", res)
    try:
        return res.json()["product"]["_id"]
    except:
        return None

def test_get_products():
    res = requests.get(f"{BASE}/products")
    log("GET ALL PRODUK", res)
    try:
        return res.json()[0]["_id"]
    except:
        return None

def test_add_cart(token, produk_id):
    data = {"produk": produk_id, "jumlah": 2}
    res = requests.post(f"{BASE}/cart/add", headers={"Authorization": f"Bearer {token}"}, json=data)
    log("TAMBAH CART", res)

def test_get_cart(token):
    res = requests.get(f"{BASE}/cart/me", headers={"Authorization": f"Bearer {token}"})
    log("GET CART", res)

def test_checkout(token, id_titik_ambil):
    data = {
        "id_titik_ambil": id_titik_ambil,
        "alamat_pengiriman": "Jl. Pembeli Automation",
        "no_hp": "082233445566",
        "metode_pembayaran": "transfer",
        "catatan": "Tes otomatis"
    }
    res = requests.post(f"{BASE}/checkout", headers={"Authorization": f"Bearer {token}"}, json=data)
    log("CHECKOUT", res)
    try:
        return res.json()["checkout"]["_id"]
    except:
        return None

def test_get_orders(token):
    res = requests.get(f"{BASE}/orders/me", headers={"Authorization": f"Bearer {token}"})
    log("GET MY ORDERS", res)

def test_generate_report(token):
    data = {"periode_awal": "2024-01-01", "periode_akhir": "2024-12-31"}
    res = requests.post(f"{BASE}/reports/sales", headers={"Authorization": f"Bearer {token}"}, json=data)
    log("GENERATE REPORT", res)

def main():
    # Kosongkan log file dulu
    open(LOG_FILE, "w", encoding="utf-8").close()

    # ----- TEST FLOW -----
    # Register & login konsumen
    email_konsumen = f"teskonsumen{str(int(1000*__import__('time').time()))}@mail.com"
    pw_konsumen = "password123"
    test_register(email_konsumen, pw_konsumen, "Konsumen Automation", "konsumen")
    token_konsumen = test_login(email_konsumen, pw_konsumen, "konsumen")

    # Register & login petani
    email_petani = f"tespetani{str(int(1000*__import__('time').time()))}@mail.com"
    pw_petani = "password123"
    test_register(email_petani, pw_petani, "Petani Automation", "petani")
    token_petani = test_login(email_petani, pw_petani, "petani")

    # Get profile
    if token_konsumen:
        test_profile(token_konsumen, "konsumen")
    if token_petani:
        test_profile(token_petani, "petani")

    # Tambah produk oleh petani
    produk_id = None
    if token_petani:
        produk_id = test_add_product(token_petani)

    # Konsumen lihat produk, tambah ke cart
    produk_id_get = produk_id or test_get_products()
    if token_konsumen and produk_id_get:
        test_add_cart(token_konsumen, produk_id_get)
        test_get_cart(token_konsumen)

    # --- Untuk tes checkout, pastikan sudah ada Pickup Point aktif ---
    # Lihat semua pickup point
    res = requests.get(f"{BASE}/pickup-points")
    log("GET PICKUP POINTS", res)
    try:
        id_titik_ambil = res.json()[0]["_id"]
    except:
        id_titik_ambil = None

    # Konsumen checkout
    id_checkout = None
    if token_konsumen and id_titik_ambil:
        id_checkout = test_checkout(token_konsumen, id_titik_ambil)

    # Konsumen lihat order (jika ada)
    if token_konsumen:
        test_get_orders(token_konsumen)

    # Generate report sales (gunakan token_petani jika role petani, bisa juga admin)
    if token_petani:
        test_generate_report(token_petani)

    print("\n==== LOG TESTING TERSIMPAN DI", LOG_FILE)

if __name__ == "__main__":
    main()
