// Fungsi untuk menjalankan animasi saat scroll (dari volunteer page)
document.addEventListener('DOMContentLoaded', function() {
    initScrollAnimations();
});

function initScrollAnimations() {
    // Buat sebuah Intersection Observer
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, {
        threshold: 0.1 // Picu animasi saat 10% elemen terlihat
    });
    
    // Elemen-elemen yang akan dianimasi dengan fade-in
    const fadeElements = document.querySelectorAll(
    '.rescue-card, .step-item, .benefit-card, .photo-item, .management-card, .match-item, .wishlist-card, .help-method, .testimonial-card'
    );
    
    // Elemen-elemen yang akan dianimasi dengan slide-up
    const slideElements = document.querySelectorAll(
        '.section-title, .lead, .text-center h2, .text-center p'
    );
    
    // Tambahkan kelas animasi dan mulai mengamati elemen
    fadeElements.forEach(el => {
        el.classList.add('fade-in');
        observer.observe(el);
    });
    
    slideElements.forEach(el => {
        el.classList.add('slide-up');
        observer.observe(el);
    });
}