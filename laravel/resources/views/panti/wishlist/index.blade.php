<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist Saya - RaihAsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                @if($panti)
                    @if($panti->status_verif === 'verified')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <strong>Status: Terverifikasi</strong>
                        <br>
                        Panti Asuhan Anda telah diverifikasi dan dapat menerima donasi.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @else
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Status: {{ $panti->status_verif === 'pending' ? 'Belum Terverifikasi' : ($panti->status_verif === 'rejected' ? 'Ditolak' : 'Belum Terverifikasi') }}</strong>
                        <br>
                        Panti Asuhan Anda belum diverifikasi dan tidak dapat menerima donasi. Silakan tunggu admin memverifikasi akun Anda terlebih dahulu.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif
                @endif
                
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
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
                
                <!-- Content Header -->
                <div class="content-header">
                    <div class="page-info">
                        <h2>Daftar Wishlist Panti</h2>
                        <p>Kelola kebutuhan panti dan pantau perkembangan donasi</p>
                    </div>
                    <div class="page-actions">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWishlistModal" {{ $panti && $panti->status_verif !== 'verified' ? 'disabled' : '' }}>
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
                                    @forelse($wishlists as $wishlist)
                                    <tr>
                                        <td>#WL{{ str_pad($wishlist->id_wishlist, 3, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ $wishlist->nama_barang }}</td>
                                        <td>
                                            @php
                                                $badgeColors = [
                                                    'Makanan' => 'bg-info',
                                                    'Pakaian' => 'bg-secondary',
                                                    'Pendidikan' => 'bg-warning',
                                                    'Kesehatan' => 'bg-danger',
                                                ];
                                                $color = $badgeColors[$wishlist->kategori] ?? 'bg-primary';
                                            @endphp
                                            <span class="badge {{ $color }}">{{ $wishlist->kategori }}</span>
                                        </td>
                                        <td>{{ $wishlist->jumlah }}</td>
                                        <td>-</td>
                                        <td>
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar bg-primary" style="width: 0%"></div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $urgencyLabels = [
                                                    'mendesak' => 'Mendesak',
                                                    'rutin' => 'Rutin',
                                                    'pendidikan' => 'Pendidikan',
                                                    'kesehatan' => 'Kesehatan'
                                                ];
                                            @endphp
                                            <span class="badge {{ $wishlist->urgensi === 'mendesak' ? 'bg-danger' : ($wishlist->urgensi === 'kesehatan' ? 'bg-danger' : ($wishlist->urgensi === 'pendidikan' ? 'bg-warning' : 'bg-secondary')) }}">
                                                {{ $urgencyLabels[$wishlist->urgensi] ?? $wishlist->urgensi }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $statusLabels = [
                                                    'open' => ['label' => 'Aktif', 'class' => 'bg-primary'],
                                                    'fulfilled' => ['label' => 'Terpenuhi', 'class' => 'bg-success'],
                                                    'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-danger']
                                                ];
                                                $status = $statusLabels[$wishlist->status] ?? ['label' => $wishlist->status, 'class' => 'bg-secondary'];
                                            @endphp
                                            <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editWishlistModal{{ $wishlist->id_wishlist }}"><i class="fas fa-edit"></i></button>
                                                <form action="{{ route('panti.wishlist.destroy', $wishlist->id_wishlist) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus wishlist ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Belum ada wishlist. Tambahkan wishlist pertama Anda!</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $wishlists->links() }}
                        </div>
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
                    <form id="addWishlistForm" method="POST" action="{{ route('panti.wishlist.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_barang" class="form-label">Nama Item</label>
                                <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kategori" class="form-label">Kategori</label>
                                <select class="form-select" id="kategori" name="kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Makanan">Makanan</option>
                                    <option value="Pakaian">Pakaian</option>
                                    <option value="Pendidikan">Pendidikan</option>
                                    <option value="Kesehatan">Kesehatan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jumlah" class="form-label">Jumlah Dibutuhkan</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="urgensi" class="form-label">Urgensi</label>
                                <select class="form-select" id="urgensi" name="urgensi" required>
                                    <option value="mendesak">Mendesak</option>
                                    <option value="rutin" selected>Rutin</option>
                                    <option value="pendidikan">Pendidikan</option>
                                    <option value="kesehatan">Kesehatan</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar (Opsional)</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="text-muted">Format: JPEG, PNG, JPG, GIF | Ukuran maksimal: 2MB</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" form="addWishlistForm" class="btn btn-primary">Simpan</button>
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

    <!-- Edit Modals for each wishlist -->
    @foreach($wishlists as $wishlist)
    <div class="modal fade" id="editWishlistModal{{ $wishlist->id_wishlist }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Wishlist</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('panti.wishlist.update', $wishlist->id_wishlist) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_nama_barang{{ $wishlist->id_wishlist }}" class="form-label">Nama Item</label>
                                <input type="text" class="form-control" id="edit_nama_barang{{ $wishlist->id_wishlist }}" name="nama_barang" value="{{ $wishlist->nama_barang }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_kategori{{ $wishlist->id_wishlist }}" class="form-label">Kategori</label>
                                <select class="form-select" id="edit_kategori{{ $wishlist->id_wishlist }}" name="kategori" required>
                                    <option value="Makanan" {{ $wishlist->kategori == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                                    <option value="Pakaian" {{ $wishlist->kategori == 'Pakaian' ? 'selected' : '' }}>Pakaian</option>
                                    <option value="Pendidikan" {{ $wishlist->kategori == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                    <option value="Kesehatan" {{ $wishlist->kategori == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                    <option value="Lainnya" {{ $wishlist->kategori == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_jumlah{{ $wishlist->id_wishlist }}" class="form-label">Jumlah Dibutuhkan</label>
                                <input type="number" class="form-control" id="edit_jumlah{{ $wishlist->id_wishlist }}" name="jumlah" value="{{ $wishlist->jumlah }}" min="1" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_urgensi{{ $wishlist->id_wishlist }}" class="form-label">Urgensi</label>
                                <select class="form-select" id="edit_urgensi{{ $wishlist->id_wishlist }}" name="urgensi" required>
                                    <option value="mendesak" {{ $wishlist->urgensi == 'mendesak' ? 'selected' : '' }}>Mendesak</option>
                                    <option value="rutin" {{ $wishlist->urgensi == 'rutin' ? 'selected' : '' }}>Rutin</option>
                                    <option value="pendidikan" {{ $wishlist->urgensi == 'pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                    <option value="kesehatan" {{ $wishlist->urgensi == 'kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_status{{ $wishlist->id_wishlist }}" class="form-label">Status</label>
                            <select class="form-select" id="edit_status{{ $wishlist->id_wishlist }}" name="status" required>
                                <option value="open" {{ $wishlist->status == 'open' ? 'selected' : '' }}>Aktif</option>
                                <option value="fulfilled" {{ $wishlist->status == 'fulfilled' ? 'selected' : '' }}>Terpenuhi</option>
                                <option value="cancelled" {{ $wishlist->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_image{{ $wishlist->id_wishlist }}" class="form-label">Gambar (Opsional)</label>
                            @if($wishlist->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $wishlist->image) }}" alt="{{ $wishlist->nama_barang }}" style="max-width: 100px; border-radius: 4px;">
                                <small class="text-muted d-block">Gambar saat ini</small>
                            </div>
                            @endif
                            <input type="file" class="form-control" id="edit_image{{ $wishlist->id_wishlist }}" name="image" accept="image/*">
                            <small class="text-muted">Format: JPEG, PNG, JPG, GIF | Ukuran maksimal: 2MB</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>
</body>
</html>