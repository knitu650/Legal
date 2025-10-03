// MIS Export
const MISExport = {
    exportToExcel: function() {
        window.location.href = '/api/mis/export/excel';
    },
    
    exportToPDF: function() {
        window.location.href = '/api/mis/export/pdf';
    },
    
    exportToCSV: function() {
        window.location.href = '/api/mis/export/csv';
    }
};
