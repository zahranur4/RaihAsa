<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - RaihAsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css','resources/css/penerima-dashboard.css','resources/js/penerima-dashboard.js'])
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('assets/raih asa logo.png') }}" alt="RaihAsa Logo" class="logo">
                <h3>{{ $currentPanti->nama ?? 'Panti Asuhan Harapan' }}</h3>
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('panti.dashboard') }}" class="{{ request()->routeIs('panti.dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="{{ route('panti.wishlist') }}" class="{{ request()->routeIs('panti.wishlist') ? 'active' : '' }}"><i class="fas fa-list-alt"></i> Wishlist Saya</a></li>
                <li><a href="{{ route('panti.donasi-masuk') }}" class="{{ request()->routeIs('panti.donasi-masuk') ? 'active' : '' }}"><i class="fas fa-hand-holding-heart"></i> Donasi Masuk</a></li>
                <li><a href="{{ route('panti.food-rescue') }}" class="{{ request()->routeIs('panti.food-rescue') ? 'active' : '' }}"><i class="fas fa-utensils"></i> Food Rescue</a></li>
                <li><a href="{{ route('panti.laporan') }}" class="{{ request()->routeIs('panti.laporan') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
                <li><a href="{{ route('panti.profil') }}" class="{{ request()->routeIs('panti.profil') ? 'active' : '' }}"><i class="fas fa-building"></i> Profil Panti</a></li>
                <li><a href="{{ route('panti.pengaturan') }}" class="{{ request()->routeIs('panti.pengaturan') ? 'active' : '' }}"><i class="fas fa-cog"></i> Pengaturan</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <button class="toggle-sidebar" id="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="header-title">
                    <h1>Pengaturan</h1>
                </div>
                <div class="header-actions">
                    <div class="search-bar">
                        <input type="text" placeholder="Cari...">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </div>
                    <div class="admin-profile">
                        <div class="profile-info" onclick="toggleProfileMenu()">
                            <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Admin Panti">
                            <span>{{ $currentPanti->nama ?? 'Panti Asuhan Harapan' }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <ul class="profile-menu" id="profileMenu">
                            <li><a href="{{ route('panti.profil') }}"><i class="fas fa-building"></i> Profil Panti</a></li>
                            <li><a href="{{ route('panti.pengaturan') }}"><i class="fas fa-cog"></i> Pengaturan</a></li>
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Keluar</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <div class="content">
                <!-- Tab Navigasi -->
                <ul class="nav nav-tabs" id="settingsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab" aria-controls="account" aria-selected="true">
                            <i class="fas fa-user-circle me-2"></i>Akun
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab" aria-controls="password" aria-selected="false">
                            <i class="fas fa-lock me-2"></i>Password
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="notification-tab" data-bs-toggle="tab" data-bs-target="#notification" type="button" role="tab" aria-controls="notification" aria-selected="false">
                            <i class="fas fa-bell me-2"></i>Notifikasi
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="privacy-tab" data-bs-toggle="tab" data-bs-target="#privacy" type="button" role="tab" aria-controls="privacy" aria-selected="false">
                            <i class="fas fa-shield-alt me-2"></i>Privasi
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="settingsTabContent">
                    <!-- Akun Settings -->
                    <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mb-4">Informasi Akun</h4>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="accountName" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="accountName" value="Admin {{ $currentPanti->nama ?? 'Panti Asuhan Harapan' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="accountEmail" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="accountEmail" value="admin@pantiharapan.com">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="accountPhone" class="form-label">Nomor Telepon</label>
                                        <input type="tel" class="form-control" id="accountPhone" value="0812-3456-7890">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="accountUsername" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="accountUsername" value="pantiharapan_admin">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="accountPhoto" class="form-label">Foto Profil</label>
                                    <div class="d-flex align-items-center">
                                        <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Foto Profil" class="user-avatar me-3">
                                        <div>
                                            <button class="btn btn-primary btn-sm">Ganti Foto</button>
                                            <button class="btn btn-outline-secondary btn-sm ms-2">Hapus</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Settings -->
                    <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mb-4">Ubah Password</h4>
                                <div class="mb-3">
                                    <label for="currentPassword" class="form-label">Password Saat Ini</label>
                                    <input type="password" class="form-control" id="currentPassword">
                                </div>
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label">Password Baru</label>
                                    <input type="password" class="form-control" id="newPassword">
                                </div>
                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" class="form-control" id="confirmPassword">
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary" onclick="changePassword()">Ubah Password</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Settings -->
                    <div class="tab-pane fade" id="notification" role="tabpanel" aria-labelledby="notification-tab">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mb-4">Pengaturan Notifikasi</h4>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="emailNotif" checked>
                                        <label class="form-check-label" for="emailNotif">
                                            Notifikasi Email
                                        </label>
                                        <p class="text-muted small">Terima notifikasi melalui email untuk setiap aktivitas penting</p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="donationNotif" checked>
                                        <label class="form-check-label" for="donationNotif">
                                            Notifikasi Donasi Masuk
                                        </label>
                                        <p class="text-muted small">Terima notifikasi ketika ada donasi baru masuk</p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="wishlistNotif" checked>
                                        <label class="form-check-label" for="wishlistNotif">
                                            Notifikasi Wishlist Terpenuhi
                                        </label>
                                        <p class="text-muted small">Terima notifikasi ketika item wishlist terpenuhi</p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="foodRescueNotif" checked>
                                        <label class="form-check-label" for="foodRescueNotif">
                                            Notifikasi Food Rescue
                                        </label>
                                        <p class="text-muted small">Terima notifikasi ketika ada food rescue tersedia</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary" onclick="saveNotificationSettings()">Simpan Pengaturan</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Privacy Settings -->
                    <div class="tab-pane fade" id="privacy" role="tabpanel" aria-labelledby="privacy-tab">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mb-4">Pengaturan Privasi</h4>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="showProfile" checked>
                                        <label class="form-check-label" for="showProfile">
                                            Tampilkan Profil Publik
                                        </label>
                                        <p class="text-muted small">Izinkan donatur melihat profil panti Anda</p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="showDonors" checked>
                                        <label class="form-check-label" for="showDonors">
                                            Tampilkan Daftar Donatur
                                        </label>
                                        <p class="text-muted small">Tampilkan nama donatur yang telah memberikan donasi</p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="showStatistics" checked>
                                        <label class="form-check-label" for="showStatistics">
                                            Tampilkan Statistik Donasi
                                        </label>
                                        <p class="text-muted small">Tampilkan statistik donasi panti di halaman profil</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary">Simpan Pengaturan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>
</body>
</html>