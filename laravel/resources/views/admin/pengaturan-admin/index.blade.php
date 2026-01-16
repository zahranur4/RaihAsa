<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - RaihAsa Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css','resources/css/admin-dashboard.css','resources/js/admin-dashboard.js'])
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('assets/raih asa logo.png') }}" alt="RaihAsa Logo" class="logo">
                <h3>RaihAsa Admin</h3>
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="fas fa-users"></i> Manajemen Pengguna</a></li>
                <li><a href="{{ route('admin.donations.index') }}" class="{{ request()->routeIs('admin.donations.*') ? 'active' : '' }}"><i class="fas fa-hand-holding-heart"></i> Manajemen Donasi</a></li>
                <li><a href="{{ route('admin.food-rescue.index') }}" class="{{ request()->routeIs('admin.food-rescue.*') ? 'active' : '' }}"><i class="fas fa-utensils"></i> Food Rescue</a></li>
                <li><a href="{{ route('admin.volunteers.index') }}" class="{{ request()->routeIs('admin.volunteers.*') ? 'active' : '' }}"><i class="fas fa-hands-helping"></i> Manajemen Relawan</a></li>
                <li><a href="{{ route('admin.recipients.index') }}" class="{{ request()->routeIs('admin.recipients.*') ? 'active' : '' }}"><i class="fas fa-home"></i> Manajemen Penerima</a></li>
                <li><a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
                <li><a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><i class="fas fa-cog"></i> Pengaturan</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <button class="toggle-sidebar" id="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="header-title">
                    <h1>Pengaturan</h1>
                </div>
                <div class="header-actions">
                    <div class="search-bar">
                        <input type="text" placeholder="Cari pengaturan..." id="settingsSearchInput">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </div>
                    <div class="admin-profile">
                        <div class="profile-info" onclick="event.stopPropagation(); toggleProfileMenu()">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama ?? Auth::user()->email) }}&background=0D6EFD&color=fff" alt="Admin">
                            <span>{{ Auth::user()->nama ?? Auth::user()->email }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <ul class="profile-menu" id="profileMenu" onclick="event.stopPropagation();">
                            <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> Kembali ke Halaman Utama</a></li>
                            <li><a href="#"><i class="fas fa-user"></i> Profil Saya</a></li>
                            <li><a href="{{ route('admin.settings.index') }}"><i class="fas fa-cog"></i> Pengaturan</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                                    @csrf
                                    <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <div class="content">
                <!-- Content Header -->
                <div class="content-header">
                    <div class="page-info">
                        <h2>Pengaturan Sistem</h2>
                        <p>Kelola konfigurasi dan pengaturan untuk aplikasi RaihAsa</p>
                    </div>
                    <div class="page-actions">
                        <button class="btn btn-success" onclick="saveAllSettings()">
                            <i class="fas fa-save"></i> Simpan Semua Perubahan
                        </button>
                    </div>
                </div>

                <!-- Settings Tabs -->
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="settingsTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">
                                    <i class="fas fa-cog"></i> Umum
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="notification-tab" data-bs-toggle="tab" data-bs-target="#notification" type="button" role="tab" aria-controls="notification" aria-selected="false">
                                    <i class="fas fa-bell"></i> Notifikasi
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-controls="security" aria-selected="false">
                                    <i class="fas fa-shield-alt"></i> Keamanan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab" aria-controls="payment" aria-selected="false">
                                    <i class="fas fa-credit-card"></i> Pembayaran
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab" aria-controls="email" aria-selected="false">
                                    <i class="fas fa-envelope"></i> Email
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="settingsTabContent">
                            <!-- General Settings -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                <div class="pengaturan-section">
                                    <h4>Informasi Aplikasi</h4>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="appName" class="form-label">Nama Aplikasi</label>
                                            <input type="text" class="form-control" id="appName" value="RaihAsa">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="appVersion" class="form-label">Versi Aplikasi</label>
                                            <input type="text" class="form-control" id="appVersion" value="1.0.0">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="appDescription" class="form-label">Deskripsi Aplikasi</label>
                                        <textarea class="form-control" id="appDescription" rows="3">Platform untuk menghubungkan donatur, relawan, dan penerima donasi makanan</textarea>
                                    </div>
                                </div>

                                <div class="pengaturan-section">
                                    <h4>Konfigurasi Sistem</h4>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="timezone" class="form-label">Zona Waktu</label>
                                            <select class="form-select" id="timezone">
                                                <option value="WIB">WIB (UTC+7)</option>
                                                <option value="WITA">WITA (UTC+8)</option>
                                                <option value="WIT">WIT (UTC+9)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="language" class="form-label">Bahasa Default</label>
                                            <select class="form-select" id="language">
                                                <option value="id">Indonesia</option>
                                                <option value="en">English</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="maxFileSize" class="form-label">Ukuran Maksimum File Upload (MB)</label>
                                            <input type="number" class="form-control" id="maxFileSize" value="10">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="sessionTimeout" class="form-label">Batas Waktu Sesi (menit)</label>
                                            <input type="number" class="form-control" id="sessionTimeout" value="30">
                                        </div>
                                    </div>
                                </div>

                                <div class="pengaturan-section">
                                    <h4>Tampilan</h4>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="primaryColor" class="form-label">Warna Primer</label>
                                            <input type="color" class="form-control form-control-color" id="primaryColor" value="#000957">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="secondaryColor" class="form-label">Warna Sekunder</label>
                                            <input type="color" class="form-control form-control-color" id="secondaryColor" value="#f8f9fa">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="darkMode">
                                            <label class="form-check-label" for="darkMode">Aktifkan Mode Gelap</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notification Settings -->
                            <div class="tab-pane fade" id="notification" role="tabpanel" aria-labelledby="notification-tab">
                                <div class="pengaturan-section">
                                    <h4>Pengaturan Notifikasi</h4>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                                            <label class="form-check-label" for="emailNotifications">Aktifkan Notifikasi Email</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="pushNotifications" checked>
                                            <label class="form-check-label" for="pushNotifications">Aktifkan Notifikasi Push</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="smsNotifications">
                                            <label class="form-check-label" for="smsNotifications">Aktifkan Notifikasi SMS</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="pengaturan-section">
                                    <h4>Jenis Notifikasi</h4>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="newDonation" checked>
                                            <label class="form-check-label" for="newDonation">Donasi Baru</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="newVolunteer" checked>
                                            <label class="form-check-label" for="newVolunteer">Pendaftaran Relawan Baru</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="foodRescue" checked>
                                            <label class="form-check-label" for="foodRescue">Food Rescue Baru</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="systemUpdates" checked>
                                            <label class="form-check-label" for="systemUpdates">Pembaruan Sistem</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Settings -->
                            <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                                <div class="pengaturan-section">
                                    <h4>Kebijakan Password</h4>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="minPasswordLength" class="form-label">Panjang Minimum Password</label>
                                            <input type="number" class="form-control" id="minPasswordLength" value="8">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="passwordExpiry" class="form-label">Kadaluarsa Password (hari)</label>
                                            <input type="number" class="form-control" id="passwordExpiry" value="90">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="requireSpecialChars" checked>
                                            <label class="form-check-label" for="requireSpecialChars">Memerlukan Karakter Khusus</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="requireNumbers" checked>
                                            <label class="form-check-label" for="requireNumbers">Memerlukan Angka</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="pengaturan-section">
                                    <h4>Keamanan Login</h4>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="twoFactorAuth">
                                            <label class="form-check-label" for="twoFactorAuth">Aktifkan Autentikasi Dua Faktor</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="loginAttempts" checked>
                                            <label class="form-check-label" for="loginAttempts">Batasi Percobaan Login</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="maxAttempts" class="form-label">Maksimum Percobaan</label>
                                            <input type="number" class="form-control" id="maxAttempts" value="5">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="lockoutDuration" class="form-label">Durasi Lockout (menit)</label>
                                            <input type="number" class="form-control" id="lockoutDuration" value="15">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Settings -->
                            <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                                <div class="pengaturan-section">
                                    <h4>Gateway Pembayaran</h4>
                                    <div class="mb-3">
                                        <label for="paymentGateway" class="form-label">Gateway Pembayaran</label>
                                        <select class="form-select" id="paymentGateway">
                                            <option value="midtrans">Midtrans</option>
                                            <option value="xendit">Xendit</option>
                                            <option value="ipaymu">iPaymu</option>
                                            <option value="manual">Manual Transfer</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="merchantId" class="form-label">Merchant ID</label>
                                        <input type="text" class="form-control" id="merchantId">
                                    </div>
                                    <div class="mb-3">
                                        <label for="apiKey" class="form-label">API Key</label>
                                        <input type="password" class="form-control" id="apiKey">
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="sandboxMode" checked>
                                            <label class="form-check-label" for="sandboxMode">Mode Sandbox</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="pengaturan-section">
                                    <h4>Metode Pembayaran</h4>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="creditCard" checked>
                                            <label class="form-check-label" for="creditCard">Kartu Kredit</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="bankTransfer" checked>
                                            <label class="form-check-label" for="bankTransfer">Transfer Bank</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="eWallet" checked>
                                            <label class="form-check-label" for="eWallet">Dompet Digital</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="overTheCounter" checked>
                                            <label class="form-check-label" for="overTheCounter">Over the Counter</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Email Settings -->
                            <div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
                                <div class="pengaturan-section">
                                    <h4>Konfigurasi Email</h4>
                                    <div class="mb-3">
                                        <label for="emailProvider" class="form-label">Penyedia Email</label>
                                        <select class="form-select" id="emailProvider">
                                            <option value="smtp">SMTP</option>
                                            <option value="sendgrid">SendGrid</option>
                                            <option value="mailgun">Mailgun</option>
                                            <option value="ses">Amazon SES</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="smtpHost" class="form-label">SMTP Host</label>
                                        <input type="text" class="form-control" id="smtpHost" placeholder="smtp.gmail.com">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="smtpPort" class="form-label">SMTP Port</label>
                                            <input type="number" class="form-control" id="smtpPort" value="587">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="smtpEncryption" class="form-label">Enkripsi</label>
                                            <select class="form-select" id="smtpEncryption">
                                                <option value="tls">TLS</option>
                                                <option value="ssl">SSL</option>
                                                <option value="none">None</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="smtpUsername" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="smtpUsername">
                                    </div>
                                    <div class="mb-3">
                                        <label for="smtpPassword" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="smtpPassword">
                                    </div>
                                    <div class="mb-3">
                                        <label for="fromEmail" class="form-label">Email Pengirim</label>
                                        <input type="email" class="form-control" id="fromEmail" placeholder="noreply@raihasa.id">
                                    </div>
                                    <div class="mb-3">
                                        <label for="fromName" class="form-label">Nama Pengirim</label>
                                        <input type="text" class="form-control" id="fromName" value="RaihAsa">
                                    </div>
                                </div>

                                <div class="pengaturan-section">
                                    <h4>Template Email</h4>
                                    <div class="mb-3">
                                        <label for="welcomeEmail" class="form-label">Email Selamat Datang</label>
                                        <textarea class="form-control" id="welcomeEmail" rows="5">Hai {name}, terima kasih telah bergabung dengan RaihAsa!</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="donationConfirmation" class="form-label">Email Konfirmasi Donasi</label>
                                        <textarea class="form-control" id="donationConfirmation" rows="5">Terima kasih atas donasi Anda sebesar {amount}!</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="passwordReset" class="form-label">Email Reset Password</label>
                                        <textarea class="form-control" id="passwordReset" rows="5">Klik link berikut untuk mereset password Anda: {reset_link}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>