@extends('layouts.auth.auth')

@section('title', 'Register - Pasar Tani Digital')

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
                    <h1 class="form-title">Daftar Akun Anda</h1>
                    <p class="form-subtitle">Bergabunglah dengan komunitas Pasar Tani Digital</p>
                </div>

                <form id="registerForm" class="auth-form" method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            Email <span class="required">*</span>
                        </label>
                        <div class="input-group">
                            <i class="fas fa-envelope input-icon"></i>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="nama@email.com"
                                class="form-input @error('email') error @enderror"
                                value="{{ old('email') }}"
                                {{-- hapus required --}}
                            />
                        </div>
                        @error('email')
                            <div class="validation-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            Password <span class="required">*</span>
                            <span class="field-hint">(minimal 6 karakter)</span>
                        </label>
                        <div class="input-group">
                            <i class="fas fa-lock input-icon"></i>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="••••••••"
                                class="form-input @error('password') error @enderror"
                                {{-- hapus required dan minlength --}}
                            />
                            <button type="button" class="password-toggle" id="passwordToggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="validation-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="form-group">
                        <label for="nama" class="form-label">
                            Nama Lengkap <span class="required">*</span>
                            <span class="field-hint">(maksimal 255 karakter)</span>
                        </label>
                        <div class="input-group">
                            <i class="fas fa-user input-icon"></i>
                            <input
                                type="text"
                                id="nama"
                                name="nama"
                                placeholder="Nama Lengkap Anda"
                                class="form-input @error('nama') error @enderror"
                                maxlength="255"
                                value="{{ old('nama') }}"
                                {{-- hapus required --}}
                            />
                        </div>
                        @error('nama')
                            <div class="validation-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- No HP (opsional) -->
                    <div class="form-group">
                        <label for="no_hp" class="form-label">
                            Nomor HP <span class="optional">(opsional)</span>
                        </label>
                        <div class="input-group">
                            <i class="fas fa-phone input-icon"></i>
                            <input
                                type="tel"
                                id="no_hp"
                                name="no_hp"
                                placeholder="081234567890"
                                class="form-input @error('no_hp') error @enderror"
                                value="{{ old('no_hp') }}"
                            />
                        </div>
                        @error('no_hp')
                            <div class="validation-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Peran -->
                    <div class="form-group">
                        <label for="peran" class="form-label">
                            Pilih Peran Anda <span class="required">*</span>
                        </label>
                        <div class="select-group">
                            <select id="peran" name="peran" class="form-select @error('peran') error @enderror">
                                <option value="">Pilih Peran Anda</option>
                                <option value="petani" {{ old('peran') == 'petani' ? 'selected' : '' }}>🌾 Petani</option>
                                <option value="konsumen" {{ old('peran') == 'konsumen' ? 'selected' : '' }}>🛒 Konsumen</option>
                                <option value="petugas" {{ old('peran') == 'petugas' ? 'selected' : '' }}>👷‍♂️ Petugas</option>
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                        @error('peran')
                            <div class="validation-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Alamat (opsional) -->
                    <div class="form-group">
                        <label for="alamat" class="form-label">
                            Alamat <span class="optional">(opsional)</span>
                        </label>
                        <div class="input-group">
                            <i class="fas fa-map-marker-alt input-icon textarea-icon"></i>
                            <textarea
                                id="alamat"
                                name="alamat"
                                placeholder="Alamat lengkap Anda"
                                class="form-textarea"
                                rows="3"
                            >{{ old('alamat') }}</textarea>
                        </div>
                        @error('alamat')
                            <div class="validation-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-btn" id="registerBtn">
                        <span class="btn-text">Daftar</span>
                        <span class="btn-loader">
                            <i class="fas fa-spinner fa-spin"></i>
                            Mendaftar...
                        </span>
                        <div class="btn-shine"></div>
                    </button>

                    <!-- Login Link -->
                    <p class="auth-link">
                        Sudah punya akun?
                        <a href="{{ route('login') }}">Masuk sekarang</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
