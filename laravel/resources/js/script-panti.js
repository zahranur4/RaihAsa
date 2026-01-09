document.addEventListener('DOMContentLoaded', function() {

    // --- ELEMEN YANG AKAN DIOLAH ---
    const navLinks = document.querySelectorAll('.top-nav .nav-link');
    const contentSections = document.querySelectorAll('.content-section');
    const postingForm = document.querySelector('.posting-form');
    const actionButtons = document.querySelectorAll('.dashboard-card .btn');

    // --- LOGIKA NAVIGASI ---
    // Fungsi untuk mengaktifkan navigasi dan menampilkan konten
    function activateNavLink(clickedLink) {
        // 1. Hapus class 'active' dari semua link dan section
        navLinks.forEach(link => link.classList.remove('active'));
        contentSections.forEach(section => section.classList.remove('active'));

        // 2. Tambahkan class 'active' ke link yang diklik
        clickedLink.classList.add('active');

        // 3. Tampilkan section yang sesuai dengan 'data-target'
        const targetId = clickedLink.getAttribute('data-target');
        const targetSection = document.getElementById(targetId);
        if (targetSection) {
            targetSection.classList.add('active');
        }
    }

    // Pasang event listener ke setiap link navigasi
    navLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault(); // Mencegah link meloncat ke halaman lain
            activateNavLink(link);
        });
    });

    // --- LOGIKA FORM POSTING ---
    if (postingForm) {
        postingForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah form dari pengiriman standar (reload halaman)
            
            // Tampilkan pesan sukses
            alert('Kebutuhan Anda berhasil diposting ke Timeline! Donatur akan segera melihatnya.');
            
            // (Opsional) Kosongkan form setelah posting
            this.reset();
        });
    }

    // --- LOGIKA TOMBOL AKSI (Edit, Hapus, dll.) ---
    actionButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Ambil teks dari tombol yang diklik
            const buttonText = this.innerText;
            
            // Tampilkan alert sesuai aksi
            if (buttonText === 'Hapus') {
                if (confirm('Apakah Anda yakin ingin menghapus postingan ini?')) {
                    alert('Postingan berhasil dihapus.');
                    // Di aplikasi asli, di sini akan ada kode untuk menghapus kartu dari DOM
                }
            } else {
                alert(`Anda menekan tombol: ${buttonText}. Fitur ini akan segera hadir!`);
            }
        });
    });

});