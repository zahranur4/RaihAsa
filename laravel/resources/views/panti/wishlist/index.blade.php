<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist Saya - RaihAsa</title>
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
                    <h1>Wishlist Saya</h1>
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
                        <h2>Daftar Wishlist Panti</h2>
                        <p>Kelola kebutuhan panti dan pantau perkembangan donasi</p>
                    </div>
                    <div class="page-actions">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWishlistModal">
                            <i class="fas fa-plus-circle"></i> Tambah Wishlist
                        </button>
                    </div>
                </div>

                <!-- Filter Bar -->
                <div class="filter-bar">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="statusFilter" class="form-label">Filter Status</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">Semua Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="proses">Dalam Proses</option>
                                <option value="terpenuhi">Terkumpul</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="categoryFilter" class="form-label">Filter Kategori</label>
                            <select class="form-select" id="categoryFilter">
                                <option value="">Semua Kategori</option>
                                <option value="makanan">Makanan</option>
                                <option value="pakaian">Pakaian</option>
                                <option value="pendidikan">Pendidikan</option>
                                <option value="kesehatan">Kesehatan</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-primary w-100">Terapkan Filter</button>
                        </div>
                    </div>
                </div>

                <!-- Wishlist Table -->
                <div class="card">
                    <div class="card-header">
                        <h5>Daftar Wishlist</h5>
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
                                        <th>ID</th>
                                        <th>Nama Item</th>
                                        <th>Kategori</th>
                                        <th>Jumlah Dibutuhkan</th>
                                        <th>Jumlah Terkumpul</th>
                                        <th>Progress</th>
                                        <th>Batas Waktu</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#WL001</td>
                                        <td>Susu Formula Usia 1-3 Tahun</td>
                                        <td><span class="badge bg-info">Makanan</span></td>
                                        <td>20 kaleng</td>
                                        <td>20 kaleng</td>
                                        <td>
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar bg-success" style="width: 100%"></div>
                                            </div>
                                        </td>
                                        <td>30 Jun 2023</td>
                                        <td><span class="badge bg-success">Terkumpul</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-info" onclick="viewWishlistDetail('WL001')"><i class="fas fa-eye"></i></button>
                                                <button class="btn btn-sm btn-warning" onclick="editWishlist('WL001')"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-danger" onclick="hapusWishlist(this, 'WL001')"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#WL002</td>
                                        <td>Buku Tulis 60 Lembar</td>
                                        <td><span class="badge bg-warning">Pendidikan</span></td>
                                        <td>50 pcs</td>
                                        <td>35 pcs</td>
                                        <td>
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar bg-warning" style="width: 70%"></div>
                                            </div>
                                        </td>
                                        <td>15 Jul 2023</td>
                                        <td><span class="badge bg-warning">Dalam Proses</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-info" onclick="viewWishlistDetail('WL002')"><i class="fas fa-eye"></i></button>
                                                <button class="btn btn-sm btn-warning" onclick="editWishlist('WL002')"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-danger" onclick="hapusWishlist(this, 'WL002')"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#WL003</td>
                                        <td>Pakaian Anak (Usia 5-7 th)</td>
                                        <td><span class="badge bg-secondary">Pakaian</span></td>
                                        <td>30 set</td>
                                        <td>5 set</td>
                                        <td>
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar" style="width: 16.67%"></div>
                                            </div>
                                        </td>
                                        <td>30 Jul 2023</td>
                                        <td><span class="badge bg-primary">Aktif</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-info" onclick="viewWishlistDetail('WL003')"><i class="fas fa-eye"></i></button>
                                                <button class="btn btn-sm btn-warning" onclick="editWishlist('WL003')"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-danger" onclick="hapusWishlist(this, 'WL003')"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
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

    <!-- Modal Tambah Wishlist -->
    <div class="modal fade" id="addWishlistModal" tabindex="-1" aria-labelledby="addWishlistModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addWishlistModalLabel">Tambah Wishlist Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addWishlistForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="itemName" class="form-label">Nama Item</label>
                                <input type="text" class="form-control" id="itemName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="itemCategory" class="form-label">Kategori</label>
                                <select class="form-select" id="itemCategory" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="makanan">Makanan</option>
                                    <option value="pakaian">Pakaian</option>
                                    <option value="pendidikan">Pendidikan</option>
                                    <option value="kesehatan">Kesehatan</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="itemQuantity" class="form-label">Jumlah Dibutuhkan</label>
                                <input type="text" class="form-control" id="itemQuantity" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="itemDeadline" class="form-label">Batas Waktu</label>
                                <input type="date" class="form-control" id="itemDeadline" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="itemDescription" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="itemDescription" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="simpanWishlistBaru()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Wishlist -->
    <div class="modal fade" id="detailWishlistModal" tabindex="-1" aria-labelledby="detailWishlistModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailWishlistModalLabel">Detail Wishlist</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="wishlistDetailContent">
                        <!-- Konten akan diisi oleh JS -->
                    </div>
                    
                    <h5 class="mt-4">Daftar Donatur</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Donatur</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody id="donaturList">
                                <!-- Data akan diisi oleh JS -->
                            </tbody>
                        </table>
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