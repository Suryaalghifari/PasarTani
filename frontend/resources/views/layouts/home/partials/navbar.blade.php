<nav class="navbar">
    <div class="nav-container">
        <div class="nav-content">
            <!-- Logo -->
            <a href="#" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <span class="logo-text">TaniDirect</span>
            </a>

            <!-- Search Bar -->
            <div class="search-container">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Cari sayuran, buah, beras, atau produk tani lainnya..." class="search-input">
                </div>
            </div>

            <!-- Right Menu -->
            <div class="nav-right">
    <a href="{{ route('keranjang') }}" class="nav-btn cart-btn"
   @if(!session('user')) onclick="window.location='{{ route('login') }}'; return false;" @endif
   title="@if(!session('user')) Login untuk akses keranjang @endif">
    <i class="fas fa-shopping-cart"></i>
    <span class="cart-badge">
        @if(session('user'))
            {{ $cartCount ?? 0 }}
        @else
            0
        @endif
    </span>
</a>

    <!-- User Icon/Dropdown, selalu tampil -->
    <div class="dropdown">
        <button class="nav-btn dropdown-toggle">
            <i class="fas fa-user"></i>
        </button>
        <div class="dropdown-menu">
            @if(session('user'))
                <div class="dropdown-header">Akun {{ ucfirst(session('user')['peran'] ?? 'Pelanggan') }}</div>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-user"></i>
                    <span>Edit Profile</span>
                </a>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-box"></i>
                    <span>Pesanan Saya</span>
                </a>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-heart"></i>
                    <span>Produk Favorit</span>
                </a>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-credit-card"></i>
                    <span>Metode Pembayaran</span>
                </a>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </a>
                <div class="dropdown-divider"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger" style="background:none; border:none;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            @else
                <div class="dropdown-header">Anda belum login</div>
                <div class="dropdown-divider"></div>
                <a href="{{ route('login') }}" class="dropdown-item">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Login</span>
                </a>
            @endif
        </div>
    </div>
</div>

    </div>
</nav>
