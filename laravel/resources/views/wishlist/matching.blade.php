<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Matching - RaihAsa</title>
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
                            <a class="nav-link requires-auth" href="{{ route('wishlist') }}">Wishlist</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link requires-auth" href="{{ route('food-rescue') }}">Food Rescue</a>
                        </li>
                    </ul>
                    @auth
                    <div class="d-flex">
                        <div class="dropdown">
                            <a class="btn btn-outline-secondary dropdown-toggle" href="#" role="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->nama ?? Auth::user()->email }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                <li><a class="dropdown-item" href="{{ route('donor-profile') }}"><i class="fas fa-user me-2"></i> Profil Saya</a></li>
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
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Smart Matching</h1>
            <p class="lead">Masukkan barang yang ingin Anda donasikan dan kami akan mencocokkan dengan kebutuhan panti</p>
        </div>
    </section>

    <!-- Matching Form Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="card shadow-sm mb-5">
                        <div class="card-body p-4">
                            <h4 class="card-title mb-4"><i class="fas fa-search me-2 text-primary"></i> Cari Kebutuhan yang Sesuai</h4>
                            
                            <form action="{{ route('wishlist.matching') }}" method="GET" class="form-matching">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="item_name" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Contoh: Buku, Laptop, Pakaian" value="{{ $itemName }}" autocomplete="off">
                                        <small class="text-muted">Ketik nama barang yang ingin Anda donasikan</small>
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label for="quantity" class="form-label">Jumlah <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="1" min="1" value="{{ $quantity }}" required>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="category" class="form-label">Kategori</label>
                                        <select class="form-select" id="category" name="category">
                                            <option value="">Semua Kategori</option>
                                            @foreach($categories as $cat)
                                            <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-magic me-2"></i> Temukan Kecocokan
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($itemName || $category)
                    <!-- Matches Results -->
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="card-title mb-4">
                                <i class="fas fa-chart-pie me-2 text-success"></i> 
                                Kecocokan Ditemukan: {{ count($matches) }}
                            </h4>

                            @if(count($matches) > 0)
                                <div class="matches-grid">
                                    @foreach($matches as $match)
                                    <div class="match-card mb-4 p-3 border rounded" style="background: linear-gradient(to right, rgba(102,126,234,0.05), transparent);">
                                        <div class="row align-items-start">
                                            <div class="col-md-8">
                                                <h5 class="mb-2">{{ $match->nama_barang }}</h5>
                                                
                                                <div class="match-details mb-3">
                                                    <div class="mb-2">
                                                        <strong>Penerima:</strong> {{ $match->panti->nama_panti ?? 'Panti Asuhan' }}, {{ $match->panti->kota ?? 'Lokasi' }}
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong>Kategori:</strong> <span class="badge bg-info">{{ $match->kategori }}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong>Urgensi:</strong> 
                                                        <span class="badge {{ $match->urgensi === 'mendesak' || $match->urgensi === 'kesehatan' ? 'bg-danger' : 'bg-warning' }}">
                                                            {{ ucfirst($match->urgensi) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <strong>Jumlah Dibutuhkan:</strong> {{ $match->jumlah }} unit
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4 text-center">
                                                <div class="match-score mb-3">
                                                    <div class="score-circle" style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white;">
                                                        <div>
                                                            <div style="font-size: 2rem; font-weight: bold;">{{ $match->match_percentage }}%</div>
                                                            <small>Kecocokan</small>
                                                        </div>
                                                    </div>
                                                    <small class="text-muted d-block mt-2">
                                                        Dapat memenuhi {{ $match->quantity_fulfillment }}% kebutuhan
                                                    </small>
                                                </div>

                                                <button type="button" class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#fulfillModal{{ $match->id_wishlist }}">
                                                    <i class="fas fa-hand-holding-heart me-1"></i> Penuhi
                                                </button>

                                                <!-- Fulfill Modal -->
                                                <div class="modal fade" id="fulfillModal{{ $match->id_wishlist }}" tabindex="-1" aria-labelledby="fulfillLabel{{ $match->id_wishlist }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="fulfillLabel{{ $match->id_wishlist }}">Penuhi Kebutuhan</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('wishlist.fulfill', $match->id_wishlist) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="item_name_confirm" class="form-label">Barang yang Akan Didonasikan</label>
                                                                        <input type="text" class="form-control" id="item_name_confirm" name="item_name" value="{{ $itemName }}" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="quantity_offered" class="form-label">Jumlah</label>
                                                                        <input type="number" class="form-control" id="quantity_offered" name="quantity_offered" value="{{ $quantity }}" min="1" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="notes" class="form-label">Catatan (Opsional)</label>
                                                                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Contoh: Kondisi barang, waktu pengiriman, dll"></textarea>
                                                                    </div>
                                                                    <div class="alert alert-info">
                                                                        <i class="fas fa-info-circle me-2"></i>
                                                                        <strong>Penerima:</strong> {{ $match->panti->nama_panti ?? 'Panti' }} akan menerima penawaran donasi Anda. Tunggu konfirmasi lebih lanjut.
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-primary">Kirim Penawaran</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Tidak ada kecocokan ditemukan</strong><br>
                                    Coba dengan nama barang atau kategori lain. Silakan kembali ke <a href="{{ route('wishlist') }}" class="alert-link">Wishlist</a> untuk melihat semua kebutuhan.
                                </div>
                            @endif
                        </div>
                    </div>
                    @else
                    <!-- Initial Tips -->
                    <div class="alert alert-light border-2 border-primary">
                        <h5 class="mb-3"><i class="fas fa-lightbulb text-warning me-2"></i> Tips Pencarian</h5>
                        <ul class="mb-0">
                            <li>Masukkan nama barang yang ingin Anda donasikan (misal: Buku, Pakaian, Vitamin, Peralatan Masak)</li>
                            <li>Tentukan jumlah barang yang Anda miliki</li>
                            <li>Pilih kategori jika Anda tahu kategorinya (opsional)</li>
                            <li>Sistem akan mencocokkan dengan kebutuhan panti berdasarkan urgensi dan kecocokan kategori</li>
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Bagaimana Smart Matching Bekerja?</h2>
            </div>
            
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="text-center">
                        <div class="mb-3" style="font-size: 3rem; color: #667eea;">
                            <i class="fas fa-edit"></i>
                        </div>
                        <h5>1. Input Barang</h5>
                        <p class="text-muted">Masukkan nama dan jumlah barang yang ingin Anda donasikan</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="text-center">
                        <div class="mb-3" style="font-size: 3rem; color: #764ba2;">
                            <i class="fas fa-magic"></i>
                        </div>
                        <h5>2. Pencarian Cerdas</h5>
                        <p class="text-muted">Sistem mencocokkan dengan kebutuhan yang paling mendesak</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="text-center">
                        <div class="mb-3" style="font-size: 3rem; color: #FF6B6B;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h5>3. Pilih Kecocokan</h5>
                        <p class="text-muted">Pilih dari hasil yang paling sesuai dengan kebutuhan</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="text-center">
                        <div class="mb-3" style="font-size: 3rem; color: #4ECDC4;">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h5>4. Konfirmasi</h5>
                        <p class="text-muted">Tunggu konfirmasi dari panti untuk melanjutkan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="text-center">
                    <a href="{{ route('home') }}" class="footer-logo">
                        <img src="{{ asset('assets/raih asa logo.png') }}" alt="RaihAsa Logo" class="footer-logo-img me-2" style="height: 40px;">
                        <span>RaihAsa</span>
                    </a>
                    <p class="mt-3">Menghubungkan hati yang ingin berbagi dengan yang membutuhkan</p>
                </div>
            </div>
            <div class="footer-bottom mt-4">
                <div class="text-center">
                    <p>&copy; 2025 RaihAsa. Hak Cipta Dilindungi.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
