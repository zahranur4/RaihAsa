// Fungsi global untuk toggle menu profil (bisa dipanggil dari HTML)
function toggleProfileMenu() {
    const menu = document.getElementById('profileMenu');
    if (menu) {
        menu.classList.toggle('show');
    }
}

// Fungsi global untuk aksi tabel
function viewDetail(id) {
    alert('Melihat detail untuk: ' + id);
    // Nanti bisa diarahkan ke halaman detail atau buka modal
}

function deleteRow(button) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        // Temukan baris (<tr>) dan hapus
        const row = button.closest('tr');
        row.remove();
    }
}


// Jalankan semua kode setelah DOM siap
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi Elemen
    const toggleSidebarBtn = document.getElementById('toggleSidebar');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    const searchInput = document.querySelector('.search-bar input');

    // --- EVENT LISTENERS ---

    // 1. Toggle Sidebar
    if (toggleSidebarBtn) {
        toggleSidebarBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');

            // On mobile, toggle active class to show/hide
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('active');
            }
        });
    }

    // 2. Gabungkan semua event listener 'klik di luar elemen'
    document.addEventListener('click', function(event) {
        // Tutup menu profil jika klik di luar
        const profile = document.querySelector('.admin-profile');
        if (profile && !profile.contains(event.target)) {
            const profileMenu = document.getElementById('profileMenu');
            if (profileMenu) {
                profileMenu.classList.remove('show');
            }
        }

        // Tutup sidebar mobile jika klik di luar
        if (window.innerWidth <= 768) {
            if (sidebar && !sidebar.contains(event.target) && !toggleSidebarBtn.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        }
    });

    // 3. Pencarian
    if (searchInput) {
        searchInput.addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                const searchTerm = this.value;
                console.log('Mencari:', searchTerm);
                alert('Mencari: ' + searchTerm);
            }
        });
    }

    // --- INISIALISASI KOMPONEN LAIN ---
    initializeDonationTrendChart();
    initializeDonationCategoryChart();
});


// --- FUNGSI-FUNGSI CHART (Dipanggil di dalam DOMContentLoaded) ---

function initializeDonationTrendChart() {
    const ctx = document.getElementById('donationTrendChart');
    if (!ctx) return; // Jangan jalankan jika elemen tidak ada

    new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Jumlah Donasi',
                data: [65, 78, 90, 81, 96, 115],
                borderColor: '#000957',
                backgroundColor: 'rgba(0, 9, 87, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function initializeDonationCategoryChart() {
    const ctx = document.getElementById('donationCategoryChart');
    if (!ctx) return; // Jangan jalankan jika elemen tidak ada

    new Chart(ctx.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Makanan', 'Barang', 'Uang'],
            datasets: [{
                data: [300, 150, 100],
                backgroundColor: [
                    '#000957',
                    '#17a2b8',
                    '#28a745'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function viewUserDetail(userId) {
    alert('Melihat detail user: ' + userId);
    // Bisa membuka modal baru atau redirect ke halaman detail
}

function editUser(userId) {
    alert('Mengedit user: ' + userId);
    // Bisa membuka modal edit dengan data user yang dipilih
}

function deleteUser(button, userId) {
    if (confirm('Apakah Anda yakin ingin menghapus user ' + userId + '?')) {
        const row = button.closest('tr');
        row.remove();
        alert('User ' + userId + ' telah dihapus.');
    }
}

function saveNewUser() {
    // Logika untuk menyimpan user baru
    alert('User baru berhasil disimpan!');
    // Tutup modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
    modal.hide();
}