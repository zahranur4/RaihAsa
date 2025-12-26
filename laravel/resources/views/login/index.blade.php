<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - RaihAsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css','resources/css/components.css','resources/css/forms.css','resources/js/main.js','resources/js/login.js'])
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
            <h1>Masuk</h1>
            <p class="lead">Masuk ke akun RaihAsa Anda</p>
        </div>
    </section>

    <!-- Login Section -->
    <section class="login-section py-5">
        <div class="container">
            <div class="login-container">
                <div class="login-form">
                    <div class="text-center mb-4">
                        <div class="login-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <h3>Masuk ke Akun Anda</h3>
                        <p class="text-muted">Masukkan email dan password untuk melanjutkan</p>
                    </div>
                    
                    <form id="login-form">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Masuk</button>
                        </div>
                        <div class="text-center mt-3">
                            <p>Belum punya akun? <a href="register.html">Daftar sekarang</a></p>
                            <p><a href="#" class="text-primary">Lupa password?</a></p>
                        </div>
                    </form>
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