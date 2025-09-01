/**
 * Company Ideas Management System - JavaScript
 * Client-side validation and interactive features
 */

// Application namespace
const IdeaApp = {
    // Configuration
    config: {
        debounceDelay: 300,
        validationRules: {
            email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/,
            name: /^[a-zA-ZÀ-ÿ\s\-']{2,}$/
        }
    },

    // Initialize application
    init() {
        this.bindEvents();
        this.initValidation();
        this.initComponents();
    },

    // Bind global events
    bindEvents() {
        // CSRF token refresh
        this.refreshCsrfToken();
        
        // Form submissions
        document.addEventListener('submit', this.handleFormSubmit.bind(this));
        
        // File uploads
        document.addEventListener('change', this.handleFileUpload.bind(this));
        
        // Confirm dialogs
        document.addEventListener('click', this.handleConfirmActions.bind(this));
        
        // Auto-save functionality
        this.initAutoSave();
    },

    // Initialize form validation
    initValidation() {
        const forms = document.querySelectorAll('form[data-validate="true"]');
        forms.forEach(form => {
            this.attachValidation(form);
        });
    },

    // Attach validation to a form
    attachValidation(form) {
        const inputs = form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            // Real-time validation
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', this.debounce(() => this.validateField(input), this.config.debounceDelay));
        });

        // Form submission validation
        form.addEventListener('submit', (e) => {
            if (!this.validateForm(form)) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    },

    // Validate individual field
    validateField(field) {
        const value = field.value.trim();
        const rules = this.getValidationRules(field);
        const errors = [];

        // Clear previous errors
        this.clearFieldErrors(field);

        // Required validation
        if (field.hasAttribute('required') && !value) {
            errors.push(`${this.getFieldLabel(field)} is required.`);
        }

        // Type-specific validation
        if (value && rules) {
            switch (field.type) {
                case 'email':
                    if (!this.config.validationRules.email.test(value)) {
                        errors.push('Please enter a valid email address.');
                    }
                    break;
                
                case 'password':
                    if (field.dataset.validatePassword === 'true') {
                        if (!this.config.validationRules.password.test(value)) {
                            errors.push('Password must contain at least 8 characters with uppercase, lowercase, and number.');
                        }
                    }
                    break;
                
                case 'text':
                    if (field.dataset.validateName === 'true') {
                        if (!this.config.validationRules.name.test(value)) {
                            errors.push('Please enter a valid name (2+ characters, letters only).');
                        }
                    }
                    break;
            }

            // Custom length validation
            const minLength = field.getAttribute('minlength');
            const maxLength = field.getAttribute('maxlength');
            
            if (minLength && value.length < parseInt(minLength)) {
                errors.push(`Must be at least ${minLength} characters long.`);
            }
            
            if (maxLength && value.length > parseInt(maxLength)) {
                errors.push(`Must be no more than ${maxLength} characters long.`);
            }
        }

        // Password confirmation
        if (field.dataset.confirmPassword) {
            const passwordField = document.getElementById(field.dataset.confirmPassword);
            if (passwordField && value !== passwordField.value) {
                errors.push('Passwords do not match.');
            }
        }

        // Display errors
        if (errors.length > 0) {
            this.showFieldErrors(field, errors);
            return false;
        }

        this.showFieldSuccess(field);
        return true;
    },

    // Validate entire form
    validateForm(form) {
        const fields = form.querySelectorAll('input, textarea, select');
        let isValid = true;

        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    },

    // Get validation rules for field
    getValidationRules(field) {
        return field.dataset.validate ? JSON.parse(field.dataset.validate) : null;
    },

    // Get field label
    getFieldLabel(field) {
        const label = document.querySelector(`label[for="${field.id}"]`);
        return label ? label.textContent.replace('*', '').trim() : field.name;
    },

    // Clear field errors
    clearFieldErrors(field) {
        field.classList.remove('is-invalid', 'is-valid');
        const feedback = field.parentNode.querySelector('.invalid-feedback');
        if (feedback) {
            feedback.remove();
        }
    },

    // Show field errors
    showFieldErrors(field, errors) {
        field.classList.add('is-invalid');
        field.classList.remove('is-valid');
        
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        feedback.textContent = errors[0]; // Show first error
        
        field.parentNode.appendChild(feedback);
    },

    // Show field success
    showFieldSuccess(field) {
        if (field.value.trim()) {
            field.classList.add('is-valid');
            field.classList.remove('is-invalid');
        }
    },

    // Handle form submission
    handleFormSubmit(e) {
        const form = e.target;
        
        if (form.dataset.validate === 'true') {
            if (!this.validateForm(form)) {
                e.preventDefault();
                this.showAlert('Please correct the errors below.', 'error');
                return false;
            }
        }

        // Show loading state
        this.showFormLoading(form);
    },

    // Handle file uploads
    handleFileUpload(e) {
        const input = e.target;
        
        if (input.type === 'file') {
            this.validateFileUpload(input);
        }
    },

    // Validate file upload
    validateFileUpload(input) {
        const files = input.files;
        const maxSize = parseInt(input.dataset.maxSize) || 5242880; // 5MB default
        const allowedTypes = input.dataset.allowedTypes ? input.dataset.allowedTypes.split(',') : [];
        
        Array.from(files).forEach(file => {
            // Size validation
            if (file.size > maxSize) {
                this.showAlert(`File "${file.name}" is too large. Maximum size is ${this.formatFileSize(maxSize)}.`, 'error');
                input.value = '';
                return;
            }

            // Type validation
            if (allowedTypes.length > 0) {
                const extension = file.name.split('.').pop().toLowerCase();
                if (!allowedTypes.includes(extension)) {
                    this.showAlert(`File type "${extension}" is not allowed.`, 'error');
                    input.value = '';
                    return;
                }
            }
        });
    },

    // Handle confirm actions
    handleConfirmActions(e) {
        const element = e.target;
        
        if (element.dataset.confirm) {
            if (!confirm(element.dataset.confirm)) {
                e.preventDefault();
                e.stopPropagation();
            }
        }
    },

    // Initialize components
    initComponents() {
        this.initTables();
        this.initCharts();
        this.initTooltips();
        this.initModals();
    },

    // Initialize sortable tables
    initTables() {
        const tables = document.querySelectorAll('.table-sortable');
        tables.forEach(table => {
            this.makeSortable(table);
        });
    },

    // Make table sortable
    makeSortable(table) {
        const headers = table.querySelectorAll('th[data-sort]');
        
        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () => {
                this.sortTable(table, header.dataset.sort);
            });
        });
    },

    // Sort table
    sortTable(table, column) {
        // Implementation for table sorting
        console.log('Sorting table by', column);
    },

    // Initialize charts (if needed)
    initCharts() {
        const chartElements = document.querySelectorAll('[data-chart]');
        // Chart implementation would go here
    },

    // Initialize tooltips
    initTooltips() {
        const tooltips = document.querySelectorAll('[data-tooltip]');
        tooltips.forEach(element => {
            element.addEventListener('mouseenter', this.showTooltip.bind(this));
            element.addEventListener('mouseleave', this.hideTooltip.bind(this));
        });
    },

    // Initialize modals
    initModals() {
        const modalTriggers = document.querySelectorAll('[data-modal]');
        modalTriggers.forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                this.openModal(trigger.dataset.modal);
            });
        });
    },

    // Auto-save functionality
    initAutoSave() {
        const autoSaveForms = document.querySelectorAll('form[data-autosave]');
        
        autoSaveForms.forEach(form => {
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('input', this.debounce(() => {
                    this.autoSave(form);
                }, 2000));
            });
        });
    },

    // Auto-save form data
    autoSave(form) {
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);
        
        // Save to localStorage
        localStorage.setItem(`autosave_${form.id}`, JSON.stringify(data));
        
        // Show save indicator
        this.showSaveIndicator();
    },

    // Show save indicator
    showSaveIndicator() {
        const indicator = document.createElement('div');
        indicator.className = 'save-indicator';
        indicator.textContent = 'Draft saved';
        indicator.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #27ae60;
            color: white;
            padding: 10px 15px;
            border-radius: 4px;
            z-index: 9999;
            animation: fadeInOut 2s;
        `;
        
        document.body.appendChild(indicator);
        
        setTimeout(() => {
            indicator.remove();
        }, 2000);
    },

    // Utility functions
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    // Show alert message
    showAlert(message, type = 'info') {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type}`;
        alert.textContent = message;
        alert.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            min-width: 300px;
            animation: slideDown 0.3s;
        `;
        
        document.body.appendChild(alert);
        
        setTimeout(() => {
            alert.remove();
        }, 5000);
    },

    // Show form loading state
    showFormLoading(form) {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="loading"></span> Processing...';
        }
    },

    // Format file size
    formatFileSize(bytes) {
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        if (bytes === 0) return '0 Bytes';
        const i = Math.floor(Math.log(bytes) / Math.log(1024));
        return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
    },

    // Refresh CSRF token
    refreshCsrfToken() {
        // Implementation for CSRF token refresh if needed
    },

    // Show tooltip
    showTooltip(e) {
        const element = e.target;
        const text = element.dataset.tooltip;
        
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.textContent = text;
        tooltip.style.cssText = `
            position: absolute;
            background: #333;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            z-index: 9999;
            pointer-events: none;
        `;
        
        document.body.appendChild(tooltip);
        
        const rect = element.getBoundingClientRect();
        tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + 'px';
        tooltip.style.left = (rect.left + rect.width / 2 - tooltip.offsetWidth / 2) + 'px';
        
        element._tooltip = tooltip;
    },

    // Hide tooltip
    hideTooltip(e) {
        const element = e.target;
        if (element._tooltip) {
            element._tooltip.remove();
            delete element._tooltip;
        }
    },

    // Open modal
    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    },

    // Close modal
    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }
};

// Initialize application when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    IdeaApp.init();
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInOut {
        0% { opacity: 0; transform: translateY(-10px); }
        20% { opacity: 1; transform: translateY(0); }
        80% { opacity: 1; transform: translateY(0); }
        100% { opacity: 0; transform: translateY(-10px); }
    }
    
    @keyframes slideDown {
        0% { transform: translate(-50%, -100%); }
        100% { transform: translate(-50%, 0); }
    }
`;
document.head.appendChild(style);
