// User Documents Management
const UserDocuments = {
    table: null,
    
    init: function() {
        // Initialize DataTable
        this.table = DataTableHelper.init('#documents-table', {
            ajax: '/api/user/documents',
            columns: [
                { data: 'title' },
                { data: 'category' },
                { data: 'status' },
                { data: 'created_at' },
                { data: 'actions', orderable: false }
            ]
        });
        
        // Delete document
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('delete-document')) {
                this.deleteDocument(e.target.dataset.id);
            }
        });
    },
    
    deleteDocument: function(id) {
        if (confirm('Are you sure you want to delete this document?')) {
            Ajax.post(`/api/user/documents/${id}/delete`, {}, 
                (response) => {
                    Notify.success('Document deleted successfully');
                    this.table.ajax.reload();
                },
                () => Notify.error('Failed to delete document')
            );
        }
    }
};

document.addEventListener('DOMContentLoaded', () => UserDocuments.init());
