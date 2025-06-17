import requests
import json
import time

BASE = "http://localhost:5000/api"
LOG_FILE = "log_testing_petugas.txt"

def log(title, res):
    try: body = res.json()
    except: body = res.text
    line = f"\n=== {title} ===\nSTATUS: {res.status_code}\nRESPONSE: {json.dumps(body, indent=2) if isinstance(body, dict) else body}\n"
    print(line)
    with open(LOG_FILE, "a", encoding="utf-8") as f: f.write(line)

def register_login_petugas():
    email = f"tespetugas{int(time.time())}@mail.com"
    pw = "petugas123"
    res = requests.post(f"{BASE}/auth/register", json={"nama": "Petugas Test", "email": email, "password": pw, "peran": "petugas"})
    log("REGISTER PETUGAS", res)
    res = requests.post(f"{BASE}/auth/login", json={"email": email, "password": pw})
    log("LOGIN PETUGAS", res)
    token = res.json().get("token", "")
    return token

def get_profile(token):
    res = requests.get(f"{BASE}/users/me", headers={"Authorization": f"Bearer {token}"})
    log("GET PROFILE", res)

def dashboard_petugas(token):
    res = requests.get(f"{BASE}/dashboard/petugas", headers={"Authorization": f"Bearer {token}"})
    log("DASHBOARD PETUGAS", res)

def get_pickup_verifications(token):
    res = requests.get(f"{BASE}/pickup-verification", headers={"Authorization": f"Bearer {token}"})
    log("LIST PICKUP VERIFICATION", res)

def create_pickup_verification(token, id_order, id_konsumen, id_titik_ambil, foto_path="foto_bukti.jpg"):
    # Buat file dummy jika tidak ada
    import os
    if not os.path.exists(foto_path):
        with open(foto_path, "wb") as f: f.write(b"\x89PNG\r\n\x1a\n")
    data = {
        "id_order": id_order,
        "id_konsumen": id_konsumen,
        "id_titik_ambil": id_titik_ambil,
        "waktu_verifikasi": "14:30",
        "keterangan": "Barang diterima baik",
        "status_verifikasi": "berhasil"
    }
    files = {"foto_bukti": open(foto_path, "rb")}
    res = requests.post(f"{BASE}/pickup-verification", headers={"Authorization": f"Bearer {token}"}, data=data, files=files)
    log("CREATE PICKUP VERIFICATION", res)

def test_negative_no_token():
    res = requests.get(f"{BASE}/dashboard/petugas")
    log("NEGATIVE: DASHBOARD PETUGAS TANPA TOKEN", res)

def main():
    open(LOG_FILE, "w", encoding="utf-8").close()
    token = register_login_petugas()
    if not token:
        print("Token gagal didapatkan, STOP TEST!")
        return

    get_profile(token)
    dashboard_petugas(token)
    get_pickup_verifications(token)
    # Untuk test create pickup verification, perlu id_order, id_konsumen, id_titik_ambil valid.
    # Contoh pemakaian (jika data tersedia):
    # create_pickup_verification(token, "id_order", "id_konsumen", "id_titik_ambil")
    test_negative_no_token()
    print(f"\n=== Semua log hasil uji PETUGAS tersimpan di {LOG_FILE}")

if __name__ == "__main__":
    main()
