// Admin Analytics
const Analytics = {
    init: function() {
        this.loadRevenueChart();
        this.loadDocumentStats();
    },
    
    loadRevenueChart: function() {
        Ajax.get('/api/admin/analytics/revenue', (data) => {
            const ctx = document.getElementById('revenueChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Revenue',
                            data: data.values,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)'
                        }]
                    }
                });
            }
        });
    },
    
    loadDocumentStats: function() {
        Ajax.get('/api/admin/analytics/documents', (data) => {
            const ctx = document.getElementById('documentStatsChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Draft', 'Completed', 'Signed'],
                        datasets: [{
                            data: [data.draft, data.completed, data.signed],
                            backgroundColor: ['#ffc107', '#28a745', '#007bff']
                        }]
                    }
                });
            }
        });
    }
};

document.addEventListener('DOMContentLoaded', () => Analytics.init());
