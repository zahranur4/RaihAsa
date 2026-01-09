document.addEventListener('DOMContentLoaded', function() {
    // Donor form submission
    const donorFormElement = document.getElementById('donor-registration-form');
    if (donorFormElement) {
        donorFormElement.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                fullname: document.getElementById('donor-fullname').value,
                email: document.getElementById('donor-email').value,
                phone: document.getElementById('donor-phone').value,
                password: document.getElementById('donor-password').value,
                address: document.getElementById('donor-address').value,
                city: document.getElementById('donor-city').value,
                postal: document.getElementById('donor-postal').value,
                type: document.getElementById('donor-type').value,
                description: document.getElementById('donor-description').value,
                terms: document.getElementById('donor-terms').checked
            };
            
            // Validate form
            if (validateDonorForm(formData)) {
                // In a real application, you would send this data to a server
                console.log('Donor form data:', formData);
                
                // Show success message
                showSuccessMessage('donor');
            }
        });
    }
    
    // Validate donor form
    function validateDonorForm(data) {
        if (!data.fullname || !data.email || !data.phone || !data.password || !data.address || !data.city || !data.postal || !data.type) {
            alert('Silakan lengkapi semua field yang wajib diisi');
            return false;
        }
        
        if (!validateEmail(data.email)) {
            alert('Email tidak valid');
            return false;
        }
        
        if (data.password.length < 6) {
            alert('Password minimal 6 karakter');
            return false;
        }
        
        if (!data.terms) {
            alert('Anda harus menyetujui syarat dan ketentuan');
            return false;
        }
        
        return true;
    }
    
    // Validate email format
    function validateEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
    
    // Show success message
    function showSuccessMessage(type) {
        const formContainer = document.querySelector('.registration-container');
        const successMessage = document.createElement('div');
        successMessage.className = 'registration-success show';
        
        successMessage.innerHTML = `
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3>Pendaftaran Berhasil!</h3>
            <p>Terima kasih telah mendaftar sebagai donor. Kami telah mengirim email verifikasi ke alamat email Anda. Silakan cek email Anda untuk mengaktifkan akun.</p>
            <div class="mt-4">
                <a href="/pages/login.html" class="btn btn-primary">Masuk ke Akun</a>
                <button class="btn btn-outline-secondary ms-2" id="back-to-form">Kembali ke Form</button>
            </div>
        `;
        
        // Hide form and show success message
        document.querySelector('.registration-form').style.display = 'none';
        formContainer.appendChild(successMessage);
        
        // Add event listener to back button
        document.getElementById('back-to-form').addEventListener('click', function() {
            // Remove success message
            successMessage.remove();
            
            // Show form
            document.querySelector('.registration-form').style.display = 'block';
        });
    }
});