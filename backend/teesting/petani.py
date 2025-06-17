import requests
import json
import time
import os

BASE = "http://localhost:5000/api"
LOG_FILE = "log_testing_petani.txt"

def log(title, res):
    try: body = res.json()
    except: body = res.text
    line = f"\n=== {title} ===\nSTATUS: {res.status_code}\nRESPONSE: {json.dumps(body, indent=2) if isinstance(body, dict) else body}\n"
    print(line)
    with open(LOG_FILE, "a", encoding="utf-8") as f: f.write(line)

def register_login_petani():
    email = f"tespetani{int(time.time())}@mail.com"
    pw = "petani123"
    # Register
    res = requests.post(f"{BASE}/auth/register", json={"nama": "Petani Test", "email": email, "password": pw, "peran": "petani"})
    log("REGISTER PETANI", res)
    # Login
    res = requests.post(f"{BASE}/auth/login", json={"email": email, "password": pw})
    log("LOGIN PETANI", res)
    token = res.json().get("token", "")
    return token

def get_profile(token):
    res = requests.get(f"{BASE}/users/me", headers={"Authorization": f"Bearer {token}"})
    log("GET PROFILE", res)

def update_profile(token):
    res = requests.put(f"{BASE}/users/me", headers={"Authorization": f"Bearer {token}"}, json={
        "nama": "Petani Diupdate",
        "alamat": "Jl. Ladang 12",
        "no_hp": "081234567890"
    })
    log("UPDATE PROFILE", res)

def add_product(token, foto_path='foto_tomat.jpg'):
    if not os.path.exists(foto_path):
        # buat file dummy jika tidak ada, supaya tetap jalan
        with open(foto_path, "wb") as f:
            f.write(b"\x89PNG\r\n\x1a\n")  # minimal PNG header
    files = {'foto': open(foto_path, 'rb')}
    data = {
        "nama": f"Tomat Test {int(time.time())}",
        "deskripsi": "Tomat segar automation",
        "kategori": "sayur",
        "harga": 12000,
        "stok": 25
    }
    res = requests.post(f"{BASE}/products", headers={"Authorization": f"Bearer {token}"}, data=data, files=files)
    log("TAMBAH PRODUK", res)
    try:
        return res.json()["product"]["_id"]
    except:
        return None

def get_my_products(token):
    res = requests.get(f"{BASE}/products/petani/me", headers={"Authorization": f"Bearer {token}"})
    log("MY PRODUCTS", res)
    data = res.json()
    if data and isinstance(data, list) and len(data) > 0:
        return data[0]["_id"]
    return None

def update_product(token, produk_id):
    data = {
        "nama": "Tomat Edit Automation",
        "deskripsi": "Edit deskripsi produk",
        "kategori": "sayur",
        "harga": 10000,
        "stok": 100
    }
    res = requests.put(f"{BASE}/products/{produk_id}", headers={"Authorization": f"Bearer {token}"}, data=data)
    log("UPDATE PRODUK", res)

def delete_product(token, produk_id):
    res = requests.delete(f"{BASE}/products/{produk_id}", headers={"Authorization": f"Bearer {token}"})
    log("DELETE PRODUK", res)

def dashboard_petani(token):
    res = requests.get(f"{BASE}/dashboard/petani", headers={"Authorization": f"Bearer {token}"})
    log("DASHBOARD PETANI", res)

def test_negative_no_token():
    res = requests.get(f"{BASE}/products/petani/me")
    log("NEGATIVE: MY PRODUCTS TANPA TOKEN", res)

def test_negative_wrong_input(token):
    # harga kurang dari 0
    data = {
        "nama": "Tomat Error",
        "deskripsi": "Harus error",
        "kategori": "sayur",
        "harga": -100,
        "stok": 5
    }
    res = requests.post(f"{BASE}/products", headers={"Authorization": f"Bearer {token}"}, data=data)
    log("NEGATIVE: ADD PRODUK SALAH", res)

def main():
    # Kosongkan log file dulu
    open(LOG_FILE, "w", encoding="utf-8").close()

    # Register & login petani
    token = register_login_petani()
    if not token:
        print("Token gagal didapatkan, STOP TEST!")
        return
    get_profile(token)
    update_profile(token)
    produk_id = add_product(token)
    get_my_products(token)
    if produk_id:
        update_product(token, produk_id)
        get_my_products(token)
        delete_product(token, produk_id)
        get_my_products(token)
    dashboard_petani(token)
    test_negative_no_token()
    test_negative_wrong_input(token)
    print(f"\n=== Semua log hasil uji PETANI tersimpan di {LOG_FILE}")

if __name__ == "__main__":
    main()
