#!/bin/bash

# Create all JavaScript files for the public assets

# Common JavaScript Files
cat > public/assets/js/common/ajax.js << 'EOF'
/**
 * AJAX Helper - Simplified AJAX requests
 */
const Ajax = {
    /**
     * Make GET request
     */
    get: function(url, success, error) {
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => success(data))
        .catch(err => error && error(err));
    },
    
    /**
     * Make POST request
     */
    post: function(url, data, success, error) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': this.getCsrfToken()
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => success(data))
        .catch(err => error && error(err));
    },
    
    /**
     * Upload file
     */
    upload: function(url, formData, success, error, progress) {
        const xhr = new XMLHttpRequest();
        
        if (progress) {
            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    progress(percentComplete);
                }
            });
        }
        
        xhr.addEventListener('load', () => {
            const response = JSON.parse(xhr.responseText);
            success(response);
        });
        
        xhr.addEventListener('error', () => error && error());
        
        xhr.open('POST', url);
        xhr.setRequestHeader('X-CSRF-TOKEN', this.getCsrfToken());
        xhr.send(formData);
    },
    
    /**
     * Get CSRF token
     */
    getCsrfToken: function() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }
};
EOF

cat > public/assets/js/common/validation.js << 'EOF'
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
EOF

cat > public/assets/js/common/notifications.js << 'EOF'
/**
 * Toast Notifications
 */
const Notify = {
    /**
     * Show notification
     */
    show: function(message, type = 'info', duration = 3000) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="toast-icon fas fa-${this.getIcon(type)}"></i>
                <span class="toast-message">${message}</span>
            </div>
        `;
        
        // Add styles
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            background-color: ${this.getColor(type)};
            color: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    },
    
    success: function(message) {
        this.show(message, 'success');
    },
    
    error: function(message) {
        this.show(message, 'error');
    },
    
    warning: function(message) {
        this.show(message, 'warning');
    },
    
    info: function(message) {
        this.show(message, 'info');
    },
    
    getIcon: function(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    },
    
    getColor: function(type) {
        const colors = {
            success: '#10b981',
            error: '#ef4444',
            warning: '#f59e0b',
            info: '#3b82f6'
        };
        return colors[type] || '#3b82f6';
    }
};

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
EOF

cat > public/assets/js/common/datatables.js << 'EOF'
/**
 * DataTables Initialization
 */
const DataTableHelper = {
    /**
     * Initialize DataTable
     */
    init: function(tableSelector, options = {}) {
        const defaultOptions = {
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[0, 'desc']],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search...",
                lengthMenu: "Show _MENU_ entries"
            }
        };
        
        const mergedOptions = { ...defaultOptions, ...options };
        
        if (typeof $ !== 'undefined' && $.fn.DataTable) {
            return $(tableSelector).DataTable(mergedOptions);
        } else {
            console.error('DataTables library not loaded');
        }
    },
    
    /**
     * Reload table
     */
    reload: function(table) {
        if (table && table.ajax) {
            table.ajax.reload();
        }
    },
    
    /**
     * Export to CSV
     */
    exportCSV: function(table, filename = 'export.csv') {
        const data = table.data().toArray();
        let csv = '';
        
        // Add headers
        const headers = table.columns().header().toArray().map(th => th.textContent);
        csv += headers.join(',') + '\n';
        
        // Add data
        data.forEach(row => {
            csv += row.join(',') + '\n';
        });
        
        // Download
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
    }
};
EOF

# Web JavaScript Files
cat > public/assets/js/web/main.js << 'EOF'
/**
 * Main Web App JavaScript
 */
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const navbarMenu = document.querySelector('.navbar-menu');
    
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            navbarMenu.classList.toggle('show');
        });
    }
    
    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
    
    // Close alerts
    document.querySelectorAll('.alert-close').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.alert').remove();
        });
    });
});
EOF

cat > public/assets/js/web/search.js << 'EOF'
/**
 * Search Functionality
 */
const SearchModule = {
    init: function() {
        const searchInput = document.querySelector('#search-input');
        const searchResults = document.querySelector('#search-results');
        
        if (searchInput) {
            searchInput.addEventListener('input', this.debounce(function(e) {
                SearchModule.search(e.target.value);
            }, 300));
        }
    },
    
    search: function(query) {
        if (query.length < 2) return;
        
        Ajax.get(`/api/search?q=${encodeURIComponent(query)}`, 
            (data) => {
                this.displayResults(data);
            },
            (error) => {
                console.error('Search error:', error);
            }
        );
    },
    
    displayResults: function(results) {
        const container = document.querySelector('#search-results');
        if (!container) return;
        
        if (results.length === 0) {
            container.innerHTML = '<p class="text-muted">No results found</p>';
            return;
        }
        
        container.innerHTML = results.map(item => `
            <div class="search-result-item">
                <h5>${item.title}</h5>
                <p>${item.excerpt}</p>
            </div>
        `).join('');
    },
    
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};

document.addEventListener('DOMContentLoaded', () => SearchModule.init());
EOF

cat > public/assets/js/web/document.js << 'EOF'
/**
 * Document Handling
 */
const DocumentHandler = {
    init: function() {
        // Document preview
        document.querySelectorAll('.document-card').forEach(card => {
            card.addEventListener('click', function() {
                const documentId = this.dataset.documentId;
                DocumentHandler.preview(documentId);
            });
        });
        
        // View toggle
        const viewToggle = document.querySelectorAll('.view-btn');
        viewToggle.forEach(btn => {
            btn.addEventListener('click', function() {
                viewToggle.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const view = this.dataset.view;
                DocumentHandler.switchView(view);
            });
        });
    },
    
    preview: function(documentId) {
        Ajax.get(`/api/documents/${documentId}`, 
            (data) => {
                this.showPreviewModal(data);
            },
            (error) => {
                Notify.error('Failed to load document');
            }
        );
    },
    
    showPreviewModal: function(document) {
        // Implementation for modal display
        console.log('Show document:', document);
    },
    
    switchView: function(view) {
        const grid = document.querySelector('.documents-grid');
        const list = document.querySelector('.documents-list');
        
        if (view === 'grid') {
            grid.classList.add('active');
            list.classList.remove('active');
        } else {
            grid.classList.remove('active');
            list.classList.add('active');
        }
    }
};

document.addEventListener('DOMContentLoaded', () => DocumentHandler.init());
EOF

cat > public/assets/js/web/payment.js << 'EOF'
/**
 * Payment Processing
 */
const PaymentHandler = {
    init: function() {
        const paymentForm = document.querySelector('#payment-form');
        if (paymentForm) {
            paymentForm.addEventListener('submit', this.handlePayment.bind(this));
        }
    },
    
    handlePayment: function(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);
        
        // Validate
        const validation = Validator.validateForm(e.target);
        if (!validation.valid) {
            Validator.showErrors(validation.errors);
            return;
        }
        
        // Process payment
        this.processPayment(data);
    },
    
    processPayment: function(data) {
        Ajax.post('/api/payment/process', data,
            (response) => {
                if (response.success) {
                    Notify.success('Payment successful!');
                    setTimeout(() => {
                        window.location.href = response.redirect_url;
                    }, 1000);
                } else {
                    Notify.error(response.message || 'Payment failed');
                }
            },
            (error) => {
                Notify.error('Payment processing error');
            }
        );
    }
};

document.addEventListener('DOMContentLoaded', () => PaymentHandler.init());
EOF

echo "All JavaScript files created successfully!"
