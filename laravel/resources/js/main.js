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
    
    // Enhanced animation on scroll
    const animateElements = document.querySelectorAll(
        '.feature-card, .benefit-card, .help-method, .stat-item, ' +
        '.about-image, .food-rescue-image, .wishlist-image, .volunteer-image, ' +
        '.testimonial-card, .small-category-card'
    );
    
    // Add animation class to elements
    animateElements.forEach(element => {
        element.classList.add('animate-on-scroll');
    });
    
    function checkScroll() {
        const triggerBottom = window.innerHeight / 5 * 4;
        
        animateElements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            
            if (elementTop < triggerBottom) {
                element.classList.add('animated');
            }
        });
    }
    
    // Initial check and add scroll event listener
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
    
    // Check if user is logged in
    const isLoggedIn = checkLoginStatus();
    
    // Show appropriate content in donation modal
    const donationModal = document.getElementById('donationModal');
    if (donationModal) {
        donationModal.addEventListener('show.bs.modal', function() {
            if (isLoggedIn) {
                document.getElementById('loginPrompt').style.display = 'none';
                document.getElementById('donationForm').style.display = 'block';
            } else {
                document.getElementById('loginPrompt').style.display = 'block';
                document.getElementById('donationForm').style.display = 'none';
            }
        });
    }
    
    // Show appropriate content in carousel donation modal
    const carouselDonateModal = document.getElementById('donate-modal');
    if (carouselDonateModal) {
        carouselDonateModal.addEventListener('show.bs.modal', function() {
            if (isLoggedIn) {
                document.getElementById('carouselLoginPrompt').style.display = 'none';
                document.getElementById('carouselLoginForm').style.display = 'none';
                document.getElementById('carouselDonationForm').style.display = 'block';
            } else {
                document.getElementById('carouselLoginPrompt').style.display = 'block';
                document.getElementById('carouselLoginForm').style.display = 'none';
                document.getElementById('carouselDonationForm').style.display = 'none';
            }
        });
    }
    
    // Handle login button click in carousel modal
    const carouselLoginBtn = document.querySelector('#carouselLoginPrompt .btn-primary');
    if (carouselLoginBtn) {
        carouselLoginBtn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('carouselLoginPrompt').style.display = 'none';
            document.getElementById('carouselLoginForm').style.display = 'block';
        });
    }
    
    // Handle carousel login form submission
    const carouselLoginForm = document.getElementById('carousel-login-form');
    if (carouselLoginForm) {
        carouselLoginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('carousel-email').value;
            const password = document.getElementById('carousel-password').value;
            
            if (email && password) {
                // In a real application, you would send this data to a server
                // For demo purposes, we'll just show next step
                const steps = document.querySelectorAll('#donate-modal .step');
                steps[0].classList.remove('active');
                steps[1].classList.add('active');
                
                // Hide login form and show donation form
                document.getElementById('carouselLoginForm').style.display = 'none';
                document.getElementById('carouselDonationForm').style.display = 'block';
            } else {
                alert('Silakan isi email dan password');
            }
        });
    }
    
    // Handle carousel donation form submission
    const carouselDonationForm = document.getElementById('carousel-donation-form');
    if (carouselDonationForm) {
        carouselDonationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                type: 'food-rescue',
                foodType: document.getElementById('carousel-food-type').value,
                quantity: document.getElementById('carousel-quantity').value,
                expiry: document.getElementById('carousel-expiry').value,
                description: document.getElementById('carousel-description').value,
                pickupMethod: document.getElementById('carousel-pickup-method').value
            };
            
            // Validate form
            if (validateCarouselDonationForm(formData)) {
                // Move to next step
                const steps = document.querySelectorAll('#donate-modal .step');
                steps[1].classList.remove('active');
                steps[2].classList.add('active');
                
                // Hide donation form and show matching
                document.getElementById('carouselDonationForm').style.display = 'none';
                
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
                
                carouselDonationForm.parentNode.appendChild(matchingScreen);
                
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
                            <li>Jenis: ${document.getElementById('carousel-food-type').value}</li>
                            <li>Jumlah: ${document.getElementById('carousel-quantity').value}</li>
                            <li>Kadaluwarsa: ${new Date(document.getElementById('carousel-expiry').value).toLocaleString()}</li>
                            <li>Metode: ${document.getElementById('carousel-pickup-method').value === 'self-delivery' ? 'Pengantaran' : 'Pengambilan'}</li>
                        </ul>
                        <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Selesai</button>
                    `;
                    
                    matchingScreen.parentNode.appendChild(confirmationScreen);
                }, 3000);
            }
        });
    }
    
    // Handle register link in carousel modal
    const carouselRegisterLinks = document.querySelectorAll('.carousel-register-link');
    carouselRegisterLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            // Redirect to register page
            window.location.href = '/register';
        });
    });
    
    // Form validation for login form
    // Demo interception removed: login is handled by the server and by resources/js/login.js (progressive validation).
    // This prevents the demo alert/modal flow from hijacking the actual login form submission.
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        // Intentionally left blank to allow normal form submission handled by server
    }
    
    // Register link in login modal
    const registerLinks = document.querySelectorAll('.register-link');
    registerLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            // Redirect to register page
            window.location.href = '/register';
        });
    });
    
    // Handle form submissions
    const foodRescueForm = document.getElementById('foodRescueForm');
    if (foodRescueForm) {
        foodRescueForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                type: 'food-rescue',
                foodType: document.getElementById('food-type').value,
                foodName: document.getElementById('food-name').value,
                quantity: document.getElementById('food-quantity').value,
                expiry: document.getElementById('food-expiry').value,
                description: document.getElementById('food-description').value,
                pickupMethod: document.getElementById('pickup-method').value
            };
            
            // Validate form
            if (validateFoodRescueForm(formData)) {
                // In a real application, you would send this data to a server
                console.log('Food Rescue form data:', formData);
                
                // Show success message
                alert('Donasi Food Rescue berhasil dikirim!');
                
                // Reset form and close modal
                foodRescueForm.reset();
                const modal = bootstrap.Modal.getInstance(donationModal);
                modal.hide();
            }
        });
    }
    
    const wishlistForm = document.getElementById('wishlistForm');
    if (wishlistForm) {
        wishlistForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                type: 'wishlist',
                recipient: document.getElementById('wishlist-recipient').value,
                item: document.getElementById('wishlist-item').value,
                quantity: document.getElementById('wishlist-quantity').value,
                description: document.getElementById('wishlist-description').value,
                deliveryMethod: document.getElementById('delivery-method').value
            };
            
            // Validate form
            if (validateWishlistForm(formData)) {
                // In a real application, you would send this data to a server
                console.log('Wishlist form data:', formData);
                
                // Show success message
                alert('Donasi Wishlist berhasil dikirim!');
                
                // Reset form and close modal
                wishlistForm.reset();
                const modal = bootstrap.Modal.getInstance(donationModal);
                modal.hide();
            }
        });
    }
    
    const otherForm = document.getElementById('otherForm');
    if (otherForm) {
        otherForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                type: 'other',
                donationType: document.getElementById('other-type').value,
                itemName: document.getElementById('other-name').value,
                quantity: document.getElementById('other-quantity').value,
                description: document.getElementById('other-description').value,
                recipient: document.getElementById('other-recipient').value,
                deliveryMethod: document.getElementById('delivery-method-other').value
            };
            
            // Validate form
            if (validateOtherForm(formData)) {
                // In a real application, you would send this data to a server
                console.log('Other form data:', formData);
                
                // Show success message
                alert('Donasi berhasil dikirim!');
                
                // Reset form and close modal
                otherForm.reset();
                const modal = bootstrap.Modal.getInstance(donationModal);
                modal.hide();
            }
        });
    }
    
    // Initialize tooltips if any
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Check login status (dummy function)
    function checkLoginStatus() {
        // In a real application, you would check if user is logged in
        // For demo purposes, we'll return false
        return false;
    }
    
    // Validate carousel donation form
    function validateCarouselDonationForm(data) {
        if (!data.foodType || !data.quantity || !data.expiry || !data.pickupMethod) {
            alert('Silakan lengkapi semua field yang wajib diisi');
            return false;
        }
        
        // Check if expiry date is in future
        const expiryDate = new Date(data.expiry);
        const now = new Date();
        if (expiryDate <= now) {
            alert('Tanggal kadaluwarsa harus di masa depan');
            return false;
        }
        
        return true;
    }
    
    // Validate Food Rescue form
    function validateFoodRescueForm(data) {
        if (!data.foodType || !data.foodName || !data.quantity || !data.expiry || !data.pickupMethod) {
            alert('Silakan lengkapi semua field yang wajib diisi');
            return false;
        }
        
        // Check if expiry date is in future
        const expiryDate = new Date(data.expiry);
        const now = new Date();
        if (expiryDate <= now) {
            alert('Tanggal kadaluwarsa harus di masa depan');
            return false;
        }
        
        return true;
    }
    
    // Validate Wishlist form
    function validateWishlistForm(data) {
        if (!data.recipient || !data.item || !data.quantity || !data.deliveryMethod) {
            alert('Silakan lengkapi semua field yang wajib diisi');
            return false;
        }
        
        return true;
    }
    
    // Validate Other form
    function validateOtherForm(data) {
        if (!data.donationType || !data.itemName || !data.quantity || !data.recipient || !data.deliveryMethod) {
            alert('Silakan lengkapi semua field yang wajib diisi');
            return false;
        }
        
        return true;
    }
});