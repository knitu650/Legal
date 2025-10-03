<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Create Non-Disclosure Agreement</h1>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <form id="ndaForm" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="party_one">
                        Party One (Disclosing Party)
                    </label>
                    <input type="text" id="party_one" name="party_one" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="party_two">
                        Party Two (Receiving Party)
                    </label>
                    <input type="text" id="party_two" name="party_two" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="confidential_info">
                        Description of Confidential Information
                    </label>
                    <textarea id="confidential_info" name="confidential_info" required rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="duration">
                        Duration (months)
                    </label>
                    <input type="number" id="duration" name="duration" required min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="effective_date">
                        Effective Date
                    </label>
                    <input type="date" id="effective_date" name="effective_date" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="terms">
                        Additional Terms
                    </label>
                    <textarea id="terms" name="terms" rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" onclick="window.history.back()"
                        class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:opacity-90 transition-opacity">
                    Create NDA
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('ndaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitButton = this.querySelector('[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    submitButton.innerHTML = '<div class="spinner"></div>';
    submitButton.disabled = true;

    fetch('<?= BASEURL ?>/business/nda', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => {
                window.location.href = '<?= BASEURL ?>/business';
            }, 2000);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('An error occurred. Please try again.', 'error');
    })
    .finally(() => {
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
});</script>