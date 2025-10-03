// Admin Dashboard
const AdminDashboard = {
    charts: {},
    
    init: function() {
        this.loadMetrics();
        this.initCharts();
        this.setupRefresh();
    },
    
    loadMetrics: function() {
        Ajax.get('/api/admin/metrics', (data) => {
            this.updateMetrics(data);
        });
    },
    
    updateMetrics: function(data) {
        document.querySelector('#total-users').textContent = data.total_users || 0;
        document.querySelector('#active-users').textContent = data.active_users || 0;
        document.querySelector('#total-documents').textContent = data.total_documents || 0;
        document.querySelector('#total-revenue').textContent = '₹' + (data.total_revenue || 0);
    },
    
    initCharts: function() {
        // User growth chart
        const ctx = document.getElementById('userGrowthChart');
        if (ctx) {
            this.charts.userGrowth = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Users',
                        data: [12, 19, 3, 5, 2, 3],
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                }
            });
        }
    },
    
    setupRefresh: function() {
        setInterval(() => this.loadMetrics(), 30000); // Refresh every 30s
    }
};

document.addEventListener('DOMContentLoaded', () => AdminDashboard.init());
