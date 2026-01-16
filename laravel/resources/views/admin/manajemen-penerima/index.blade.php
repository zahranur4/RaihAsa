<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Penerima - RaihAsa Admin</title>
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
                    <h1>Manajemen Penerima</h1>
                </div>
                <div class="header-actions">
                    <div class="search-bar">
                        <input type="text" placeholder="Cari penerima..." id="recipientSearchInput">
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
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="stat-info">
                            <h3>78</h3>
                            <p>Total Penerima</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3>65</h3>
                            <p>Terverifikasi</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div class="stat-info">
                            <h3>13</h3>
                            <p>Menunggu Verifikasi</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3>5</h3>
                            <p>Ditolak</p>
                        </div>
                    </div>
                </div>

                <!-- Content Header -->
                <div class="content-header">
                    <div class="page-info">
                        <h2>Daftar Penerima</h2>
                        <p>Kelola semua penerima donasi yang terdaftar di platform RaihAsa</p>
                    </div>
                    <div class="page-actions">
                        <a href="{{ route('admin.recipients.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Penerima
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
                                <option value="orphanage">Panti Asuhan</option>
                                <option value="elderly">Panti Jompo</option>
                                <option value="disability">Panti Disabilitas</option>
                                <option value="foundation">Yayasan</option>
                                <option value="community">Komunitas</option>
                                <option value="mosque">Masjid/Mushola</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="statusFilter" class="form-label">Filter berdasarkan status</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">Semua Status</option>
                                <option value="verified">Terverifikasi</option>
                                <option value="pending">Menunggu Verifikasi</option>
                                <option value="rejected">Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="cityFilter" class="form-label">Filter berdasarkan kota</label>
                            <select class="form-select" id="cityFilter">
                                <option value="">Semua Kota</option>
                                <option value="jakarta">Jakarta</option>
                                <option value="bandung">Bandung</option>
                                <option value="surabaya">Surabaya</option>
                                <option value="medan">Medan</option>
                            </select>
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

                <!-- Recipients Table -->
                <div class="card">
                    <div class="card-header">
                        <h5>Daftar Penerima</h5>
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
                                        <th>Nama Panti</th>
                                        <th>Email User</th>
                                        <th>Kota</th>
                                        <th>No. SK</th>
                                        <th>Status Verifikasi</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pantis as $panti)
                                    @php
                                        $statusMap = $panti->status_verif;
                                        $badgeClass = $statusMap === 'verified' ? 'success' : ($statusMap === 'rejected' ? 'danger' : 'warning');
                                        $statusLabel = $statusMap === 'verified' ? 'Terverifikasi' : ($statusMap === 'rejected' ? 'Ditolak' : 'Menunggu');
                                    @endphp
                                    <tr>
                                        <td>#{{ $panti->id_panti }}</td>
                                        <td>{{ $panti->nama_panti }}</td>
                                        <td>{{ $panti->user->email ?? '-' }}</td>
                                        <td>{{ $panti->kota }}</td>
                                        <td>{{ $panti->no_sk ?? '-' }}</td>
                                        <td><span class="badge bg-{{ $badgeClass }}">{{ $statusLabel }}</span></td>
                                        <td>{{ \Carbon\Carbon::parse($panti->created_at)->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.recipients.edit', $panti->id_panti) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.recipients.destroy', $panti->id_panti) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Hapus panti ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada panti</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $pantis->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- View Recipient Modal -->
    <div class="modal fade" id="viewRecipientModal" tabindex="-1" aria-labelledby="viewRecipientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewRecipientModalLabel">Detail Penerima</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Umum</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%">Nama Panti/Lembaga</td>
                                    <td id="view-name">-</td>
                                </tr>
                                <tr>
                                    <td>Jenis</td>
                                    <td id="view-type">-</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td id="view-email">-</td>
                                </tr>
                                <tr>
                                    <td>Telepon</td>
                                    <td id="view-phone">-</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td id="view-address">-</td>
                                </tr>
                                <tr>
                                    <td>Kota</td>
                                    <td id="view-city">-</td>
                                </tr>
                                <tr>
                                    <td>Kode Pos</td>
                                    <td id="view-postal">-</td>
                                </tr>
                                <tr>
                                    <td>Kapasitas</td>
                                    <td id="view-capacity">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Informasi Legalitas</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%">Status Legal</td>
                                    <td id="view-legal-status">-</td>
                                </tr>
                                <tr>
                                    <td>Nomor Registrasi</td>
                                    <td id="view-legal-number">-</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Pendirian</td>
                                    <td id="view-legal-date">-</td>
                                </tr>
                                <tr>
                                    <td>Nama Notaris</td>
                                    <td id="view-legal-notary">-</td>
                                </tr>
                                <tr>
                                    <td>Penanggung Jawab</td>
                                    <td id="view-contact-person">-</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td id="view-contact-position">-</td>
                                </tr>
                                <tr>
                                    <td>No. KTP Penanggung Jawab</td>
                                    <td id="view-contact-id">-</td>
                                </tr>
                                <tr>
                                    <td>Status Verifikasi</td>
                                    <td id="view-verification-status">-</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h6>Dokumen Legalitas</h6>
                            <div class="document-list" id="view-documents">
                                <!-- Dokumen akan dimuat secara dinamis -->
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h6>Informasi Operasional</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%">Jam Operasional</td>
                                    <td id="view-operational-hour">-</td>
                                </tr>
                                <tr>
                                    <td>Ketersediaan Pengambilan</td>
                                    <td id="view-pickup-availability">-</td>
                                </tr>
                                <tr>
                                    <td>Kapasitas Penyimpanan</td>
                                    <td id="view-storage-capacity">-</td>
                                </tr>
                                <tr>
                                    <td>Fasilitas Pendingin</td>
                                    <td id="view-refrigeration">-</td>
                                </tr>
                                <tr>
                                    <td>Kendaraan Operasional</td>
                                    <td id="view-transportation">-</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h6>Deskripsi</h6>
                            <p id="view-description">-</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-warning" id="editFromViewBtn">Edit</button>
                    <button type="button" class="btn btn-success" id="verifyFromViewBtn">Verifikasi</button>
                    <button type="button" class="btn btn-danger" id="rejectFromViewBtn">Tolak</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Recipient Modal -->
    <div class="modal fade" id="addRecipientModal" tabindex="-1" aria-labelledby="addRecipientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRecipientModalLabel">Tambah Penerima Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addRecipientForm">
                        <ul class="nav nav-tabs" id="recipientTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">Informasi Umum</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="legal-tab" data-bs-toggle="tab" data-bs-target="#legal" type="button" role="tab" aria-controls="legal" aria-selected="false">Legalitas</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="operational-tab" data-bs-toggle="tab" data-bs-target="#operational" type="button" role="tab" aria-controls="operational" aria-selected="false">Operasional</button>
                            </li>
                        </ul>
                        <div class="tab-content mt-3" id="recipientTabContent">
                            <!-- Informasi Umum -->
                            <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="recipient-name" class="form-label">Nama Panti/Lembaga</label>
                                        <input type="text" class="form-control" id="recipient-name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="recipient-email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="recipient-email" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="recipient-phone" class="form-label">Nomor Telepon</label>
                                        <input type="tel" class="form-control" id="recipient-phone" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="recipient-password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="recipient-password" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="recipient-address" class="form-label">Alamat Lengkap</label>
                                    <textarea class="form-control" id="recipient-address" rows="3" required></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="recipient-city" class="form-label">Kota</label>
                                        <input type="text" class="form-control" id="recipient-city" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="recipient-postal" class="form-label">Kode Pos</label>
                                        <input type="text" class="form-control" id="recipient-postal" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="recipient-type" class="form-label">Jenis Panti/Lembaga</label>
                                        <select class="form-control" id="recipient-type" required>
                                            <option value="">Pilih jenis panti/lembaga</option>
                                            <option value="orphanage">Panti Asuhan</option>
                                            <option value="elderly">Panti Jompo</option>
                                            <option value="disability">Panti Disabilitas</option>
                                            <option value="foundation">Yayasan</option>
                                            <option value="community">Komunitas</option>
                                            <option value="mosque">Masjid/Mushola</option>
                                            <option value="other">Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="recipient-capacity" class="form-label">Kapasitas (Jumlah Penghuni)</label>
                                        <input type="number" class="form-control" id="recipient-capacity" min="1" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="recipient-status" class="form-label">Status</label>
                                        <select class="form-control" id="recipient-status" required>
                                            <option value="pending">Menunggu Verifikasi</option>
                                            <option value="verified">Terverifikasi</option>
                                            <option value="rejected">Ditolak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="recipient-description" class="form-label">Deskripsi Singkat</label>
                                    <textarea class="form-control" id="recipient-description" rows="3" placeholder="Ceritakan sedikit tentang panti/lembaga Anda" required></textarea>
                                </div>
                            </div>
                            
                            <!-- Legalitas -->
                            <div class="tab-pane fade" id="legal" role="tabpanel" aria-labelledby="legal-tab">
                                <div class="mb-3">
                                    <label for="legal-status" class="form-label">Status Legal</label>
                                    <select class="form-control" id="legal-status" required>
                                        <option value="">Pilih status legal</option>
                                        <option value="foundation">Yayasan (Akta Notaris & SK Kemenkumham)</option>
                                        <option value="association">Perkumpulan (Akta Notaris & SK Kemenkumham)</option>
                                        <option value="cooperative">Koperasi</option>
                                        <option value="religious">Lembaga Keagamaan Terdaftar</option>
                                        <option value="community">Komunitas dengan Surat Keterangan</option>
                                        <option value="informal">Informal (membutuhkan verifikasi tambahan)</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="legal-number" class="form-label">Nomor Registrasi Legal</label>
                                        <input type="text" class="form-control" id="legal-number" placeholder="Contoh: AHU-0001.AH.01.01.Tahun 2023">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="legal-date" class="form-label">Tanggal Pendirian</label>
                                        <input type="date" class="form-control" id="legal-date">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="legal-notary" class="form-label">Nama Notaris (jika ada)</label>
                                        <input type="text" class="form-control" id="legal-notary">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="contact-person" class="form-label">Nama Penanggung Jawab</label>
                                        <input type="text" class="form-control" id="contact-person" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="contact-position" class="form-label">Jabatan Penanggung Jawab</label>
                                        <input type="text" class="form-control" id="contact-position" placeholder="Contoh: Ketua Yayasan, Pimpinan Panti, dll" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="contact-id" class="form-label">Nomor KTP Penanggung Jawab</label>
                                        <input type="text" class="form-control" id="contact-id" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Unggah Dokumen Legalitas</label>
                                    <div class="document-upload">
                                        <div class="upload-item">
                                            <label for="doc-akte">Akta Pendirian <span class="text-muted">(PDF, max 5MB)</span></label>
                                            <input type="file" class="form-control" id="doc-akte" accept=".pdf">
                                        </div>
                                        <div class="upload-item">
                                            <label for="doc-sk">SK Kemenkumham <span class="text-muted">(PDF, max 5MB)</span></label>
                                            <input type="file" class="form-control" id="doc-sk" accept=".pdf">
                                        </div>
                                        <div class="upload-item">
                                            <label for="doc-npwp">NPWP <span class="text-muted">(PDF, max 5MB)</span></label>
                                            <input type="file" class="form-control" id="doc-npwp" accept=".pdf">
                                        </div>
                                        <div class="upload-item">
                                            <label for="doc-other">Dokumen Pendukung Lainnya <span class="text-muted">(PDF, max 5MB)</span></label>
                                            <input type="file" class="form-control" id="doc-other" accept=".pdf">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Operasional -->
                            <div class="tab-pane fade" id="operational" role="tabpanel" aria-labelledby="operational-tab">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="operational-hour" class="form-label">Jam Operasional</label>
                                        <input type="text" class="form-control" id="operational-hour" placeholder="Contoh: Senin-Jumat, 08:00-17:00" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="pickup-availability" class="form-label">Ketersediaan Pengambilan Donasi</label>
                                        <select class="form-control" id="pickup-availability" required>
                                            <option value="">Pilih ketersediaan</option>
                                            <option value="anytime">Setiap Saat</option>
                                            <option value="morning">Pagi (08:00-12:00)</option>
                                            <option value="afternoon">Siang (12:00-16:00)</option>
                                            <option value="evening">Sore (16:00-20:00)</option>
                                            <option value="weekend">Akhir Pekan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="storage-capacity" class="form-label">Kapasitas Penyimpanan</label>
                                        <select class="form-control" id="storage-capacity" required>
                                            <option value="">Pilih kapasitas</option>
                                            <option value="small">Kecil (1-50 kg)</option>
                                            <option value="medium">Sedang (50-200 kg)</option>
                                            <option value="large">Besar (200-500 kg)</option>
                                            <option value="xlarge">Sangat Besar (>500 kg)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="refrigeration" class="form-label">Fasilitas Pendingin</label>
                                        <select class="form-control" id="refrigeration" required>
                                            <option value="">Pilih fasilitas</option>
                                            <option value="none">Tidak Ada</option>
                                            <option value="small">Kulkas Kecil</option>
                                            <option value="medium">Kulkas Sedang</option>
                                            <option value="large">Freezer/Chiller</option>
                                            <option value="coldroom">Cold Room</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="transportation" class="form-label">Kendaraan Operasional</label>
                                    <select class="form-control" id="transportation" required>
                                        <option value="">Pilih kendaraan</option>
                                        <option value="none">Tidak Ada</option>
                                        <option value="motor">Motor</option>
                                        <option value="car">Mobil Pribadi</option>
                                        <option value="van">Van/Minibus</option>
                                        <option value="truck">Truk Kecil</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveNewRecipient()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Recipient Modal -->
    <div class="modal fade" id="editRecipientModal" tabindex="-1" aria-labelledby="editRecipientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRecipientModalLabel">Edit Penerima</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editRecipientForm">
                        <ul class="nav nav-tabs" id="editRecipientTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="edit-info-tab" data-bs-toggle="tab" data-bs-target="#edit-info" type="button" role="tab" aria-controls="edit-info" aria-selected="true">Informasi Umum</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="edit-legal-tab" data-bs-toggle="tab" data-bs-target="#edit-legal" type="button" role="tab" aria-controls="edit-legal" aria-selected="false">Legalitas</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="edit-operational-tab" data-bs-toggle="tab" data-bs-target="#edit-operational" type="button" role="tab" aria-controls="edit-operational" aria-selected="false">Operasional</button>
                            </li>
                        </ul>
                        <div class="tab-content mt-3" id="editRecipientTabContent">
                            <!-- Informasi Umum -->
                            <div class="tab-pane fade show active" id="edit-info" role="tabpanel" aria-labelledby="edit-info-tab">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="edit-recipient-name" class="form-label">Nama Panti/Lembaga</label>
                                        <input type="text" class="form-control" id="edit-recipient-name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit-recipient-email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="edit-recipient-email" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="edit-recipient-phone" class="form-label">Nomor Telepon</label>
                                        <input type="tel" class="form-control" id="edit-recipient-phone" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit-recipient-password" class="form-label">Password (kosongkan jika tidak diubah)</label>
                                        <input type="password" class="form-control" id="edit-recipient-password">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-recipient-address" class="form-label">Alamat Lengkap</label>
                                    <textarea class="form-control" id="edit-recipient-address" rows="3" required></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="edit-recipient-city" class="form-label">Kota</label>
                                        <input type="text" class="form-control" id="edit-recipient-city" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="edit-recipient-postal" class="form-label">Kode Pos</label>
                                        <input type="text" class="form-control" id="edit-recipient-postal" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="edit-recipient-type" class="form-label">Jenis Panti/Lembaga</label>
                                        <select class="form-control" id="edit-recipient-type" required>
                                            <option value="">Pilih jenis panti/lembaga</option>
                                            <option value="orphanage">Panti Asuhan</option>
                                            <option value="elderly">Panti Jompo</option>
                                            <option value="disability">Panti Disabilitas</option>
                                            <option value="foundation">Yayasan</option>
                                            <option value="community">Komunitas</option>
                                            <option value="mosque">Masjid/Mushola</option>
                                            <option value="other">Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="edit-recipient-capacity" class="form-label">Kapasitas (Jumlah Penghuni)</label>
                                        <input type="number" class="form-control" id="edit-recipient-capacity" min="1" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit-recipient-status" class="form-label">Status</label>
                                        <select class="form-control" id="edit-recipient-status" required>
                                            <option value="pending">Menunggu Verifikasi</option>
                                            <option value="verified">Terverifikasi</option>
                                            <option value="rejected">Ditolak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-recipient-description" class="form-label">Deskripsi Singkat</label>
                                    <textarea class="form-control" id="edit-recipient-description" rows="3" placeholder="Ceritakan sedikit tentang panti/lembaga Anda" required></textarea>
                                </div>
                            </div>
                            
                            <!-- Legalitas -->
                            <div class="tab-pane fade" id="edit-legal" role="tabpanel" aria-labelledby="edit-legal-tab">
                                <div class="mb-3">
                                    <label for="edit-legal-status" class="form-label">Status Legal</label>
                                    <select class="form-control" id="edit-legal-status" required>
                                        <option value="">Pilih status legal</option>
                                        <option value="foundation">Yayasan (Akta Notaris & SK Kemenkumham)</option>
                                        <option value="association">Perkumpulan (Akta Notaris & SK Kemenkumham)</option>
                                        <option value="cooperative">Koperasi</option>
                                        <option value="religious">Lembaga Keagamaan Terdaftar</option>
                                        <option value="community">Komunitas dengan Surat Keterangan</option>
                                        <option value="informal">Informal (membutuhkan verifikasi tambahan)</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="edit-legal-number" class="form-label">Nomor Registrasi Legal</label>
                                        <input type="text" class="form-control" id="edit-legal-number" placeholder="Contoh: AHU-0001.AH.01.01.Tahun 2023">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit-legal-date" class="form-label">Tanggal Pendirian</label>
                                        <input type="date" class="form-control" id="edit-legal-date">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="edit-legal-notary" class="form-label">Nama Notaris (jika ada)</label>
                                        <input type="text" class="form-control" id="edit-legal-notary">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit-contact-person" class="form-label">Nama Penanggung Jawab</label>
                                        <input type="text" class="form-control" id="edit-contact-person" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="edit-contact-position" class="form-label">Jabatan Penanggung Jawab</label>
                                        <input type="text" class="form-control" id="edit-contact-position" placeholder="Contoh: Ketua Yayasan, Pimpinan Panti, dll" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit-contact-id" class="form-label">Nomor KTP Penanggung Jawab</label>
                                        <input type="text" class="form-control" id="edit-contact-id" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Unggah Dokumen Legalitas</label>
                                    <div class="document-upload">
                                        <div class="upload-item">
                                            <label for="edit-doc-akte">Akta Pendirian <span class="text-muted">(PDF, max 5MB)</span></label>
                                            <input type="file" class="form-control" id="edit-doc-akte" accept=".pdf">
                                        </div>
                                        <div class="upload-item">
                                            <label for="edit-doc-sk">SK Kemenkumham <span class="text-muted">(PDF, max 5MB)</span></label>
                                            <input type="file" class="form-control" id="edit-doc-sk" accept=".pdf">
                                        </div>
                                        <div class="upload-item">
                                            <label for="edit-doc-npwp">NPWP <span class="text-muted">(PDF, max 5MB)</span></label>
                                            <input type="file" class="form-control" id="edit-doc-npwp" accept=".pdf">
                                        </div>
                                        <div class="upload-item">
                                            <label for="edit-doc-other">Dokumen Pendukung Lainnya <span class="text-muted">(PDF, max 5MB)</span></label>
                                            <input type="file" class="form-control" id="edit-doc-other" accept=".pdf">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Operasional -->
                            <div class="tab-pane fade" id="edit-operational" role="tabpanel" aria-labelledby="edit-operational-tab">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="edit-operational-hour" class="form-label">Jam Operasional</label>
                                        <input type="text" class="form-control" id="edit-operational-hour" placeholder="Contoh: Senin-Jumat, 08:00-17:00" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit-pickup-availability" class="form-label">Ketersediaan Pengambilan Donasi</label>
                                        <select class="form-control" id="edit-pickup-availability" required>
                                            <option value="">Pilih ketersediaan</option>
                                            <option value="anytime">Setiap Saat</option>
                                            <option value="morning">Pagi (08:00-12:00)</option>
                                            <option value="afternoon">Siang (12:00-16:00)</option>
                                            <option value="evening">Sore (16:00-20:00)</option>
                                            <option value="weekend">Akhir Pekan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="edit-storage-capacity" class="form-label">Kapasitas Penyimpanan</label>
                                        <select class="form-control" id="edit-storage-capacity" required>
                                            <option value="">Pilih kapasitas</option>
                                            <option value="small">Kecil (1-50 kg)</option>
                                            <option value="medium">Sedang (50-200 kg)</option>
                                            <option value="large">Besar (200-500 kg)</option>
                                            <option value="xlarge">Sangat Besar (>500 kg)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit-refrigeration" class="form-label">Fasilitas Pendingin</label>
                                        <select class="form-control" id="edit-refrigeration" required>
                                            <option value="">Pilih fasilitas</option>
                                            <option value="none">Tidak Ada</option>
                                            <option value="small">Kulkas Kecil</option>
                                            <option value="medium">Kulkas Sedang</option>
                                            <option value="large">Freezer/Chiller</option>
                                            <option value="coldroom">Cold Room</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-transportation" class="form-label">Kendaraan Operasional</label>
                                    <select class="form-control" id="edit-transportation" required>
                                        <option value="">Pilih kendaraan</option>
                                        <option value="none">Tidak Ada</option>
                                        <option value="motor">Motor</option>
                                        <option value="car">Mobil Pribadi</option>
                                        <option value="van">Van/Minibus</option>
                                        <option value="truck">Truk Kecil</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="updateRecipient()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>