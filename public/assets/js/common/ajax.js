/**
 * AJAX Helper - Simplified AJAX requests
 */
const Ajax = {
    /**
     * Make GET request
     */
    get: function(url, success, error) {
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => success(data))
        .catch(err => error && error(err));
    },
    
    /**
     * Make POST request
     */
    post: function(url, data, success, error) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': this.getCsrfToken()
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => success(data))
        .catch(err => error && error(err));
    },
    
    /**
     * Upload file
     */
    upload: function(url, formData, success, error, progress) {
        const xhr = new XMLHttpRequest();
        
        if (progress) {
            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    progress(percentComplete);
                }
            });
        }
        
        xhr.addEventListener('load', () => {
            const response = JSON.parse(xhr.responseText);
            success(response);
        });
        
        xhr.addEventListener('error', () => error && error());
        
        xhr.open('POST', url);
        xhr.setRequestHeader('X-CSRF-TOKEN', this.getCsrfToken());
        xhr.send(formData);
    },
    
    /**
     * Get CSRF token
     */
    getCsrfToken: function() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }
};
