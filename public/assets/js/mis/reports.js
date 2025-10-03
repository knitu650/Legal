// MIS Reports
const MISReports = {
    init: function() {
        document.querySelector('#generate-report')?.addEventListener('click', this.generateReport.bind(this));
    },
    
    generateReport: function() {
        const reportType = document.querySelector('#report-type').value;
        const dateFrom = document.querySelector('#date-from').value;
        const dateTo = document.querySelector('#date-to').value;
        
        Ajax.post('/api/mis/reports/generate', {
            type: reportType,
            date_from: dateFrom,
            date_to: dateTo
        }, (data) => {
            this.displayReport(data);
        });
    },
    
    displayReport: function(data) {
        document.querySelector('#report-preview').innerHTML = data.html;
    }
};

document.addEventListener('DOMContentLoaded', () => MISReports.init());
