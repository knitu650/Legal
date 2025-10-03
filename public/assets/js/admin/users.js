// Admin Users Management
const AdminUsers = {
    table: null,
    
    init: function() {
        this.table = DataTableHelper.init('#users-table', {
            ajax: '/api/admin/users',
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'email' },
                { data: 'role' },
                { data: 'status' },
                { data: 'created_at' },
                { data: 'actions', orderable: false }
            ]
        });
        
        this.setupEventListeners();
    },
    
    setupEventListeners: function() {
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('ban-user')) {
                this.banUser(e.target.dataset.userId);
            }
        });
    },
    
    banUser: function(userId) {
        if (confirm('Ban this user?')) {
            Ajax.post(`/api/admin/users/${userId}/ban`, {},
                () => {
                    Notify.success('User banned');
                    this.table.ajax.reload();
                }
            );
        }
    }
};

document.addEventListener('DOMContentLoaded', () => AdminUsers.init());
