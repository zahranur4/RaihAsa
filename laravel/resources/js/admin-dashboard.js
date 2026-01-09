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

// Fungsi untuk Manajemen Pengguna
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

// Fungsi untuk Manajemen Donasi
function viewDonationDetail(donationId) {
    alert('Melihat detail donasi: ' + donationId);
    // Bisa membuka modal baru atau redirect ke halaman detail
}

function editDonation(donationId) {
    alert('Mengedit donasi: ' + donationId);
    // Bisa membuka modal edit dengan data donasi yang dipilih
}

function deleteDonation(button, donationId) {
    if (confirm('Apakah Anda yakin ingin menghapus donasi ' + donationId + '?')) {
        const row = button.closest('tr');
        row.remove();
        alert('Donasi ' + donationId + ' telah dihapus.');
    }
}

function saveNewDonation() {
    // Logika untuk menyimpan donasi baru
    alert('Donasi baru berhasil disimpan!');
    // Tutup modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('addDonationModal'));
    modal.hide();
}

// Fungsi untuk Food Rescue
function viewFoodRescueDetail(foodRescueId) {
    alert('Melihat detail food rescue: ' + foodRescueId);
    // Bisa membuka modal baru atau redirect ke halaman detail
}

function editFoodRescue(foodRescueId) {
    alert('Mengedit food rescue: ' + foodRescueId);
    // Bisa membuka modal edit dengan data food rescue yang dipilih
}

function deleteFoodRescue(button, foodRescueId) {
    if (confirm('Apakah Anda yakin ingin menghapus food rescue ' + foodRescueId + '?')) {
        const row = button.closest('tr');
        row.remove();
        alert('Food Rescue ' + foodRescueId + ' telah dihapus.');
    }
}

function saveNewFoodRescue() {
    // Logika untuk menyimpan food rescue baru
    alert('Food rescue baru berhasil disimpan!');
    // Tutup modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('addFoodRescueModal'));
    modal.hide();
}

// Fungsi untuk Manajemen Relawan
function viewVolunteerDetail(volunteerId) {
    alert('Melihat detail relawan: ' + volunteerId);
    // Bisa membuka modal baru atau redirect ke halaman detail
}

function editVolunteer(volunteerId) {
    alert('Mengedit relawan: ' + volunteerId);
    // Bisa membuka modal edit dengan data relawan yang dipilih
}

function deleteVolunteer(button, volunteerId) {
    if (confirm('Apakah Anda yakin ingin menghapus relawan ' + volunteerId + '?')) {
        const row = button.closest('tr');
        row.remove();
        alert('Relawan ' + volunteerId + ' telah dihapus.');
    }
}

function saveNewVolunteer() {
    // Logika untuk menyimpan relawan baru
    alert('Relawan baru berhasil disimpan!');
    // Tutup modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('addVolunteerModal'));
    modal.hide();
}

// Fungsi untuk Laporan
function viewReport(reportId) {
    alert('Melihat laporan: ' + reportId);
    // Buka modal atau halaman detail laporan
}

function downloadReport(reportId) {
    alert('Mengunduh laporan: ' + reportId);
    // Logika untuk mengunduh file laporan
}

function generateReport() {
    alert('Membuat laporan baru...');
    // Logika untuk membuat laporan baru
}

function applyReportFilters() {
    alert('Filter diterapkan');
    // Logika untuk menerapkan filter pada laporan
}

// Fungsi untuk Pengaturan
function saveAllSettings() {
    if (confirm('Apakah Anda yakin ingin menyimpan semua perubahan?')) {
        alert('Semua pengaturan berhasil disimpan!');
        // Logika untuk menyimpan semua pengaturan
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

    // 4. Filter pada halaman Manajemen Pengguna
    const resetFilterBtn = document.getElementById('resetFilter');
    const applyFilterBtn = document.getElementById('applyFilter');
    
    if (resetFilterBtn) {
        resetFilterBtn.addEventListener('click', function() {
            // Reset semua filter
            const selects = document.querySelectorAll('.filter-bar select');
            const inputs = document.querySelectorAll('.filter-bar input');
            
            selects.forEach(select => select.selectedIndex = 0);
            inputs.forEach(input => input.value = '');
            
            alert('Filter telah direset');
        });
    }
    
    if (applyFilterBtn) {
        applyFilterBtn.addEventListener('click', function() {
            // Terapkan filter
            alert('Filter diterapkan');
            // Logika untuk menerapkan filter
        });
    }

    // --- INISIALISASI KOMPONEN LAIN ---
    initializeCharts();
});

// --- FUNGSI-FUNGSI CHART (Dipanggil di dalam DOMContentLoaded) ---

function initializeCharts() {
    // Cek halaman mana yang sedang aktif dan inisialisasi chart yang relevan
    const currentPath = window.location.pathname;
    
    // Initialize charts for admin pages
    if (currentPath.includes('/admin') || currentPath.includes('/admin/dashboard') || currentPath.includes('/admin/laporan')) {
        initializeDonationTrendChart();
        initializeDonationCategoryChart();
    }
    
    if (currentPath.includes('/admin/laporan')) {
        initializeFoodRescueTrendChart();
        initializeVolunteerActivityChart();
    }
}

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
            labels: ['Makanan', 'Barang'],
            datasets: [{
                data: [300, 150],
                backgroundColor: [
                    '#000957',
                    '#17a2b8'
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

function initializeFoodRescueTrendChart() {
    const ctx = document.getElementById('foodRescueTrendChart');
    if (!ctx) return; // Jangan jalankan jika elemen tidak ada

    new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Makanan Terselamatkan (kg)',
                data: [320, 402, 378, 451, 512, 489],
                backgroundColor: 'rgba(0, 9, 87, 0.7)',
                borderColor: '#000957',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function initializeVolunteerActivityChart() {
    const ctx = document.getElementById('volunteerActivityChart');
    if (!ctx) return; // Jangan jalankan jika elemen tidak ada

    new Chart(ctx.getContext('2d'), {
        type: 'pie',
        data: {
            labels: ['Edukasi', 'Distribusi', 'Pengumpulan', 'Administrasi'],
            datasets: [{
                data: [30, 25, 20, 25],
                backgroundColor: [
                    '#000957',
                    '#17a2b8',
                    '#28a745',
                    '#ffc107'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
}

// Fungsi untuk Manajemen Penerima
function viewRecipientDetail(recipientId) {
    // Simulasi data penerima
    const recipientData = {
        'R001': {
            name: 'Panti Asuhan Harapan',
            type: 'Panti Asuhan',
            email: 'info@pantiharapan.com',
            phone: '022-1234567',
            address: 'Jl. Harapan No. 123, Bandung',
            city: 'Bandung',
            postal: '40123',
            capacity: '50 anak',
            legalStatus: 'Yayasan (Akta Notaris & SK Kemenkumham)',
            legalNumber: 'AHU-0001.AH.01.01.Tahun 2023',
            legalDate: '15 Jan 2023',
            legalNotary: 'Notaris Ahmad Fadli, SH',
            contactPerson: 'Budi Santoso',
            contactPosition: 'Ketua Yayasan',
            contactId: '3271011505850001',
            verificationStatus: 'Terverifikasi',
            operationalHour: 'Senin-Jumat, 08:00-17:00',
            pickupAvailability: 'Pagi (08:00-12:00)',
            storageCapacity: 'Sedang (50-200 kg)',
            refrigeration: 'Kulkas Sedang',
            transportation: 'Van/Minibus',
            description: 'Panti asuhan yang menampung anak-anak yatim piatu dan anak-anak yang kurang mampu. Kami berkomitmen untuk memberikan pendidikan dan kebutuhan dasar bagi anak-anak di bawah asuhan kami.'
        },
        'R002': {
            name: 'Yayasan Peduli Anak',
            type: 'Yayasan',
            email: 'info@pedulianak.com',
            phone: '021-7654321',
            address: 'Jl. Peduli No. 45, Jakarta',
            city: 'Jakarta',
            postal: '12345',
            capacity: '75 anak',
            legalStatus: 'Yayasan (Akta Notaris & SK Kemenkumham)',
            legalNumber: 'AHU-0002.AH.01.01.Tahun 2023',
            legalDate: '20 Feb 2023',
            legalNotary: 'Notaris Siti Nurhaliza, SH',
            contactPerson: 'Ahmad Fadli',
            contactPosition: 'Direktur Eksekutif',
            contactId: '3171052002750001',
            verificationStatus: 'Menunggu Verifikasi',
            operationalHour: 'Senin-Sabtu, 07:00-18:00',
            pickupAvailability: 'Siang (12:00-16:00)',
            storageCapacity: 'Besar (200-500 kg)',
            refrigeration: 'Freezer/Chiller',
            transportation: 'Truk Kecil',
            description: 'Yayasan yang berfokus pada pendidikan dan kesejahteraan anak-anak dari keluarga kurang mampu. Kami menyediakan beasiswa, makanan bergizi, dan kegiatan ekstrakurikuler.'
        }
    };
    
    const data = recipientData[recipientId];
    if (!data) {
        alert('Data penerima tidak ditemukan');
        return;
    }
    
    // Isi data ke modal
    document.getElementById('view-name').textContent = data.name;
    document.getElementById('view-type').textContent = data.type;
    document.getElementById('view-email').textContent = data.email;
    document.getElementById('view-phone').textContent = data.phone;
    document.getElementById('view-address').textContent = data.address;
    document.getElementById('view-city').textContent = data.city;
    document.getElementById('view-postal').textContent = data.postal;
    document.getElementById('view-capacity').textContent = data.capacity;
    document.getElementById('view-legal-status').textContent = data.legalStatus;
    document.getElementById('view-legal-number').textContent = data.legalNumber;
    document.getElementById('view-legal-date').textContent = data.legalDate;
    document.getElementById('view-legal-notary').textContent = data.legalNotary;
    document.getElementById('view-contact-person').textContent = data.contactPerson;
    document.getElementById('view-contact-position').textContent = data.contactPosition;
    document.getElementById('view-contact-id').textContent = data.contactId;
    document.getElementById('view-verification-status').textContent = data.verificationStatus;
    document.getElementById('view-operational-hour').textContent = data.operationalHour;
    document.getElementById('view-pickup-availability').textContent = data.pickupAvailability;
    document.getElementById('view-storage-capacity').textContent = data.storageCapacity;
    document.getElementById('view-refrigeration').textContent = data.refrigeration;
    document.getElementById('view-transportation').textContent = data.transportation;
    document.getElementById('view-description').textContent = data.description;
    
    // Tambahkan dokumen legalitas (simulasi)
    const documentsHtml = `
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="document-item">
                    <i class="fas fa-file-pdf text-danger me-2"></i>
                    <a href="#" class="text-decoration-none">Akta Pendirian.pdf</a>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="document-item">
                    <i class="fas fa-file-pdf text-danger me-2"></i>
                    <a href="#" class="text-decoration-none">SK Kemenkumham.pdf</a>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="document-item">
                    <i class="fas fa-file-pdf text-danger me-2"></i>
                    <a href="#" class="text-decoration-none">NPWP.pdf</a>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="document-item">
                    <i class="fas fa-file-pdf text-danger me-2"></i>
                    <a href="#" class="text-decoration-none">Surat Keterangan Domisili.pdf</a>
                </div>
            </div>
        </div>
    `;
    document.getElementById('view-documents').innerHTML = documentsHtml;
    
    // Set ID untuk tombol edit dan verifikasi
    document.getElementById('editFromViewBtn').setAttribute('onclick', `editRecipient('${recipientId}')`);
    document.getElementById('verifyFromViewBtn').setAttribute('onclick', `verifyRecipient('${recipientId}')`);
    document.getElementById('rejectFromViewBtn').setAttribute('onclick', `rejectRecipient('${recipientId}')`);
    
    // Tampilkan modal
    const modal = new bootstrap.Modal(document.getElementById('viewRecipientModal'));
    modal.show();
}

function editRecipient(recipientId) {
    // Tutup modal view jika terbuka
    const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewRecipientModal'));
    if (viewModal) viewModal.hide();
    
    // Simulasi data penerima
    const recipientData = {
        'R001': {
            name: 'Panti Asuhan Harapan',
            email: 'info@pantiharapan.com',
            phone: '022-1234567',
            address: 'Jl. Harapan No. 123, Bandung',
            city: 'Bandung',
            postal: '40123',
            type: 'orphanage',
            capacity: '50',
            status: 'verified',
            description: 'Panti asuhan yang menampung anak-anak yatim piatu dan anak-anak yang kurang mampu. Kami berkomitmen untuk memberikan pendidikan dan kebutuhan dasar bagi anak-anak di bawah asuhan kami.',
            legalStatus: 'foundation',
            legalNumber: 'AHU-0001.AH.01.01.Tahun 2023',
            legalDate: '2023-01-15',
            legalNotary: 'Notaris Ahmad Fadli, SH',
            contactPerson: 'Budi Santoso',
            contactPosition: 'Ketua Yayasan',
            contactId: '3271011505850001',
            operationalHour: 'Senin-Jumat, 08:00-17:00',
            pickupAvailability: 'morning',
            storageCapacity: 'medium',
            refrigeration: 'medium',
            transportation: 'van'
        },
        'R002': {
            name: 'Yayasan Peduli Anak',
            email: 'info@pedulianak.com',
            phone: '021-7654321',
            address: 'Jl. Peduli No. 45, Jakarta',
            city: 'Jakarta',
            postal: '12345',
            type: 'foundation',
            capacity: '75',
            status: 'pending',
            description: 'Yayasan yang berfokus pada pendidikan dan kesejahteraan anak-anak dari keluarga kurang mampu. Kami menyediakan beasiswa, makanan bergizi, dan kegiatan ekstrakurikuler.',
            legalStatus: 'foundation',
            legalNumber: 'AHU-0002.AH.01.01.Tahun 2023',
            legalDate: '2023-02-20',
            legalNotary: 'Notaris Siti Nurhaliza, SH',
            contactPerson: 'Ahmad Fadli',
            contactPosition: 'Direktur Eksekutif',
            contactId: '3171052002750001',
            operationalHour: 'Senin-Sabtu, 07:00-18:00',
            pickupAvailability: 'afternoon',
            storageCapacity: 'large',
            refrigeration: 'large',
            transportation: 'truck'
        }
    };
    
    const data = recipientData[recipientId];
    if (!data) {
        alert('Data penerima tidak ditemukan');
        return;
    }
    
    // Isi data ke form edit
    document.getElementById('edit-recipient-name').value = data.name;
    document.getElementById('edit-recipient-email').value = data.email;
    document.getElementById('edit-recipient-phone').value = data.phone;
    document.getElementById('edit-recipient-address').value = data.address;
    document.getElementById('edit-recipient-city').value = data.city;
    document.getElementById('edit-recipient-postal').value = data.postal;
    document.getElementById('edit-recipient-type').value = data.type;
    document.getElementById('edit-recipient-capacity').value = data.capacity;
    document.getElementById('edit-recipient-status').value = data.status;
    document.getElementById('edit-recipient-description').value = data.description;
    document.getElementById('edit-legal-status').value = data.legalStatus;
    document.getElementById('edit-legal-number').value = data.legalNumber;
    document.getElementById('edit-legal-date').value = data.legalDate;
    document.getElementById('edit-legal-notary').value = data.legalNotary;
    document.getElementById('edit-contact-person').value = data.contactPerson;
    document.getElementById('edit-contact-position').value = data.contactPosition;
    document.getElementById('edit-contact-id').value = data.contactId;
    document.getElementById('edit-operational-hour').value = data.operationalHour;
    document.getElementById('edit-pickup-availability').value = data.pickupAvailability;
    document.getElementById('edit-storage-capacity').value = data.storageCapacity;
    document.getElementById('edit-refrigeration').value = data.refrigeration;
    document.getElementById('edit-transportation').value = data.transportation;
    
    // Tampilkan modal edit
    const modal = new bootstrap.Modal(document.getElementById('editRecipientModal'));
    modal.show();
}

function verifyRecipient(recipientId) {
    if (confirm(`Apakah Anda yakin ingin memverifikasi penerima ${recipientId}?`)) {
        // Simulasi update status
        const statusCell = document.querySelector(`#recipientTable tr:has(td:contains('${recipientId}')) td:nth-child(6)`);
        if (statusCell) {
            statusCell.innerHTML = '<span class="badge bg-success">Terverifikasi</span>';
        }
        
        alert(`Penerima ${recipientId} telah diverifikasi`);
        
        // Tutup modal view jika terbuka
        const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewRecipientModal'));
        if (viewModal) viewModal.hide();
    }
}

function rejectRecipient(recipientId) {
    const reason = prompt(`Alasan penolakan penerima ${recipientId}:`);
    if (reason) {
        // Simulasi update status
        const statusCell = document.querySelector(`#recipientTable tr:has(td:contains('${recipientId}')) td:nth-child(6)`);
        if (statusCell) {
            statusCell.innerHTML = '<span class="badge bg-danger">Ditolak</span>';
        }
        
        alert(`Penerima ${recipientId} telah ditolak dengan alasan: ${reason}`);
        
        // Tutup modal view jika terbuka
        const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewRecipientModal'));
        if (viewModal) viewModal.hide();
    }
}

function deleteRecipient(button, recipientId) {
    if (confirm(`Apakah Anda yakin ingin menghapus penerima ${recipientId}?`)) {
        const row = button.closest('tr');
        row.remove();
        alert(`Penerima ${recipientId} telah dihapus`);
    }
}

function saveNewRecipient() {
    // Logika untuk menyimpan penerima baru
    alert('Penerima baru berhasil disimpan!');
    
    // Tutup modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('addRecipientModal'));
    modal.hide();
    
    // Reset form
    document.getElementById('addRecipientForm').reset();
}

function updateRecipient() {
    // Logika untuk update penerima
    alert('Data penerima berhasil diperbarui!');
    
    // Tutup modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('editRecipientModal'));
    modal.hide();
}