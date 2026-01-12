<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - RaihAsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.css">
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
                <li><a href="dashboard-panti.html"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="penerima-wishlist.html"><i class="fas fa-list-alt"></i> Wishlist Saya</a></li>
                <li><a href="penerima-donasi-masuk.html"><i class="fas fa-hand-holding-heart"></i> Donasi Masuk</a></li>
                <li><a href="penerima-food-rescue.html"><i class="fas fa-utensils"></i> Food Rescue</a></li>
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
                    <h1>Laporan</h1>
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
                <!-- Content Header -->
                <div class="content-header">
                    <div class="page-info">
                        <h2>Laporan Donasi & Aktivitas</h2>
                        <p>Pantau perkembangan donasi dan aktivitas panti</p>
                    </div>
                    <div class="page-actions">
                        <button class="btn btn-success">
                            <i class="fas fa-download"></i> Unduh Laporan
                        </button>
                    </div>
                </div>

                <!-- Filter Bar -->
                <div class="filter-bar">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="periodeFilter" class="form-label">Periode</label>
                            <select class="form-select" id="periodeFilter">
                                <option value="7hari">7 Hari Terakhir</option>
                                <option value="30hari" selected>30 Hari Terakhir</option>
                                <option value="3bulan">3 Bulan Terakhir</option>
                                <option value="custom">Kustom</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="dariTanggal" class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" id="dariTanggal" value="2023-05-01">
                        </div>
                        <div class="col-md-3">
                            <label for="sampaiTanggal" class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="sampaiTanggal" value="2023-05-31">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary w-100">Terapkan Filter</button>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <div class="stat-info">
                            <h3>124</h3>
                            <p>Total Donasi</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3>45</h3>
                            <p>Total Donatur</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-list-check"></i>
                        </div>
                        <div class="stat-info">
                            <h3>38</h3>
                            <p>Wishlist Terpenuhi</p>
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
                </div>

                <!-- Charts Section -->
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5>Tren Donasi</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="donationTrendChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Kategori Donasi</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="donationCategoryChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="card">
                    <div class="card-header">
                        <h5>Aktivitas Terkini</h5>
                        <div class="table-actions">
                            <button class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-download"></i> Export
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Aktivitas</th>
                                        <th>Pelaku</th>
                                        <th>Detail</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>15 Jun 2023</td>
                                        <td>Donasi Masuk</td>
                                        <td>Ahmad Fadli</td>
                                        <td>Susu Formula (10 kaleng)</td>
                                        <td><span class="badge bg-success">Diterima</span></td>
                                    </tr>
                                    <tr>
                                        <td>14 Jun 2023</td>
                                        <td>Food Rescue</td>
                                        <td>Restoran Padang Sederhana</td>
                                        <td>Nasi Padang (20 porsi)</td>
                                        <td><span class="badge bg-success">Diambil</span></td>
                                    </tr>
                                    <tr>
                                        <td>13 Jun 2023</td>
                                        <td>Donasi Masuk</td>
                                        <td>Siti Nurhaliza</td>
                                        <td>Buku Tulis (35 pcs)</td>
                                        <td><span class="badge bg-warning">Dalam Pengiriman</span></td>
                                    </tr>
                                    <tr>
                                        <td>12 Jun 2023</td>
                                        <td>Wishlist Terpenuhi</td>
                                        <td>-</td>
                                        <td>Susu Formula (20 kaleng)</td>
                                        <td><span class="badge bg-success">Selesai</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <nav aria-label="Page navigation">
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
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>
    <script>
        // Inisialisasi Chart
        document.addEventListener('DOMContentLoaded', function() {
            // Chart Tren Donasi
            const ctx1 = document.getElementById('donationTrendChart').getContext('2d');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Jumlah Donasi',
                        data: [10, 15, 20, 18, 25, 30],
                        borderColor: '#000957',
                        backgroundColor: 'rgba(0, 9, 87, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            // Chart Kategori Donasi
            const ctx2 = document.getElementById('donationCategoryChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['Makanan', 'Pakaian', 'Pendidikan'],
                    datasets: [{
                        data: [50, 30, 20],
                        backgroundColor: ['#000957', '#17a2b8', '#ffc107']
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        });
    </script>
</body>
</html>