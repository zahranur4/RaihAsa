<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist Penerima - RaihAsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/font-awesome.css','resources/css/style.css','resources/css/components.css','resources/css/wishlist.css','resources/css/scroll-animation.css','resources/js/main.js','resources/js/scroll-animation.js'])
</head>
<body>
<!-- Header with Navigation -->
    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                    <img src="{{ asset('assets/raih asa logo.png') }}" alt="RaihAsa Logo" height="40" class="me-2">
                    <span>RaihAsa</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/index.html#about">Tentang</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link requires-auth" href="{{ route('food-rescue') }}">Food Rescue</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link requires-auth" href="{{ route('wishlist') }}">Wishlist</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link requires-auth" href="{{ route('volunteer') }}">Relawan</a>
                        </li>
                        @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('my-donations') }}">Kontribusiku</a>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link requires-auth" href="{{ route('my-donations') }}">Kontribusiku</a>
                        </li>
                        @endauth
                    </ul>
                    @auth
                    <div class="d-flex">
                        <div class="dropdown">
                            <a class="btn btn-outline-secondary dropdown-toggle" href="#" role="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->nama ?? Auth::user()->email }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                @if((Auth::user()->is_admin ?? false) || (Auth::user()->email === 'admin@example.com'))
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-user me-2"></i> Dashboard</a></li>
                                @elseif(Auth::check() && (\Illuminate\Support\Facades\DB::table('panti_asuhan')->where('user_id', Auth::id())->exists() || \Illuminate\Support\Facades\DB::table('panti_profiles')->where('id_user', Auth::id())->exists()))
                                    <li><a class="dropdown-item" href="{{ route('panti.dashboard') }}"><i class="fas fa-user me-2"></i> Dashboard Panti</a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('donor-profile') }}"><i class="fas fa-user me-2"></i> Profil Saya</a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('donor-profile') }}#settings"><i class="fas fa-cog me-2"></i> Pengaturan</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i> Keluar</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @else
                    <div class="d-flex">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                    </div>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Wishlist Penerima</h1>
            <p class="lead">Temukan kebutuhan mendesak dari panti dan lembaga yang membutuhkan bantuan</p>
            @auth
            <a href="{{ route('wishlist.matching') }}" class="btn btn-light btn-lg mt-3">
                <i class="fas fa-magic me-2"></i> Gunakan Smart Matching
            </a>
            @endauth
        </div>
    </section>

    <!-- Wishlist Section -->
    <section class="wishlist-section py-5">
        <div class="container">
            <div class="wishlist-categories">
                @if(isset($recommendations) && $recommendations->isNotEmpty())
                <div class="mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">Rekomendasi untukmu</h3>
                        <span class="text-muted" style="font-size: 0.95rem;">Disusun dari urgensi, riwayat kategori donasi, dan kedekatan lokasi</span>
                    </div>
                    <div class="wishlist-grid">
                        @foreach($recommendations as $rec)
                        <div class="wishlist-card {{ $rec->urgensi === 'mendesak' ? 'urgent' : 'routine' }}">
                            <div class="wishlist-image">
                                @if($rec->image)
                                    <img src="{{ asset('storage/' . $rec->image) }}" alt="{{ $rec->nama_barang }}" style="object-fit: cover; width: 100%; height: 200px;">
                                @else
                                    <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                                        <i class="fas fa-hands-helping"></i>
                                    </div>
                                @endif
                                <div class="badge bg-primary" style="position: absolute; top: 12px; left: 12px;">Skor {{ number_format($rec->match_score, 1) }}</div>
                            </div>
                            <div class="wishlist-content">
                                <div class="wishlist-header">
                                    <h4>{{ $rec->nama_barang }}</h4>
                                    <div class="wishlist-urgency {{ $rec->urgensi === 'mendesak' ? 'urgent' : ($rec->urgensi === 'kesehatan' ? 'urgent' : 'routine') }}">
                                        <i class="fas {{ $rec->urgensi === 'mendesak' || $rec->urgensi === 'kesehatan' ? 'fa-exclamation-triangle' : 'fa-sync-alt' }}"></i>
                                        {{ ucfirst($rec->urgensi) }}
                                    </div>
                                </div>
                                <div class="wishlist-meta">
                                    <span><i class="fas fa-map-marker-alt"></i> {{ $rec->panti->nama_panti ?? 'Panti Asuhan' }}, {{ $rec->panti->kota ?? 'Lokasi' }}</span>
                                    <span><i class="fas fa-box"></i> {{ $rec->jumlah }} unit</span>
                                </div>
                                <div class="wishlist-details">
                                    <p><strong>Kategori:</strong> {{ $rec->kategori }}</p>
                                    <p><strong>Jumlah Dibutuhkan:</strong> {{ $rec->jumlah }}</p>
                                    <p><strong>Penerima:</strong> {{ $rec->panti->nama_panti ?? 'Panti Asuhan' }}</p>
                                </div>
                                <div class="wishlist-actions">
                                    <button class="btn btn-primary btn-sm">Penuhi Sekarang</button>
                                    <button class="btn btn-outline-primary btn-sm" onclick="showDetail({{ $rec->id_wishlist }})">Detail</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="category-tabs">
                    <a href="{{ route('wishlist') }}" class="category-btn {{ !request('urgensi') && !request('kategori') ? 'active' : '' }}">Semua</a>
                    <a href="{{ route('wishlist', ['urgensi' => 'mendesak']) }}" class="category-btn {{ request('urgensi') === 'mendesak' ? 'active' : '' }}">Mendesak</a>
                    <a href="{{ route('wishlist', ['urgensi' => 'rutin']) }}" class="category-btn {{ request('urgensi') === 'rutin' ? 'active' : '' }}">Rutin</a>
                    <a href="{{ route('wishlist', ['kategori' => 'Pendidikan']) }}" class="category-btn {{ request('kategori') === 'Pendidikan' ? 'active' : '' }}">Pendidikan</a>
                    <a href="{{ route('wishlist', ['kategori' => 'Kesehatan']) }}" class="category-btn {{ request('kategori') === 'Kesehatan' ? 'active' : '' }}">Kesehatan</a>
                </div>
                
                <div class="wishlist-grid">
                    @forelse($wishlists as $wishlist)
                    <div class="wishlist-card {{ $wishlist->urgensi === 'mendesak' ? 'urgent' : 'routine' }}">
                        <div class="wishlist-image">
                            @if($wishlist->image)
                                <img src="{{ asset('storage/' . $wishlist->image) }}" alt="{{ $wishlist->nama_barang }}" style="object-fit: cover; width: 100%; height: 200px;">
                            @else
                                <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                                    <i class="fas fa-hands-helping"></i>
                                </div>
                            @endif
                        </div>
                        <div class="wishlist-content">
                            <div class="wishlist-header">
                                <h4>{{ $wishlist->nama_barang }}</h4>
                                <div class="wishlist-urgency {{ $wishlist->urgensi === 'mendesak' ? 'urgent' : ($wishlist->urgensi === 'kesehatan' ? 'urgent' : 'routine') }}">
                                    <i class="fas {{ $wishlist->urgensi === 'mendesak' || $wishlist->urgensi === 'kesehatan' ? 'fa-exclamation-triangle' : 'fa-sync-alt' }}"></i>
                                    {{ ucfirst($wishlist->urgensi) }}
                                </div>
                            </div>
                            <div class="wishlist-meta">
                                <span><i class="fas fa-map-marker-alt"></i> {{ $wishlist->panti->nama_panti ?? 'Panti Asuhan' }}, {{ $wishlist->panti->kota ?? 'Lokasi' }}</span>
                                <span><i class="fas fa-box"></i> {{ $wishlist->jumlah }} unit</span>
                            </div>
                            <div class="wishlist-details">
                                <p><strong>Kategori:</strong> {{ $wishlist->kategori }}</p>
                                <p><strong>Jumlah Dibutuhkan:</strong> {{ $wishlist->jumlah }}</p>
                                <p><strong>Penerima:</strong> {{ $wishlist->panti->nama_panti ?? 'Panti Asuhan' }}</p>
                            </div>
                            <div class="wishlist-actions">
                                <button class="btn btn-primary btn-sm">Donasi Sekarang</button>
                                <button class="btn btn-outline-primary btn-sm" onclick="showDetail({{ $wishlist->id_wishlist }})">Detail</button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Tidak ada wishlist yang tersedia pada kategori ini.</p>
                    </div>
                    @endforelse
                </div>

                @if($wishlists->hasPages())
                <div class="pagination-wrapper mt-5 mb-4">
                    <div class="d-flex justify-content-center">
                        {{ $wishlists->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- How to Help Section -->
    <section class="how-to-help py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Cara Membantu</h2>
                <p class="lead">Ada beberapa cara untuk membantu memenuhi kebutuhan panti dan lembaga</p>
            </div>
            
            <div class="help-methods">
                <div class="help-method">
                    <div class="help-icon">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <h3>Donasi Langsung</h3>
                    <p>Pilih salah satu wishlist dan donasikan barang yang dibutuhkan secara langsung</p>
                </div>
                
                <div class="help-method">
                    <div class="help-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h3>Donasi Uang</h3>
                    <p>Donasikan uang untuk membiayai pembelian barang yang dibutuhkan panti</p>
                </div>
                
                <div class="help-method">
                    <div class="help-icon">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <h3>Sebarkan Informasi</h3>
                    <p>Bantu menyebarkan informasi wishlist ke teman, keluarga, dan kolega Anda</p>
                </div>
                
                <div class="help-method">
                    <div class="help-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h3>Jadi Relawan</h3>
                    <p>Bantu mengkoordinasi dan mendistribusikan donasi kepada panti yang membutuhkan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5">
        <div class="container">
            <div class="cta-content text-center">
                <h2>Bantu Mereka yang Membutuhkan</h2>
                <p class="lead">Setiap donasi Anda, sekecil apapun, akan sangat berarti bagi mereka yang membutuhkan</p>
                <div class="cta-buttons">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg me-2">Daftar sebagai Donor</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">Daftar sebagai Penerima</a>
                </div>
            </div>
        </div>
    </section>

         <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="row">
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <div class="footer-about">
                            <a href="{{ route('home') }}" class="footer-logo">
                                <img src="{{ asset('assets/raih asa logo.png') }}" alt="RaihAsa Logo" class="footer-logo-img me-2">
                                <span>RaihAsa</span>
                            </a>
                            <p>Platform donasi makanan terpercaya yang menghubungkan donor dengan penerima yang membutuhkan untuk mengurangi pemborosan makanan di Indonesia.</p>
                            <div class="social-links">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                        <div class="footer-links">
                            <h4>Link Cepat</h4>
                            <ul>
                                <li><a href="{{ route('home') }}">Beranda</a></li>
                                <li><a href="#about">Tentang Kami</a></li>
                                <li><a href="#donate">Cara Kerja</a></li>
                                <li><a href="pages/food-rescue.html">Food Rescue</a></li>
                                <li><a href="#contact">Kontak</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                        <div class="footer-links">
                            <h4>Donor</h4>
                            <ul>
                                <li><a href="#donate">Cara Donasi</a></li>
                                <li><a href="#donate">Jenis Makanan</a></li>
                                <li><a href="#donate">Panduan Pengemasan</a></li>
                                <li><a href="#donate">FAQ Donor</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                        <div class="footer-links">
                            <h4>Relawan</h4>
                            <ul>
                                <li><a href="#volunteer-section">Cara Mendaftar</a></li>
                                <li><a href="pages/volunteer.html">Kegiatan</a></li>
                                <li><a href="pages/volunteer.html">Kategori</a></li>
                                <li><a href="pages/volunteer.html">FAQ Relawan</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <div class="footer-contact">
                            <h4>Hubungi Kami</h4>
                            <ul>
                                <li><i class="fas fa-map-marker-alt"></i> Bandung, Indonesia</li>
                                <li><i class="fas fa-phone"></i> +62 22 1234 5678</li>
                                <li><i class="fas fa-envelope"></i> info@raihasa.id</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p>&copy; 2025 RaihAsa. Hak Cipta Dilindungi.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <ul class="footer-bottom-links">
                            <li><a href="#">Kebijakan Privasi</a></li>
                            <li><a href="#">Syarat & Ketentuan</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>