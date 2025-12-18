document.addEventListener('DOMContentLoaded', function() {
    // Login form submission
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember').checked;
            
            // Validate form
            if (!email || !password) {
                alert('Silakan masukkan email dan password');
                return;
            }
            
            if (!validateEmail(email)) {
                alert('Email tidak valid');
                return;
            }
            
            // In a real application, you would send this data to a server
            console.log('Login form data:', { email, password, remember });
            
            // Show success message
            showSuccessMessage();
        });
    }
    
    // Validate email format
    function validateEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
    
    // Show success message
    function showSuccessMessage() {
        const loginContainer = document.querySelector('.login-container');
        const successMessage = document.createElement('div');
        successMessage.className = 'login-success';
        successMessage.innerHTML = `
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3>Login Berhasil!</h3>
            <p>Selamat datang kembali di RaihAsa. Anda akan diarahkan ke halaman dashboard dalam beberapa detik.</p>
        `;
        
        // Hide form and show success message
        document.querySelector('.login-form').style.display = 'none';
        loginContainer.appendChild(successMessage);
        
        // Redirect to dashboard after 3 seconds
        setTimeout(() => {
            window.location.href = 'pages/my-donations.html';
        }, 3000);
    }
});