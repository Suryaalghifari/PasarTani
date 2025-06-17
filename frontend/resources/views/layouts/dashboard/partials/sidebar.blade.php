<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2" id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-xl-none" id="iconSidenav"></i>
    <a class="navbar-brand px-4 py-3 m-0" href="#">
      <img src="{{ asset('template/dashboard/img/LogoDashboard.png') }}" class="navbar-brand-img" width="26" height="26" alt="main_logo">
      <span class="ms-1 text-sm text-dark">Pasar Tani</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0 mb-2">
  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      @if ($role === 'admin')
        <li class="nav-item">
          <a href="{{ route('admin.dashboard') }}" class="nav-link active bg-gradient-dark text-white">
            <i class="material-symbols-rounded opacity-5 me-2">dashboard</i>
            <span class="nav-link-text ms-1 fw-bold">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="material-symbols-rounded opacity-5 me-2">report_problem</i>
            <span class="nav-link-text ms-1">Verivikasi Produk</span>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="material-symbols-rounded opacity-5 me-2">badge</i>
            <span class="nav-link-text ms-1">Pengajuan Surat SKCK</span>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="material-symbols-rounded opacity-5 me-2">favorite</i>
            <span class="nav-link-text ms-1">Pengajuan Surat Menikah</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">
            Account pages
          </h6>
        </li>
        <li class="nav-item">
      <form action="{{ route('logout') }}" method="POST" style="margin:0; display:inline;">
        @csrf
        <button type="submit" class="nav-link text-danger" style="background:none; border:none; padding:0; width:100%; text-align:left;">
          <i class="material-symbols-rounded opacity-5 me-2">logout</i>
          <span class="nav-link-text ms-1">Logout</span>
        </button>
      </form>
    </li>

        
      @elseif ($role === 'petani')
        <li class="nav-item">
          <a href="{{ route('petani.dashboard') }}" class="nav-link active bg-gradient-dark text-white">
            <i class="material-symbols-rounded opacity-5 me-2">dashboard</i>
            <span class="nav-link-text ms-1 fw-bold">Dashboard Petani</span>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('petani.dashboard') }}" class="nav-link">
            <i class="material-symbols-rounded opacity-5 me-2">shopping_cart</i>
            <span class="nav-link-text ms-1">Produk Saya</span>
          </a>
        </li>
        <li class="nav-item">
        <a href="{{ route('petani.profile.edit') }}" class="nav-link active bg-gradient-dark text-white">
          <i class="material-symbols-rounded opacity-5 me-2">person</i>
          <span class="nav-link-text ms-1">Edit Profil</span>
        </a>
      </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">
            Account pages
          </h6>
        </li>
        <form action="{{ route('logout') }}" method="POST" style="margin:0; display:inline;">
        @csrf
        <button type="submit" class="nav-link text-danger" style="background:none; border:none; padding:0; width:100%; text-align:left;">
          <i class="material-symbols-rounded opacity-5 me-2">logout</i>
          <span class="nav-link-text ms-1">Logout</span>
        </button>
      </form>
    </li>

      @endif
    </ul>
  </div>
  <div class="sidenav-footer position-absolute w-100 bottom-0 "></div>
</aside>
