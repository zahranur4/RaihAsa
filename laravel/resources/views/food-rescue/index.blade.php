<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Food Rescue - RaihAsa</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@vite(['resources/css/font-awesome.css','resources/css/style.css','resources/css/components.css','resources/css/food-rescue.css','resources/css/scroll-animation.css','resources/js/main.js','resources/js/scroll-animation.js'])
<header class="header">
<nav class="navbar navbar-expand-lg navbar-light">
<div class="container">
<a class="navbar-brand d-flex align-items-center" href="index.html">
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
<a class="nav-link" href="{{ route('home') }}#about">Tentang</a>
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
<li class="nav-item">
<a class="nav-link requires-auth" href="{{ route('my-donations') }}">Kontribusiku</a>
</li>
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
<h1>Food Rescue</h1>
<p class="lead">Simpan makanan dari pemborosan dengan mendistribusikannya kepada yang membutuhkan</p>
</div>
</section>

<!-- Food Rescue Section -->
<section class="food-rescue-section py-5">
<div class="container">
@php
    $isPantiUser = Auth::check() && (\Illuminate\Support\Facades\DB::table('panti_asuhan')->where('user_id', Auth::id())->exists() || \Illuminate\Support\Facades\DB::table('panti_profiles')->where('id_user', Auth::id())->exists());
@endphp
<div class="rescue-categories">
<div class="category-tabs">
<button class="category-btn active" data-category="all">Semua</button>
<button class="category-btn" data-category="makanan-basah">Makanan Basah</button>
<button class="category-btn" data-category="makanan-kering">Makanan Kering</button>
<button class="category-btn" data-category="buah">Buah</button>
<button class="category-btn" data-category="sayur">Sayuran</button>
<button class="category-btn" data-category="minuman">Minuman</button>
</div>

<div class="text-end mb-4">
                 @auth
                 <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalDonasiBaru" style="background-color: #000957; border-color: #000957;">
                    <i class="fas fa-plus-circle me-2"></i> Donasi Baru
                </button>
                @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg" style="background-color: #000957; border-color: #000957;">
                    <i class="fas fa-plus-circle me-2"></i> Donasi Baru
                </a>
                @endauth
            </div>

<div class="rescue-grid">
@forelse($foods as $food)
<div class="rescue-card {{ $food->urgency }}" data-category="{{ $food->category }}">
<div class="rescue-image">
<div style="width: 100%; height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
<i class="fas fa-utensils"></i>
</div>
</div>
<div class="rescue-content">
<div class="rescue-header">
<h4>{{ $food->nama_makanan }}</h4>
<div class="rescue-urgency {{ $food->urgency }}">
@if($food->urgency === 'critical')
<i class="fas fa-exclamation-circle"></i> Kritis
@elseif($food->urgency === 'urgent')
<i class="fas fa-exclamation-triangle"></i> Mendesak
@else
<i class="fas fa-info-circle"></i> Normal
@endif
</div>
</div>
<div class="rescue-meta">
<span><i class="fas fa-map-marker-alt"></i> {{ number_format($food->distance, 1) }} km</span>
<span><i class="fas fa-clock"></i> {{ (int)$food->hours_remaining }} jam lagi</span>
</div>
<div class="rescue-details">
<p><strong>Jumlah:</strong> {{ $food->porsi }} porsi</p>
<p><strong>Kadaluwarsa:</strong> {{ \Carbon\Carbon::parse($food->waktu_expired)->format('d M Y, H:i') }}</p>
<p><strong>Donor:</strong> {{ $food->donor_name }}</p>
</div>
<div class="rescue-actions">
    @if($isPantiUser)
        <form action="{{ route('food-rescue.claim', $food->id_food) }}" method="POST" style="display: inline;">
            @csrf
            <button class="btn btn-primary btn-sm" onclick="event.preventDefault(); Swal.fire({title: 'Konfirmasi', text: 'Klaim makanan ini?', icon: 'question', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Ya, Klaim!', cancelButtonText: 'Batal'}).then((result) => {if (result.isConfirmed) {this.closest('form').submit();}})">Klaim Donasi</button>
        </form>
    @else
        <button class="btn btn-secondary btn-sm" disabled title="Klaim hanya untuk akun panti">Klaim Donasi</button>
    @endif
    <a href="{{ route('food-rescue.detail', $food->id_food) }}" class="btn btn-outline-primary btn-sm">Detail</a>
</div>
</div>
</div>
@empty
<div class="col-12">
<div class="alert alert-info text-center">
<i class="fas fa-inbox"></i> Tidak ada makanan yang tersedia saat ini
</div>
</div>
@endforelse
</div>
</div>
</section>

<!-- How It Works Section -->
<section class="how-it-works py-5 bg-light">
<div class="container">
<div class="text-center mb-5">
<h2 class="section-title">Cara Kerja Food Rescue</h2>
<p class="lead">Proses mudah untuk menyelamatkan makanan dari pemborosan</p>
</div>

<div class="steps-container">
<div class="step-item">
<div class="step-number">1</div>
<div class="step-content">
<h3>Donor Mendaftar</h3>
<p>Restoran, toko makanan, atau individu mendaftar sebagai donor di platform RaihAsa</p>
</div>
</div>

<div class="step-item">
<div class="step-number">2</div>
<div class="step-content">
<h3>Input Makanan Berlebih</h3>
<p>Donor menginformasikan makanan berlebih yang akan didonasikan beserta detailnya</p>
</div>
</div>

<div class="step-item">
<div class="step-number">3</div>
<div class="step-content">
<h3>Notifikasi Otomatis</h3>
<p>Sistem akan mengirim notifikasi ke penerima terdekat yang membutuhkan makanan tersebut</p>
</div>
</div>

<div class="step-item">
<div class="step-number">4</div>
<div class="step-content">
<h3>Konfirmasi & Pengambilan</h3>
<p>Penerima mengkonfirmasi dan mengatur jadwal pengambilan atau pengantaran</p>
</div>
</div>

<div class="step-item">
<div class="step-number">5</div>
<div class="step-content">
<h3>Distribusi & Pelaporan</h3>
<p>Makanan didistribusikan kepada yang membutuhkan dan donor menerima laporan</p>
</div>
</div>
</div>
</div>
</section>

<!-- Benefits Section -->
<section class="benefits-section py-5">
<div class="container">
<div class="text-center mb-5">
<h2 class="section-title">Manfaat Food Rescue</h2>
<p class="lead">Banyak manfaat yang bisa didapatkan dari program Food Rescue</p>
</div>

<div class="benefits-grid">
<div class="benefit-card">
<div class="benefit-icon">
<i class="fas fa-leaf"></i>
</div>
<h3>Kurangi Pemborosan</h3>
<p>Mengurangi jumlah makanan yang terbuang sia-sia dan berdampak pada lingkungan</p>
</div>

<div class="benefit-card">
<div class="benefit-icon">
<i class="fas fa-hands-helping"></i>
</div>
<h3>Bantu Mereka yang Membutuhkan</h3>
<p>Memberikan makanan bergizi kepada mereka yang tidak mampu membelinya</p>
</div>

<div class="benefit-card">
<div class="benefit-icon">
<i class="fas fa-award"></i>
</div>
<h3>Dapatkan Penghargaan</h3>
<p>Donor mendapatkan penghargaan dan sertifikat sebagai bentuk apresiasi</p>
</div>

<div class="benefit-card">
<div class="benefit-icon">
<i class="fas fa-chart-line"></i>
</div>
<h3>Pantau Dampak</h3>
<p>Memantau dampak positif yang dihasilkan dari setiap donasi yang diberikan</p>
</div>
</div>
</div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5">
<div class="container">
<div class="cta-content text-center">
<h2>Bergabunglah dalam Gerakan Food Rescue</h2>
<p class="lead">Jadilah bagian dari solusi untuk mengurangi pemborosan makanan dan membantu sesama</p>
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
                            <p>Jembatan kebaikan yang menghubungkan kepedulian Anda dengan mereka yang membutuhkan, mewujudkan harapan melalui donasi makanan, barang, dan tenaga.</p>
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

<!-- Modal Donasi Baru -->
<div class="modal fade" id="modalDonasiBaru" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Donasi Makanan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formDonasiBaru" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Nama Makanan -->
                    <div class="mb-3">
                        <label for="nama_makanan" class="form-label fw-bold">Nama Makanan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_makanan" name="nama_makanan" placeholder="Contoh: Nasi Goreng, Ayam Bakar, dll" required>
                    </div>

                    <!-- Jumlah -->
                    <div class="mb-3">
                        <label for="jumlah" class="form-label fw-bold">Jumlah (Porsi) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" placeholder="Contoh: 20" required>
                    </div>

                    <!-- Kadaluarsa -->
                    <div class="mb-3">
                        <label for="kadaluarsa" class="form-label fw-bold">Waktu Kadaluarsa <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" id="kadaluarsa" name="kadaluarsa" required>
                    </div>

                    <!-- Foto -->
                    <div class="mb-3">
                        <label for="foto" class="form-label fw-bold">Foto Makanan <span class="text-muted">(Opsional)</span></label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                        <small class="text-muted">Jika tidak ada foto, akan menggunakan gambar default</small>
                    </div>

                    <!-- Alert Info -->
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Catatan:</strong> Donasi Anda akan diverifikasi oleh admin terlebih dahulu sebelum ditampilkan di halaman Food Rescue.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" style="background-color: #000957; border-color: #000957;">
                        <i class="fas fa-upload me-2"></i> Kirim Donasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Handle form submission for donation
    document.getElementById('formDonasiBaru').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const actionUrl = "{{ route('food-rescue.store') }}";

        fetch(actionUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: data.message || 'Donasi Anda telah dikirim dan menunggu verifikasi admin.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Close modal and reset form
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalDonasiBaru'));
                    modal.hide();
                    document.getElementById('formDonasiBaru').reset();
                    // Reload page to see if new donation appears
                    setTimeout(() => location.reload(), 1000);
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'Terjadi kesalahan saat mengirim donasi',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Terjadi kesalahan jaringan',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    });
</script>
</body>
</html>
