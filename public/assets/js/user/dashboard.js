// User Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Load dashboard stats
    loadStats();
    loadRecentDocuments();
    
    // Refresh button
    const refreshBtn = document.querySelector('#refresh-stats');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', loadStats);
    }
});

function loadStats() {
    Ajax.get('/api/user/stats', (data) => {
        updateStatCards(data);
    });
}

function updateStatCards(data) {
    document.querySelector('#total-documents').textContent = data.total_documents || 0;
    document.querySelector('#active-documents').textContent = data.active_documents || 0;
    document.querySelector('#signed-documents').textContent = data.signed_documents || 0;
}

function loadRecentDocuments() {
    Ajax.get('/api/user/documents/recent', (data) => {
        const container = document.querySelector('#recent-documents');
        if (container && data.documents) {
            container.innerHTML = data.documents.map(doc => `
                <div class="document-item">
                    <span>${doc.title}</span>
                    <span class="badge badge-${doc.status}">${doc.status}</span>
                </div>
            `).join('');
        }
    });
}
