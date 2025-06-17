import requests
import json
import time

BASE = "http://localhost:5000/api"
LOG_FILE = "log_testing_admin.txt"

def log(title, res):
    try: body = res.json()
    except: body = res.text
    line = f"\n=== {title} ===\nSTATUS: {res.status_code}\nRESPONSE: {json.dumps(body, indent=2) if isinstance(body, dict) else body}\n"
    print(line)
    with open(LOG_FILE, "a", encoding="utf-8") as f: f.write(line)

def register_login_admin():
    email = f"tesadmin{int(time.time())}@mail.com"
    pw = "admin123"
    res = requests.post(f"{BASE}/auth/register", json={"nama": "Admin Test", "email": email, "password": pw, "peran": "admin"})
    log("REGISTER ADMIN", res)
    res = requests.post(f"{BASE}/auth/login", json={"email": email, "password": pw})
    log("LOGIN ADMIN", res)
    token = res.json().get("token", "")
    return token

def get_all_users(token):
    res = requests.get(f"{BASE}/users", headers={"Authorization": f"Bearer {token}"})
    log("GET ALL USERS", res)
    users = res.json()
    if isinstance(users, list) and users:
        return users[0]["_id"]
    return None

def get_all_products(token):
    res = requests.get(f"{BASE}/products", headers={"Authorization": f"Bearer {token}"})
    log("GET ALL PRODUCTS", res)
    prods = res.json()
    if isinstance(prods, list) and prods:
        return prods[0]["_id"]
    return None

def verify_product(token, produk_id, status="tersedia"):
    res = requests.patch(f"{BASE}/products/{produk_id}/verify", headers={"Authorization": f"Bearer {token}"}, json={"status": status})
    log(f"VERIFIKASI PRODUK ({status})", res)

def add_pickup_point(token):
    data = {
        "nama": "Gudang Tani Admin",
        "alamat": "Jl. Admin No. 8",
        "jam_operasional": "08:00 - 17:00",
        "kontak": "0899112233",
        "keterangan": "Gudang Pusat"
    }
    res = requests.post(f"{BASE}/pickup-points", headers={"Authorization": f"Bearer {token}"}, json=data)
    log("TAMBAH PICKUP POINT", res)
    try:
        return res.json()["pickupPoint"]["_id"]
    except:
        return None

def get_pickup_points(token):
    res = requests.get(f"{BASE}/pickup-points", headers={"Authorization": f"Bearer {token}"})
    log("LIST PICKUP POINT", res)
    data = res.json()
    if data and isinstance(data, list) and len(data) > 0:
        return data[0]["_id"]
    return None

def update_pickup_point(token, id_titik):
    data = {
        "nama": "Gudang Tani Edit",
        "alamat": "Jl. Admin Baru",
        "jam_operasional": "07:00 - 16:00",
        "kontak": "0899000111",
        "keterangan": "Edit Gudang"
    }
    res = requests.put(f"{BASE}/pickup-points/{id_titik}", headers={"Authorization": f"Bearer {token}"}, json=data)
    log("UPDATE PICKUP POINT", res)

def delete_pickup_point(token, id_titik):
    res = requests.delete(f"{BASE}/pickup-points/{id_titik}", headers={"Authorization": f"Bearer {token}"})
    log("DELETE PICKUP POINT", res)

def get_all_orders(token):
    res = requests.get(f"{BASE}/orders", headers={"Authorization": f"Bearer {token}"})
    log("GET ALL ORDERS", res)
    data = res.json()
    if data and isinstance(data, list) and len(data) > 0:
        return data[0]["_id"]
    return None

def approve_payment(token):
    # Get all payments
    res = requests.get(f"{BASE}/payments", headers={"Authorization": f"Bearer {token}"})
    log("LIST PAYMENTS", res)
    data = res.json()
    if data and isinstance(data, list) and len(data) > 0:
        id_payment = data[0]["_id"]
        res2 = requests.patch(f"{BASE}/payments/{id_payment}/verify", headers={"Authorization": f"Bearer {token}"}, json={"status": "berhasil"})
        log("APPROVE PAYMENT", res2)

def generate_report(token):
    data = {"periode_awal": "2024-01-01", "periode_akhir": "2024-12-31"}
    res = requests.post(f"{BASE}/reports/sales", headers={"Authorization": f"Bearer {token}"}, json=data)
    log("GENERATE SALES REPORT", res)

def dashboard_admin(token):
    res = requests.get(f"{BASE}/dashboard/admin", headers={"Authorization": f"Bearer {token}"})
    log("DASHBOARD ADMIN", res)

def test_negative_no_token():
    res = requests.get(f"{BASE}/users")
    log("NEGATIVE: GET USERS TANPA TOKEN", res)

def test_negative_wrong_input(token):
    res = requests.post(f"{BASE}/pickup-points", headers={"Authorization": f"Bearer {token}"}, json={
        "nama": "", "alamat": "", "jam_operasional": ""
    })
    log("NEGATIVE: ADD PICKUP POINT SALAH", res)

def main():
    open(LOG_FILE, "w", encoding="utf-8").close()
    token = register_login_admin()
    if not token:
        print("Token gagal didapatkan, STOP TEST!")
        return

    # Lihat semua user dan produk
    get_all_users(token)
    produk_id = get_all_products(token)
    # Verifikasi produk (approve, tolak)
    if produk_id:
        verify_product(token, produk_id, "tersedia")
        verify_product(token, produk_id, "ditolak")
    # CRUD pickup point
    id_titik = add_pickup_point(token)
    get_pickup_points(token)
    if id_titik:
        update_pickup_point(token, id_titik)
        delete_pickup_point(token, id_titik)
    # Orders dan payment
    get_all_orders(token)
    approve_payment(token)
    # Report dan dashboard
    generate_report(token)
    dashboard_admin(token)
    # Negative test
    test_negative_no_token()
    test_negative_wrong_input(token)
    print(f"\n=== Semua log hasil uji ADMIN tersimpan di {LOG_FILE}")

if __name__ == "__main__":
    main()
