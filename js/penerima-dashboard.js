document.addEventListener('DOMContentLoaded', function() {
    // Fungsi global untuk toggle menu profil (dipanggil dari HTML)
    window.toggleProfileMenu = function() {
        const menu = document.getElementById('profileMenu');
        if (menu) {
            menu.classList.toggle('show');
        }
    };

    // Event listener untuk menutup menu profil jika klik di luar
    document.addEventListener('click', function(event) {
        const profile = document.querySelector('.admin-profile');
        if (profile && !profile.contains(event.target)) {
            const profileMenu = document.getElementById('profileMenu');
            if (profileMenu) {
                profileMenu.classList.remove('show');
            }
        }
    });

    // --- Inisialisasi Chart untuk Halaman Laporan ---
    // Hanya jalankan jika elemen chart ada di halaman
    if (document.getElementById('donationTrendChart')) {
        const ctx1 = document.getElementById('donationTrendChart').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Jumlah Donasi',
                    data: [10, 15, 20, 18, 25, 30],
                    borderColor: '#000957',
                    backgroundColor: 'rgba(0, 9, 87, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }

    if (document.getElementById('donationCategoryChart')) {
        const ctx2 = document.getElementById('donationCategoryChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Makanan', 'Pakaian', 'Pendidikan'],
                datasets: [{
                    data: [50, 30, 20],
                    backgroundColor: ['#000957', '#17a2b8', '#ffc107']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }
});


// --- FUNGSI KHUSUS HALAMAN WISHLIST ---
window.viewWishlistDetail = function(id) {
    const data = {
        'WL001': {
            nama: 'Susu Formula Usia 1-3 Tahun',
            kategori: 'Makanan',
            jumlah: '20 kaleng',
            terkumpul: '20 kaleng',
            status: 'Terkumpul',
            deskripsi: 'Susu formula sangat dibutuhkan untuk 15 anak balita yang berada di bawah asuhan kami.',
            batasWaktu: '30 Juni 2023',
            donatur: [
                { nama: 'Ahmad Fadli', jumlah: '10 kaleng', tanggal: '15 Jun 2023' },
                { nama: 'Siti Nurhaliza', jumlah: '5 kaleng', tanggal: '20 Jun 2023' },
                { nama: 'Budi Santoso', jumlah: '5 kaleng', tanggal: '25 Jun 2023' }
            ]
        }
    };
    
    const item = data[id];
    if (item) {
        document.getElementById('wishlistDetailContent').innerHTML = `
            <p><strong>Nama Item:</strong> ${item.nama}</p>
            <p><strong>Kategori:</strong> ${item.kategori}</p>
            <p><strong>Jumlah Dibutuhkan:</strong> ${item.jumlah}</p>
            <p><strong>Jumlah Terkumpul:</strong> ${item.terkumpul}</p>
            <p><strong>Status:</strong> <span class="badge bg-success">${item.status}</span></p>
            <p><strong>Batas Waktu:</strong> ${item.batasWaktu}</p>
            <p><strong>Deskripsi:</strong> ${item.deskripsi}</p>
        `;

        let donaturHtml = '';
        item.donatur.forEach(d => {
            donaturHtml += `<tr><td>${d.nama}</td><td>${d.jumlah}</td><td>${d.tanggal}</td></tr>`;
        });
        document.getElementById('donaturList').innerHTML = donaturHtml;

        const modal = new bootstrap.Modal(document.getElementById('detailWishlistModal'));
        modal.show();
    }
};

window.editWishlist = function(id) {
    alert('Membuka form edit untuk wishlist ID: ' + id);
};

window.hapusWishlist = function(button, id) {
    if (confirm('Apakah Anda yakin ingin menghapus wishlist ini?')) {
        const row = button.closest('tr');
        row.style.transition = 'opacity 0.5s';
        row.style.opacity = '0';
        setTimeout(() => row.remove(), 500);
        alert('Wishlist berhasil dihapus.');
    }
};

window.simpanWishlistBaru = function() {
    alert('Wishlist baru berhasil ditambahkan!');
    const modal = bootstrap.Modal.getInstance(document.getElementById('addWishlistModal'));
    modal.hide();
    document.getElementById('addWishlistForm').reset();
};


// --- FUNGSI KHUSUS HALAMAN PROFIL ---
let isEditMode = false;
window.toggleEditMode = function() {
    isEditMode = !isEditMode;
    const inputs = document.querySelectorAll('#infoForm input, #infoForm textarea, #infoForm select, #legalForm input, #legalForm select, #operationalForm input, #operationalForm select, #contactForm input');
    const editBtn = document.getElementById('editProfileBtn');
    const saveActions = document.getElementById('saveActions');

    if (isEditMode) {
        inputs.forEach(input => input.disabled = false);
        editBtn.style.display = 'none';
        saveActions.style.display = 'block';
    } else {
        inputs.forEach(input => input.disabled = true);
        editBtn.style.display = 'block';
        saveActions.style.display = 'none';
    }
};

window.cancelEdit = function() {
    toggleEditMode(); 
};

window.saveProfile = function() {
    alert('Profil berhasil diperbarui!');
    toggleEditMode();
};


// --- FUNGSI KHUSUS HALAMAN DONASI MASUK ---
window.confirmReceipt = function(id) {
    if (confirm('Apakah Anda yakin telah menerima donasi ini?')) {
        alert(`Donasi ${id} telah dikonfirmasi diterima.`);
        // Logika untuk mengubah status di tabel
    }
};

window.viewDonationDetail = function(id) {
    const data = {
        'D002': {
            donatur: 'Siti Nurhaliza',
            item: 'Buku Tulis 60 Lembar',
            jumlah: '35 pcs',
            tanggal: '10 Jun 2023',
            catatan: 'Semoga buku-buku ini bermanfaat untuk anak-anak.'
        }
    };
    const item = data[id];
    if(item) {
        document.getElementById('donationDetailContent').innerHTML = `
            <p><strong>Donatur:</strong> ${item.donatur}</p>
            <p><strong>Item:</strong> ${item.item}</p>
            <p><strong>Jumlah:</strong> ${item.jumlah}</p>
            <p><strong>Tanggal Donasi:</strong> ${item.tanggal}</p>
            <p><strong>Catatan dari Donatur:</strong> ${item.catatan}</p>
        `;
        const modal = new bootstrap.Modal(document.getElementById('donationDetailModal'));
        modal.show();
    }
};


// --- FUNGSI KHUSUS HALAMAN FOOD RESCUE ---
window.claimFood = function(id) {
    if (confirm('Apakah Anda yakin ingin mengklaim makanan ini? Segera ambil sesuai jam operasional yang ditentukan.')) {
        alert(`Food Rescue ${id} berhasil diklaim!`);
        // Logika untuk mengubah status di tabel
    }
};


// --- FUNGSI KHUSUS HALAMAN PENGATURAN ---
window.changePassword = function() {
    const current = document.getElementById('currentPassword').value;
    const newPass = document.getElementById('newPassword').value;
    const confirm = document.getElementById('confirmPassword').value;

    if (!current || !newPass || !confirm) {
        alert('Semua field harus diisi!');
        return;
    }
    if (newPass !== confirm) {
        alert('Password baru dan konfirmasi tidak cocok!');
        return;
    }
    alert('Password berhasil diubah!');
    // Logika untuk mengirim data ke backend
};

window.saveNotificationSettings = function() {
    alert('Pengaturan notifikasi berhasil disimpan!');
    // Logika untuk menyimpan ke backend
};

// Fungsi untuk toggle sidebar
document.addEventListener('DOMContentLoaded', function() {
    const toggleSidebarBtn = document.getElementById('toggleSidebar');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (toggleSidebarBtn) {
        toggleSidebarBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        });
    }
});