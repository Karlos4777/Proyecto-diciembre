// ===== Authentication Page JavaScript =====

document.addEventListener('DOMContentLoaded', function() {
    // Initialize auth page features
    initTogglePassword();
    initFormValidation();
    initAlertDismissal();
    initFormFocus();
    initEnterKeySubmit();
});

/**
 * Toggle password visibility
 */
function initTogglePassword() {
    const toggleButtons = document.querySelectorAll('.toggle-password');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-target');
            const inputField = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (inputField) {
                const isPassword = inputField.type === 'password';
                inputField.type = isPassword ? 'text' : 'password';
                
                // Update icon
                if (icon) {
                    icon.classList.remove(isPassword ? 'bi-eye' : 'bi-eye-slash');
                    icon.classList.add(isPassword ? 'bi-eye-slash' : 'bi-eye');
                }
                
                // Visual feedback
                this.classList.add('active');
                setTimeout(() => {
                    this.classList.remove('active');
                }, 100);
            }
        });
    });
}

/**
 * Form validation with visual feedback
 */
function initFormValidation() {
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            // HTML5 validation will handle the rest
            const email = document.getElementById('loginEmail');
            const password = document.getElementById('loginPassword');
            
            // Clear previous validation states
            email?.classList.remove('is-invalid');
            password?.classList.remove('is-invalid');
            
            // Check if form is valid
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                
                // Add visual feedback
                if (email && !email.value) {
                    email.classList.add('is-invalid');
                    email.focus();
                }
                if (password && !password.value) {
                    password.classList.add('is-invalid');
                }
            }
            
            this.classList.add('was-validated');
        });
    }
}

/**
 * Auto-dismiss alerts after 5 seconds
 */
function initAlertDismissal() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        // Auto-dismiss after 5 seconds (skip if it has a close button the user needs)
        const dismissible = alert.classList.contains('alert-dismissible');
        
        if (!dismissible) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        }
    });
}

/**
 * Focus effects on form inputs
 */
function initFormFocus() {
    const formControls = document.querySelectorAll('.form-control, .form-select');
    
    formControls.forEach(control => {
        // Add focus class for animations
        control.addEventListener('focus', function() {
            this.parentElement?.classList.add('focused');
        });
        
        control.addEventListener('blur', function() {
            this.parentElement?.classList.remove('focused');
        });
        
        // Validate on blur
        control.addEventListener('blur', function() {
            validateField(this);
        });
        
        // Clear error on typing
        control.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
            }
        });
    });
}

/**
 * Allow submitting form with Enter key
 */
function initEnterKeySubmit() {
    const inputs = document.querySelectorAll('#loginForm input[type="email"], #loginForm input[type="password"]');
    
    inputs.forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                
                // If email field, focus password
                if (this.id === 'loginEmail') {
                    document.getElementById('loginPassword')?.focus();
                } else {
                    // If password field, submit form
                    document.getElementById('loginForm')?.submit();
                }
            }
        });
    });
}

/**
 * Validate individual field
 */
function validateField(field) {
    if (!field.value.trim()) {
        field.classList.add('is-invalid');
        return false;
    }
    
    if (field.type === 'email') {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(field.value)) {
            field.classList.add('is-invalid');
            return false;
        }
    }
    
    field.classList.remove('is-invalid');
    return true;
}

/**
 * Show/hide password with animation
 */
document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('.toggle-password');
    
    toggleButtons.forEach(button => {
        button.style.cursor = 'pointer';
        button.setAttribute('aria-label', 'Toggle password visibility');
    });
});
