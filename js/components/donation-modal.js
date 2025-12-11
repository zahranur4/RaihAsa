// Donation Modal Component
class DonationModal {
    constructor() {
        this.modal = document.getElementById('donate-modal');
        this.init();
    }
    
    init() {
        if (!this.modal) return;
        
        // Reset modal when it's hidden
        this.modal.addEventListener('hidden.bs.modal', () => {
            this.resetModal();
        });
    }
    
    resetModal() {
        // Reset steps
        const steps = this.modal.querySelectorAll('.step');
        steps.forEach(step => step.classList.remove('active'));
        steps[0].classList.add('active');
        
        // Reset forms
        const loginForm = this.modal.querySelector('#login-form');
        if (loginForm) {
            loginForm.reset();
            loginForm.style.display = 'block';
        }
        
        // Remove dynamically created forms
        const donationForm = this.modal.querySelector('.donation-form');
        if (donationForm) donationForm.remove();
        
        const matchingScreen = this.modal.querySelector('.matching-screen');
        if (matchingScreen) matchingScreen.remove();
        
        const confirmationScreen = this.modal.querySelector('.confirmation-screen');
        if (confirmationScreen) confirmationScreen.remove();
    }
}

// My Donations Modal Component
class MyDonationsModal {
    constructor() {
        this.modal = document.getElementById('my-donations-modal');
        this.init();
    }
    
    init() {
        if (!this.modal) return;
        
        // Handle register button click
        const registerBtn = this.modal.querySelector('.auth-buttons .btn-primary');
        if (registerBtn) {
            registerBtn.addEventListener('click', (e) => {
                e.preventDefault();
                alert('Register functionality would be implemented here. This is a demo.');
            });
        }
        
        // Handle login button click
        const loginBtn = this.modal.querySelector('.auth-buttons .btn-outline-primary');
        if (loginBtn) {
            loginBtn.addEventListener('click', (e) => {
                e.preventDefault();
                alert('Login functionality would be implemented here. This is a demo.');
            });
        }
    }
}

// Initialize components when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new DonationModal();
    new MyDonationsModal();
});