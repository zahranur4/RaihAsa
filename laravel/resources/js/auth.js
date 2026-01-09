document.addEventListener('DOMContentLoaded', function() {
    // Client-side demo auth disabled: server-side authentication is used instead.
    // If you need client-side demo behavior for testing, set a flag and re-enable.
    return;

    // Cek status login
    checkLoginStatus();
    
    // Tangani klik pada link yang memerlukan login
    setupAuthLinks();
    
    // Tangani form login
    setupLoginForm();
});

function checkLoginStatus() {
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
    const userRole = localStorage.getItem('userRole');
    
    // Update UI berdasarkan status login
    updateAuthUI(isLoggedIn, userRole);
}

function updateAuthUI(isLoggedIn, userRole) {
    const authButtons = document.querySelector('.d-flex');
    
    if (isLoggedIn) {
        // Ganti tombol login/register dengan tombol profil dan logout
        authButtons.innerHTML = `
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle me-2"></i> ${userRole === 'admin' ? 'Admin' : 'Pengguna'}
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="/my-donations">Kontribusiku</a></li>
                    <li><a class="dropdown-item" href="/profile">Profil Saya</a></li>
                    ${userRole === 'admin' ? '<li><a class="dropdown-item" href="/admin">Dashboard Admin</a></li>' : ''}
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" id="logoutBtn">Keluar</a></li>
                </ul>
            </div>
        `;
        
        // Tambahkan event listener untuk tombol logout
        document.getElementById('logoutBtn').addEventListener('click', function(e) {
            e.preventDefault();
            logout();
        });
    }
}

function setupAuthLinks() {
    // Dapatkan semua link yang memerlukan autentikasi
    const authLinks = document.querySelectorAll('.requires-auth');
    
    authLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
            
            if (!isLoggedIn) {
                // Tampilkan modal login
                const loginModal = new bootstrap.Modal(document.getElementById('donationModal'));
                loginModal.show();
            } else {
                // Arahkan ke halaman yang diminta
                window.location.href = this.getAttribute('href');
            }
        });
    });
}

function setupLoginForm() {
    const loginForm = document.getElementById('login-form');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Simulasi proses login
            // Di sini Anda bisa mengganti dengan request ke server
            if (email && password) {
                // Simpan status login di localStorage
                localStorage.setItem('isLoggedIn', 'true');
                localStorage.setItem('userEmail', email);
                
                // Tentukan role berdasarkan email (ini hanya simulasi)
                if (email.includes('admin')) {
                    localStorage.setItem('userRole', 'admin');
                } else {
                    localStorage.setItem('userRole', 'user');
                }
                
                // Tampilkan pesan sukses
                showAlert('Login berhasil! Anda akan diarahkan...', 'success');
                
                // Redirect ke halaman yang sesuai
                setTimeout(() => {
                    const userRole = localStorage.getItem('userRole');
                    if (userRole === 'admin') {
                        window.location.href = '/admin';
                    } else {
                        window.location.href = '/my-donations';
                    }
                }, 1500);
            } else {
                showAlert('Email dan password harus diisi!', 'danger');
            }
        });
    }
}

function logout() {
    // Hapus status login dari localStorage
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('userEmail');
    localStorage.removeItem('userRole');
    
    // Tampilkan pesan
    showAlert('Anda telah berhasil keluar', 'info');
    
    // Redirect ke halaman utama
    setTimeout(() => {
        window.location.href = '/';
    }, 1000);
}

function showAlert(message, type) {
    // Buat elemen alert
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    alert.setAttribute('role', 'alert');
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Tambahkan ke body
    document.body.appendChild(alert);
    
    // Hapus otomatis setelah 3 detik
    setTimeout(() => {
        alert.remove();
    }, 3000);
}