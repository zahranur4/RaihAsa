document.addEventListener('DOMContentLoaded', function() {
    // Category filtering for Food Rescue and Wishlist pages
    const categoryBtns = document.querySelectorAll('.category-btn');
    const rescueCards = document.querySelectorAll('.rescue-card');
    const wishlistCards = document.querySelectorAll('.wishlist-card');
    
    if (categoryBtns.length > 0) {
        categoryBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                categoryBtns.forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                const category = this.getAttribute('data-category');
                
                // Filter rescue cards
                rescueCards.forEach(card => {
                    if (category === 'all' || card.getAttribute('data-category') === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Filter wishlist cards
                wishlistCards.forEach(card => {
                    if (category === 'all' || card.getAttribute('data-category') === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }
    
    // Animation on scroll
    const animateElements = document.querySelectorAll('.feature-card, .benefit-card, .help-method');
    
    function checkScroll() {
        const triggerBottom = window.innerHeight / 5 * 4;
        
        animateElements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            
            if (elementTop < triggerBottom) {
                element.classList.add('animate');
            }
        });
    }
    
    window.addEventListener('scroll', checkScroll);
    checkScroll(); // Check on load
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Form validation for login form
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (email && password) {
                // In a real application, you would send this data to a server
                alert('Login functionality would be implemented here. This is a demo.');
                
                // For demo purposes, show the next step in donation process
                const steps = document.querySelectorAll('.step');
                steps[0].classList.remove('active');
                steps[1].classList.add('active');
                
                // Hide login form and show donation form (in a real app)
                loginForm.style.display = 'none';
                
                // Create a simple donation form for demo
                const donationForm = document.createElement('div');
                donationForm.className = 'donation-form';
                donationForm.innerHTML = `
                    <h4>Isi Form Donasi</h4>
                    <form id="donation-form">
                        <div class="mb-3">
                            <label for="food-type" class="form-label">Jenis Makanan</label>
                            <select class="form-control" id="food-type" required>
                                <option value="">Pilih jenis makanan</option>
                                <option value="makanan-basah">Makanan Basah</option>
                                <option value="makanan-kering">Makanan Kering</option>
                                <option value="buah">Buah</option>
                                <option value="sayur">Sayuran</option>
                                <option value="minuman">Minuman</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Jumlah</label>
                            <input type="text" class="form-control" id="quantity" placeholder="Contoh: 5 kg, 10 porsi" required>
                        </div>
                        <div class="mb-3">
                            <label for="expiry" class="form-label">Kadaluwarsa</label>
                            <input type="datetime-local" class="form-control" id="expiry" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" rows="3" placeholder="Deskripsikan makanan yang akan didonasikan"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="pickup-method" class="form-label">Metode Pengambilan</label>
                            <select class="form-control" id="pickup-method" required>
                                <option value="">Pilih metode</option>
                                <option value="self-delivery">Saya akan mengantar</option>
                                <option value="self-pickup">Diambil oleh penerima</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Lanjutkan</button>
                    </form>
                `;
                
                loginForm.parentNode.appendChild(donationForm);
                
                // Handle donation form submission
                const donationFormElement = document.getElementById('donation-form');
                donationFormElement.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Move to next step
                    steps[1].classList.remove('active');
                    steps[2].classList.add('active');
                    
                    // Hide donation form and show matching
                    donationForm.style.display = 'none';
                    
                    // Create matching screen
                    const matchingScreen = document.createElement('div');
                    matchingScreen.className = 'matching-screen text-center';
                    matchingScreen.innerHTML = `
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h4>Mencocokkan dengan Penerima</h4>
                        <p>Sistem kami sedang mencari penerima yang paling sesuai untuk donasi Anda</p>
                    `;
                    
                    donationForm.parentNode.appendChild(matchingScreen);
                    
                    // Simulate matching process
                    setTimeout(() => {
                        // Move to next step
                        steps[2].classList.remove('active');
                        steps[3].classList.add('active');
                        
                        // Hide matching screen and show confirmation
                        matchingScreen.style.display = 'none';
                        
                        // Create confirmation screen
                        const confirmationScreen = document.createElement('div');
                        confirmationScreen.className = 'confirmation-screen';
                        confirmationScreen.innerHTML = `
                            <h4>Donasi Berhasil Dibuat!</h4>
                            <div class="alert alert-success">
                                Donasi Anda telah berhasil dibuat dan sedang menunggu konfirmasi dari penerima.
                            </div>
                            <p><strong>Detail Donasi:</strong></p>
                            <ul>
                                <li>Jenis: ${document.getElementById('food-type').value}</li>
                                <li>Jumlah: ${document.getElementById('quantity').value}</li>
                                <li>Kadaluwarsa: ${new Date(document.getElementById('expiry').value).toLocaleString()}</li>
                                <li>Metode: ${document.getElementById('pickup-method').value === 'self-delivery' ? 'Pengantaran' : 'Pengambilan'}</li>
                            </ul>
                            <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Selesai</button>
                        `;
                        
                        matchingScreen.parentNode.appendChild(confirmationScreen);
                    }, 3000);
                });
            } else {
                alert('Silakan isi email dan password');
            }
        });
    }
    
    // Register link in login modal
    const registerLinks = document.querySelectorAll('.register-link');
    registerLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            alert('Register functionality would be implemented here. This is a demo.');
        });
    });
    
    // Initialize tooltips if any
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});