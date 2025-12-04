document.addEventListener('DOMContentLoaded', function() {

    const filterButtons = document.querySelectorAll('.filter-btn');
    const needsCards = document.querySelectorAll('.kartu-kebutuhan');

    // --- Logika Filter Kategori ---
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            const selectedCategory = button.getAttribute('data-category');

            needsCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');

                if (selectedCategory === 'all' || cardCategory === selectedCategory) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // --- Logika Tombol "Saya Penuhi Kebutuhan Ini" ---
    const fulfillButtons = document.querySelectorAll('.kartu-footer .btn-accent');
    fulfillButtons.forEach(button => {
        button.addEventListener('click', () => {
            const originalText = button.innerText;
            button.innerText = 'Memproses...';
            button.disabled = true;

            setTimeout(() => {
                alert('Terima kasih! Kami akan menghubungkan Anda dengan panti.');
                button.innerText = 'Donasi Sedang Diproses';
            }, 1500);
        });
    });

});