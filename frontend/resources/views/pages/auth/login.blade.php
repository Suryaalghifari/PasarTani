@extends('layouts.auth.auth')

@section('title', 'Login - Pasar Tani Digital')

@section('content')
<div class="auth-card">
    <div class="card-grid">
        <!-- Image Side -->
        <div class="image-side">
            <img src="{{ asset('template/Auth/img/login.jpg') }}" alt="Petani bekerja di sawah terasering" class="bg-image" />
            <div class="image-overlay"></div>
            <div class="floating-particle particle-1"></div>
            <div class="floating-particle particle-2"></div>
        </div>

        <!-- Form Side -->
        <div class="form-side">
            <div class="background-pattern"></div>

            <div class="form-container">
                <div class="form-header">
                    <h1 class="form-title">Masuk ke Akun Anda</h1>
                    <p class="form-subtitle">Selamat datang kembali di Pasar Tani Digital</p>
                </div>

                <form id="loginForm" class="auth-form" method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email" class="form-label">Alamat Email</label>
                        <div class="input-group">
                            <i class="fas fa-envelope input-icon"></i>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="nama@email.com"
                                class="form-input @error('email') error @enderror"
                                value="{{ old('email') }}"
                                {{-- Hapus required supaya validasi manual --}}
                            />
                            <div class="input-underline"></div>
                        </div>
                        @error('email')
                            <div class="validation-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <div class="input-group">
                            <i class="fas fa-lock input-icon"></i>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="••••••••"
                                class="form-input @error('password') error @enderror"
                                {{-- Hapus required supaya validasi manual --}}
                            />
                            <button type="button" class="password-toggle" id="passwordToggle">
                                <i class="fas fa-eye"></i>
                            </button>
                            <div class="input-underline"></div>
                        </div>
                        @error('password')
                            <div class="validation-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" id="rememberMe" name="remember" {{ old('remember') ? 'checked' : '' }} />
                            <span class="checkmark"></span>
                            Ingat saya
                        </label>
                        <a href="#" class="forgot-link">Lupa kata sandi?</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-btn" id="loginBtn">
                        <span class="btn-text">Masuk</span>
                        <span class="btn-loader">
                            <i class="fas fa-spinner fa-spin"></i>
                            Memproses...
                        </span>
                        <div class="btn-shine"></div>
                    </button>

                    <!-- Register Link -->
                    <p class="auth-link">
                        Belum punya akun? 
                        <a href="{{ route('register') }}">Daftar sekarang</a>
                    </p>

                    @if ($errors->has('error'))
                        <div class="validation-message" style="color:red; margin-top:1rem;">
                            {{ $errors->first('error') }}
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
