/**
 * Form Validation Helper
 */
const Validator = {
    /**
     * Validate email
     */
    email: function(value) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(value);
    },
    
    /**
     * Validate phone
     */
    phone: function(value) {
        const re = /^[0-9]{10}$/;
        return re.test(value);
    },
    
    /**
     * Validate required field
     */
    required: function(value) {
        return value && value.trim() !== '';
    },
    
    /**
     * Validate minimum length
     */
    minLength: function(value, min) {
        return value.length >= min;
    },
    
    /**
     * Validate maximum length
     */
    maxLength: function(value, max) {
        return value.length <= max;
    },
    
    /**
     * Validate form
     */
    validateForm: function(formElement) {
        const errors = {};
        const inputs = formElement.querySelectorAll('[data-validate]');
        
        inputs.forEach(input => {
            const rules = input.dataset.validate.split('|');
            const fieldName = input.name;
            const value = input.value;
            
            rules.forEach(rule => {
                if (rule === 'required' && !this.required(value)) {
                    errors[fieldName] = 'This field is required';
                }
                else if (rule === 'email' && !this.email(value)) {
                    errors[fieldName] = 'Invalid email format';
                }
                else if (rule === 'phone' && !this.phone(value)) {
                    errors[fieldName] = 'Invalid phone number';
                }
                else if (rule.startsWith('min:')) {
                    const min = parseInt(rule.split(':')[1]);
                    if (!this.minLength(value, min)) {
                        errors[fieldName] = `Minimum ${min} characters required`;
                    }
                }
            });
        });
        
        return {
            valid: Object.keys(errors).length === 0,
            errors: errors
        };
    },
    
    /**
     * Show errors
     */
    showErrors: function(errors) {
        // Clear previous errors
        document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        
        // Show new errors
        Object.keys(errors).forEach(fieldName => {
            const input = document.querySelector(`[name="${fieldName}"]`);
            if (input) {
                input.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = errors[fieldName];
                input.parentElement.appendChild(errorDiv);
            }
        });
    }
};
