<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Rescue - RaihAsa</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/font-awesome.css','resources/css/style.css','resources/css/components.css','resources/css/food-rescue.css','resources/css/scroll-animation.css','resources/js/main.js','resources/js/scroll-animation.js'])
</head>
<body>

<!-- Header with Navigation (ASLI) -->
<header class="header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
                    <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <!-- LOGO ASLI KAMU -->
                <img src="/assets/raih asa logo.png" alt="RaihAsa Logo" height="40" class="me-2">
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

            <!-- [TOMBOL DONASI BARU] -->
            <div class="text-end mb-4">
                 <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalDonasiBaru" style="background-color: #000957; border-color: #000957;">
                    <i class="fas fa-plus-circle me-2"></i> Donasi Baru
                </button>
            </div>

            <div class="rescue-grid">
                @if($foods && $foods->count())
                    @foreach($foods as $food)
                        @php
                            $urgencyClass = $food->urgency ?? 'normal';
                            $urgencyLabel = $urgencyClass === 'critical' ? 'Kritis' : ($urgencyClass === 'urgent' ? 'Mendesak' : 'Normal');
                            $countdownSeconds = $food->countdown_seconds ?? 0;
                            $initialCountdown = gmdate('H:i:s', max(0, $countdownSeconds));
                            $imageUrl = !empty($food->foto) ? asset('storage/'.$food->foto) : asset('assets/food-placeholder.svg');
                            $claimDisabled = !$isPanti || !$food->claimable;
                            $claimTitle = !$isPanti ? 'Hanya panti yang bisa mengklaim' : '';
                        @endphp
                        <div class="rescue-card {{ $urgencyClass }}" data-category="{{ $food->category }}">
                            <div class="rescue-image">
                                @if(!empty($food->foto))
                                    <img src="{{ $imageUrl }}" alt="{{ $food->nama_makanan }}">
                                @else
                                    <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                                        <i class="fas fa-utensils"></i>
                                    </div>
                                @endif
                                <div class="countdown-timer {{ $urgencyClass }}">
                                    <i class="fas fa-clock"></i> <span class="card-countdown" data-time="{{ $countdownSeconds }}">{{ $initialCountdown }}</span>
                                </div>
                            </div>
                            <div class="rescue-content">
                                <div class="rescue-header">
                                    <h4>{{ $food->nama_makanan }}</h4>
                                    <div class="rescue-urgency {{ $urgencyClass }}">
                                        @if($urgencyClass === 'critical')
                                            <i class="fas fa-exclamation-circle"></i> {{ $urgencyLabel }}
                                        @elseif($urgencyClass === 'urgent')
                                            <i class="fas fa-exclamation-triangle"></i> {{ $urgencyLabel }}
                                        @else
                                            <i class="fas fa-info-circle"></i> {{ $urgencyLabel }}
                                        @endif
                                    </div>
                                </div>
                                <div class="rescue-meta">
                                    <span><i class="fas fa-map-marker-alt"></i> {{ number_format($food->distance ?? 0, 1) }} km</span>
                                    <span><i class="fas fa-clock"></i> {{ round($food->hours_remaining ?? 0) }} jam lagi</span>
                                </div>
                                <div class="rescue-details">
                                    <p><strong>Jumlah:</strong> {{ $food->porsi }} porsi</p>
                                    <p><strong>Kadaluwarsa:</strong> {{ \Carbon\Carbon::parse($food->waktu_expired)->format('d M Y, H:i') }}</p>
                                    <p><strong>Donor:</strong> {{ $food->donor_name }}</p>
                                </div>
                                <div class="rescue-actions">
                                    <form action="{{ route('food-rescue.claim', $food->id_food) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm @if($claimDisabled) disabled @endif" @if($claimDisabled) disabled @endif title="{{ $claimTitle }}">
                                            @if(!$isPanti)
                                                Khusus Panti
                                            @elseif(!$food->claimable)
                                                Tidak Tersedia
                                            @else
                                                Klaim Donasi
                                            @endif
                                        </button>
                                    </form>
                                    <a href="{{ route('food-rescue.detail', $food->id_food) }}" class="btn btn-outline-primary btn-sm">Detail</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center w-100 py-5">
                        <p class="lead mb-1">Belum ada donasi makanan tersedia.</p>
                        <p class="text-muted">Coba lagi nanti atau buat donasi baru.</p>
                    </div>
                @endif
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
                <div class="div class="step-content">
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
                <p>Pantau dampak positif yang dihasilkan dari setiap donasi yang diberikan</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5">
    <div class="container">
        <div class="cta-content text-center">
            <h2>Bergabunglah dalam Gerakan Food Rescue</h2>
            <p class="lead">Bersama-sama, kita dapat membuat perbedaan nyata dalam kehidupan banyak orang dan mengurangi dampak negatif pemborosan makanan terhadap lingkungan.</p>
            <div class="cta-buttons">
                <a href="/pages/register.html" class="btn btn-light btn-lg me-2">Daftar sebagai Donor</a>
                <a href="/pages/volunteer.html" class="btn btn-outline-light btn-lg">Daftar sebagai Penerima</a>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER ASLI KAMU (Dari Index) -->
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
                            <li><a href="pages/volunteer.html">FAQ Relawan</li>
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

<!-- [MODAL DONASI BARU] -->
<div class="modal fade" id="modalDonasiBaru" tabindex="-1" aria-labelledby="donasiBaruModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color: #000957; font-weight: 700;" id="donasiBaruModalLabel">Donasi Makanan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Perbaikan: Menambahkan Tag Form agar bisa submit -->
                <form id="donationForm" action="{{ route('food-rescue.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- KOLOM KIRI: Upload Gambar -->
                        <div class="col-md-5 mb-4 mb-md-0 text-center">
                            <label class="form-label fw-bold mb-3">Foto Makanan</label>
                            
                            <div id="preview-container" class="image-preview-container">
                                <img id="preview-img" src="" alt="Preview Makanan">
                                <div class="preview-placeholder">
                                    <i class="fas fa-camera fa-3x mb-2"></i>
                                    <p class="small text-muted">Klik ikon untuk upload<br>foto makanan</p>
                                </div>
                            </div>
                            
                            <label for="foodImage" class="btn btn-outline-primary w-100">
                                <i class="fas fa-upload me-2"></i> Pilih Foto
                            </label>
                            <input type="file" id="foodImage" name="foto" class="d-none" accept="image/*">
                        </div>

                        <!-- KOLOM KANAN: Kategori & Detail -->
                        <div class="col-md-7">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold" style="color: #000957; margin:0;">Detail Donasi</h5>
                                <small class="text-muted">Lengkapi data makanan</small>
                            </div>

                            <!-- Pilihan Kategori (Grid Rapi) -->
                            <div class="category-selection mb-4">
                                <label class="form-label small fw-bold mb-2">Pilih Kategori</label>
                                <div class="category-grid-sm">
                                    <input type="hidden" id="inputCategory" name="kategori" value="makanan-basah" required>
                                    
                                    <div class="category-item-sm selected" onclick="selectCategory('makanan-basah', this)">
                                        <i class="fas fa-utensils category-icon-sm"></i>
                                        <span class="small fw-bold">Basah</span>
                                    </div>
                                    <div class="category-item-sm" onclick="selectCategory('makanan-kering', this)">
                                        <i class="fas fa-cookie-bite category-icon-sm"></i>
                                        <span class="small fw-bold">Kering</span>
                                    </div>
                                    <div class="category-item-sm" onclick="selectCategory('buah', this)">
                                        <i class="fas fa-apple-alt category-icon-sm"></i>
                                        <span class="small fw-bold">Buah</span>
                                    </div>
                                    <div class="category-item-sm" onclick="selectCategory('sayur', this)">
                                        <i class="fas fa-carrot category-icon-sm"></i>
                                        <span class="small fw-bold">Sayur</span>
                                    </div>
                                    <div class="category-item-sm" onclick="selectCategory('minuman', this)">
                                        <i class="fas fa-glass-cheers category-icon-sm"></i>
                                        <span class="small fw-bold">Minuman</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Inputs -->
                            <div class="row g-3">
                                <div class="col-12 mb-3">
                                    <label for="foodName" class="form-label small fw-bold">Nama Makanan</label>
                                    <input type="text" class="form-control" id="foodName" name="nama_makanan" placeholder="Contoh: Nasi Kotak Catering" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="foodQuantity" class="form-label small fw-bold">Jumlah</label>
                                    <input type="number" class="form-control" id="foodQuantity" name="jumlah" placeholder="Contoh: 50" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="expiryDate" class="form-label small fw-bold">Kadaluwarsa</label>
                                    <input type="datetime-local" class="form-control" id="expiryDate" name="kadaluarsa" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="foodLocation" class="form-label small fw-bold">Lokasi Penjemputan</label>
                                <select class="form-select" id="foodLocation" name="lokasi" required>
                                    <option value="" disabled selected>Pilih Lokasi</option>
                                    <option value="andir">Andir</option>
                                    <option value="astana-anyar">Astana Anyar</option>
                                    <option value="antapani">Antapani</option>
                                    <option value="arcamanik">Arcamanik</option>
                                    <option value="babakan-ciparay">Babakan Ciparay</option>
                                    <option value="bandung-kidul">Bandung Kidul</option>
                                    <option value="bandung-kulon">Bandung Kulon</option>
                                    <option value="bandung-wetan">Bandung Wetan</option>
                                    <option value="batununggal">Batununggal</option>
                                    <option value="bojongloa-kaler">Bojongloa Kaler</option>
                                    <option value="bojongloa-kidul">Bojongloa Kidul</option>
                                    <option value="buahbatu">Buahbatu</option>
                                    <option value="cibeunying-kaler">Cibeunying Kaler</option>
                                    <option value="cibeunying-kidul">Cibeunying Kidul</option>
                                    <option value="cibiru">Cibiru</option>
                                    <option value="cicendo">Cicendo</option>
                                    <option value="cidadap">Cidadap</option>
                                    <option value="cinambo">Cinambo</option>
                                    <option value="coblong">Coblong</option>
                                    <option value="gedebage">Gedebage</option>
                                    <option value="kiaracondong">Kiaracondong</option>
                                    <option value="lengkong">Lengkong</option>
                                    <option value="mandalajati">Mandalajati</option>
                                    <option value="panyileukan">Panyileukan</option>
                                    <option value="rancasari">Rancasari</option>
                                    <option value="regol">Regol</option>
                                    <option value="sukajadi">Sukajadi</option>
                                    <option value="sukasari">Sukasari</option>
                                    <option value="sumur-bandung">Sumur Bandung</option>
                                    <option value="ujung-berung">Ujung Berung</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="foodDescription" class="form-label small fw-bold">Deskripsi</label>
                                <textarea class="form-control" id="foodDescription" name="deskripsi" rows="2" placeholder="Deskripsikan kondisi makanan..." required></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm" style="background-color: #000957; border-color: #000957;">
                            <i class="fas fa-paper-plane me-1"></i> Kirim ke Admin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- [SCRIPT KHUSUS FITUR BARU] -->
<script>
    // Initialize modal to reset form when opened
    const modalElement = document.getElementById('modalDonasiBaru');
    if (modalElement) {
        modalElement.addEventListener('show.bs.modal', function() {
            // Reset form and category
            document.getElementById('donationForm').reset();
            document.getElementById('inputCategory').value = 'makanan-basah';
            document.querySelectorAll('.category-item-sm').forEach((el, idx) => {
                if (idx === 0) {
                    el.classList.add('selected');
                } else {
                    el.classList.remove('selected');
                }
            });
        });
    }

    // 1. Logic Select Kategori di Modal (Grid)
    // Perbaikan: Menggunakan selector .category-item-sm sesuai HTML
    function selectCategory(category, element) {
        console.log('Selected category:', category); // Debug log
        document.getElementById('inputCategory').value = category;
        
        // Hapus class selected dari semua item
        document.querySelectorAll('.category-item-sm').forEach(el => el.classList.remove('selected'));
        
        // Tambahkan class selected ke item yang diklik
        element.classList.add('selected');
    }

    // 2. Logic Preview Gambar
    const foodImageInput = document.getElementById('foodImage');
    const previewImg = document.getElementById('preview-img');
    const placeholderText = document.querySelector('.preview-placeholder');

    if(foodImageInput) {
        foodImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                    if(placeholderText) placeholderText.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // 3. Logic Countdown Timer
    document.querySelectorAll('.card-countdown').forEach(timer => {
        let totalSeconds = parseInt(timer.getAttribute('data-time'));
        
        if (isNaN(totalSeconds) || totalSeconds <= 0) {
            timer.innerText = "Expired";
            return;
        }

        const interval = setInterval(() => {
            if (totalSeconds <= 0) {
                clearInterval(interval);
                timer.innerText = "Expired";
                return;
            }
            
            totalSeconds--;
            
            const hh = Math.floor(totalSeconds / 3600).toString().padStart(2, '0');
            const mm = Math.floor((totalSeconds % 3600) / 60).toString().padStart(2, '0');
            const ss = (totalSeconds % 60).toString().padStart(2, '0');
            
            timer.innerText = `${hh}:${mm}:${ss}`;
        }, 1000);
    });

    // 4. Logic Submit Form (AJAX ke backend)
    const formDonasi = document.getElementById('donationForm');
    if(formDonasi) {
        formDonasi.addEventListener('submit', async function(e) {
            e.preventDefault();

            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            const formData = new FormData(this);

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await response.json();
                const success = data?.success !== false;
                btn.innerHTML = success ? '<i class="fas fa-check-circle"></i> Terkirim!' : originalText;
                if (success) {
                    btn.classList.replace('btn-primary', 'btn-success');
                }

                if (success) {
                    this.reset();
                    // Reset category to default after form reset
                    document.getElementById('inputCategory').value = 'makanan-basah';
                    document.querySelectorAll('.category-item-sm').forEach(el => el.classList.remove('selected'));
                    document.querySelectorAll('.category-item-sm')[0].classList.add('selected');
                    
                    previewImg.style.display = 'none';
                    if(placeholderText) placeholderText.style.display = 'block';

                    setTimeout(() => {
                        const myModalEl = document.getElementById('modalDonasiBaru');
                        const modalInstance = bootstrap.Modal.getInstance(myModalEl);
                        if(modalInstance) modalInstance.hide();

                        setTimeout(() => {
                            btn.innerHTML = originalText;
                            btn.classList.replace('btn-success', 'btn-primary');
                            btn.disabled = false;
                        }, 500);
                    }, 800);
                } else {
                    btn.disabled = false;
                    alert(data?.message || 'Gagal mengirim donasi, coba lagi.');
                }
            } catch (error) {
                btn.disabled = false;
                btn.innerHTML = originalText;
                alert('Terjadi kesalahan, coba lagi.');
            }
        });
    }
</script>

</body>
</html>