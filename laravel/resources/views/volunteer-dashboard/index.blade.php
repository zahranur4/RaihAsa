<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Relawan - RaihAsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    @vite(['resources/css/style.css', 'resources/css/components.css', 'resources/css/volunteer-dashboard.css'])
</head>
<body>

<!-- Header -->
<header class="header">
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="/assets/raih asa logo.png" alt="RaihAsa Logo" height="40" class="me-2">
                <span style="color: #000957; font-weight: 700;">RaihAsa</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mt-3 mt-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Beranda</a></li>
                    <!-- MENU TENTANG DITAMBAHKAN DI SINI -->
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#about">Tentang</a></li>

                    <li class="nav-item"><a class="nav-link" href="{{ route('food-rescue') }}">Food Rescue</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('wishlist') }}">Wishlist</a></li>
                    <li class="nav-item"><a class="nav-link active fw-bold" href="{{ auth()->check() && auth()->user()->relawan_profiles()->where('status_verif', 'verified')->exists() ? route('volunteer.dashboard') : route('volunteer') }}">Relawan</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('my-donations') }}">Kontribusiku</a></li>
                </ul>

                @auth
                <!-- User Profile -->
                <div class="d-flex align-items-center user-profile-nav ms-lg-3">
                    <div class="text-end me-3">
                        <div class="fw-bold" style="color: #000957;">{{ Auth::user()->nama ?? Auth::user()->email }}</div>
                        <div class="small text-muted">
                            @if(isset($volunteer))
                                @if($volunteer->status_verif === 'verified')
                                    Relawan Terverifikasi
                                @elseif($volunteer->status_verif === 'pending')
                                    Menunggu Verifikasi
                                @else
                                    Relawan
                                @endif
                            @else
                                Pengguna
                            @endif
                        </div>
                    </div>
                    <div class="dropdown">
                        <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="rounded-circle d-flex align-items-center justify-content-center border border-2 border-light shadow-sm" style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: bold; font-size: 1.1rem;">
                                {{ strtoupper(substr(Auth::user()->nama ?? 'U', 0, 1)) }}
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item" href="{{ route('donor-profile') }}">Pengaturan Akun</a></li>
                            @if(isset($volunteer))
                            <li><a class="dropdown-item" href="{{ route('volunteer.status') }}">Sertifikat Saya</a></li>
                            @endif
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
                @else
                <!-- Guest buttons -->
                <div class="d-flex align-items-center ms-lg-3">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary" style="background-color: #000957; border-color: #000957;">Daftar</a>
                </div>
                @endauth
            </div>
        </div>
    </nav>
</header>

<main class="py-4 volunteer-dashboard-section bg-light">
    <div class="container">
        
        @guest
        <!-- Login Required Section - Only for guests -->
        <div class="row justify-content-center py-5">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="fas fa-sign-in-alt fa-4x" style="color: #000957;"></i>
                        </div>
                        <h3 class="fw-bold mb-3" style="color: #000957;">Login Diperlukan</h3>
                        <p class="text-muted mb-4">Anda harus login terlebih dahulu untuk mengakses fitur volunteer lengkap</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg" style="background-color: #000957; border-color: #000957;">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-outline-secondary">Daftar Akun</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        
        <!-- Welcome Header -->
        <div class="mb-4">
            <h2 class="fw-bold" style="color: #000957;">Dashboard Relawan</h2>
            <p class="text-muted mb-0">Halo {{ $volunteer->nama ?? Auth::user()->nama }}, siap membantu hari ini?</p>
        </div>

        <!-- Statistik Relawan (Tanpa Total Jam) -->
        <section class="row g-4 mb-5">
            <div class="col-md-6 col-lg-4">
                <div class="stat-card bg-white p-4 rounded shadow-sm h-100 border-0">
                    <div class="d-flex align-items-center mb-2">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary rounded-circle me-3">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $stats['registered_activities'] ?? 0 }}</h3>
                            <small class="text-muted">Kegiatan Terdaftar</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="stat-card bg-white p-4 rounded shadow-sm h-100 border-0">
                    <div class="d-flex align-items-center mb-2">
                        <div class="stat-icon bg-info bg-opacity-10 text-info rounded-circle me-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $stats['children_helped'] ?? 0 }}</h3>
                            <small class="text-muted">Anak Terbantu</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="stat-card bg-white p-4 rounded shadow-sm h-100 border-0">
                    <div class="d-flex align-items-center mb-2">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning rounded-circle me-3">
                            <i class="fas fa-star"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $stats['certificates'] ?? 0 }}</h3>
                            <small class="text-muted">Sertifikat Didapat</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Layout Grid -->
        <div class="row g-4">
            
            <!-- Kolom Kiri: List Kegiatan -->
            <div class="col-lg-8">
                
                <!-- BAGIAN 1: Pilihan Kegiatan (Rekomendasi) -->
                <div class="mb-5">
                    <h4 class="fw-bold mb-3" style="color: #000957;"><i class="fas fa-lightbulb me-2"></i>Rekomendasi Kegiatan</h4>
                    <p class="text-muted small mb-3">Pilih kegiatan yang sesuai dengan skill Anda</p>

                    <div class="recommended-grid">
                        @forelse($recommendedActivities as $activity)
                        <div class="activity-card recommended">
                            <div class="card-body p-0">
                                <div class="d-flex">
                                    <div class="activity-thumb bg-primary text-white d-flex align-items-center justify-content-center">
                                        <i class="fas fa-{{ $activity->category === 'Edukasi & Literasi' ? 'chalkboard-teacher' : ($activity->category === 'Kreatif & Psikososial' ? 'palette' : 'hands-helping') }} fa-2x"></i>
                                    </div>
                                    <div class="activity-content p-3 flex-grow-1">
                                        <div class="mb-2">
                                            <h5 class="mb-0 fw-bold">{{ $activity->title }}</h5>
                                        </div>
                                        <div class="activity-meta small text-muted mb-2">
                                            <span class="me-3"><i class="fas fa-map-marker-alt me-1"></i> {{ $activity->location }}</span>
                                            <span><i class="fas fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($activity->activity_date)->format('d M Y') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Butuh {{ $activity->needed_volunteers - $activity->registered_count }} relawan lagi</small>
                                            <button class="btn btn-sm btn-primary" style="background-color: #000957; border-color: #000957;" data-bs-toggle="modal" data-bs-target="#registerActivityModal" onclick="setActivityId({{ $activity->id_activity }})">
                                                Daftar Sekarang
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted">Tidak ada kegiatan tersedia saat ini</p>
                        @endforelse
                    </div>
                </div>

                <!-- BAGIAN 2: Kegiatan Saya (History) -->
                <div>
                    <h4 class="fw-bold mb-3" style="color: #000957;"><i class="fas fa-history me-2"></i>Kegiatan Saya</h4>
                    
                    <!-- Filter Tabs -->
                    <div class="volunteer-tabs mb-4">
                        <button class="volunteer-tab active" onclick="filterActivities('all', this)">Semua</button>
                        <button class="volunteer-tab" onclick="filterActivities('registered', this)">Terdaftar</button>
                        <button class="volunteer-tab" onclick="filterActivities('approved', this)">Disetujui</button>
                        <button class="volunteer-tab" onclick="filterActivities('completed', this)">Selesai</button>
                    </div>

                    <!-- Grid History -->
                    <div class="activities-grid">
                        @forelse($userActivities as $activity)
                        <div class="activity-card" data-status="{{ $activity->status }}">
                            <div class="card-body p-0">
                                <div class="d-flex">
                                    <div class="activity-thumb bg-light d-flex align-items-center justify-content-center">
                                        <i class="fas fa-{{ $activity->status === 'completed' ? 'check-circle' : ($activity->status === 'approved' ? 'calendar-check' : 'hourglass-half') }} fa-2x text-primary"></i>
                                    </div>
                                    <div class="activity-content p-3 flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="mb-0 fw-bold">{{ $activity->title }}</h5>
                                            @if($activity->status === 'approved')
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill">Disetujui</span>
                                            @elseif($activity->status === 'registered')
                                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill">Menunggu</span>
                                            @elseif($activity->status === 'completed')
                                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill">Selesai</span>
                                            @elseif($activity->status === 'rejected')
                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill">Ditolak</span>
                                            @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill">{{ ucfirst($activity->status) }}</span>
                                            @endif
                                        </div>
                                        <div class="activity-meta small text-muted mb-2">
                                            <span class="me-3"><i class="fas fa-map-marker-alt me-1"></i> {{ $activity->location }}</span>
                                            <span><i class="fas fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($activity->activity_date)->format('d M Y') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            @if($activity->status === 'approved')
                                            <small class="text-success fw-bold">Siap Melaksanakan</small>
                                            <div>
                                                <button class="btn btn-primary btn-sm" style="background-color: #000957; border-color: #000957;" onclick="alert('Membuka Grup WhatsApp...')">
                                                    <i class="fab fa-whatsapp me-1"></i> Lihat Grup WA
                                                </button>
                                            </div>
                                            @elseif($activity->status === 'registered')
                                            <small class="text-warning">Menunggu persetujuan panitia...</small>
                                            <div>
                                                <button class="btn btn-sm btn-outline-danger">Batalkan</button>
                                            </div>
                                            @elseif($activity->status === 'completed')
                                            <small class="text-info">Kegiatan Selesai</small>
                                            <div>
                                                <button class="btn btn-sm btn-primary"><i class="fas fa-download me-1"></i> Sertifikat</button>
                                            </div>
                                            @else
                                            <small class="text-muted">{{ ucfirst($activity->status) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted">Anda belum mengikuti kegiatan apapun</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Profil User -->
            <div class="col-lg-4">
                <!-- User Profile Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4 text-center">
                        <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-size: 2.5rem; font-weight: bold;">
                            {{ strtoupper(substr(Auth::user()->nama ?? 'V', 0, 1)) }}
                        </div>
                        <h5 class="fw-bold mb-1">{{ Auth::user()->nama ?? 'Volunteer' }}</h5>
                        <span class="badge bg-success rounded-pill mb-3">Relawan Terverifikasi</span>
                        
                        <div class="text-start mt-3">
                            <p class="small text-muted mb-1"><strong>Skill Utama:</strong></p>
                            <div class="d-flex flex-wrap gap-1 mb-3">
                                @if($volunteer->skill)
                                    @foreach(explode(',', $volunteer->skill) as $skill)
                                    <span class="badge bg-light text-dark border">{{ trim($skill) }}</span>
                                    @endforeach
                                @else
                                <span class="badge bg-light text-dark border">Belum ada skill</span>
                                @endif
                            </div>
                            
                            <p class="small text-muted mb-1"><strong>Ketersediaan:</strong></p>
                            <p class="small text-dark">{{ $volunteer->ketersediaan ?? 'Belum diset' }}</p>
                        </div>
                        
                        <hr class="my-3">
                        
                        <!-- UPDATE: Link ke donor-profile.html -->
                        <a href="{{ route('volunteer.status') }}" class="btn btn-outline-primary btn-sm w-100 mb-2">Edit Profil</a>
                        
                        <!-- DIHAPUS: <button class="btn btn-outline-secondary btn-sm w-100">Lihat Skill Saya</button> -->
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Info Relawan</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small text-muted">Kategori</span>
                            <span class="small fw-bold">{{ $volunteer->kategori ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small text-muted">Kegiatan Bulan Ini</span>
                            <span class="small fw-bold">{{ $stats['registered_activities'] ?? 0 }} Kegiatan</span>
                        </div>
                        <p class="small text-muted mb-0 mt-2">Teruslah berpartisipasi untuk meningkatkan reputasi Anda!</p>
                    </div>
                </div>
            </div>
        </div>
        @endguest
    </div>
</main>

<!-- Modal Daftar Kegiatan -->
<div class="modal fade" id="registerActivityModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" style="color: #000957;" id="registerModalLabel">Daftar Kegiatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="resetModal()"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-light border-primary bg-white d-flex align-items-center mb-4" role="alert">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    <div>
                        <strong>Mengajar Matematika</strong><br>
                        <small class="text-muted">Panti Asuhan Harapan | 25 Okt 2023</small>
                    </div>
                </div>

                <!-- Form Pendaftaran -->
                <form id="activityRegistrationForm" method="POST">
                    @csrf
                    <input type="hidden" id="volunteer_id" name="volunteer_id" value="{{ auth()->user()->id }}">
                    <input type="hidden" id="activity_id" name="activity_id" value="">
                    
                    <!-- Input 1: Motivasi -->
                    <div class="mb-3">
                        <label for="motivation" class="form-label fw-bold">Motivasi Mengikuti</label>
                        <textarea class="form-control" id="motivation" name="motivation" rows="3" placeholder="Ceritakan singkat kenapa Anda ingin ikut kegiatan ini..." required></textarea>
                    </div>

                    <!-- Input 2: Kontak Darurat -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="emergencyName" class="form-label fw-bold">Nama Kontak Darurat</label>
                            <input type="text" class="form-control" id="emergencyName" name="emergency_name" placeholder="Misal: Ibu / Teman" required>
                        </div>
                        <div class="col-md-6">
                            <label for="emergencyPhone" class="form-label fw-bold">No. Telepon</label>
                            <input type="tel" class="form-control" id="emergencyPhone" name="emergency_phone" placeholder="08..." required>
                        </div>
                    </div>

                    <!-- Input 3: Transportasi -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Transportasi ke Lokasi</label>
                        <select class="form-select" id="transportation" name="transportation" required>
                            <option value="" selected disabled>Pilih kendaraan</option>
                            <option value="motor">Motor Pribadi</option>
                            <option value="mobil">Mobil Pribadi</option>
                            <option value="ojek">Transportasi Online (Ojek)</option>
                            <option value="umum">Angkutan Umum</option>
                        </select>
                    </div>

                    <!-- Input 4: Persetujuan -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="commitment" name="commitment" required>
                            <label class="form-check-label small" for="commitment">
                                Saya berkomitmen untuk hadir tepat waktu dan mengikuti arahan panitia.
                            </label>
                        </div>
                    </div>
                </form>

                <div id="registrationSuccess" class="d-none text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-check-circle fa-4x text-success"></i>
                    </div>
                    <h5 class="fw-bold text-success">Terima Kasih!</h5>
                    <p class="text-muted">Anda sudah mendaftar pada kegiatan ini.</p>
                    <div class="alert alert-warning d-inline-block mt-3">
                        <strong>Status:</strong> Menunggu verifikasi dari admin.
                    </div>
                    <p class="small mt-2 mb-0 text-muted">Anda akan mendapatkan notifikasi setelah disetujui.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="activityRegistrationForm" class="btn btn-primary fw-bold" style="background-color: #000957; border-color: #000957;">Kirim Pendaftaran</button>
            </div>
        </div>
    </div>
</div>

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
    // 1. Filter Tabs (Kegiatan Saya)
    function filterActivities(status, element) {
        const tabs = document.querySelectorAll('.volunteer-tab');
        tabs.forEach(tab => tab.classList.remove('active'));
        element.classList.add('active');

        const cards = document.querySelectorAll('.activities-grid .activity-card');
        
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

    // 2. Reset Modal (Agar kembali ke form saat dibuka ulang)
    function resetModal() {
        document.getElementById('activityRegistrationForm').style.display = 'block';
        document.getElementById('registrationSuccess').classList.add('d-none');
        document.getElementById('activityRegistrationForm').reset();
    }

    // 3. Set Activity ID when button is clicked
    function setActivityId(activityId) {
        document.getElementById('activity_id').value = activityId;
        resetModal();
    }

    // 4. Handle Submit Form -> Send to Controller
    document.getElementById('activityRegistrationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const actionUrl = "{{ route('volunteer-activities.store') }}";

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
                // Sembunyikan Form
                document.getElementById('activityRegistrationForm').style.display = 'none';
                
                // Tampilkan Pesan Sukses
                document.getElementById('registrationSuccess').classList.remove('d-none');

                // Tutup modal setelah 3 detik
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('registerActivityModal'));
                    if (modal) modal.hide();
                }, 3000);
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'Terjadi kesalahan saat mendaftarkan kegiatan',
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