<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - RaihAsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css','resources/css/components.css','resources/css/forms.css','resources/js/main.js','resources/js/register.js'])
</head>
<body>
    
    <!-- Header with Navigation -->
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
                            <a class="nav-link" href="/index.html">Beranda</a>
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
            <h1>Daftar Akun</h1>
            <p class="lead">Bergabunglah dengan kami dalam mengurangi pemborosan makanan</p>
        </div>
    </section>

    <!-- Registration Section -->
    <section class="registration-section py-5">
        <div class="container">
            <div class="registration-container">
                <div class="registration-type-selector">
                    <div class="text-center mb-4">
                        <h2>Pilih Jenis Pendaftaran</h2>
                        <p class="text-muted">Silakan pilih jenis akun yang ingin Anda daftarkan</p>
                    </div>
                    <div class="type-options">
                        <div class="type-option active" data-type="donor">
                            <div class="type-icon">
                                <i class="fas fa-hand-holding-heart"></i>
                            </div>
                            <h4>Donor</h4>
                            <p>Daftar sebagai donor untuk memberikan donasi makanan atau barang</p>
                        </div>
                        <div class="type-option" data-type="recipient">
                            <div class="type-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <h4>Penerima</h4>
                            <p>Daftar sebagai penerima (panti/lembaga) untuk menerima donasi</p>
                        </div>
                    </div>
                </div>

                <!-- Donor Registration Form -->
                <div class="registration-form donor-form active">
                    <div class="form-header">
                        <h3>Form Pendaftaran Donor</h3>
                        <p>Isi formulir di bawah ini untuk mendaftar sebagai donor</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    <form id="donor-registration-form" method="POST" action="{{ route('register.donor') }}"> 
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="donor-fullname" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="donor-fullname" name="nama" value="{{ old('nama') }}" required>
                                @error('nama') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="donor-email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="donor-email" name="email" value="{{ old('email') }}" required>
                                @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="donor-phone" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control" id="donor-phone" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required>
                                @error('nomor_telepon') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="donor-password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="donor-password" name="kata_sandi" required>
                                @error('kata_sandi') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="donor-address" class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" id="donor-address" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                                @error('alamat') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="donor-city" class="form-label">Kota</label>
                                <input type="text" class="form-control" id="donor-city" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="donor-postal" class="form-label">Kode Pos</label>
                                <input type="text" class="form-control" id="donor-postal" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="donor-type" class="form-label">Jenis Donor</label>
                            <select class="form-control" id="donor-type" required>
                                <option value="">Pilih jenis donor</option>
                                <option value="individual">Individu</option>
                                <option value="restaurant">Restoran</option>
                                <option value="catering">Jasa Boga</option>
                                <option value="market">Pasar/Supermarket</option>
                                <option value="company">Perusahaan</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="donor-description" class="form-label">Deskripsi (Opsional)</label>
                            <textarea class="form-control" id="donor-description" rows="3" placeholder="Ceritakan sedikit tentang diri Anda atau organisasi Anda"></textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="donor-terms" required>
                            <label class="form-check-label" for="donor-terms">
                                Saya setuju dengan <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Syarat & Ketentuan</a> dan <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Kebijakan Privasi</a>
                            </label>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Daftar sebagai Donor</button>
                        </div>
                        <div class="text-center mt-3">
                            <p>Sudah punya akun? <a href="login.html">Masuk</a></p>
                        </div>
                    </form>
                </div>

                <!-- Recipient Registration Form -->
                <div class="registration-form recipient-form">
                    <div class="form-header">
                        <h3>Form Pendaftaran Penerima</h3>
                        <p>Isi formulir di bawah ini untuk mendaftar sebagai penerima donasi</p>
                    </div>
                    <form id="recipient-registration-form" method="POST" action="{{ route('register.recipient') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="recipient-name" class="form-label">Nama Panti/Lembaga</label>
                                <input type="text" class="form-control" id="recipient-name" name="nama" value="{{ old('nama') }}" required>
                                @error('nama') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="recipient-email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="recipient-email" name="email" value="{{ old('email') }}" required>
                                @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="recipient-phone" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control" id="recipient-phone" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required>
                                @error('nomor_telepon') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="recipient-password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="recipient-password" name="kata_sandi" required>
                                @error('kata_sandi') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="recipient-address" class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" id="recipient-address" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                                @error('alamat') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="recipient-city" class="form-label">Kota</label>
                                <input type="text" class="form-control" id="recipient-city" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="recipient-postal" class="form-label">Kode Pos</label>
                                <input type="text" class="form-control" id="recipient-postal" name="kode_pos" value="{{ old('kode_pos') }}" required>
                                @error('kode_pos') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="recipient-type" class="form-label">Jenis Panti/Lembaga</label>
                            <select class="form-control" id="recipient-type" required>
                                <option value="">Pilih jenis panti/lembaga</option>
                                <option value="orphanage">Panti Asuhan</option>
                                <option value="elderly">Panti Jompo</option>
                                <option value="disability">Panti Disabilitas</option>
                                <option value="foundation">Yayasan</option>
                                <option value="community">Komunitas</option>
                                <option value="mosque">Masjid/Mushola</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="recipient-capacity" class="form-label">Kapasitas (Jumlah Penghuni)</label>
                            <input type="number" class="form-control" id="recipient-capacity" name="kapasitas" min="1" value="{{ old('kapasitas') }}" required>
                                @error('kapasitas') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="recipient-description" class="form-label">Deskripsi Singkat</label>
                                <textarea class="form-control" id="recipient-description" name="deskripsi" rows="3" placeholder="Ceritakan sedikit tentang panti/lembaga Anda" required>{{ old('deskripsi') }}</textarea>
                                @error('deskripsi') <div class="text-danger small">{{ $message }}</div> @enderror
                        <!-- Legal Documents Section -->
                        <div class="legal-documents-section">
                            <h4>Dokumen Legalitas</h4>
                            <p class="text-muted">Untuk memastikan kepercayaan donor, kami memerlukan dokumen legalitas panti/lembaga Anda</p>
                            
                            <div class="mb-3">
                                <label for="legal-status" class="form-label">Status Legal</label>
                                <select class="form-control" id="legal-status" required>
                                    <option value="">Pilih status legal</option>
                                    <option value="foundation">Yayasan (Akta Notaris & SK Kemenkumham)</option>
                                    <option value="association">Perkumpulan (Akta Notaris & SK Kemenkumham)</option>
                                    <option value="cooperative">Koperasi</option>
                                    <option value="religious">Lembaga Keagamaan Terdaftar</option>
                                    <option value="community">Komunitas dengan Surat Keterangan</option>
                                    <option value="informal">Informal (membutuhkan verifikasi tambahan)</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="legal-number" class="form-label">Nomor Registrasi Legal</label>
                                <input type="text" class="form-control" id="legal-number" name="nomor_legalitas" value="{{ old('nomor_legalitas') }}" placeholder="Contoh: AHU-0001.AH.01.01.Tahun 2023">
                                @error('nomor_legalitas') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="legal-date" class="form-label">Tanggal Pendirian</label>
                                <input type="date" class="form-control" id="legal-date" name="tanggal_berdiri" value="{{ old('tanggal_berdiri') }}">
                                @error('tanggal_berdiri') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="legal-notary" class="form-label">Nama Notaris (jika ada)</label>
                                <input type="text" class="form-control" id="legal-notary" name="posisi_penanggung_jawab" value="{{ old('posisi_penanggung_jawab') }}">
                                @error('posisi_penanggung_jawab') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Unggah Dokumen Legalitas</label>
                                <div class="document-upload">
                                    <div class="upload-item">
                                        <label for="doc-akte">Akta Pendirian <span class="text-muted">(PDF, max 5MB)</span></label>
                                        <input type="file" class="form-control" id="doc-akte" accept=".pdf">
                                    </div>
                                    <div class="upload-item">
                                        <label for="doc-sk">SK Kemenkumham <span class="text-muted">(PDF, max 5MB)</span></label>
                                        <input type="file" class="form-control" id="doc-sk" accept=".pdf">
                                    </div>
                                    <div class="upload-item">
                                        <label for="doc-npwp">NPWP <span class="text-muted">(PDF, max 5MB)</span></label>
                                        <input type="file" class="form-control" id="doc-npwp" accept=".pdf">
                                    </div>
                                    <div class="upload-item">
                                        <label for="doc-other">Dokumen Pendukung Lainnya <span class="text-muted">(PDF, max 5MB)</span></label>
                                        <input type="file" class="form-control" id="doc-other" accept=".pdf">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="contact-person" class="form-label">Nama Penanggung Jawab</label>
                                <input type="text" class="form-control" id="contact-person" name="nama_penanggung_jawab" value="{{ old('nama_penanggung_jawab') }}" required>
                                @error('nama_penanggung_jawab') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="contact-position" class="form-label">Jabatan Penanggung Jawab</label>
                                <input type="text" class="form-control" id="contact-position" name="posisi_penanggung_jawab" placeholder="Contoh: Ketua Yayasan, Pimpinan Panti, dll" value="{{ old('posisi_penanggung_jawab') }}" required>
                                @error('posisi_penanggung_jawab') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="contact-id" class="form-label">Nomor KTP Penanggung Jawab</label>
                                <input type="text" class="form-control" id="contact-id" name="nik" value="{{ old('nik') }}" required>
                                @error('nik') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="operational-hour" class="form-label">Jam Operasional</label>
                                <input type="text" class="form-control" id="operational-hour" placeholder="Contoh: Senin-Jumat, 08:00-17:00" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="pickup-availability" class="form-label">Ketersediaan Pengambilan Donasi</label>
                                <select class="form-control" id="pickup-availability" required>
                                    <option value="">Pilih ketersediaan</option>
                                    <option value="anytime">Setiap Saat</option>
                                    <option value="morning">Pagi (08:00-12:00)</option>
                                    <option value="afternoon">Siang (12:00-16:00)</option>
                                    <option value="evening">Sore (16:00-20:00)</option>
                                    <option value="weekend">Akhir Pekan</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="storage-capacity" class="form-label">Kapasitas Penyimpanan</label>
                                <select class="form-control" id="storage-capacity" required>
                                    <option value="">Pilih kapasitas</option>
                                    <option value="small">Kecil (1-50 kg)</option>
                                    <option value="medium">Sedang (50-200 kg)</option>
                                    <option value="large">Besar (200-500 kg)</option>
                                    <option value="xlarge">Sangat Besar (>500 kg)</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="refrigeration" class="form-label">Fasilitas Pendingin</label>
                                <select class="form-control" id="refrigeration" required>
                                    <option value="">Pilih fasilitas</option>
                                    <option value="none">Tidak Ada</option>
                                    <option value="small">Kulkas Kecil</option>
                                    <option value="medium">Kulkas Sedang</option>
                                    <option value="large">Freezer/Chiller</option>
                                    <option value="coldroom">Cold Room</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="transportation" class="form-label">Kendaraan Operasional</label>
                                <select class="form-control" id="transportation" required>
                                    <option value="">Pilih kendaraan</option>
                                    <option value="none">Tidak Ada</option>
                                    <option value="motor">Motor</option>
                                    <option value="car">Mobil Pribadi</option>
                                    <option value="van">Van/Minibus</option>
                                    <option value="truck">Truk Kecil</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="recipient-terms" required>
                            <label class="form-check-label" for="recipient-terms">
                                Saya setuju dengan <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Syarat & Ketentuan</a> dan <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Kebijakan Privasi</a>
                            </label>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="verification-consent" required>
                            <label class="form-check-label" for="verification-consent">
                                Saya menyetujui untuk dilakukan verifikasi terhadap dokumen legalitas yang saya unggah dan siap untuk dikunjungi oleh tim verifikasi jika diperlukan
                            </label>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Daftar sebagai Penerima</button>
                        </div>
                        
                        <div class="text-center mt-3">
                            <p>Sudah punya akun? <a href="login.html">Masuk</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Terms & Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Syarat & Ketentuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>1. Pendahuluan</h6>
                    <p>Selamat datang di RaihAsa. Dengan mengakses dan menggunakan situs web RaihAsa, Anda setuju untuk terikat oleh syarat dan ketentuan layanan berikut.</p>
                    
                    <h6>2. Pendaftaran Akun</h6>
                    <p>Untuk menggunakan layanan RaihAsa, Anda harus mendaftar akun dengan memberikan informasi yang akurat dan lengkap. Anda bertanggung jawab untuk menjaga kerahasiaan informasi akun Anda.</p>
                    
                    <h6>3. Penggunaan Layanan</h6>
                    <p>Anda setuju untuk menggunakan layanan RaihAsa hanya untuk tujuan yang sah dan sesuai dengan hukum yang berlaku. Anda dilarang menggunakan layanan untuk tujuan ilegal atau melanggar hak orang lain.</p>
                    
                    <h6>4. Donasi</h6>
                    <p>Setiap donasi yang diberikan melalui platform RaihAsa adalah sukarela. RaihAsa tidak bertanggung jawab atas kualitas atau keamanan donasi yang diberikan oleh donor.</p>
                    
                    <h6>5. Verifikasi Penerima</h6>
                    <p>Setiap penerima donasi akan melalui proses verifikasi untuk memastikan keabsahan dan kelayakan. RaihAsa berhak menolak atau menangguhkan pendaftaran penerima yang tidak memenuhi syarat.</p>
                    
                    <h6>6. Pembatalan dan Pengembalian</h6>
                    <p>RaihAsa berhak membatalkan donasi atau akun pengguna yang melanggar syarat dan ketentuan. Keputusan RaihAsa bersifat final dan mengikat.</p>
                    
                    <h6>7. Perubahan Syarat dan Ketentuan</h6>
                    <p>RaihAsa berhak mengubah syarat dan ketentuan layanan sewaktu-waktu. Perubahan akan berlaku efektif setelah dipublikasikan di situs web.</p>
                    
                    <h6>8. Kontak</h6>
                    <p>Jika Anda memiliki pertanyaan tentang syarat dan ketentuan ini, silakan hubungi kami di info@raihasa.id.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Saya Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel">Kebijakan Privasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>1. Informasi yang Kami Kumpulkan</h6>
                    <p>Kami mengumpulkan informasi yang Anda berikan saat mendaftar, menggunakan layanan, atau berinteraksi dengan situs web kami. Informasi ini meliputi nama, email, nomor telepon, alamat, dan informasi lain yang relevan.</p>
                    
                    <h6>2. Penggunaan Informasi</h6>
                    <p>Informasi yang kami kumpulkan digunakan untuk menyediakan, memelihara, dan meningkatkan layanan kami, memproses transaksi, mengirimkan pemberitahuan, dan berkomunikasi dengan Anda.</p>
                    
                    <h6>3. Berbagi Informasi</h6>
                    <p>Kami tidak menjual, menyewakan, atau membagikan informasi pribadi Anda kepada pihak ketiga tanpa persetujuan Anda, kecuali sebagaimana diizinkan oleh hukum.</p>
                    
                    <h6>4. Keamanan Data</h6>
                    <p>Kami mengambil langkah-langkah keamanan yang wajar untuk melindungi informasi pribadi Anda dari akses, penggunaan, atau pengungkapan yang tidak sah.</p>
                    
                    <h6>5. Cookie</h6>
                    <p>Kami menggunakan cookie untuk meningkatkan pengalaman pengguna, menganalisis penggunaan situs web, dan menampilkan iklan yang relevan. Anda dapat menonaktifkan cookie melalui pengaturan browser Anda.</p>
                    
                    <h6>6. Perubahan Kebijakan Privasi</h6>
                    <p>Kami dapat memperbarui kebijakan privasi ini sewaktu-waktu. Perubahan akan berlaku efektif setelah dipublikasikan di situs web.</p>
                    
                    <h6>7. Kontak</h6>
                    <p>Jika Anda memiliki pertanyaan tentang kebijakan privasi ini, silakan hubungi kami di info@raihasa.id.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Saya Mengerti</button>
                </div>
            </div>
        </div>
    </div>

<!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="row">
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <div class="footer-about">
                            <a href="index.html" class="footer-logo">
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