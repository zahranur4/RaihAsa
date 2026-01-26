<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi Masuk - RaihAsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/font-awesome.css','resources/css/style.css','resources/css/penerima-dashboard.css','resources/js/penerima-dashboard.js'])
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
                    <h1>Donasi Masuk</h1>
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
                            <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> Kembali Ke Halaman Utama</a></li>
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
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $stats['total_donations'] }}</h3>
                            <p>Total Donasi</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $stats['received_donations'] }}</h3>
                            <p>Donasi Diterima</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $stats['pending_donations'] }}</h3>
                            <p>Menunggu Konfirmasi</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $stats['total_items'] }}</h3>
                            <p>Total Item Donasi</p>
                        </div>
                    </div>
                </div>

                <!-- Filter Bar -->
                <div class="filter-bar">
                    <form action="{{ route('panti.donasi-masuk') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="statusFilter" class="form-label">Filter Status</label>
                                <select class="form-select" id="statusFilter" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="dateFilter" class="form-label">Filter Tanggal</label>
                                <input type="date" class="form-control" id="dateFilter" name="date" value="{{ request('date') }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Terapkan Filter</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Donations Table -->
                <div class="card">
                    <div class="card-header">
                        <h5>Daftar Donasi untuk Panti Anda</h5>
                        <div class="table-actions">
                            <button class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-download"></i> Export
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>ID Donasi</th>
                                        <th>Donatur</th>
                                        <th>Item</th>
                                        <th>Jumlah</th>
                                        <th>Tanggal Donasi</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($donations as $donation)
                                    <tr>
                                        <td>#P{{ str_pad($donation->id_pledge, 4, '0', STR_PAD_LEFT) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($donation->donor_name) }}&background=667eea&color=fff" alt="Donatur" class="user-avatar me-2">
                                                <span>{{ $donation->donor_name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $donation->item_offered }}</td>
                                        <td>{{ $donation->quantity_offered }} unit</td>
                                        <td>{{ \Carbon\Carbon::parse($donation->created_at)->format('d M Y') }}</td>
                                        <td>
                                            @if($donation->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($donation->status === 'confirmed')
                                                <span class="badge bg-info">Dikonfirmasi</span>
                                            @elseif($donation->status === 'completed')
                                                <span class="badge bg-success">Selesai</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($donation->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($donation->status === 'confirmed')
                                            <form action="{{ route('panti.donasi-masuk.confirm', $donation->id_pledge) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Konfirmasi Penerimaan</button>
                                            </form>
                                            <form action="{{ route('panti.donasi-masuk.decline', $donation->id_pledge) }}" method="POST" style="display:inline;" onsubmit="event.preventDefault(); const form = this; Swal.fire({title: 'Konfirmasi', text: 'Tolak donasi ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, Tolak', cancelButtonText: 'Batal'}).then((result) => {if (result.isConfirmed) {form.submit();}});">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger ms-1">Tolak</button>
                                            </form>
                                            @else
                                            <a href="{{ route('panti.donasi-masuk.detail', $donation->id_pledge) }}" class="btn btn-sm btn-info">Lihat Detail</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Belum ada donasi masuk</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        @if($donations->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $donations->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Detail Donasi -->
    <div class="modal fade" id="donationDetailModal" tabindex="-1" aria-labelledby="donationDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="donationDetailModalLabel">Detail Donasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="donationDetailContent">
                        <!-- Konten akan diisi oleh JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>
</body>
</html>