<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Panti - RaihAsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css','resources/css/penerima-dashboard.css','resources/js/penerima-dashboard.js'])
    
    <style>
        .content { padding: 20px 30px !important; }
        .form-label { margin-bottom: 0.2rem; font-size: 0.9rem; } /* Label lebih dekat ke input */
        .card-body { padding: 1rem; }
        /* Tab dibiarkan default sesuai permintaan */
        .nav-tabs { margin-bottom: 1rem; } 
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('assets/raih asa logo.png') }}" alt="RaihAsa Logo" class="logo">
                <h3>{{ $currentPanti->nama ?? 'Panti Asuhan Harapan' }}</h3>
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('panti.dashboard') }}" class="{{ request()->routeIs('panti.dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="{{ route('panti.wishlist') }}" class="{{ request()->routeIs('panti.wishlist') ? 'active' : '' }}"><i class="fas fa-list-alt"></i> Wishlist Saya</a></li>
                <li><a href="{{ route('panti.donasi-masuk') }}" class="{{ request()->routeIs('panti.donasi-masuk') ? 'active' : '' }}"><i class="fas fa-hand-holding-heart"></i> Donasi Masuk</a></li>
                <li><a href="{{ route('panti.food-rescue') }}" class="{{ request()->routeIs('panti.food-rescue') ? 'active' : '' }}"><i class="fas fa-utensils"></i> Food Rescue</a></li>
                <li><a href="{{ route('panti.laporan') }}" class="{{ request()->routeIs('panti.laporan') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
                <li><a href="{{ route('panti.profil') }}" class="{{ request()->routeIs('panti.profil') ? 'active' : '' }}"><i class="fas fa-building"></i> Profil Panti</a></li>
                <li><a href="{{ route('panti.pengaturan') }}" class="{{ request()->routeIs('panti.pengaturan') ? 'active' : '' }}"><i class="fas fa-cog"></i> Pengaturan</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <header class="top-header">
                <button class="toggle-sidebar" id="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="header-title">
                    <h1>Profil Panti</h1>
                </div>
                <div class="header-actions">
                    <div class="search-bar">
                        <input type="text" placeholder="Cari...">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </div>
                    <div class="admin-profile">
                        <div class="profile-info" onclick="toggleProfileMenu()">
                            <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Admin Panti">
                            <span>{{ $currentPanti->nama ?? 'Panti Asuhan Harapan' }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <ul class="profile-menu" id="profileMenu">
                            <li><a href="{{ route('panti.profil') }}"><i class="fas fa-building"></i> Profil Panti</a></li>
                            <li><a href="{{ route('panti.pengaturan') }}"><i class="fas fa-cog"></i> Pengaturan</a></li>
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Keluar</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <div class="content">
                <div class="card mb-3" style="background-color: rgba(40, 167, 69, 0.1); border-left: 4px solid var(--success-color);">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Status: Terverifikasi</h6>
                                <p class="mb-0 small">Panti Asuhan Anda telah diverifikasi dan dapat menerima donasi.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-header mb-3"> <div class="page-info">
                        <h2 class="mb-1" style="font-size: 1.5rem;">Informasi {{ $currentPanti->nama ?? 'Panti Asuhan Harapan' }}</h2>
                        <p class="mb-0 text-muted">Kelola data profil dan legalitas panti Anda</p>
                    </div>
                    <div class="page-actions">
                        <button class="btn btn-primary btn-sm" id="editProfileBtn" onclick="toggleEditMode()"><i class="fas fa-edit"></i> Edit Profil</button>
                    </div>
                </div>

                @if(session('success'))
                <div class="alert alert-success py-2 mb-3">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form id="profileForm" method="POST" action="{{ route('panti.profil.update') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <ul class="nav nav-tabs" id="profileTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">Informasi Umum</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="legal-tab" data-bs-toggle="tab" data-bs-target="#legal" type="button" role="tab" aria-controls="legal" aria-selected="false">Legalitas</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="operational-tab" data-bs-toggle="tab" data-bs-target="#operational" type="button" role="tab" aria-controls="operational" aria-selected="false">Operasional</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Penanggung Jawab</button>
                        </li>
                    </ul>

                    <div class="tab-content pt-3" id="profileTabContent">
                        
                        <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                            <div class="row gx-3"> 
                                <div class="col-md-6 mb-2">
                                    <label for="pantiName" class="form-label">Nama Panti/Lembaga</label>
                                    <input type="text" class="form-control form-control-sm" id="pantiName" name="nama" value="{{ old('nama', $currentPanti->nama ?? '') }}" disabled>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="pantiEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control form-control-sm" id="pantiEmail" name="email" value="{{ old('email', optional(Auth::user())->email ?? '') }}" disabled>
                                </div>
                            </div>
                            <div class="row gx-3">
                                <div class="col-md-6 mb-2">
                                    <label for="pantiPhone" class="form-label">Nomor Telepon</label>
                                    <input type="tel" class="form-control form-control-sm" id="pantiPhone" name="nomor_telepon" value="{{ old('nomor_telepon', $currentPanti->nomor_telepon ?? '') }}" disabled>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="pantiType" class="form-label">Jenis Panti/Lembaga</label>
                                    <select class="form-select form-select-sm" id="pantiType" name="jenis" disabled>
                                        <option value="orphanage" {{ (old('jenis', $currentPanti->jenis ?? 'orphanage') == 'orphanage') ? 'selected' : '' }}>Panti Asuhan</option>
                                        <option value="elderly" {{ (old('jenis', $currentPanti->jenis ?? '') == 'elderly') ? 'selected' : '' }}>Panti Jompo</option>
                                        <option value="foundation" {{ (old('jenis', $currentPanti->jenis ?? '') == 'foundation') ? 'selected' : '' }}>Yayasan</option>
                                    </select> 
                                </div>
                            </div>
                            <div class="mb-2">
                                <label for="pantiAddress" class="form-label">Alamat Lengkap</label>
                                <textarea class="form-control form-control-sm" id="pantiAddress" name="alamat" rows="2" disabled>{{ old('alamat', $currentPanti->alamat ?? '') }}</textarea>
                            </div>
                            <div class="row gx-3">
                                <div class="col-md-6 mb-2">
                                    <label for="pantiCity" class="form-label">Kota</label>
                                    <input type="text" class="form-control form-control-sm" id="pantiCity" value="Bandung" disabled>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="pantiCapacity" class="form-label">Kapasitas (Orang)</label>
                                    <input type="number" class="form-control form-control-sm" id="pantiCapacity" name="kapasitas" value="{{ old('kapasitas', $currentPanti->kapasitas ?? '') }}" disabled> 
                                </div>
                            </div>
                            <div class="mb-2">
                                <label for="pantiDescription" class="form-label">Deskripsi Singkat</label>
                                <textarea class="form-control form-control-sm" id="pantiDescription" name="deskripsi" rows="3" disabled>{{ old('deskripsi', $currentPanti->deskripsi ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="legal" role="tabpanel" aria-labelledby="legal-tab">
                            <div class="row gx-3">
                                <div class="col-md-6 mb-2">
                                    <label for="legalStatus" class="form-label">Status Legal</label>
                                    <select class="form-select form-select-sm" id="legalStatus" disabled>
                                        <option value="foundation" selected>Yayasan (Akta Notaris & SK Kemenkumham)</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="legalNumber" class="form-label">Nomor Registrasi Legal</label>
                                    <input type="text" class="form-control form-control-sm" id="legalNumber" name="nomor_legalitas" value="{{ old('nomor_legalitas', $currentPanti->nomor_legalitas ?? '') }}" disabled>
                                </div>
                            </div>
                            <div class="row gx-3">
                                <div class="col-md-6 mb-2">
                                    <label for="legalDate" class="form-label">Tanggal Pendirian</label>
                                    <input type="date" class="form-control form-control-sm" id="legalDate" name="tanggal_berdiri" value="{{ old('tanggal_berdiri', $currentPanti->tanggal_berdiri ?? '') }}" disabled>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="legalNotary" class="form-label">Nama Notaris</label>
                                    <input type="text" class="form-control form-control-sm" id="legalNotary" name="nama_notaris" value="{{ old('nama_notaris', $currentPanti->nama_notaris ?? '') }}" disabled>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Dokumen Legalitas</label>
                                <ul class="list-group list-group-sm">
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-file-pdf text-danger"></i>
                                            <span class="small fw-bold">{{ $currentPanti && $currentPanti->doc_akte ? basename($currentPanti->doc_akte) : 'Akta Pendirian.pdf' }}</span>
                                        </div>
                                        <a href="{{ $currentPanti && $currentPanti->doc_akte ? asset('storage/'.$currentPanti->doc_akte) : '#' }}" class="btn btn-outline-primary btn-sm py-0 px-2" {{ $currentPanti && $currentPanti->doc_akte ? '' : 'disabled' }} target="_blank"><i class="fas fa-download small"></i></a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-file-pdf text-danger"></i>
                                            <span class="small fw-bold">{{ $currentPanti && $currentPanti->doc_sk ? basename($currentPanti->doc_sk) : 'SK Kemenkumham.pdf' }}</span>
                                        </div>
                                        <a href="{{ $currentPanti && $currentPanti->doc_sk ? asset('storage/'.$currentPanti->doc_sk) : '#' }}" class="btn btn-outline-primary btn-sm py-0 px-2" {{ $currentPanti && $currentPanti->doc_sk ? '' : 'disabled' }} target="_blank"><i class="fas fa-download small"></i></a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-file-pdf text-danger"></i>
                                            <span class="small fw-bold">{{ $currentPanti && $currentPanti->doc_npwp ? basename($currentPanti->doc_npwp) : 'NPWP.pdf' }}</span>
                                        </div>
                                        <a href="{{ $currentPanti && $currentPanti->doc_npwp ? asset('storage/'.$currentPanti->doc_npwp) : '#' }}" class="btn btn-outline-primary btn-sm py-0 px-2" {{ $currentPanti && $currentPanti->doc_npwp ? '' : 'disabled' }} target="_blank"><i class="fas fa-download small"></i></a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-file-pdf text-muted"></i>
                                            <span class="small fw-bold">{{ $currentPanti && $currentPanti->doc_other ? basename($currentPanti->doc_other) : 'Dokumen Lainnya (opsional)' }}</span>
                                        </div>
                                        <a href="{{ $currentPanti && $currentPanti->doc_other ? asset('storage/'.$currentPanti->doc_other) : '#' }}" class="btn btn-outline-secondary btn-sm py-0 px-2" {{ $currentPanti && $currentPanti->doc_other ? '' : 'disabled' }} target="_blank"><i class="fas fa-download small"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="operational" role="tabpanel" aria-labelledby="operational-tab">
                            <div class="row gx-3">
                                <div class="col-md-6 mb-2">
                                    <label for="opHour" class="form-label">Jam Operasional</label>
                                    <input type="text" class="form-control form-control-sm" id="opHour" value="Senin-Jumat, 08:00-17:00" disabled>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="pickupAvail" class="form-label">Ketersediaan Pengambilan Donasi</label>
                                    <select class="form-select form-select-sm" id="pickupAvail" disabled>
                                        <option value="morning" selected>Pagi (08:00-12:00)</option>
                                        <option value="afternoon">Siang (12:00-16:00)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row gx-3">
                                <div class="col-md-6 mb-2">
                                    <label for="storageCap" class="form-label">Kapasitas Penyimpanan</label>
                                    <select class="form-select form-select-sm" id="storageCap" disabled>
                                        <option value="medium" selected>Sedang (50-200 kg)</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="refrigeration" class="form-label">Fasilitas Pendingin</label>
                                    <select class="form-select form-select-sm" id="refrigeration" disabled>
                                        <option value="medium" selected>Kulkas Sedang</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label for="transport" class="form-label">Kendaraan Operasional</label>
                                <select class="form-select form-select-sm" id="transport" disabled>
                                    <option value="van" selected>Van/Minibus</option>
                                </select>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="row gx-3">
                                <div class="col-md-6 mb-2">
                                    <label for="contactName" class="form-label">Nama Penanggung Jawab</label>
                                    <input type="text" class="form-control form-control-sm" id="contactName" name="nama_penanggung_jawab" value="{{ old('nama_penanggung_jawab', $currentPanti->nama_penanggung_jawab ?? '') }}" disabled> 
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="contactPosition" class="form-label">Jabatan</label>
                                    <input type="text" class="form-control form-control-sm" id="contactPosition" name="posisi_penanggung_jawab" value="{{ old('posisi_penanggung_jawab', $currentPanti->posisi_penanggung_jawab ?? '') }}" disabled> 
                                </div>
                            </div>
                            <div class="mb-2">
                                <label for="contactId" class="form-label">Nomor KTP Penanggung Jawab</label>
                                <input type="text" class="form-control form-control-sm" id="contactId" name="nik" value="{{ old('nik', $currentPanti->nik ?? '') }}" disabled> 
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3" id="saveActions" style="display: none;">
                        <button type="button" class="btn btn-secondary btn-sm me-2" onclick="cancelEdit()">Batal</button>
                        <button type="button" class="btn btn-success btn-sm" onclick="saveProfile()"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>
</body>
</html>