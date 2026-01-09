document.addEventListener('DOMContentLoaded', function() {
    // Profile Edit Functionality
    const editProfileBtn = document.getElementById('editProfileBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const saveProfileBtn = document.getElementById('saveProfileBtn');
    const profileForm = document.getElementById('profileForm');
    const profileActions = document.getElementById('profileActions');
    const formInputs = profileForm.querySelectorAll('input, textarea, select');
    
    // Enable form editing
    if (editProfileBtn) {
        editProfileBtn.addEventListener('click', function() {
            formInputs.forEach(input => {
                input.disabled = false;
            });
            editProfileBtn.classList.add('d-none');
            profileActions.classList.remove('d-none');
        });
    }
    
    // Cancel editing
    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', function() {
            formInputs.forEach(input => {
                input.disabled = true;
            });
            editProfileBtn.classList.remove('d-none');
            profileActions.classList.add('d-none');
        });
    }
    
    // Save profile changes
    if (saveProfileBtn) {
        saveProfileBtn.addEventListener('click', function() {
            // Here you would typically send the data to the server
            // For demo purposes, we'll just disable the form again
            
            formInputs.forEach(input => {
                input.disabled = true;
            });
            editProfileBtn.classList.remove('d-none');
            profileActions.classList.add('d-none');
            
            // Show success message
            showNotification('Profil berhasil diperbarui!', 'success');
        });
    }
    
    // Profile Photo Preview
    const profilePhoto = document.getElementById('profilePhoto');
    const previewImage = document.getElementById('previewImage');
    
    if (profilePhoto && previewImage) {
        profilePhoto.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Initialize Charts
    initDonationTypeChart();
    initRecipientChart();
    initDonationTrendChart();
    
    // Settings Tabs
    const settingsTabs = document.querySelectorAll('#settingsTabs button');
    settingsTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetId = this.getAttribute('data-bs-target');
            const targetPane = document.querySelector(targetId);
            
            // Remove active class from all tabs and panes
            settingsTabs.forEach(t => t.classList.remove('active'));
            document.querySelectorAll('#settingsTabContent .tab-pane').forEach(pane => {
                pane.classList.remove('show', 'active');
            });
            
            // Add active class to clicked tab and corresponding pane
            this.classList.add('active');
            targetPane.classList.add('show', 'active');
        });
    });
    
    // Save Settings Functions
    const saveAccountBtn = document.querySelector('#account-settings button[type="button"]');
    if (saveAccountBtn) {
        saveAccountBtn.addEventListener('click', function() {
            showNotification('Pengaturan akun berhasil disimpan!', 'success');
        });
    }
    
    const savePasswordBtn = document.querySelector('#password-settings button[type="button"]');
    if (savePasswordBtn) {
        savePasswordBtn.addEventListener('click', function() {
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            
            if (newPassword !== confirmPassword) {
                showNotification('Password baru dan konfirmasi tidak cocok!', 'danger');
                return;
            }
            
            // Here you would typically send the data to the server
            showNotification('Password berhasil diubah!', 'success');
            
            // Clear form fields
            document.getElementById('current-password').value = '';
            document.getElementById('new-password').value = '';
            document.getElementById('confirm-password').value = '';
        });
    }
    
    const saveNotificationBtn = document.querySelector('#notification-settings button[type="button"]');
    if (saveNotificationBtn) {
        saveNotificationBtn.addEventListener('click', function() {
            showNotification('Pengaturan notifikasi berhasil disimpan!', 'success');
        });
    }
    
    const savePrivacyBtn = document.querySelector('#privacy-settings button[type="button"]');
    if (savePrivacyBtn) {
        savePrivacyBtn.addEventListener('click', function() {
            showNotification('Pengaturan privasi berhasil disimpan!', 'success');
        });
    }
    
    // Save Photo Profile
    const savePhotoBtn = document.querySelector('#changePhotoModal .btn-primary');
    if (savePhotoBtn) {
        savePhotoBtn.addEventListener('click', function() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('changePhotoModal'));
            modal.hide();
            
            // Update profile photos on the page
            const profilePhotos = document.querySelectorAll('.profile-avatar img');
            profilePhotos.forEach(photo => {
                photo.src = previewImage.src;
            });
            
            showNotification('Foto profil berhasil diperbarui!', 'success');
        });
    }
    
    // Notification Function
    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed animate-on-scroll`;
        notification.style.top = '80px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.style.minWidth = '300px';
        
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Add to body
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.add('animated');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.remove('animated');
            setTimeout(() => {
                notification.remove();
            }, 800);
        }, 5000);
    }
    
    // Chart Functions
    function initDonationTypeChart() {
        const ctx = document.getElementById('donationTypeChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Food Rescue', 'Donasi Barang', 'Donasi Uang'],
                    datasets: [{
                        data: [15, 7, 2],
                        backgroundColor: [
                            '#29B6F6', // info-color
                            '#FFCA28', // warning-color
                            '#000957'  // primary-color
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: {
                                    family: 'Poppins'
                                }
                            }
                        }
                    }
                }
            });
        }
    }
    
    function initRecipientChart() {
        const ctx = document.getElementById('recipientChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Panti Asuhan', 'Panti Jompo', 'Rumah Belajar', 'Komunitas', 'Lainnya'],
                    datasets: [{
                        label: 'Jumlah Donasi',
                        data: [10, 5, 4, 3, 2],
                        backgroundColor: '#000957', // primary-color
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 2
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    }
    
    function initDonationTrendChart() {
        const ctx = document.getElementById('donationTrendChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Donasi Makanan',
                        data: [3, 5, 4, 7, 6, 8],
                        borderColor: '#29B6F6', // info-color
                        backgroundColor: 'rgba(41, 182, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Donasi Barang',
                        data: [2, 3, 2, 4, 3, 5],
                        borderColor: '#FFCA28', // warning-color
                        backgroundColor: 'rgba(255, 202, 40, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Donasi Uang',
                        data: [1, 0, 1, 0, 1, 1],
                        borderColor: '#000957', // primary-color
                        backgroundColor: 'rgba(0, 9, 87, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 2
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                padding: 15,
                                font: {
                                    family: 'Poppins'
                                }
                            }
                        }
                    }
                }
            });
        }
    }
    
    // Scroll Animation
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.animate-on-scroll');
        
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementPosition < windowHeight - 100) {
                element.classList.add('animated');
            }
        });
    };
    
    // Add animate-on-scroll class to elements
    const addAnimationClasses = function() {
        const profileSidebar = document.querySelector('.profile-sidebar');
        const cards = document.querySelectorAll('.card');
        const statItems = document.querySelectorAll('.stat-item');
        
        if (profileSidebar) {
            profileSidebar.classList.add('animate-on-scroll');
        }
        
        cards.forEach(card => {
            card.classList.add('animate-on-scroll');
        });
        
        statItems.forEach(item => {
            item.classList.add('animate-on-scroll');
        });
    };
    
    // Initialize animations
    addAnimationClasses();
    animateOnScroll();
    
    // Run animation on scroll
    window.addEventListener('scroll', animateOnScroll);
});