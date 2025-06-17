import requests
import json
import time
import os

BASE = "http://localhost:5000/api"
LOG_FILE = "log_testing_full_workflow.txt"

def log(title, res):
    try: body = res.json()
    except: body = res.text
    line = f"\n=== {title} ===\nSTATUS: {res.status_code}\nRESPONSE: {json.dumps(body, indent=2) if isinstance(body, dict) else body}\n"
    print(line)
    with open(LOG_FILE, "a", encoding="utf-8") as f: f.write(line)

def register_login(role, pw="rahasia123"):
    email = f"test{role}{int(time.time())}@mail.com"
    requests.post(f"{BASE}/auth/register", json={"nama": f"{role.capitalize()} Test", "email": email, "password": pw, "peran": role})
    res = requests.post(f"{BASE}/auth/login", json={"email": email, "password": pw})
    log(f"LOGIN {role.upper()}", res)
    token = res.json().get("token", "")
    user_id = res.json()["user"]["_id"] if res.status_code == 200 and "user" in res.json() else None
    return token, user_id, email

def add_product(token, foto_path='foto_tomat.jpg'):
    if not os.path.exists(foto_path):
        with open(foto_path, "wb") as f: f.write(b"\x89PNG\r\n\x1a\n")
    files = {'foto': open(foto_path, 'rb')}
    data = {
        "nama": f"Tomat E2E {int(time.time())}",
        "deskripsi": "Workflow automation",
        "kategori": "sayur",
        "harga": 5000,
        "stok": 10
    }
    res = requests.post(f"{BASE}/products", headers={"Authorization": f"Bearer {token}"}, data=data, files=files)
    log("PETANI: TAMBAH PRODUK", res)
    return res.json().get("product", {}).get("_id")

def admin_approve_product(token, produk_id):
    res = requests.patch(f"{BASE}/products/{produk_id}/verify", headers={"Authorization": f"Bearer {token}"}, json={"status": "tersedia"})
    log("ADMIN: APPROVE PRODUK", res)

def add_pickup_point(token):
    data = {
        "nama": "Point E2E",
        "alamat": "Jl. E2E No. 1",
        "jam_operasional": "07:00 - 15:00",
        "kontak": "08001111222",
        "keterangan": "E2E"
    }
    res = requests.post(f"{BASE}/pickup-points", headers={"Authorization": f"Bearer {token}"}, json=data)
    log("ADMIN: TAMBAH PICKUP POINT", res)
    return res.json().get("pickupPoint", {}).get("_id")

def konsumen_add_cart(token, produk_id):
    res = requests.post(f"{BASE}/cart/add", headers={"Authorization": f"Bearer {token}"}, json={"produk": produk_id, "jumlah": 2})
    log("KONSUMEN: ADD CART", res)

def konsumen_update_cart(token, produk_id):
    res = requests.post(f"{BASE}/cart/add", headers={"Authorization": f"Bearer {token}"}, json={"produk": produk_id, "jumlah": 5})
    log("KONSUMEN: UPDATE CART", res)

def konsumen_get_cart(token):
    res = requests.get(f"{BASE}/cart/me", headers={"Authorization": f"Bearer {token}"})
    log("KONSUMEN: GET CART", res)
    items = res.json().get("items", [])
    return items[0]["produk"] if items else None

def konsumen_remove_cart(token, produk_id):
    res = requests.delete(f"{BASE}/cart/remove", headers={"Authorization": f"Bearer {token}"}, json={"produk": produk_id})
    log("KONSUMEN: REMOVE CART", res)

def konsumen_checkout(token, id_titik_ambil):
    data = {
        "id_titik_ambil": id_titik_ambil,
        "alamat_pengiriman": "Jl. Konsumen",
        "no_hp": "0812000111",
        "metode_pembayaran": "transfer",
        "catatan": "Checkout E2E"
    }
    res = requests.post(f"{BASE}/checkout", headers={"Authorization": f"Bearer {token}"}, json=data)
    log("KONSUMEN: CHECKOUT", res)
    return res.json().get("checkout", {}).get("_id")

def get_checkout_detail(token, checkout_id):
    res = requests.get(f"{BASE}/checkout/" + checkout_id, headers={"Authorization": f"Bearer {token}"})
    log("KONSUMEN: GET CHECKOUT DETAIL", res)
    return res.json().get("cart_items", [])

def admin_approve_checkout(token, checkout_id):
    res = requests.patch(f"{BASE}/checkout/{checkout_id}/verify", headers={"Authorization": f"Bearer {token}"}, json={"status_checkout": "paid"})
    log("ADMIN: APPROVE CHECKOUT (BAYAR)", res)

def admin_add_logistics(token, order_id, petani_id, pickup_point_id):
    data = {
        "id_order": order_id,
        "id_petani": petani_id,
        "id_titik_ambil": pickup_point_id,
        "jadwal_pengambilan": "2025-06-11T07:00:00.000Z",
        "jadwal_pengiriman": "2025-06-11T09:00:00.000Z",
        "kurir": "JNE",
        "catatan": "E2E"
    }
    res = requests.post(f"{BASE}/logistics", headers={"Authorization": f"Bearer {token}"}, json=data)
    log("ADMIN: ADD LOGISTICS", res)
    return res.json().get("logistics", {}).get("_id")

def get_order_for_petani(token):
    res = requests.get(f"{BASE}/orders/petani/me", headers={"Authorization": f"Bearer {token}"})
    log("PETANI: GET ORDER MASUK", res)
    orders = res.json()
    return orders[0]["_id"] if orders else None

def petugas_pickup_verification(token, order_id, konsumen_id, titik_ambil_id, foto_path="foto_bukti_e2e.jpg"):
    if not os.path.exists(foto_path):
        with open(foto_path, "wb") as f: f.write(b"\x89PNG\r\n\x1a\n")
    data = {
        "id_order": order_id,
        "id_konsumen": konsumen_id,
        "id_titik_ambil": titik_ambil_id,
        "waktu_verifikasi": "14:00",
        "keterangan": "Barang diterima baik",
        "status_verifikasi": "berhasil"
    }
    files = {"foto_bukti": open(foto_path, "rb")}
    res = requests.post(f"{BASE}/pickup-verification", headers={"Authorization": f"Bearer {token}"}, data=data, files=files)
    log("PETUGAS: PICKUP VERIFICATION", res)

def konsumen_delete_profile(token):
    res = requests.delete(f"{BASE}/users/me", headers={"Authorization": f"Bearer {token}"})
    log("KONSUMEN: DELETE PROFILE", res)

def main():
    open(LOG_FILE, "w", encoding="utf-8").close()

    # Step 1: Petani - Register, login, tambah produk
    token_petani, id_petani, _ = register_login("petani")
    produk_id = add_product(token_petani)

    # Step 2: Admin - Register, login, approve produk, tambah pickup point
    token_admin, _, _ = register_login("admin")
    admin_approve_product(token_admin, produk_id)
    id_titik_ambil = add_pickup_point(token_admin)

    # Step 3: Konsumen - Register, login, lihat produk, add cart, update cart, checkout
    token_konsumen, id_konsumen, _ = register_login("konsumen")
    konsumen_add_cart(token_konsumen, produk_id)
    konsumen_update_cart(token_konsumen, produk_id)
    konsumen_get_cart(token_konsumen)
    konsumen_checkout(token_konsumen, id_titik_ambil)

    # Step 4: Admin - Approve checkout
    res_orders = requests.get(f"{BASE}/orders", headers={"Authorization": f"Bearer {token_admin}"})
    log("ADMIN: GET ALL ORDERS", res_orders)
    orders = res_orders.json()
    order_id = orders[0]["_id"] if orders else None
    checkout_id = order_id  # Assume order id = checkout id for simplicity

    admin_approve_checkout(token_admin, checkout_id)
    admin_add_logistics(token_admin, order_id, id_petani, id_titik_ambil)

    # Step 5: Petugas - Register, login, pickup verification
    token_petugas, _, _ = register_login("petugas")
    petugas_pickup_verification(token_petugas, order_id, id_konsumen, id_titik_ambil)

    # Step 6: Konsumen - delete profile (contoh DELETE)
    konsumen_delete_profile(token_konsumen)

    print(f"\n=== Semua log hasil uji WORKFLOW E2E tersimpan di {LOG_FILE}")

if __name__ == "__main__":
    main()
