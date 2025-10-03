// Franchise Dashboard
const FranchiseDashboard = {
    init: function() {
        this.loadPerformanceMetrics();
        this.loadCommissionData();
    },
    
    loadPerformanceMetrics: function() {
        Ajax.get('/api/franchise/metrics', (data) => {
            this.updateMetrics(data);
        });
    },
    
    updateMetrics: function(data) {
        document.querySelector('#total-customers').textContent = data.total_customers || 0;
        document.querySelector('#total-revenue').textContent = '₹' + (data.total_revenue || 0);
        document.querySelector('#commission-earned').textContent = '₹' + (data.commission || 0);
    },
    
    loadCommissionData: function() {
        // Load commission chart
    }
};

document.addEventListener('DOMContentLoaded', () => FranchiseDashboard.init());
