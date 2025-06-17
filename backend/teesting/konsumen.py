import requests
import json

BASE = "http://localhost:5000/api"
LOG_FILE = "log_testing_konsumen.txt"

def log(title, res):
    try: body = res.json()
    except: body = res.text
    line = f"\n=== {title} ===\nSTATUS: {res.status_code}\nRESPONSE: {json.dumps(body, indent=2) if isinstance(body, dict) else body}\n"
    print(line)
    with open(LOG_FILE, "a", encoding="utf-8") as f: f.write(line)

def register_login_konsumen():
    email = f"teskonsumen{str(int(1000*__import__('time').time()))}@mail.com"
    pw = "passkonsumen123"
    # Register
    res = requests.post(f"{BASE}/auth/register", json={"nama": "Konsumen Test", "email": email, "password": pw, "peran": "konsumen"})
    log("REGISTER KONSUMEN", res)
    # Login
    res = requests.post(f"{BASE}/auth/login", json={"email": email, "password": pw})
    log("LOGIN KONSUMEN", res)
    token = res.json().get("token", "")
    return token

def get_profile(token):
    res = requests.get(f"{BASE}/users/me", headers={"Authorization": f"Bearer {token}"})
    log("GET PROFILE", res)
    return res.json().get("_id")

def update_profile(token):
    res = requests.put(f"{BASE}/users/me", headers={"Authorization": f"Bearer {token}"}, json={
        "nama": "Konsumen Diupdate",
        "alamat": "Jl. Update",
        "no_hp": "085712345678"
    })
    log("UPDATE PROFILE", res)

def list_products():
    res = requests.get(f"{BASE}/products")
    log("LIST PRODUCTS", res)
    if res.status_code == 200 and len(res.json()) > 0:
        return res.json()[0]["_id"]
    return None

def product_detail(produk_id):
    res = requests.get(f"{BASE}/products/" + produk_id)
    log("PRODUCT DETAIL", res)

def add_to_cart(token, produk_id, jumlah=2):
    res = requests.post(f"{BASE}/cart/add", headers={"Authorization": f"Bearer {token}"}, json={
        "produk": produk_id,
        "jumlah": jumlah
    })
    log("ADD TO CART", res)

def update_cart_item(token, produk_id, jumlah=5):
    # Update jumlah dengan POST yang sama
    add_to_cart(token, produk_id, jumlah)

def get_cart(token):
    res = requests.get(f"{BASE}/cart/me", headers={"Authorization": f"Bearer {token}"})
    log("GET CART", res)

def remove_cart_item(token, produk_id):
    res = requests.delete(f"{BASE}/cart/remove", headers={"Authorization": f"Bearer {token}"}, json={
        "produk": produk_id
    })
    log("REMOVE CART ITEM", res)

def clear_cart(token):
    res = requests.delete(f"{BASE}/cart/clear", headers={"Authorization": f"Bearer {token}"})
    log("CLEAR CART", res)

def checkout(token, id_titik_ambil):
    res = requests.post(f"{BASE}/checkout", headers={"Authorization": f"Bearer {token}"}, json={
        "id_titik_ambil": id_titik_ambil,
        "alamat_pengiriman": "Jl. Konsumen",
        "no_hp": "081212121212",
        "metode_pembayaran": "transfer",
        "catatan": "Test checkout"
    })
    log("CHECKOUT", res)
    try:
        return res.json()["checkout"]["_id"]
    except:
        return None

def get_my_orders(token):
    res = requests.get(f"{BASE}/orders/me", headers={"Authorization": f"Bearer {token}"})
    log("GET MY ORDERS", res)
    orders = res.json()
    if orders and len(orders) > 0:
        return orders[0]["_id"]
    return None

def get_order_detail(token, id_order):
    res = requests.get(f"{BASE}/orders/" + id_order, headers={"Authorization": f"Bearer {token}"})
    log("ORDER DETAIL", res)

def test_notif(token):
    res = requests.get(f"{BASE}/notifications/me", headers={"Authorization": f"Bearer {token}"})
    log("NOTIFIKASI SAYA", res)

def test_negative_no_token():
    res = requests.get(f"{BASE}/users/me")  # No Authorization
    log("NEGATIVE: TANPA TOKEN", res)

def test_negative_wrong_input(token):
    res = requests.post(f"{BASE}/cart/add", headers={"Authorization": f"Bearer {token}"}, json={
        "produk": "",
        "jumlah": -1
    })
    log("NEGATIVE: CART INPUT SALAH", res)

def main():
    open(LOG_FILE, "w", encoding="utf-8").close()
    token = register_login_konsumen()
    if not token:
        print("Token gagal didapatkan, STOP TEST!")
        return
    get_profile(token)
    update_profile(token)
    produk_id = list_products()
    if produk_id:
        product_detail(produk_id)
        add_to_cart(token, produk_id)
        update_cart_item(token, produk_id, jumlah=4)
        get_cart(token)
        remove_cart_item(token, produk_id)
        clear_cart(token)
        add_to_cart(token, produk_id) # tambah lagi supaya bisa lanjut checkout

        # --- Pastikan ada pickup point aktif! ---
        res = requests.get(f"{BASE}/pickup-points")
        try: id_titik_ambil = res.json()[0]["_id"]
        except: id_titik_ambil = None

        if id_titik_ambil:
            id_checkout = checkout(token, id_titik_ambil)
            get_my_orders(token)
            # Lihat order detail jika ada order
            id_order = get_my_orders(token)
            if id_order:
                get_order_detail(token, id_order)

    test_notif(token)
    test_negative_no_token()
    test_negative_wrong_input(token)
    print(f"\n=== Semua log hasil uji disimpan di {LOG_FILE}")

if __name__ == "__main__":
    main()
