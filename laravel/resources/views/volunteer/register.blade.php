<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Relawan - RaihAsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css','resources/css/forms.css'])
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
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('volunteer') }}">Relawan</a>
                        </li>
                    </ul>
                    <div class="d-flex">
                        <div class="dropdown">
                            <a class="btn btn-outline-secondary dropdown-toggle" href="#" role="button" id="userMenu" data-bs-toggle="dropdown">
                                {{ Auth::user()->nama ?? Auth::user()->email }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
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
                </div>
            </div>
        </nav>
    </header>

    <!-- Registration Form Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <div class="volunteer-icon mb-3">
                                    <i class="fas fa-hands-helping fa-3x text-primary"></i>
                                </div>
                                <h2 class="mb-2">Pendaftaran Relawan</h2>
                                <p class="text-muted">Lengkapi data diri Anda untuk menjadi relawan RaihAsa</p>
                            </div>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if(session('info'))
                                <div class="alert alert-info">{{ session('info') }}</div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('volunteer.register.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                           value="{{ old('nama_lengkap', Auth::user()->nama) }}" required>
                                    <small class="text-muted">Nama lengkap sesuai identitas</small>
                                </div>

                                <div class="mb-3">
                                    <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan)</label>
                                    <input type="text" class="form-control" id="nik" name="nik" 
                                           value="{{ old('nik') }}" maxlength="16" pattern="[0-9]{16}">
                                    <small class="text-muted">16 digit NIK (opsional)</small>
                                </div>

                                <div class="mb-3">
                                    <label for="skill" class="form-label">Keahlian & Minat</label>
                                    <textarea class="form-control" id="skill" name="skill" rows="4" 
                                              placeholder="Contoh: Mengajar anak-anak, memasak, menggambar, fotografi, dll.">{{ old('skill') }}</textarea>
                                    <small class="text-muted">Ceritakan keahlian atau minat yang bisa Anda kontribusikan</small>
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Informasi:</strong> Setelah mendaftar, akun Anda akan diverifikasi oleh admin. Anda akan menerima notifikasi setelah verifikasi selesai.
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i> Daftar sebagai Relawan
                                    </button>
                                    <a href="{{ route('volunteer') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i> Kembali
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0 text-muted">&copy; 2024 RaihAsa. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
