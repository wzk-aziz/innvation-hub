// Login page JavaScript functionality

// Password toggle functionality
function togglePassword() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('password-toggle-icon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.textContent = '🙈';
    } else {
        passwordField.type = 'password';
        toggleIcon.textContent = '👁️';
    }
}

// Fill demo credentials
function fillCredentials(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
}

// Form validation and submission
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            document.querySelectorAll('.invalid-feedback').forEach(el => {
                el.classList.remove('show');
                el.textContent = '';
            });
            
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            let isValid = true;
            
            // Validate email
            if (!email) {
                showError('email-error', 'L\'adresse email est requise');
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showError('email-error', 'Veuillez saisir une adresse email valide');
                isValid = false;
            }
            
            // Validate password
            if (!password) {
                showError('password-error', 'Le mot de passe est requis');
                isValid = false;
            } else if (password.length < 6) {
                showError('password-error', 'Le mot de passe doit contenir au moins 6 caractères');
                isValid = false;
            }
            
            if (isValid) {
                submitForm();
            }
        });
    }
});

function showError(elementId, message) {
    const errorElement = document.getElementById(elementId);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.add('show');
    }
}

function submitForm() {
    const loginBtn = document.getElementById('loginBtn');
    const loginBtnText = document.getElementById('loginBtnText');
    const loginSpinner = document.getElementById('loginSpinner');
    
    if (loginBtn && loginBtnText && loginSpinner) {
        // Show loading state
        loginBtn.disabled = true;
        loginBtnText.style.display = 'none';
        loginSpinner.style.display = 'block';
        
        // Submit the form
        setTimeout(() => {
            document.getElementById('loginForm').submit();
        }, 500);
    }
}
