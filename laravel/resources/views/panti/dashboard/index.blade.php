<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penerima - RaihAsa</title>
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
                    <h1>Dashboard Penerima</h1>
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
                <!-- Welcome Banner -->
                <div class="welcome-banner" style="background: linear-gradient(135deg, var(--primary-color), #0030a1); color: white; padding: 30px; border-radius: 12px; margin-bottom: 30px;">
                    <h2>Selamat Datang kembali, Admin {{ $currentPanti->nama ?? 'Panti Asuhan Harapan' }}!</h2>
                    <p>Mari bersama-sama wujudkan harapan untuk anak-anak Indonesia.</p>
                </div>

                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <div class="stat-info">
                            <h3>124</h3>
                            <p>Total Donasi Diterima</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-list-check"></i>
                        </div>
                        <div class="stat-info">
                            <h3>38</h3>
                            <p>Item Wishlist Terpenuhi</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="stat-info">
                            <h3>15</h3>
                            <p>Food Rescue Diambil</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3>50</h3>
                            <p>Total Penghuni</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center p-4">
                                <i class="fas fa-plus-circle fa-3x text-primary mb-3"></i>
                                <h5>Buat Wishlist Baru</h5>
                                <p>Tambahkan kebutuhan panti</p>
                                <a href="{{ route('panti.wishlist', ['action' => 'create']) }}" class="btn btn-primary">Buat Sekarang</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center p-4">
                                <i class="fas fa-search fa-3x text-primary mb-3"></i>
                                <h5>Cari Food Rescue</h5>
                                <p>Temukan makanan berlimpah di sekitar Anda</p>
                                <a href="{{ route('panti.food-rescue') }}" class="btn btn-primary">Cari Sekarang</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities & Wishlist Preview -->
                <div class="row">
                    <!-- Recent Activities -->
                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Aktivitas Terkini</h5>
                            </div>
                            <div class="card-body">
                                <div class="activity-list">
                                    <div class="d-flex mb-3">
                                        <div class="me-3">
                                            <div class="rounded-circle bg-success bg-opacity-10 p-2">
                                                <i class="fas fa-check text-success"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="mb-1"><strong>Wishlist Susu Formula</strong> terpenuhi!</p>
                                            <small class="text-muted">2 jam yang lalu</small>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="me-3">
                                            <div class="rounded-circle bg-info bg-opacity-10 p-2">
                                                <i class="fas fa-truck text-info"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="mb-1">Donasi dari <strong>Toko Buku Pintar</strong> dalam perjalanan.</p>
                                            <small class="text-muted">5 jam yang lalu</small>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="me-3">
                                            <div class="rounded-circle bg-warning bg-opacity-10 p-2">
                                                <i class="fas fa-utensils text-warning"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="mb-1">Food Rescue <strong>Nasi Kotak</strong> dari Restoran Padang Sederhana tersedia.</p>
                                            <small class="text-muted">1 hari yang lalu</small>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <div class="rounded-circle bg-success bg-opacity-10 p-2">
                                                <i class="fas fa-user-plus text-success"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="mb-1"><strong>Ahmad Fadli</strong> berdonasi Buku Tulis.</p>
                                            <small class="text-muted">2 hari yang lalu</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Wishlist Preview -->
                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Wishlist Saya</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Progress</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Susu Formula</td>
                                                <td>
                                                    <div class="progress" style="height: 10px;">
                                                        <div class="progress-bar bg-success" style="width: 100%"></div>
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-success">Terkumpul</span></td>
                                            </tr>
                                            <tr>
                                                <td>Buku Tulis</td>
                                                <td>
                                                    <div class="progress" style="height: 10px;">
                                                        <div class="progress-bar bg-warning" style="width: 70%"></div>
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-warning">Dalam Proses</span></td>
                                            </tr>
                                            <tr>
                                                <td>Pakaian Anak</td>
                                                <td>
                                                    <div class="progress" style="height: 10px;">
                                                        <div class="progress-bar" style="width: 16.67%"></div>
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-primary">Aktif</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="{{ route('panti.wishlist') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
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