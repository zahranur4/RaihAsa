<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontribusiku - RaihAsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css','resources/css/components.css','resources/css/my-donations.css','resources/css/scroll-animation.css','resources/js/main.js','resources/js/scroll-animation.js'])
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
                                <li><a class="dropdown-item" href="{{ route('home') }}">Beranda</a></li>
                                <li><a class="dropdown-item" href="{{ route('my-donations') }}">Kontribusiku</a></li>                                @if((Auth::user()->is_admin ?? false) || (Auth::user()->email === 'admin@example.com'))
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                @else
                                    @if(Auth::check() && \Illuminate\Support\Facades\DB::table('panti_asuhan')->where('user_id', Auth::id())->exists())
                                        <li><a class="dropdown-item" href="{{ route('panti.dashboard') }}">Dashboard Panti</a></li>
                                    @endif
                                @endif                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Keluar</button>
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
            <h1>Kontribusiku</h1>
            <p class="lead">Lacak dan kelola semua donasi yang telah Anda berikan</p>
        </div>
    </section>

    <!-- Auth Prompt Section -->
    <section class="auth-prompt py-5">
        <div class="container">
            <div class="auth-card">
                <div class="auth-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h2>Mau Berbuat Amalan Sekarang?</h2>
                <p>Daftar akun RaihAsa untuk mulai berdonasi dan melacak kontribusi Anda dalam membantu sesama.</p>
                <div class="auth-buttons">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-2">Daftar Sekarang</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">Masuk</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="benefits-section py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Manfaat Membuat Amalan</h2>
                <p class="lead">Banyak kebaikan yang bisa Anda dapatkan dengan berdonasi melalui RaihAsa</p>
            </div>
            
            <div class="benefits-grid">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Tracking Kontribusi</h3>
                    <p>Pantau semua donasi yang telah Anda berikan dan lihat dampaknya secara real-time</p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h3>Sertifikat Amal</h3>
                    <p>Dapatkan sertifikat digital sebagai bukti kontribusi Anda dalam membantu sesama</p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3>Riwayat Donasi</h3>
                    <p>Lihat riwayat lengkap semua donasi yang pernah Anda lakukan sebelumnya</p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3>Notifikasi Update</h3>
                    <p>Dapatkan notifikasi saat donasi Anda diterima dan didistribusikan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Cara Kerja Kontribusiku</h2>
                <p class="lead">Proses mudah untuk mulai berdonasi dan melacak kontribusi Anda</p>
            </div>
            
            <div class="steps-container">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>Daftar Akun</h3>
                        <p>Buat akun RaihAsa dengan mengisi formulir pendaftaran yang tersedia</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>Pilih Donasi</h3>
                        <p>Pilih makanan atau barang yang ingin Anda donasikan dari wishlist yang tersedia</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>Konfirmasi & Kirim</h3>
                        <p>Konfirmasi detail donasi dan kirimkan ke panti yang membutuhkan</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h3>Terima Update</h3>
                        <p>Dapatkan update status donasi Anda dan lihat dampak yang dihasilkan</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">5</div>
                    <div class="step-content">
                        <h3>Lacak Kontribusi</h3>
                        <p>Lihat semua riwayat donasi Anda di halaman Kontribusiku</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Cerita Kebanggaan</h2>
                <p class="lead">Dengarkan pengalaman mereka yang telah berkontribusi melalui RaihAsa</p>
            </div>
            
            <div class="testimonials-slider">
                <div class="row">
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <div class="testimonial-icon">
                                    <i class="fas fa-quote-left"></i>
                                </div>
                                <p>Dengan fitur Kontribusiku, saya bisa melihat semua donasi yang telah saya berikan dan dampaknya bagi panti. Sangat memuaskan!</p>
                            </div>
                            <div class="testimonial-author">
                                <div class="author-image">
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Budi Santoso">
                                </div>
                                <div class="author-info">
                                    <h4>Budi Santoso</h4>
                                    <p>Donor</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <div class="testimonial-icon">
                                    <i class="fas fa-quote-left"></i>
                                </div>
                                <p>Saya suka bisa melacak kontribusi saya dan melihat bagaimana donasi saya membantu mereka yang membutuhkan.</p>
                            </div>
                            <div class="testimonial-author">
                                <div class="author-image">
                                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Siti Nurhaliza">
                                </div>
                                <div class="author-info">
                                    <h4>Siti Nurhaliza</h4>
                                    <p>Donor</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <div class="testimonial-icon">
                                    <i class="fas fa-quote-left"></i>
                                </div>
                                <p>Fitur notifikasi sangat membantu saya untuk mengetahui kapan donasi saya sudah diterima dan didistribusikan.</p>
                            </div>
                            <div class="testimonial-author">
                                <div class="author-image">
                                    <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Ahmad Fauzi">
                                </div>
                                <div class="author-info">
                                    <h4>Ahmad Fauzi</h4>
                                    <p>Donor</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5">
        <div class="container">
            <div class="cta-content text-center">
                <h2>Bergabunglah dalam Gerakan Kebaikan</h2>
                <p class="lead">Setiap kontribusi Anda, sekecil apapun, akan sangat berarti bagi mereka yang membutuhkan</p>
                <div class="cta-buttons">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg me-2">Daftar Sekarang</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Masuk</a>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>