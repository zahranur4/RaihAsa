document.addEventListener('DOMContentLoaded', function() {
    const editBtn = document.getElementById('editProfileBtn');
    const cancelBtn = document.getElementById('cancelEditBtn');
    const saveBtn = document.getElementById('saveProfileBtn');
    const profileActions = document.getElementById('profileActions');
    const profileForm = document.getElementById('profileForm');
    
    let originalValues = {};
    
    // Capture original form values
    function captureOriginalValues() {
        originalValues = {};
        const inputs = profileForm.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            if (input.name) {
                originalValues[input.name] = input.value;
            }
        });
    }
    
    // Restore original form values
    function restoreOriginalValues() {
        const inputs = profileForm.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            if (input.name && originalValues.hasOwnProperty(input.name)) {
                input.value = originalValues[input.name];
            }
        });
    }
    
    // Enable edit mode
    if (editBtn) {
        editBtn.addEventListener('click', function() {
            captureOriginalValues();
            
            // Enable all form inputs
            const inputs = profileForm.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.disabled = false;
            });
            
            // Show action buttons, hide edit button
            editBtn.classList.add('d-none');
            profileActions.classList.remove('d-none');
        });
    }
    
    // Cancel edit mode
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            restoreOriginalValues();
            
            // Disable all form inputs
            const inputs = profileForm.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.disabled = true;
            });
            
            // Hide action buttons, show edit button
            profileActions.classList.add('d-none');
            editBtn.classList.remove('d-none');
        });
    }
    
    // Handle form submission
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            // Disable submit button to prevent double submission
            if (saveBtn) {
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
            }
        });
    }
});
