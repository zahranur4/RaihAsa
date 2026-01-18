<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Donatur - RaihAsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/font-awesome.css','resources/css/style.css','resources/css/components.css','resources/css/forms.css','resources/css/donor-profile.css'])
</head>
<body>
    
<!-- Header with Navigation -->
    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                    <img src="/assets/raih asa logo.png" alt="RaihAsa Logo" height="40" class="me-2">
                    <span>RaihAsa</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}#about">Tentang</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('food-rescue') }}">Food Rescue</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('wishlist') }}">Wishlist</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('volunteer') }}">Relawan</a>
                        </li>
                        @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('my-donations') }}">Kontribusiku</a>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link requires-auth" href="{{ route('my-donations') }}">Kontribusiku</a>
                        </li>
                        @endauth
                    </ul>
                    <div class="d-flex">
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                @php
                                    $initials = strtoupper(substr(Auth::user()->nama ?? Auth::user()->name, 0, 1));
                                @endphp
                                <div style="width: 25px; height: 25px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 12px; margin-right: 8px; vertical-align: middle;">
                                    {{ $initials }}
                                </div>
                                {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                                <li><a class="dropdown-item" href="{{ route('donor-profile') }}"><i class="fas fa-user me-2"></i> Profil Saya</a></li>
                                <li><a class="dropdown-item" href="{{ route('donor-profile') }}#settings"><i class="fas fa-cog me-2"></i> Pengaturan</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt me-2"></i> Keluar</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Profil Donatur</h1>
            <p class="lead">Kelola informasi profil dan pantau kontribusi Anda</p>
        </div>
    </section>

    <!-- Profile Section - Tambahkan py-5 untuk padding yang sama -->
    <section class="profile-section py-5">
        <div class="container">
            <div class="row">
                <!-- Sidebar Navigation -->
                <div class="col-lg-3 mb-4">
                    <div class="profile-sidebar">
                        <div class="profile-avatar text-center mb-4">
                            @php
                                $initials = strtoupper(substr($user->nama ?? $user->name, 0, 1));
                            @endphp
                            <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 48px; margin-bottom: 1rem;">
                                {{ $initials }}
                            </div>
                            <h4>{{ $user->name }}</h4>
                            <p class="text-secondary">Halo, {{ explode(' ', $user->name)[0] }}</p>
                            <button class="btn btn-sm btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#changePhotoModal">
                                <i class="fas fa-camera me-1"></i> Ganti Foto
                            </button>
                        </div>
                        
                        <div class="profile-nav">
                            <ul class="nav nav-pills flex-column">
                                <li class="nav-item">
                                    <a class="nav-link active" id="info-tab" data-bs-toggle="pill" href="#info" role="tab" aria-controls="info" aria-selected="true">
                                        <i class="fas fa-user me-2"></i> Informasi Profil
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="donations-tab" data-bs-toggle="pill" href="#donations" role="tab" aria-controls="donations" aria-selected="false">
                                        <i class="fas fa-hand-holding-heart me-2"></i> Riwayat Donasi
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="statistics-tab" data-bs-toggle="pill" href="#statistics" role="tab" aria-controls="statistics" aria-selected="false">
                                        <i class="fas fa-chart-bar me-2"></i> Statistik
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="settings-tab" data-bs-toggle="pill" href="#settings" role="tab" aria-controls="settings" aria-selected="false">
                                        <i class="fas fa-cog me-2"></i> Pengaturan
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Main Content -->
                <div class="col-lg-9">
                    <div class="tab-content" id="profileTabContent">
                        <!-- Profile Information Tab -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Informasi Profil</h5>
                                    <button class="btn btn-sm btn-primary" id="editProfileBtn" type="button">
                                        <i class="fas fa-edit me-1"></i> Edit Profil
                                    </button>
                                </div>
                                <div class="card-body">
                                    @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                    @endif
                                    
                                    @if($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Terjadi kesalahan:</strong>
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                    @endif
                                    
                                    <form id="profileForm" method="POST" action="{{ route('donor-profile.update') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="fullname" class="form-label">Nama Lengkap</label>
                                                <input type="text" class="form-control" id="fullname" name="nama" value="{{ old('nama', $user->name) }}" disabled>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" disabled>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="phone" class="form-label">Nomor Telepon</label>
                                                <input type="tel" class="form-control" id="phone" name="nomor_telepon" value="{{ old('nomor_telepon', $user->nomor_telepon ?? '') }}" disabled>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="donorType" class="form-label">Jenis Donor</label>
                                                <select class="form-control" id="donorType" disabled>
                                                    <option value="individual" selected>Individu</option>
                                                    <option value="restaurant">Restoran</option>
                                                    <option value="catering">Jasa Boga</option>
                                                    <option value="market">Pasar/Supermarket</option>
                                                    <option value="company">Perusahaan</option>
                                                    <option value="other">Lainnya</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Alamat Lengkap</label>
                                            <textarea class="form-control" id="address" name="alamat" rows="3" disabled>{{ old('alamat', $user->alamat ?? '') }}</textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="city" class="form-label">Kota</label>
                                                <input type="text" class="form-control" id="city" name="kota" value="{{ old('kota', $user->kota ?? '') }}" disabled>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="postal" class="form-label">Kode Pos</label>
                                                <input type="text" class="form-control" id="postal" name="kode_pos" value="{{ old('kode_pos', $user->kode_pos ?? '') }}" disabled>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Deskripsi</label>
                                            <textarea class="form-control" id="description" name="deskripsi" rows="3" disabled>{{ old('deskripsi', $user->deskripsi ?? '') }}</textarea>
                                        </div>
                                        
                                        <div class="d-none" id="profileActions">
                                            <button type="button" class="btn btn-secondary me-2" id="cancelEditBtn">Batal</button>
                                            <button type="submit" class="btn btn-primary" id="saveProfileBtn">
                                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Verification Status -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Status Verifikasi</h5>
                                </div>
                                <div class="card-body">
                                    <div class="verification-status verified">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                                            <div>
                                                <h6 class="mb-1">Terverifikasi</h6>
                                                <p class="mb-0">Akun Anda telah terverifikasi. Anda dapat membuat donasi melalui platform.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Donation History Tab -->
                        <div class="tab-pane fade" id="donations" role="tabpanel" aria-labelledby="donations-tab">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Riwayat Donasi</h5>
                                    <div class="d-flex">
                                        <select class="form-select form-select-sm me-2" style="width: auto;">
                                            <option value="">Semua Jenis</option>
                                            <option value="food">Makanan</option>
                                            <option value="goods">Barang</option>
                                            <option value="money">Uang</option>
                                        </select>
                                        <select class="form-select form-select-sm me-2" style="width: auto;">
                                            <option value="">Semua Status</option>
                                            <option value="completed">Selesai</option>
                                            <option value="in-progress">Dalam Proses</option>
                                            <option value="cancelled">Dibatalkan</option>
                                        </select>
                                        <input type="month" class="form-control form-select-sm" style="width: auto;">
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Jenis</th>
                                                    <th>Item</th>
                                                    <th>Penerima</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>15 Jun 2023</td>
                                                    <td><span class="badge bg-info">Makanan</span></td>
                                                    <td>Nasi Kotak (20 porsi)</td>
                                                    <td>Panti Asuhan Harapan</td>
                                                    <td><span class="badge bg-success">Selesai</span></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#donationDetailModal">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>10 Jun 2023</td>
                                                    <td><span class="badge bg-warning">Barang</span></td>
                                                    <td>Buku Tulis (35 pcs)</td>
                                                    <td>Panti Asuhan Harapan</td>
                                                    <td><span class="badge bg-success">Selesai</span></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#donationDetailModal">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>5 Jun 2023</td>
                                                    <td><span class="badge bg-info">Makanan</span></td>
                                                    <td>Sayuran Segar (10 kg)</td>
                                                    <td>Panti Jompo Bahagia</td>
                                                    <td><span class="badge bg-warning">Dalam Proses</span></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#donationDetailModal">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>1 Jun 2023</td>
                                                    <td><span class="badge bg-primary">Uang</span></td>
                                                    <td>Rp 500.000</td>
                                                    <td>Rumah Belajar Cemerlang</td>
                                                    <td><span class="badge bg-success">Selesai</span></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#donationDetailModal">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <!-- Pagination -->
                                    <nav aria-label="Page navigation" class="mt-3">
                                        <ul class="pagination justify-content-center">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                                            </li>
                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">Next</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Statistics Tab -->
                        <div class="tab-pane fade" id="statistics" role="tabpanel" aria-labelledby="statistics-tab">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0">Statistik Donasi</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="stats-grid">
                                                <div class="stat-item">
                                                    <div class="stat-icon">
                                                        <i class="fas fa-hand-holding-heart"></i>
                                                    </div>
                                                    <div class="stat-info">
                                                        <h3>24</h3>
                                                        <p>Total Donasi</p>
                                                    </div>
                                                </div>
                                                <div class="stat-item">
                                                    <div class="stat-icon">
                                                        <i class="fas fa-utensils"></i>
                                                    </div>
                                                    <div class="stat-info">
                                                        <h3>15</h3>
                                                        <p>Food Rescue</p>
                                                    </div>
                                                </div>
                                                <div class="stat-item">
                                                    <div class="stat-icon">
                                                        <i class="fas fa-box"></i>
                                                    </div>
                                                    <div class="stat-info">
                                                        <h3>7</h3>
                                                        <p>Donasi Barang</p>
                                                    </div>
                                                </div>
                                                <div class="stat-item">
                                                    <div class="stat-icon">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </div>
                                                    <div class="stat-info">
                                                        <h3>2</h3>
                                                        <p>Donasi Uang</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <canvas id="donationTypeChart" height="200"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0">Penerima Donasi</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="recipientChart" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0">Tren Donasi</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="donationTrendChart" height="100"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Settings Tab -->
                        <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Pengaturan Akun</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="account-settings-tab" data-bs-toggle="tab" data-bs-target="#account-settings" type="button" role="tab" aria-controls="account-settings" aria-selected="true">Akun</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="password-settings-tab" data-bs-toggle="tab" data-bs-target="#password-settings" type="button" role="tab" aria-controls="password-settings" aria-selected="false">Password</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="notification-settings-tab" data-bs-toggle="tab" data-bs-target="#notification-settings" type="button" role="tab" aria-controls="notification-settings" aria-selected="false">Notifikasi</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="privacy-settings-tab" data-bs-toggle="tab" data-bs-target="#privacy-settings" type="button" role="tab" aria-controls="privacy-settings" aria-selected="false">Privasi</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="settingsTabContent">
                                        <!-- Account Settings -->
                                        <div class="tab-pane fade show active" id="account-settings" role="tabpanel" aria-labelledby="account-settings-tab">
                                            <form method="POST" action="{{ route('donor-profile.update') }}">
                                                @csrf
                                                @if(session('success'))
                                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        {{ session('success') }}
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                    </div>
                                                @endif
                                                @if($errors->any())
                                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <ul class="mb-0">
                                                            @foreach($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                    </div>
                                                @endif
                                                <div class="mb-3">
                                                    <label for="settings-email" class="form-label">Email</label>
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="settings-email" name="email" value="{{ old('email', $user->email) }}">
                                                    @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="settings-phone" class="form-label">Nomor Telepon</label>
                                                    <input type="tel" class="form-control @error('nomor_telepon') is-invalid @enderror" id="settings-phone" name="nomor_telepon" value="{{ old('nomor_telepon', $user->nomor_telepon ?? '') }}">
                                                    @error('nomor_telepon')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="settings-language" class="form-label">Bahasa</label>
                                                    <select class="form-select" id="settings-language">
                                                        <option value="id" selected>Bahasa Indonesia</option>
                                                        <option value="en">English</option>
                                                    </select>
                                                </div>
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                        
                                        <!-- Password Settings -->
                                        <div class="tab-pane fade" id="password-settings" role="tabpanel" aria-labelledby="password-settings-tab">
                                            <form method="POST" action="{{ route('donor-profile.update-password') }}">
                                                @csrf
                                                @if(session('password-success'))
                                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        {{ session('password-success') }}
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                    </div>
                                                @endif
                                                @if($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
                                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <ul class="mb-0">
                                                            @if($errors->has('current_password'))
                                                                <li>{{ $errors->first('current_password') }}</li>
                                                            @endif
                                                            @if($errors->has('password'))
                                                                <li>{{ $errors->first('password') }}</li>
                                                            @endif
                                                            @if($errors->has('password_confirmation'))
                                                                <li>{{ $errors->first('password_confirmation') }}</li>
                                                            @endif
                                                        </ul>
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                    </div>
                                                @endif
                                                <div class="mb-3">
                                                    <label for="current-password" class="form-label">Password Saat Ini</label>
                                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current-password" name="current_password">
                                                    @error('current_password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="new-password" class="form-label">Password Baru</label>
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="new-password" name="password">
                                                    @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="confirm-password" class="form-label">Konfirmasi Password Baru</label>
                                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="confirm-password" name="password_confirmation">
                                                    @error('password_confirmation')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                                </div>
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary">Ubah Password</button>
                                                </div>
                                            </form>
                                        </div>
                                        
                                        <!-- Notification Settings -->
                                        <div class="tab-pane fade" id="notification-settings" role="tabpanel" aria-labelledby="notification-settings-tab">
                                            <form>
                                                <div class="mb-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="email-notifications" checked>
                                                        <label class="form-check-label" for="email-notifications">
                                                            Notifikasi Email
                                                        </label>
                                                        <p class="text-muted small">Terima notifikasi melalui email untuk setiap aktivitas penting</p>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="donation-updates" checked>
                                                        <label class="form-check-label" for="donation-updates">
                                                            Update Donasi
                                                        </label>
                                                        <p class="text-muted small">Terima notifikasi ketika donasi Anda diterima atau diambil</p>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="wishlist-matches" checked>
                                                        <label class="form-check-label" for="wishlist-matches">
                                                            Pencocokan Wishlist
                                                        </label>
                                                        <p class="text-muted small">Terima notifikasi ketika ada wishlist yang cocok dengan donasi Anda</p>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="newsletter">
                                                        <label class="form-check-label" for="newsletter">
                                                            Newsletter
                                                        </label>
                                                        <p class="text-muted small">Terima newsletter bulanan dengan update dari RaihAsa</p>
                                                    </div>
                                                </div>
                                                <div class="d-grid">
                                                    <button type="button" class="btn btn-primary">Simpan Pengaturan</button>
                                                </div>
                                            </form>
                                        </div>
                                        
                                        <!-- Privacy Settings -->
                                        <div class="tab-pane fade" id="privacy-settings" role="tabpanel" aria-labelledby="privacy-settings-tab">
                                            <form>
                                                <div class="mb-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="public-profile">
                                                        <label class="form-check-label" for="public-profile">
                                                            Profil Publik
                                                        </label>
                                                        <p class="text-muted small">Izinkan penerima melihat profil donor Anda</p>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="show-donation-history" checked>
                                                        <label class="form-check-label" for="show-donation-history">
                                                            Tampilkan Riwayat Donasi
                                                        </label>
                                                        <p class="text-muted small">Tampilkan riwayat donasi Anda di profil publik</p>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="contact-permission">
                                                        <label class="form-check-label" for="contact-permission">
                                                            Izinkan Kontak
                                                        </label>
                                                        <p class="text-muted small">Izinkan penerima menghubungi Anda terkait donasi</p>
                                                    </div>
                                                </div>
                                                <div class="d-grid">
                                                    <button type="button" class="btn btn-primary">Simpan Pengaturan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Change Photo Modal -->
    <div class="modal fade" id="changePhotoModal" tabindex="-1" aria-labelledby="changePhotoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePhotoModalLabel">Ganti Foto Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        @php
                            $initials = strtoupper(substr($user->nama ?? $user->name, 0, 1));
                        @endphp
                        <div style="width: 150px; height: 150px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 60px; margin: 0 auto;" id="previewImage">
                            {{ $initials }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="profilePhoto" class="form-label">Pilih Foto</label>
                        <input type="file" class="form-control" id="profilePhoto" accept="image/*">
                    </div>
                    <div class="text-muted small">
                        <p>Format yang diizinkan: JPG, PNG, GIF</p>
                        <p>Ukuran maksimal: 2MB</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Donation Detail Modal -->
    <div class="modal fade" id="donationDetailModal" tabindex="-1" aria-labelledby="donationDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="donationDetailModalLabel">Detail Donasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Donasi</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>ID Donasi</td>
                                    <td>#D001</td>
                                </tr>
                                <tr>
                                    <td>Tanggal</td>
                                    <td>15 Juni 2023</td>
                                </tr>
                                <tr>
                                    <td>Jenis</td>
                                    <td><span class="badge bg-info">Makanan</span></td>
                                </tr>
                                <tr>
                                    <td>Item</td>
                                    <td>Nasi Kotak</td>
                                </tr>
                                <tr>
                                    <td>Jumlah</td>
                                    <td>20 porsi</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td><span class="badge bg-success">Selesai</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Informasi Penerima</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>Nama</td>
                                    <td>Panti Asuhan Harapan</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>Jl. Harapan No. 123, Bandung</td>
                                </tr>
                                <tr>
                                    <td>Kontak</td>
                                    <td>08123456789</td>
                                </tr>
                                <tr>
                                    <td>Penerima</td>
                                    <td>Ibu Siti</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Diterima</td>
                                    <td>15 Juni 2023, 14:30</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6>Catatan</h6>
                        <p>Donasi telah diterima dengan baik oleh panti asuhan. Anak-anak sangat senang dengan makanan yang Anda berikan. Terima kasih atas kontribusi Anda!</p>
                    </div>
                    <div class="mt-3">
                        <h6>Bukti Penerimaan</h6>
                        <img src="https://picsum.photos/seed/donation-proof/600/400.jpg" class="img-fluid rounded" alt="Bukti Penerimaan">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary">Unduh Bukti</button>
                </div>
            </div>
        </div>
    </div>

<!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="row">
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <div class="footer-about">
                            <a href="{{ route('home') }}" class="footer-logo">
                                <img src="/assets/raih asa logo.png" alt="RaihAsa Logo" class="footer-logo-img me-2">
                                <span>RaihAsa</span>
                            </a>
                            <p>Jembatan kebaikan yang menghubungkan kepedulian Anda dengan mereka yang membutuhkan, mewujudkan harapan melalui donasi makanan, barang, dan tenaga.</p>
                            <div class="social-links">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                        <div class="footer-links">
                            <h4>Link Cepat</h4>
                            <ul>
                                <li><a href="{{ route('home') }}">Beranda</a></li>
                                <li><a href="{{ route('home') }}#about">Tentang Kami</a></li>
                                <li><a href="{{ route('home') }}#donate">Cara Kerja</a></li>
                                <li><a href="{{ route('food-rescue') }}">Food Rescue</a></li>
                                <li><a href="{{ route('home') }}#contact">Kontak</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                        <div class="footer-links">
                            <h4>Donor</h4>
                            <ul>
                                <li><a href="{{ route('home') }}#donate">Cara Donasi</a></li>
                                <li><a href="{{ route('home') }}#donate">Jenis Makanan</a></li>
                                <li><a href="{{ route('home') }}#donate">Panduan Pengemasan</a></li>
                                <li><a href="{{ route('home') }}#donate">FAQ Donor</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                        <div class="footer-links">
                            <h4>Relawan</h4>
                            <ul>
                                <li><a href="{{ route('volunteer') }}">Cara Mendaftar</a></li>
                                <li><a href="{{ route('volunteer') }}">Kegiatan</a></li>
                                <li><a href="{{ route('volunteer') }}">Kategori</a></li>
                                <li><a href="{{ route('volunteer') }}">FAQ Relawan</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <div class="footer-contact">
                            <h4>Hubungi Kami</h4>
                            <ul>
                                <li><i class="fas fa-map-marker-alt"></i> Bandung, Indonesia</li>
                                <li><i class="fas fa-phone"></i> +62 22 1234 5678</li>
                                <li><i class="fas fa-envelope"></i> info@raihasa.id</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p>&copy; 2025 RaihAsa. Hak Cipta Dilindungi.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <ul class="footer-bottom-links">
                            <li><a href="#">Kebijakan Privasi</a></li>
                            <li><a href="#">Syarat & Ketentuan</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>
    
    <!-- Custom JS -->
    <script src="/js/donor-profile.js"></script>
</body>
</html>