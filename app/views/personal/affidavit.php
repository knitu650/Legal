<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Create Affidavit</h1>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <form id="affidavitForm" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="deponent_name">
                        Deponent's Name
                    </label>
                    <input type="text" id="deponent_name" name="deponent_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="affidavit_type">
                        Type of Affidavit
                    </label>
                    <select id="affidavit_type" name="affidavit_type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Select Type</option>
                        <option value="residence">Proof of Residence</option>
                        <option value="income">Income Certificate</option>
                        <option value="employment">Employment</option>
                        <option value="marriage">Marriage</option>
                        <option value="name_change">Name Change</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="content">
                        Affidavit Content
                    </label>
                    <textarea id="content" name="content" required rows="6"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="place">
                        Place of Execution
                    </label>
                    <input type="text" id="place" name="place" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" onclick="window.history.back()"
                        class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                    Create Affidavit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('affidavit_type').addEventListener('change', function() {
    const contentArea = document.getElementById('content');
    const templates = {
        residence: `I, [NAME], hereby solemnly affirm and declare that I am residing at [ADDRESS] for the past [DURATION].`,
        income: `I, [NAME], hereby declare that my annual income from all sources for the financial year [YEAR] is [AMOUNT].`,
        employment: `I, [NAME], hereby declare that I am employed at [COMPANY] as [POSITION] since [DATE].`,
        marriage: `I, [NAME], hereby declare that I am married to [SPOUSE NAME] and our marriage was solemnized on [DATE] at [PLACE].`,
        name_change: `I, [NAME], hereby declare that I have changed my name from [OLD NAME] to [NEW NAME] for all purposes.`
    };
    
    if (templates[this.value]) {
        contentArea.value = templates[this.value];
    } else {
        contentArea.value = '';
    }
});

document.getElementById('affidavitForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitButton = this.querySelector('[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    submitButton.innerHTML = '<div class="spinner"></div>';
    submitButton.disabled = true;

    fetch('<?= BASEURL ?>/personal/affidavit', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => {
                window.location.href = '<?= BASEURL ?>/personal';
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