<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Food Rescue - RaihAsa</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@vite(['resources/css/style.css','resources/css/components.css','resources/css/food-rescue.css','resources/css/scroll-animation.css','resources/js/main.js','resources/js/scroll-animation.js'])

</head>
<body>
<!-- Header with Navigation -->
<header class="header">
<nav class="navbar navbar-expand-lg navbar-light">
<div class="container">
<a class="navbar-brand d-flex align-items-center" href="index.html">
<img src="/assets/raih asa logo.png" alt="RaihAsa Logo" height="40" class="me-2">
<span>RaihAsa</span>
</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNav">
<ul class="navbar-nav mx-auto">
<li class="nav-item">
<a class="nav-link" href="/index.html">Beranda</a>
</li>
<li class="nav-item">
<a class="nav-link" href="/index.html#about">Tentang</a>
</li>
<li class="nav-item">
<a class="nav-link requires-auth" href="/pages/food-rescue.html">Food Rescue</a>
</li>
<li class="nav-item">
<a class="nav-link requires-auth" href="/pages/wishlist.html">Wishlist</a>
</li>
<li class="nav-item">
<a class="nav-link requires-auth" href="/pages/volunteer.html">Relawan</a>
</li>
<li class="nav-item">
<a class="nav-link requires-auth" href="/pages/my-donations.html">Kontribusiku</a>
</li>
</ul>
<div class="d-flex">
<a href="/pages/login.html" class="btn btn-outline-primary me-2">Masuk</a>
<a href="register.html" class="btn btn-primary">Daftar</a>
</div>
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
<div class="rescue-categories">
<div class="category-tabs">
<button class="category-btn active" data-category="all">Semua</button>
<button class="category-btn" data-category="makanan-basah">Makanan Basah</button>
<button class="category-btn" data-category="makanan-kering">Makanan Kering</button>
<button class="category-btn" data-category="buah">Buah</button>
<button class="category-btn" data-category="sayur">Sayuran</button>
<button class="category-btn" data-category="minuman">Minuman</button>
</div>

<div class="rescue-grid">
<div class="rescue-card critical" data-category="makanan-basah">
<div class="rescue-image">
<img src="/assets/nasi kotak.webp" alt="Nasi Kotak">
</div>
<div class="rescue-content">
<div class="rescue-header">
<h4>Nasi Kotak Sisa Catering</h4>
<div class="rescue-urgency critical">
<i class="fas fa-exclamation-circle"></i> Kritis
</div>
</div>
<div class="rescue-meta">
<span><i class="fas fa-map-marker-alt"></i> 3.2 km</span>
<span><i class="fas fa-clock"></i> 2 jam lagi</span>
</div>
<div class="rescue-details">
<p><strong>Jumlah:</strong> 50 porsi</p>
<p><strong>Kadaluwarsa:</strong> Hari ini, 18:00</p>
<p><strong>Donor:</strong> Catering Sejahtera</p>
</div>
<div class="rescue-actions">
<button class="btn btn-primary btn-sm">Klaim Donasi</button>
<button class="btn btn-outline-primary btn-sm">Detail</button>
</div>
</div>
</div>

<div class="rescue-card urgent" data-category="sayur">
<div class="rescue-image">
<img src="https://images.unsplash.com/photo-1610832958506-aa56368176cf?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=780&q=80" alt="Sayuran Segar">
</div>
<div class="rescue-content">
<div class="rescue-header">
<h4>Sayuran Segar Restoran</h4>
<div class="rescue-urgency urgent">
<i class="fas fa-exclamation-triangle"></i> Mendesak
</div>
</div>
<div class="rescue-meta">
<span><i class="fas fa-map-marker-alt"></i> 5.7 km</span>
<span><i class="fas fa-clock"></i> 5 jam lagi</span>
</div>
<div class="rescue-details">
<p><strong>Jumlah:</strong> 20 kg</p>
<p><strong>Kadaluwarsa:</strong> Besok, 10:00</p>
<p><strong>Donor:</strong> Restoran Lezat</p>
</div>
<div class="rescue-actions">
<button class="btn btn-primary btn-sm">Klaim Donasi</button>
<button class="btn btn-outline-primary btn-sm">Detail</button>
</div>
</div>
</div>

<div class="rescue-card urgent" data-category="makanan-kering">
<div class="rescue-image">
<img src="/assets/roti.jpg" alt="Roti">
</div>
<div class="rescue-content">
<div class="rescue-header">
<h4>Roti Sisa Toko Roti</h4>
<div class="rescue-urgency urgent">
<i class="fas fa-exclamation-triangle"></i> Mendesak
</div>
</div>
<div class="rescue-meta">
<span><i class="fas fa-map-marker-alt"></i> 2.1 km</span>
<span><i class="fas fa-clock"></i> 8 jam lagi</span>
</div>
<div class="rescue-details">
<p><strong>Jumlah:</strong> 30 bungkus</p>
<p><strong>Kadaluwarsa:</strong> Besok, 12:00</p>
<p><strong>Donor:</strong> Toko Roti Enak</p>
</div>
<div class="rescue-actions">
<button class="btn btn-primary btn-sm">Klaim Donasi</button>
<button class="btn btn-outline-primary btn-sm">Detail</button>
</div>
</div>
</div>

<div class="rescue-card normal" data-category="buah">
<div class="rescue-image">
<img src="https://images.unsplash.com/photo-1610832958506-aa56368176cf?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=780&q=80" alt="Buah">
</div>
<div class="rescue-content">
<div class="rescue-header">
<h4>Buah Segar Pasar</h4>
<div class="rescue-urgency normal">
<i class="fas fa-info-circle"></i> Normal
</div>
</div>
<div class="rescue-meta">
<span><i class="fas fa-map-marker-alt"></i> 4.5 km</span>
<span><i class="fas fa-clock"></i> 1 hari lagi</span>
</div>
<div class="rescue-details">
<p><strong>Jumlah:</strong> 15 kg</p>
<p><strong>Kadaluwarsa:</strong> 2 hari lagi</p>
<p><strong>Donor:</strong> Pasar Segar</p>
</div>
<div class="rescue-actions">
<button class="btn btn-primary btn-sm">Klaim Donasi</button>
<button class="btn btn-outline-primary btn-sm">Detail</button>
</div>
</div>
</div>

<div class="rescue-card normal" data-category="minuman">
<div class="rescue-image">
<img src="/assets/minuman kemasan.jpeg" alt="Minuman">
</div>
<div class="rescue-content">
<div class="rescue-header">
<h4>Minuman Kemasan</h4>
<div class="rescue-urgency normal">
<i class="fas fa-info-circle"></i> Normal
</div>
</div>
<div class="rescue-meta">
<span><i class="fas fa-map-marker-alt"></i> 6.8 km</span>
<span><i class="fas fa-clock"></i> 2 hari lagi</span>
</div>
<div class="rescue-details">
<p><strong>Jumlah:</strong> 100 botol</p>
<p><strong>Kadaluwarsa:</strong> 3 hari lagi</p>
<p><strong>Donor:</strong> Distributor Minuman</p>
</div>
<div class="rescue-actions">
<button class="btn btn-primary btn-sm">Klaim Donasi</button>
<button class="btn btn-outline-primary btn-sm">Detail</button>
</div>
</div>
</div>

<div class="rescue-card normal" data-category="makanan-basah">
<div class="rescue-image">
<img src="/assets/masakan restoran.jpeg" alt="Makanan Masak">
</div>
<div class="rescue-content">
<div class="rescue-header">
<h4>Makanan Masak Restoran</h4>
<div class="rescue-urgency normal">
<i class="fas fa-info-circle"></i> Normal
</div>
</div>
<div class="rescue-meta">
<span><i class="fas fa-map-marker-alt"></i> 3.8 km</span>
<span><i class="fas fa-clock"></i> 1 hari lagi</span>
</div>
<div class="rescue-details">
<p><strong>Jumlah:</strong> 20 porsi</p>
<p><strong>Kadaluwarsa:</strong> Besok, 08:00</p>
<p><strong>Donor:</strong> Restoran Keluarga</p>
</div>
<div class="rescue-actions">
<button class="btn btn-primary btn-sm">Klaim Donasi</button>
<button class="btn btn-outline-primary btn-sm">Detail</button>
</div>
</div>
</div>
</div>
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
<a href="/pages/register.html" class="btn btn-light btn-lg me-2">Daftar sebagai Donor</a>
<a href="/pages/register.html" class="btn btn-outline-light btn-lg">Daftar sebagai Penerima</a>
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
                            <a href="index.html" class="footer-logo">
                                <img src="/assets/raih asa logo.png" alt="RaihAsa Logo" class="footer-logo-img me-2">
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
                                <li><a href="index.html">Beranda</a></li>
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
