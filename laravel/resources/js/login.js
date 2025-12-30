document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const toggle = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (toggle && passwordInput) {
        toggle.addEventListener('click', function() {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

            // Toggle icon
            const icon = toggle.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            }

            // Update aria-label for accessibility
            toggle.setAttribute('aria-label', isPassword ? 'Sembunyikan Password' : 'Tampilkan Password');
        });
    }

    // Progressive client-side validation (allow normal form submission to server)
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            if (!email || !password) {
                e.preventDefault();
                alert('Silakan masukkan email dan password');
                return;
            }

            if (!validateEmail(email)) {
                e.preventDefault();
                alert('Email tidak valid');
                return;
            }

            // If validation passes, do nothing and let the form submit to the server
        });
    }

    // Validate email format
    function validateEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
});