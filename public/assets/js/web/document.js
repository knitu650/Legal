/**
 * Document Handling
 */
const DocumentHandler = {
    init: function() {
        // Document preview
        document.querySelectorAll('.document-card').forEach(card => {
            card.addEventListener('click', function() {
                const documentId = this.dataset.documentId;
                DocumentHandler.preview(documentId);
            });
        });
        
        // View toggle
        const viewToggle = document.querySelectorAll('.view-btn');
        viewToggle.forEach(btn => {
            btn.addEventListener('click', function() {
                viewToggle.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const view = this.dataset.view;
                DocumentHandler.switchView(view);
            });
        });
    },
    
    preview: function(documentId) {
        Ajax.get(`/api/documents/${documentId}`, 
            (data) => {
                this.showPreviewModal(data);
            },
            (error) => {
                Notify.error('Failed to load document');
            }
        );
    },
    
    showPreviewModal: function(document) {
        // Implementation for modal display
        console.log('Show document:', document);
    },
    
    switchView: function(view) {
        const grid = document.querySelector('.documents-grid');
        const list = document.querySelector('.documents-list');
        
        if (view === 'grid') {
            grid.classList.add('active');
            list.classList.remove('active');
        } else {
            grid.classList.remove('active');
            list.classList.add('active');
        }
    }
};

document.addEventListener('DOMContentLoaded', () => DocumentHandler.init());
