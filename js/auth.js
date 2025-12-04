document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Login berhasil! (Ini adalah simulasi)');
            // Arahkan ke dashboard panti setelah login
            window.location.href = 'dashboard-panti.html';
        });
    }

    if (registerForm) {
        registerForm.addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Pendaftaran berhasil! Silakan login.');
            // Arahkan ke halaman login setelah daftar
            window.location.href = 'login.html';
        });
    }
});