<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Donasi - RaihAsa Admin</title>
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
                <li><a href="{{ route('admin.volunteer-activities.index') }}" class="{{ request()->routeIs('admin.volunteer-activities.*') ? 'active' : '' }}"><i class="fas fa-calendar-check"></i> Manajemen Kegiatan Relawan</a></li>
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
                    <h1>Manajemen Donasi</h1>
                </div>
                <div class="header-actions">
                    <div class="search-bar">
                        <input type="text" placeholder="Cari donasi..." id="donationSearchInput">
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
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $stats['total_donations'] ?? 0 }}</h3>
                            <p>Total Donasi</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $stats['completed_donations'] ?? 0 }}</h3>
                            <p>Donasi Selesai</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $stats['active_donations'] ?? 0 }}</h3>
                            <p>Donasi Aktif</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div class="stat-info">
                            <h3>-</h3>
                            <p>Total Berat Donasi</p>
                        </div>
                    </div>
                </div>

                <!-- Content Header -->
                <div class="content-header">
                    <div class="page-info">
                        <h2>Daftar Donasi</h2>
                        <p>Kelola semua donasi makanan dan barang yang terdaftar di platform RaihAsa</p>
                    </div>
                    <div class="page-actions">
                        <a href="{{ route('admin.donations.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Donasi
                        </a>
                    </div>
                </div>

                <!-- Filter Bar -->
                <div class="filter-bar">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="typeFilter" class="form-label">Filter berdasarkan jenis</label>
                            <select class="form-select" id="typeFilter">
                                <option value="">Semua Jenis</option>
                                <option value="makanan">Makanan</option>
                                <option value="barang">Barang</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="statusFilter" class="form-label">Filter berdasarkan status</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">Semua Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="diklaim">Diklaim</option>
                                <option value="selesai">Selesai</option>
                                <option value="dibatalkan">Dibatalkan</option>
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

                <!-- Donations Table -->
                <div class="card">
                    <div class="card-header">
                        <h5>Daftar Donasi</h5>
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
                                        <th>Donatur</th>
                                        <th>Jenis</th>
                                        <th>Detail</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($donations as $donation)
                                    <tr>
                                        <td>#{{ $donation->id_donasi }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span>{{ $donation->id_donatur }}</span>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-secondary">{{ $donation->kategori ?? 'Umum' }}</span></td>
                                        <td>{{ $donation->nama_barang }}</td>
                                        <td>{{ $donation->created_at->format('d M Y') }}</td>
                                        <td><span class="badge bg-info">{{ ucfirst($donation->status) }}</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.donations.edit', $donation->id_donasi) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                                <form action="{{ route('admin.donations.destroy', $donation->id_donasi) }}" method="POST" style="display:inline-block" onsubmit="event.preventDefault(); Swal.fire({title: 'Konfirmasi', text: 'Hapus donasi ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'}).then((result) => {if (result.isConfirmed) {this.submit();}})">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada donasi</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $donations->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Donation Modal -->
    <div class="modal fade" id="addDonationModal" tabindex="-1" aria-labelledby="addDonationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDonationModalLabel">Tambah Donasi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addDonationForm">
                        <div class="mb-3">
                            <label for="donorSelect" class="form-label">Donatur</label>
                            <select class="form-select" id="donorSelect" required>
                                <option value="">Pilih Donatur</option>
                                <option value="1">Ahmad Fadli</option>
                                <option value="2">Siti Nurhaliza</option>
                                <option value="3">Budi Santoso</option>
                                <option value="4">Dewi Lestari</option>
                                <option value="5">Rizki Ahmad</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="donationType" class="form-label">Jenis Donasi</label>
                            <select class="form-select" id="donationType" required>
                                <option value="">Pilih Jenis Donasi</option>
                                <option value="makanan">Makanan</option>
                                <option value="barang">Barang</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="donationDetail" class="form-label">Detail Donasi</label>
                            <textarea class="form-control" id="donationDetail" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="donationWeight" class="form-label">Berat/Kuantitas Donasi</label>
                            <input type="text" class="form-control" id="donationWeight" placeholder="Contoh: 5 kg atau 50 porsi">
                        </div>
                        <div class="mb-3">
                            <label for="donationExpiry" class="form-label">Kadaluarsa (untuk makanan)</label>
                            <input type="datetime-local" class="form-control" id="donationExpiry">
                        </div>
                        <div class="mb-3">
                            <label for="donationDate" class="form-label">Tanggal Donasi</label>
                            <input type="date" class="form-control" id="donationDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="donationStatus" class="form-label">Status</label>
                            <select class="form-select" id="donationStatus" required>
                                <option value="aktif">Aktif</option>
                                <option value="diklaim">Diklaim</option>
                                <option value="selesai">Selesai</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveNewDonation()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>