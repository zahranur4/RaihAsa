<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Relawan - RaihAsa Admin</title>
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
                    <h1>Manajemen Relawan</h1>
                </div>
                <div class="header-actions">
                    <div class="search-bar">
                        <input type="text" placeholder="Cari relawan..." id="volunteerSearchInput">
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
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <div class="stat-info">
                            <h3>89</h3>
                            <p>Relawan Aktif</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3>156</h3>
                            <p>Program Selesai</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div class="stat-info">
                            <h3>24</h3>
                            <p>Program Aktif</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <div class="stat-info">
                            <h3>12</h3>
                            <p>Menunggu Verifikasi</p>
                        </div>
                    </div>
                </div>

                <!-- Content Header -->
                <div class="content-header">
                    <div class="page-info">
                        <h2>Daftar Relawan</h2>
                        <p>Kelola semua relawan yang terdaftar di platform RaihAsa</p>
                    </div>
                    <div class="page-actions">
                        <a href="{{ route('admin.volunteers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Relawan
                        </a>
                    </div>
                </div>

                <!-- Filter Bar -->
                <div class="filter-bar">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="categoryFilter" class="form-label">Filter berdasarkan kategori</label>
                            <select class="form-select" id="categoryFilter">
                                <option value="">Semua Kategori</option>
                                <option value="edukasi">Edukasi</option>
                                <option value="distribusi">Distribusi</option>
                                <option value="pengumpulan">Pengumpulan</option>
                                <option value="administrasi">Administrasi</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="statusFilter" class="form-label">Filter berdasarkan status</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">Semua Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Tidak Aktif</option>
                                <option value="pending">Menunggu Verifikasi</option>
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

                <!-- Volunteers Table -->
                <div class="card">
                    <div class="card-header">
                        <h5>Daftar Relawan</h5>
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
                                        <th>Foto</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Tanggal Bergabung</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($relawans as $relawan)
                                    <tr>
                                        <td>#{{ $relawan->id_relawan }}</td>
                                        <td><img src="https://ui-avatars.com/api/?name={{ urlencode($relawan->nama_lengkap) }}&background=0D6EFD&color=fff" alt="Volunteer" class="user-avatar"></td>
                                        <td>{{ $relawan->nama_lengkap }}</td>
                                        <td>-</td>
                                        <td><span class="badge bg-secondary">{{ Str::limit($relawan->skill, 20) }}</span></td>
                                        <td><span class="badge bg-{{ $relawan->status_verif == 'verified' ? 'success' : ($relawan->status_verif == 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($relawan->status_verif) }}</span></td>
                                        <td>{{ $relawan->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.volunteers.edit', $relawan->id_relawan) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.volunteers.destroy', $relawan->id_relawan) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Hapus relawan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada relawan</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" onclick="editVolunteer('V002')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="deleteVolunteer(this, 'V002')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#V003</td>
                                        <td><img src="https://randomuser.me/api/portraits/women/2.jpg" alt="Volunteer" class="user-avatar"></td>
                                        <td>Dewi Lestari</td>
                                        <td>dewi.l@email.com</td>
                                        <td><span class="badge bg-warning">Pengumpulan</span></td>
                                        <td><span class="badge bg-warning">Menunggu Verifikasi</span></td>
                                        <td>5 Feb 2023</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-info" onclick="viewVolunteerDetail('V003')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" onclick="editVolunteer('V003')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="deleteVolunteer(this, 'V003')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#V004</td>
                                        <td><img src="https://randomuser.me/api/portraits/men/2.jpg" alt="Volunteer" class="user-avatar"></td>
                                        <td>Budi Santoso</td>
                                        <td>budi.s@email.com</td>
                                        <td><span class="badge bg-info">Administrasi</span></td>
                                        <td><span class="badge bg-success">Aktif</span></td>
                                        <td>12 Feb 2023</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-info" onclick="viewVolunteerDetail('V004')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" onclick="editVolunteer('V004')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="deleteVolunteer(this, 'V004')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#V005</td>
                                        <td><img src="https://randomuser.me/api/portraits/women/3.jpg" alt="Volunteer" class="user-avatar"></td>
                                        <td>Ratna Sari</td>
                                        <td>ratna.s@email.com</td>
                                        <td><span class="badge bg-primary">Edukasi</span></td>
                                        <td><span class="badge bg-danger">Tidak Aktif</span></td>
                                        <td>1 Mar 2023</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-info" onclick="viewVolunteerDetail('V005')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" onclick="editVolunteer('V005')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="deleteVolunteer(this, 'V005')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $relawans->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Volunteer Modal -->
    <div class="modal fade" id="addVolunteerModal" tabindex="-1" aria-labelledby="addVolunteerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addVolunteerModalLabel">Tambah Relawan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addVolunteerForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="volunteerFirstName" class="form-label">Nama Depan</label>
                                <input type="text" class="form-control" id="volunteerFirstName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="volunteerLastName" class="form-label">Nama Belakang</label>
                                <input type="text" class="form-control" id="volunteerLastName" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="volunteerEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="volunteerEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="volunteerPhone" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="volunteerPhone" required>
                        </div>
                        <div class="mb-3">
                            <label for="volunteerCategory" class="form-label">Kategori</label>
                            <select class="form-select" id="volunteerCategory" required>
                                <option value="">Pilih Kategori</option>
                                <option value="edukasi">Edukasi</option>
                                <option value="distribusi">Distribusi</option>
                                <option value="pengumpulan">Pengumpulan</option>
                                <option value="administrasi">Administrasi</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="volunteerSkills" class="form-label">Keahlian</label>
                            <textarea class="form-control" id="volunteerSkills" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="volunteerStatus" class="form-label">Status</label>
                            <select class="form-select" id="volunteerStatus" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Tidak Aktif</option>
                                <option value="pending">Menunggu Verifikasi</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveNewVolunteer()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>