// Volunteer Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Check if user is logged in
    const isLoggedIn = checkUserLoginStatus();
    
    // Show/hide sections based on login status
    toggleVolunteerSections(isLoggedIn);
    
    // Volunteer Modal
    const volunteerModal = document.getElementById('volunteerModal');
    if (volunteerModal) {
        volunteerModal.addEventListener('show.bs.modal', function() {
            const loginPrompt = document.getElementById('volunteerLoginPrompt');
            const volunteerInfo = document.getElementById('volunteerInfo');
            
            if (isLoggedIn) {
                loginPrompt.style.display = 'none';
                volunteerInfo.style.display = 'block';
            } else {
                loginPrompt.style.display = 'block';
                volunteerInfo.style.display = 'none';
            }
        });
    }
    
    // Filter Activities
    const filterBtns = document.querySelectorAll('.filter-btn');
    const activityCards = document.querySelectorAll('.activity-card');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            
            // Show/hide activity cards based on filter
            activityCards.forEach(card => {
                if (filter === 'all' || card.getAttribute('data-category') === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    
    // Management Tabs
    const managementTabs = document.querySelectorAll('.management-tab');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    managementTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            managementTabs.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            const tabId = this.getAttribute('data-tab');
            
            // Hide all tab panes
            tabPanes.forEach(pane => {
                pane.classList.remove('active');
            });
            
            // Show selected tab pane
            document.getElementById(tabId).classList.add('active');
        });
    });
    
    // Rating Stars
    const ratingStars = document.querySelectorAll('.rating i');
    
    ratingStars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            
            // Remove active class from all stars
            ratingStars.forEach(s => {
                s.classList.remove('fas');
                s.classList.add('far');
            });
            
            // Add active class to selected and previous stars
            for (let i = 0; i < rating; i++) {
                ratingStars[i].classList.remove('far');
                ratingStars[i].classList.add('fas');
            }
        });
        
        star.addEventListener('mouseover', function() {
            const rating = this.getAttribute('data-rating');
            
            // Remove active class from all stars
            ratingStars.forEach(s => {
                s.classList.remove('fas');
                s.classList.add('far');
            });
            
            // Add active class to hovered and previous stars
            for (let i = 0; i < rating; i++) {
                ratingStars[i].classList.remove('far');
                ratingStars[i].classList.add('fas');
            }
        });
    });
    
    // Photo Upload Preview
    const photoInput = document.getElementById('facePhoto');
    const photoPreview = document.querySelector('.photo-preview');
    
    if (photoInput && photoPreview) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    photoPreview.style.backgroundImage = `url(${e.target.result})`;
                    photoPreview.innerHTML = '';
                }
                
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Form submission
    const volunteerForm = document.querySelector('.volunteer-form');
    if (volunteerForm) {
        volunteerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show success message
            const successAlert = document.createElement('div');
            successAlert.className = 'alert alert-success alert-dismissible fade show';
            successAlert.innerHTML = `
                <strong>Terima kasih!</strong> Pendaftaran volunteer Anda telah berhasil dikirim. Tunggu verifikasi dari admin.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            volunteerForm.parentNode.insertBefore(successAlert, volunteerForm);
            volunteerForm.reset();
            
            // Reset photo preview
            photoPreview.style.backgroundImage = '';
            photoPreview.innerHTML = '<i class="fas fa-camera"></i><span>Upload Foto</span>';
            
            // Hide success alert after 5 seconds
            setTimeout(() => {
                successAlert.classList.remove('show');
                setTimeout(() => {
                    successAlert.remove();
                }, 150);
            }, 5000);
        });
    }
});

// Function to check if user is logged in
function checkUserLoginStatus() {
    // In a real application, this would check if the user has a valid session
    // For demo purposes, we'll use localStorage to simulate login status
    return localStorage.getItem('isLoggedIn') === 'true';
}

// Function to toggle volunteer sections based on login status
function toggleVolunteerSections(isLoggedIn) {
    const loginRequiredSection = document.getElementById('loginRequired');
    const verificationForm = document.getElementById('verification-form');
    const activitiesSection = document.getElementById('activities');
    const smartMatchingSection = document.querySelector('.smart-matching');
    const activityManagementSection = document.querySelector('.activity-management');
    
    if (isLoggedIn) {
        // User is logged in, show all sections
        if (loginRequiredSection) loginRequiredSection.style.display = 'none';
        if (verificationForm) verificationForm.style.display = 'block';
        if (activitiesSection) activitiesSection.style.display = 'block';
        if (smartMatchingSection) smartMatchingSection.style.display = 'block';
        if (activityManagementSection) activityManagementSection.style.display = 'block';
    } else {
        // User is not logged in, show login required section and hide others
        if (loginRequiredSection) loginRequiredSection.style.display = 'block';
        if (verificationForm) verificationForm.style.display = 'none';
        if (activitiesSection) activitiesSection.style.display = 'none';
        if (smartMatchingSection) smartMatchingSection.style.display = 'none';
        if (activityManagementSection) activityManagementSection.style.display = 'none';
    }
}

// Function to simulate login (for demo purposes)
function simulateLogin() {
    localStorage.setItem('isLoggedIn', 'true');
    window.location.reload();
}

// Function to simulate logout (for demo purposes)
function simulateLogout() {
    localStorage.removeItem('isLoggedIn');
    window.location.reload();
}