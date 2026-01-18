<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontribusiku - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/font-awesome.css','resources/css/style.css','resources/css/components.css','resources/css/donasiku.css'])
</head>
<body>

<!-- Header -->
<header class="header">
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('assets/raih asa logo.png') }}" alt="RaihAsa Logo" height="40" class="me-2">
                <span style="color: #000957; font-weight: 700;">RaihAsa</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mt-3 mt-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('food-rescue') }}">Food Rescue</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('wishlist') }}">Wishlist</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('volunteer') }}">Relawan</a></li>
                    <li class="nav-item"><a class="nav-link active fw-bold" href="{{ route('my-donations') }}">Kontribusiku</a></li>
                </ul>

                <div class="d-flex align-items-center user-profile-nav ms-lg-3">
                    <div class="text-end me-3">
                        <div class="fw-bold" style="color: #000957;">{{ Auth::user()->nama ?? 'Pengguna' }}</div>
                        <div class="small text-muted">Donor Aktif</div>
                    </div>
                    <div class="dropdown">
                        <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            @php
                                $initials = strtoupper(substr(Auth::user()->nama ?? Auth::user()->name, 0, 1));
                            @endphp
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px; border: 2px solid #f8f9fa; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);">
                                {{ $initials }}
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item" href="{{ route('donor-profile') }}">Pengaturan Akun</a></li>
                            <li><a class="dropdown-item" href="#">Sertifikat Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>

<main class="py-4 my-donations-section">
    <div class="container">
        
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold" style="color: #000957;">Kontribusiku</h2>
                <p class="text-muted mb-0">Pantau dampak kebaikan yang telah Anda berikan</p>
            </div>
            <a href="{{ route('wishlist') }}" class="btn btn-primary" style="background-color: #000957; border-color: #000957;"><i class="fas fa-plus me-2"></i>Buat Donasi Baru</a>
        </div>

        <!-- Statistik Donasi -->
        <section class="donation-stats">
            <div class="donation-stat-card">
                <div class="donation-stat-icon"><i class="fas fa-hand-holding-heart"></i></div>
                <div class="donation-stat-value">{{ $totalDonasi }}</div>
                <div class="donation-stat-label">Total Donasi</div>
            </div>
            <div class="donation-stat-card">
                <div class="donation-stat-icon"><i class="fas fa-users"></i></div>
                <div class="donation-stat-value">{{ $pantiTerbantui }}</div>
                <div class="donation-stat-label">Panti Terbantu</div>
            </div>
            <div class="donation-stat-card">
                <div class="donation-stat-icon"><i class="fas fa-weight-hanging"></i></div>
                <div class="donation-stat-value">{{ $totalBeratMakanan }} kg</div>
                <div class="donation-stat-label">Total Berat Makanan</div>
            </div>
            <div class="donation-stat-card">
                <div class="donation-stat-icon"><i class="fas fa-certificate"></i></div>
                <div class="donation-stat-value">{{ $sertifikatDidapat }}</div>
                <div class="donation-stat-label">Sertifikat Didapat</div>
            </div>
        </section>

        <!-- Section: Grid Layout -->
        <div class="row g-4">
            <div class="col-lg-8">
                    <div class="donations-tabs mb-4">
                    <button class="donation-tab active" onclick="filterDonations('all', this)">Semua</button>
                    <button class="donation-tab" onclick="filterDonations('processing', this)">Diproses</button>
                    <button class="donation-tab" onclick="filterDonations('completed', this)">Selesai</button>
                    <button class="donation-tab" onclick="filterDonations('cancelled', this)">Dibatalkan</button>
                </div>

                <!-- Grid Donasi -->
                <div class="donations-grid">
                    @forelse($donationHistory as $donation)
                    <div class="donation-card" data-status="{{ strtolower($donation->status) }}">
                        <div class="card-body p-0">
                            <div class="d-flex">
                                <div class="donation-thumb">
                                    <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">
                                        @php
                                            $icons = [
                                                'available' => 'fa-utensils',
                                                'claimed' => 'fa-check-circle',
                                                'expired' => 'fa-times-circle'
                                            ];
                                            $icon = $icons[strtolower($donation->status)] ?? 'fa-box';
                                        @endphp
                                        <i class="fas {{ $icon }}"></i>
                                    </div>
                                </div>
                                <div class="donation-content p-3 flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="mb-0 fw-bold">{{ $donation->nama_makanan }}</h5>
                                        @php
                                            $statusBadges = [
                                                'available' => ['badge' => 'bg-success', 'label' => 'Tersedia'],
                                                'claimed' => ['badge' => 'bg-info', 'label' => 'Diklaim'],
                                                'expired' => ['badge' => 'bg-danger', 'label' => 'Kadaluarsa']
                                            ];
                                            $statusInfo = $statusBadges[strtolower($donation->status)] ?? ['badge' => 'bg-secondary', 'label' => 'Tidak Diketahui'];
                                        @endphp
                                        <span class="badge {{ $statusInfo['badge'] }} bg-opacity-10 text-{{ str_replace('bg-', '', $statusInfo['badge']) }} rounded-pill">{{ $statusInfo['label'] }}</span>
                                    </div>
                                    <div class="donation-meta small text-muted mb-2">
                                        <span class="me-3"><i class="fas fa-box me-1"></i> {{ $donation->porsi }} porsi</span>
                                        <span><i class="fas fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($donation->waktu_dibuat)->format('d M Y') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="fw-bold">
                                            @if(strtolower($donation->status) === 'available')
                                                <span class="text-success">Menunggu Pengambilan</span>
                                            @elseif(strtolower($donation->status) === 'claimed')
                                                <span class="text-info">Sudah Diklaim</span>
                                            @else
                                                <span class="text-danger">Sudah Kadaluarsa</span>
                                            @endif
                                        </small>
                                        <div>
                                            <button class="btn btn-sm btn-outline-primary me-1">Detail</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Anda belum memiliki riwayat donasi. Mulai berdonasi sekarang!
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: #000957;"><i class="fas fa-globe-asia me-2"></i>Dampak Sosial Anda</h5>
                        <div class="impact-metrics">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Orang Terbantu</span>
                                <span class="fw-bold">120 Jiwa</span>
                            </div>
                            <hr>
                            <p class="small text-muted mb-0">Anda termasuk Top 5% donor di bulan ini. Teruslah berbagi!</p>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <h5 class="fw-bold mb-3">Sertifikat Terbaru</h5>
                        
                        <div class="certificate-graphic mb-3">
                            <i class="fas fa-award fa-5x" style="color: #f1c40f;"></i>
                        </div>
                        <p class="text-muted small mb-4">Sertifikat Apresiasi Donasi Makanan</p>
                        
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary btn-sm" style="background-color: #000957; border-color: #000957;"><i class="fas fa-download me-1"></i> Unduh PDF</button>
                            <button class="btn btn-outline-secondary btn-sm">Lihat Semua Sertifikat</button>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</main>

<!-- Footer -->
<footer class="footer py-4 mt-5 bg-light text-center text-muted">
    <div class="container">
        <small>&copy; 2025 RaihAsa. Hak Cipta Dilindungi.</small>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/main.js"></script>

<script>
    function filterDonations(status, element) {
        // 1. Update Tampilan Tombol Tab (Active State)
        const tabs = document.querySelectorAll('.donation-tab');
        tabs.forEach(tab => tab.classList.remove('active'));
        element.classList.add('active');

        // 2. Filter Kartu Donasi
        const cards = document.querySelectorAll('.donation-card');
        
        cards.forEach(card => {
            const cardStatus = card.getAttribute('data-status');
            
            if (status === 'all') {
                card.style.display = 'block';
            } else {
                if (cardStatus === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            }
        });
    }
</script>
</body>
</html>