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
