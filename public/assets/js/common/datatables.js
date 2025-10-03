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
