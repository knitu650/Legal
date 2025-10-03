/**
 * Search Functionality
 */
const SearchModule = {
    init: function() {
        const searchInput = document.querySelector('#search-input');
        const searchResults = document.querySelector('#search-results');
        
        if (searchInput) {
            searchInput.addEventListener('input', this.debounce(function(e) {
                SearchModule.search(e.target.value);
            }, 300));
        }
    },
    
    search: function(query) {
        if (query.length < 2) return;
        
        Ajax.get(`/api/search?q=${encodeURIComponent(query)}`, 
            (data) => {
                this.displayResults(data);
            },
            (error) => {
                console.error('Search error:', error);
            }
        );
    },
    
    displayResults: function(results) {
        const container = document.querySelector('#search-results');
        if (!container) return;
        
        if (results.length === 0) {
            container.innerHTML = '<p class="text-muted">No results found</p>';
            return;
        }
        
        container.innerHTML = results.map(item => `
            <div class="search-result-item">
                <h5>${item.title}</h5>
                <p>${item.excerpt}</p>
            </div>
        `).join('');
    },
    
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};

document.addEventListener('DOMContentLoaded', () => SearchModule.init());
