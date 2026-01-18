<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Rescue - RaihAsa Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/font-awesome.css','resources/css/style.css','resources/css/admin-dashboard.css','resources/js/admin-dashboard.js'])
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
                    <h1>Food Rescue</h1>
                </div>
                <div class="header-actions">
                    <div class="search-bar">
                        <input type="text" placeholder="Cari makanan..." id="foodSearchInput">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </div>
                    <div class="admin-profile">
                        <div class="profile-info" onclick="event.stopPropagation(); toggleProfileMenu()">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama ?? Auth::user()->email) }}&background=0D6EFD&color=fff" alt="Admin">
                            <span>{{ Auth::user()->nama ?? Auth::user()->email }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <ul class="profile-menu" id="profileMenu" onclick="event.stopPropagation();">
                            <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> Kembali ke Halaman Utama</a></li>
                            <li><a href="#"><i class="fas fa-user"></i> Profil Saya</a></li>
                            <li><a href="{{ route('admin.settings.index') }}"><i class="fas fa-cog"></i> Pengaturan</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                                    @csrf
                                    <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <div class="content">
                <!-- Stats Cards -->
                <div class="stats-grid">
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
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3>189</h3>
                            <p>Rescue Selesai</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div class="stat-info">
                            <h3>24</h3>
                            <p>Rescue Aktif</h></p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-store"></i>
                        </div>
                        <div class="stat-info">
                            <h3>67</h3>
                            <p>Restoran Mitra</p>
                        </div>
                    </div>
                </div>

                <!-- Content Header -->
                <div class="content-header">
                    <div class="page-info">
                        <h2>Daftar Food Rescue</h2>
                        <p>Kelola semua program food rescue yang ada di platform RaihAsa</p>
                    </div>
                    <div class="page-actions">
                        <a href="{{ route('admin.food-rescue.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Food Rescue
                        </a>
                    </div>
                </div>

                <!-- Filter Bar -->
                <div class="filter-bar">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="restaurantFilter" class="form-label">Filter berdasarkan restoran</label>
                            <select class="form-select" id="restaurantFilter">
                                <option value="">Semua Restoran</option>
                                <option value="1">Restoran Padang Sederhana</option>
                                <option value="2">Warung Nusantara</option>
                                <option value="3">Kedai Kopi Kenangan</option>
                                <option value="4">Bakso Pak Kumis</option>
                                <option value="5">Ayam Bakar Wong Solo</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="statusFilter" class="form-label">Filter berdasarkan status</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">Semua Status</option>
                                <option value="tersedia">Tersedia</option>
                                <option value="diklaim">Diklaim</option>
                                <option value="diambil">Diambil</option>
                                <option value="kadaluarsa">Kadaluarsa</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="dateFilter" class="form-label">Filter berdasarkan tanggal</label>
                            <input type="date" class="form-control" id="dateFilter">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-secondary me-2" id="resetFilter">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                            <button class="btn btn-primary" id="applyFilter">
                                <i class="fas fa-filter"></i> Terapkan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Food Rescue Table -->
                <div class="card">
                    <div class="card-header">
                        <h5>Daftar Food Rescue</h5>
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
                                        <th>ID</th>
                                        <th>Restoran</th>
                                        <th>Jenis Makanan</th>
                                        <th>Jumlah</th>
                                        <th>Kadaluarsa</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($foods as $food)
                                    <tr>
                                        <td>#{{ $food->id_food }}</td>
                                        <td>{{ $food->donor_name ?? $food->id_donatur }}</td>
                                        <td>{{ $food->nama_makanan }}</td>
                                        <td>{{ $food->porsi }}</td>
                                        <td>{{ $food->waktu_expired ? \Carbon\Carbon::parse($food->waktu_expired)->format('d M Y H:i') : '-' }}</td>
                                        <td><span class="badge bg-info">{{ ucfirst($food->status) }}</span></td>
                                        <td>
                                            <a href="{{ route('admin.food-rescue.edit', $food->id_food) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.food-rescue.destroy', $food->id_food) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Hapus item ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada item</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $foods->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Food Rescue Modal -->
    <div class="modal fade" id="addFoodRescueModal" tabindex="-1" aria-labelledby="addFoodRescueModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFoodRescueModalLabel">Tambah Food Rescue Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addFoodRescueForm">
                        <div class="mb-3">
                            <label for="restaurantSelect" class="form-label">Restoran</label>
                            <select class="form-select" id="restaurantSelect" required>
                                <option value="">Pilih Restoran</option>
                                <option value="1">Restoran Padang Sederhana</option>
                                <option value="2">Warung Nusantara</option>
                                <option value="3">Kedai Kopi Kenangan</option>
                                <option value="4">Bakso Pak Kumis</option>
                                <option value="5">Ayam Bakar Wong Solo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="foodType" class="form-label">Jenis Makanan</label>
                            <input type="text" class="form-control" id="foodType" required>
                        </div>
                        <div class="mb-3">
                            <label for="foodQuantity" class="form-label">Jumlah</label>
                            <input type="text" class="form-control" id="foodQuantity" placeholder="Contoh: 20 porsi" required>
                        </div>
                        <div class="mb-3">
                            <label for="expiryDate" class="form-label">Tanggal & Waktu Kadaluarsa</label>
                            <input type="datetime-local" class="form-control" id="expiryDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="foodDescription" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="foodDescription" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="foodStatus" class="form-label">Status</label>
                            <select class="form-select" id="foodStatus" required>
                                <option value="tersedia">Tersedia</option>
                                <option value="diklaim">Diklaim</option>
                                <option value="diambil">Diambil</option>
                                <option value="kadaluarsa">Kadaluarsa</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveNewFoodRescue()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>