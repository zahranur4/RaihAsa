<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $food->nama_makanan }} - Food Rescue - RaihAsa</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css','resources/css/components.css','resources/css/food-rescue.css'])
</head>
<body>

<!-- Header with Navigation -->
<header class="header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="/assets/raih asa logo.png" alt="RaihAsa Logo" height="40" class="me-2">
                <span>RaihAsa</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('food-rescue') }}">Food Rescue</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('wishlist') }}">Wishlist</a>
                    </li>
                </ul>
                <div class="d-flex">
                    @auth
                        <form action="{{ route('logout') }}" method="POST" class="me-2">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary">Keluar</button>
                        </form>
                        <a href="{{ route('donor-profile') }}" class="btn btn-primary">Profil</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>

<!-- Food Detail Section -->
<section class="py-5">
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('food-rescue') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Food Rescue
            </a>
        </div>

        <div class="row g-4">
            <!-- Left Column - Image -->
            <div class="col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        @php
                            $imageUrl = !empty($food->foto) ? asset('storage/'.$food->foto) : asset('assets/food-placeholder.svg');
                        @endphp
                        @if(!empty($food->foto))
                            <img src="{{ $imageUrl }}" alt="{{ $food->nama_makanan }}" class="img-fluid rounded" style="width: 100%; height: 400px; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 400px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 5rem; border-radius: 0.375rem;">
                                <i class="fas fa-utensils"></i>
                            </div>
                        @endif
                    </div>
                </div>

                @php
                    $now = \Carbon\Carbon::now();
                    $expireTime = \Carbon\Carbon::parse($food->waktu_expired);
                    $hoursRemaining = $now->diffInHours($expireTime, false);
                    
                    if ($hoursRemaining < 2) {
                        $urgencyClass = 'critical';
                        $urgencyLabel = 'Kritis';
                        $urgencyBadge = 'danger';
                    } elseif ($hoursRemaining < 6) {
                        $urgencyClass = 'urgent';
                        $urgencyLabel = 'Mendesak';
                        $urgencyBadge = 'warning';
                    } else {
                        $urgencyClass = 'normal';
                        $urgencyLabel = 'Normal';
                        $urgencyBadge = 'success';
                    }
                @endphp

                <div class="alert alert-{{ $urgencyBadge }} mt-3">
                    <i class="fas fa-clock me-2"></i>
                    <strong>Status Urgensi:</strong> {{ $urgencyLabel }} ({{ max(0, round($hoursRemaining)) }} jam lagi)
                </div>
            </div>

            <!-- Right Column - Details -->
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="mb-3" style="color: #000957;">{{ $food->nama_makanan }}</h2>
                        
                        <div class="mb-4">
                            <span class="badge bg-primary me-2">
                                @if($food->status === 'available')
                                    <i class="fas fa-check-circle me-1"></i>Tersedia
                                @elseif($food->status === 'claimed')
                                    <i class="fas fa-hand-holding me-1"></i>Sudah Diklaim
                                @elseif($food->status === 'pending')
                                    <i class="fas fa-clock me-1"></i>Menunggu Verifikasi
                                @else
                                    <i class="fas fa-times-circle me-1"></i>{{ ucfirst($food->status) }}
                                @endif
                            </span>
                        </div>

                        <hr>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded p-3 me-3">
                                        <i class="fas fa-utensils fa-lg" style="color: #000957;"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Jumlah Porsi</small>
                                        <strong>{{ $food->porsi }} porsi</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded p-3 me-3">
                                        <i class="fas fa-calendar-times fa-lg text-danger"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Kadaluarsa</small>
                                        <strong>{{ \Carbon\Carbon::parse($food->waktu_expired)->format('d M Y, H:i') }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded p-3 me-3">
                                        <i class="fas fa-calendar-plus fa-lg text-success"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Dibuat</small>
                                        <strong>{{ \Carbon\Carbon::parse($food->waktu_dibuat)->format('d M Y, H:i') }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded p-3 me-3">
                                        <i class="fas fa-user-circle fa-lg" style="color: #000957;"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Donor</small>
                                        <strong>{{ $donatur->nama_lengkap ?? 'Donatur Anonim' }}</strong>
                                    </div>
                                </div>
                            </div>

                            @if($donatur && $donatur->no_telp)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded p-3 me-3">
                                        <i class="fas fa-phone fa-lg text-success"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Kontak Donor</small>
                                        <strong>{{ $donatur->no_telp }}</strong>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($donatur && $donatur->alamat_jemput)
                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <div class="bg-light rounded p-3 me-3">
                                        <i class="fas fa-map-marker-alt fa-lg text-danger"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Lokasi Penjemputan</small>
                                        <strong>{{ $donatur->alamat_jemput }}</strong>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <hr>

                        @auth
                            @php
                                $isPanti = DB::table('panti_asuhan')->where('user_id', auth()->id())->exists()
                                    || DB::table('panti_profiles')->where('id_user', auth()->id())->exists();
                            @endphp

                            @if($food->status === 'available')
                                @if($isPanti)
                                    <form action="{{ route('food-rescue.claim', $food->id_food) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-lg w-100" style="background-color: #000957; border-color: #000957;">
                                            <i class="fas fa-hand-holding me-2"></i>Klaim Donasi Ini
                                        </button>
                                    </form>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Hanya akun panti asuhan yang dapat mengklaim donasi makanan.
                                    </div>
                                @endif
                            @elseif($food->status === 'claimed')
                                <div class="alert alert-secondary">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Donasi ini sudah diklaim oleh penerima lain.
                                </div>
                            @elseif($food->status === 'pending')
                                <div class="alert alert-warning">
                                    <i class="fas fa-clock me-2"></i>
                                    Donasi ini sedang menunggu verifikasi dari admin.
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Silakan <a href="{{ route('login') }}" class="alert-link">login</a> untuk mengklaim donasi ini.
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer mt-5">
    <div class="container">
        <div class="footer-content">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h4>RaihAsa</h4>
                    <p>Platform berbagi untuk Indonesia yang lebih baik</p>
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5>Link Cepat</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('food-rescue') }}">Food Rescue</a></li>
                        <li><a href="{{ route('wishlist') }}">Wishlist</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5>Kontak</h5>
                    <ul class="footer-contact">
                        <li><i class="fas fa-phone"></i> +62 812-3456-7890</li>
                        <li><i class="fas fa-envelope"></i> info@raihasa.id</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 RaihAsa. Hak Cipta Dilindungi.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
