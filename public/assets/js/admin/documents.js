// Admin Documents Management
const AdminDocuments = {
    init: function() {
        DataTableHelper.init('#admin-documents-table', {
            ajax: '/api/admin/documents'
        });
    }
};

document.addEventListener('DOMContentLoaded', () => AdminDocuments.init());
