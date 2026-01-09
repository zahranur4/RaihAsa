document.addEventListener('DOMContentLoaded', function() {
    // Recipient form submission
    const recipientFormElement = document.getElementById('recipient-registration-form');
    if (recipientFormElement) {
        recipientFormElement.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                name: document.getElementById('recipient-name').value,
                email: document.getElementById('recipient-email').value,
                phone: document.getElementById('recipient-phone').value,
                password: document.getElementById('recipient-password').value,
                address: document.getElementById('recipient-address').value,
                city: document.getElementById('recipient-city').value,
                postal: document.getElementById('recipient-postal').value,
                type: document.getElementById('recipient-type').value,
                capacity: document.getElementById('recipient-capacity').value,
                description: document.getElementById('recipient-description').value,
                legalStatus: document.getElementById('legal-status').value,
                legalNumber: document.getElementById('legal-number').value,
                legalDate: document.getElementById('legal-date').value,
                legalNotary: document.getElementById('legal-notary').value,
                contactPerson: document.getElementById('contact-person').value,
                contactPosition: document.getElementById('contact-position').value,
                contactId: document.getElementById('contact-id').value,
                operationalHour: document.getElementById('operational-hour').value,
                pickupAvailability: document.getElementById('pickup-availability').value,
                storageCapacity: document.getElementById('storage-capacity').value,
                refrigeration: document.getElementById('refrigeration').value,
                transportation: document.getElementById('transportation').value,
                terms: document.getElementById('recipient-terms').checked,
                verificationConsent: document.getElementById('verification-consent').checked
            };
            
            // Get uploaded files
            const docAkte = document.getElementById('doc-akte').files[0];
            const docSk = document.getElementById('doc-sk').files[0];
            const docNpwp = document.getElementById('doc-npwp').files[0];
            const docOther = document.getElementById('doc-other').files[0];
            
            // Validate form
            if (validateRecipientForm(formData, docAkte, docSk)) {
                // In a real application, you would send this data and files to a server
                console.log('Recipient form data:', formData);
                console.log('Documents:', { docAkte, docSk, docNpwp, docOther });
                
                // Show success message
                showSuccessMessage('recipient');
            }
        });
    }
    
    // Validate recipient form
    function validateRecipientForm(data, docAkte, docSk) {
        if (!data.name || !data.email || !data.phone || !data.password || !data.address || !data.city || !data.postal || !data.type || !data.capacity || !data.description || !data.legalStatus || !data.contactPerson || !data.contactPosition || !data.contactId || !data.operationalHour || !data.pickupAvailability || !data.storageCapacity || !data.refrigeration || !data.transportation) {
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
        
        // Check if required documents are uploaded based on legal status
        if ((data.legalStatus === 'foundation' || data.legalStatus === 'association') && (!docAkte || !docSk)) {
            alert('Untuk status legal Yayasan atau Perkumpulan, Akta Pendirian dan SK Kemenkumham wajib diunggah');
            return false;
        }
        
        if (!data.terms) {
            alert('Anda harus menyetujui syarat dan ketentuan');
            return false;
        }
        
        if (!data.verificationConsent) {
            alert('Anda harus menyetujui proses verifikasi');
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
            <p>Terima kasih telah mendaftar sebagai penerima. Kami telah mengirim email verifikasi ke alamat email Anda. Tim kami akan melakukan verifikasi dokumen legalitas Anda dalam 3-5 hari kerja.</p>
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