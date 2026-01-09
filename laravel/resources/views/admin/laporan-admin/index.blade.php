<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - RaihAsa Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css','resources/css/admin-dashboard.css','resources/js/admin-dashboard.js'])
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('assets/raih asa logo.png') }}" alt="RaihAsa Logo" class="logo">
                <h3>RaihAsa Admin</h3>
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="fas fa-users"></i> Manajemen Pengguna</a></li>
                <li><a href="{{ route('admin.donations.index') }}" class="{{ request()->routeIs('admin.donations.*') ? 'active' : '' }}"><i class="fas fa-hand-holding-heart"></i> Manajemen Donasi</a></li>
                <li><a href="{{ route('admin.food-rescue.index') }}" class="{{ request()->routeIs('admin.food-rescue.*') ? 'active' : '' }}"><i class="fas fa-utensils"></i> Food Rescue</a></li>
                <li><a href="{{ route('admin.volunteers.index') }}" class="{{ request()->routeIs('admin.volunteers.*') ? 'active' : '' }}"><i class="fas fa-hands-helping"></i> Manajemen Relawan</a></li>
                <li><a href="{{ route('admin.recipients.index') }}" class="{{ request()->routeIs('admin.recipients.*') ? 'active' : '' }}"><i class="fas fa-home"></i> Manajemen Penerima</a></li>
                <li><a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
                <li><a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><i class="fas fa-cog"></i> Pengaturan</a></li>
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
                        <input type="text" placeholder="Cari laporan..." id="reportSearchInput">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </div>
                    <div class="admin-profile">
                        <div class="profile-info" onclick="toggleProfileMenu()">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama ?? Auth::user()->email) }}&background=0D6EFD&color=fff" alt="Admin">
                            <span>{{ Auth::user()->nama ?? Auth::user()->email }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <ul class="profile-menu" id="profileMenu">
                            <li><a href="#"><i class="fas fa-user"></i> Profil Saya</a></li>
                            <li><a href="{{ route('admin.settings.index') }}"><i class="fas fa-cog"></i> Pengaturan</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline"><i class="fas fa-sign-out-alt"></i> Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <div class="content">
                <!-- Content Header -->
                <div class="content-header">
                    <div class="page-info">
                        <h2>Laporan Aktivitas</h2>
                        <p>Analisis dan laporan lengkap mengenai aktivitas di platform RaihAsa</p>
                    </div>
                    <div class="page-actions">
                        <button class="btn btn-primary" onclick="generateReport()">
                            <i class="fas fa-file-download"></i> Generate Laporan
                        </button>
                    </div>
                </div>

                <!-- Report Filters -->
                <div class="laporan-filters">
                    <div class="card">
                        <div class="card-header">
                            <h5>Filter Laporan</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="reportType" class="form-label">Jenis Laporan</label>
                                    <select class="form-select" id="reportType">
                                        <option value="all">Semua</option>
                                        <option value="donation">Donasi</option>
                                        <option value="foodRescue">Food Rescue</option>
                                        <option value="volunteer">Relawan</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="startDate" class="form-label">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="startDate">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="endDate" class="form-label">Tanggal Selesai</label>
                                    <input type="date" class="form-control" id="endDate">
                                </div>
                                <div class="col-md-3 mb-3 d-flex align-items-end">
                                    <button class="btn btn-primary w-100" onclick="applyReportFilters()">
                                        <i class="fas fa-filter"></i> Terapkan Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Summary -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <div class="stat-info">
                            <h3>567</h3>
                            <p>Total Donasi</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="stat-info">
                            <h3>2.5 Ton</h3>
                            <p>Makanan Terselamatkan</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3>89</h3>
                            <p>Relawan Aktif</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Rp 45.6Jt</h3>
                            <p>Total Nilai Donasi</p>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="charts-section">
                    <div class="chart-container">
                        <div class="card">
                            <div class="card-header">
                                <h5>Tren Donasi 6 Bulan Terakhir</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="donationTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="chart-container">
                        <div class="card">
                            <div class="card-header">
                                <h5>Kategori Donasi</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="donationCategoryChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Food Rescue Trend -->
                <div class="chart-container mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Tren Food Rescue</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="foodRescueTrendChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Volunteer Activity -->
                <div class="chart-container mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Aktivitas Relawan</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="volunteerActivityChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Reports Table -->
                <div class="card">
                    <div class="card-header">
                        <h5>Laporan Terkini</h5>
                        <div class="table-actions">
                            <button class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-download"></i> Export All
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Jenis Laporan</th>
                                        <th>Periode</th>
                                        <th>Tanggal Generate</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#R001</td>
                                        <td>Laporan Bulanan Donasi</td>
                                        <td>1 - 30 Juni 2023</td>
                                        <td>1 Juli 2023</td>
                                        <td><span class="badge bg-success">Selesai</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-info" onclick="viewReport('R001')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-primary" onclick="downloadReport('R001')">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#R002</td>
                                        <td>Laporan Food Rescue</td>
                                        <td>1 - 30 Juni 2023</td>
                                        <td>1 Juli 2023</td>
                                        <td><span class="badge bg-success">Selesai</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-info" onclick="viewReport('R002')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-primary" onclick="downloadReport('R002')">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#R003</td>
                                        <td>Laporan Aktivitas Relawan</td>
                                        <td>1 - 30 Juni 2023</td>
                                        <td>2 Juli 2023</td>
                                        <td><span class="badge bg-warning">Diproses</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-info" onclick="viewReport('R003')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-primary" onclick="downloadReport('R003')">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#R004</td>
                                        <td>Laporan Tahunan</td>
                                        <td>1 Jan - 30 Jun 2023</td>
                                        <td>3 Juli 2023</td>
                                        <td><span class="badge bg-info">Menunggu</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-info" onclick="viewReport('R004')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-primary" onclick="downloadReport('R004')">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>