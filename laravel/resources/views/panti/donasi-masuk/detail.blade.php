<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Donasi - RaihAsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/font-awesome.css','resources/css/style.css','resources/css/penerima-dashboard.css'])
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('assets/raih asa logo.png') }}" alt="RaihAsa Logo" class="logo">
                <h3>{{ $currentPanti->nama_panti ?? 'Panti Asuhan' }}</h3>
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('panti.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="{{ route('panti.wishlist') }}"><i class="fas fa-list-alt"></i> Wishlist Saya</a></li>
                <li><a href="{{ route('panti.donasi-masuk') }}" class="active"><i class="fas fa-hand-holding-heart"></i> Donasi Masuk</a></li>
                <li><a href="{{ route('panti.food-rescue') }}"><i class="fas fa-utensils"></i> Food Rescue</a></li>
                <li><a href="{{ route('panti.laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
                <li><a href="{{ route('panti.profil') }}"><i class="fas fa-building"></i> Profil Panti</a></li>
                <li><a href="{{ route('panti.pengaturan') }}"><i class="fas fa-cog"></i> Pengaturan</a></li>
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
                    <h1>Detail Donasi</h1>
                </div>
                <div class="header-actions">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </div>
                    <div class="admin-profile">
                        <div class="profile-info" onclick="toggleProfileMenu()">
                            <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Admin Panti">
                            <span>{{ $currentPanti->nama_panti ?? 'Panti' }}</span>
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
                <!-- Back Button -->
                <div class="mb-4">
                    <a href="{{ route('panti.donasi-masuk') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Donasi
                    </a>
                </div>

                <!-- Donation Detail Card -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-gift me-2"></i> Informasi Donasi</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p class="mb-2"><strong>ID Donasi:</strong></p>
                                        <p class="text-muted">#P{{ str_pad($pledge->id_pledge, 4, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2"><strong>Status:</strong></p>
                                        <p>
                                            @if($pledge->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($pledge->status === 'confirmed')
                                                <span class="badge bg-info">Dikonfirmasi</span>
                                            @elseif($pledge->status === 'completed')
                                                <span class="badge bg-success">Selesai</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($pledge->status) }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <hr>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p class="mb-2"><strong>Item yang Ditawarkan:</strong></p>
                                        <p class="text-muted">{{ $pledge->item_offered }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2"><strong>Jumlah:</strong></p>
                                        <p class="text-muted">{{ $pledge->quantity_offered }} unit</p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p class="mb-2"><strong>Kategori Wishlist:</strong></p>
                                        <p><span class="badge bg-info">{{ $pledge->wishlist_category }}</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2"><strong>Untuk Item:</strong></p>
                                        <p class="text-muted">{{ $pledge->wishlist_item }}</p>
                                    </div>
                                </div>

                                @if($pledge->notes)
                                <div class="alert alert-light mb-3">
                                    <strong><i class="fas fa-sticky-note me-2"></i> Catatan dari Donor:</strong>
                                    <p class="mb-0 mt-2">{{ $pledge->notes }}</p>
                                </div>
                                @endif

                                <hr>

                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="mb-2"><strong>Tanggal Donasi:</strong></p>
                                        <p class="text-muted">{{ \Carbon\Carbon::parse($pledge->created_at)->format('d M Y H:i') }}</p>
                                    </div>
                                    @if($pledge->confirmed_at)
                                    <div class="col-md-4">
                                        <p class="mb-2"><strong>Dikonfirmasi:</strong></p>
                                        <p class="text-muted">{{ \Carbon\Carbon::parse($pledge->confirmed_at)->format('d M Y H:i') }}</p>
                                    </div>
                                    @endif
                                    @if($pledge->completed_at)
                                    <div class="col-md-4">
                                        <p class="mb-2"><strong>Diselesaikan:</strong></p>
                                        <p class="text-muted">{{ \Carbon\Carbon::parse($pledge->completed_at)->format('d M Y H:i') }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-clock me-2"></i> Timeline Donasi</h5>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker completed">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>Penawaran Dibuat</h6>
                                            <p class="text-muted small">{{ \Carbon\Carbon::parse($pledge->created_at)->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="timeline-item">
                                        <div class="timeline-marker {{ $pledge->confirmed_at ? 'completed' : 'pending' }}">
                                            <i class="fas fa-handshake"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>Dikonfirmasi oleh Panti</h6>
                                            <p class="text-muted small">{{ $pledge->confirmed_at ? \Carbon\Carbon::parse($pledge->confirmed_at)->format('d M Y H:i') : 'Menunggu...' }}</p>
                                        </div>
                                    </div>
                                    <div class="timeline-item">
                                        <div class="timeline-marker {{ $pledge->completed_at ? 'completed' : 'pending' }}">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>Donasi Diterima</h6>
                                            <p class="text-muted small">{{ $pledge->completed_at ? \Carbon\Carbon::parse($pledge->completed_at)->format('d M Y H:i') : 'Menunggu...' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Donor Information -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-user me-2"></i> Informasi Donatur</h5>
                            </div>
                            <div class="card-body text-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($pledge->donor_name) }}&background=667eea&color=fff&size=120" 
                                     alt="Donor" class="rounded-circle mb-3" style="width: 120px; height: 120px;">
                                <h5>{{ $pledge->donor_name }}</h5>
                                <hr>
                                <div class="text-start">
                                    <p class="mb-2">
                                        <i class="fas fa-envelope text-primary me-2"></i>
                                        <small>{{ $pledge->donor_email }}</small>
                                    </p>
                                    @if($pledge->donor_phone)
                                    <p class="mb-2">
                                        <i class="fas fa-phone text-success me-2"></i>
                                        <small>{{ $pledge->donor_phone }}</small>
                                    </p>
                                    @endif
                                    @if($pledge->donor_address)
                                    <p class="mb-0">
                                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                        <small>{{ $pledge->donor_address }}</small>
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        @if($pledge->status === 'confirmed')
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="fas fa-tasks me-2"></i> Tindakan</h6>
                                <form action="{{ route('panti.donasi-masuk.confirm', $pledge->id_pledge) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100 mb-2">
                                        <i class="fas fa-check-circle me-2"></i> Konfirmasi Penerimaan
                                    </button>
                                </form>
                                <form action="{{ route('panti.donasi-masuk.decline', $pledge->id_pledge) }}" method="POST" onsubmit="event.preventDefault(); Swal.fire({title: 'Konfirmasi', text: 'Apakah Anda yakin ingin menolak donasi ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, Tolak!', cancelButtonText: 'Batal'}).then((result) => {if (result.isConfirmed) {this.submit();}})">
                                    @csrf
                                    <button type="submit" class="btn btn-danger w-100 mb-2">
                                        <i class="fas fa-times-circle me-2"></i> Tolak Donasi
                                    </button>
                                </form>
                                <p class="small text-muted mb-0">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Konfirmasi setelah menerima donasi dari donor
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

    <style>
        .timeline {
            position: relative;
            padding: 0;
        }

        .timeline-item {
            display: flex;
            margin-bottom: 30px;
        }

        .timeline-marker {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            flex-shrink: 0;
            margin-right: 20px;
        }

        .timeline-marker.completed {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .timeline-marker.pending {
            background: #e9ecef;
            color: #6c757d;
        }

        .timeline-content {
            padding-top: 5px;
        }

        .timeline-content h6 {
            margin: 0 0 5px 0;
            font-weight: 600;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/js/penerima-dashboard.js'])
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>
</body>
</html>
