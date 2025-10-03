<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Create Your Will</h1>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <form id="willForm" class="space-y-6">
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="testator_name">
                        Your Full Name (Testator)
                    </label>
                    <input type="text" id="testator_name" name="testator_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Beneficiaries
                    </label>
                    <div id="beneficiaries" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <input type="text" name="beneficiaries[0][name]" placeholder="Full Name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <input type="text" name="beneficiaries[0][relation]" placeholder="Relation" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <input type="text" name="beneficiaries[0][share]" placeholder="Share (%)" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                    </div>
                    <button type="button" onclick="addBeneficiary()"
                            class="mt-2 text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                        + Add Another Beneficiary
                    </button>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Assets
                    </label>
                    <div id="assets" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" name="assets[0][description]" placeholder="Asset Description" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <input type="text" name="assets[0][value]" placeholder="Estimated Value" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                    </div>
                    <button type="button" onclick="addAsset()"
                            class="mt-2 text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                        + Add Another Asset
                    </button>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Executors
                    </label>
                    <div id="executors" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" name="executors[0][name]" placeholder="Executor Name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <input type="text" name="executors[0][relation]" placeholder="Relation" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                    </div>
                    <button type="button" onclick="addExecutor()"
                            class="mt-2 text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                        + Add Another Executor
                    </button>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Witnesses
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" name="witnesses[]" placeholder="Witness 1 Name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <input type="text" name="witnesses[]" placeholder="Witness 2 Name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" onclick="window.history.back()"
                        class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                    Create Will
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let beneficiaryCount = 1;
let assetCount = 1;
let executorCount = 1;

function addBeneficiary() {
    const div = document.createElement('div');
    div.className = 'grid grid-cols-1 md:grid-cols-3 gap-4';
    div.innerHTML = `
        <input type="text" name="beneficiaries[${beneficiaryCount}][name]" placeholder="Full Name" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        <input type="text" name="beneficiaries[${beneficiaryCount}][relation]" placeholder="Relation" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        <input type="text" name="beneficiaries[${beneficiaryCount}][share]" placeholder="Share (%)" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
    `;
    document.getElementById('beneficiaries').appendChild(div);
    beneficiaryCount++;
}

function addAsset() {
    const div = document.createElement('div');
    div.className = 'grid grid-cols-1 md:grid-cols-2 gap-4';
    div.innerHTML = `
        <input type="text" name="assets[${assetCount}][description]" placeholder="Asset Description" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        <input type="text" name="assets[${assetCount}][value]" placeholder="Estimated Value" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
    `;
    document.getElementById('assets').appendChild(div);
    assetCount++;
}

function addExecutor() {
    const div = document.createElement('div');
    div.className = 'grid grid-cols-1 md:grid-cols-2 gap-4';
    div.innerHTML = `
        <input type="text" name="executors[${executorCount}][name]" placeholder="Executor Name" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        <input type="text" name="executors[${executorCount}][relation]" placeholder="Relation" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
    `;
    document.getElementById('executors').appendChild(div);
    executorCount++;
}

document.getElementById('willForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitButton = this.querySelector('[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    submitButton.innerHTML = '<div class="spinner"></div>';
    submitButton.disabled = true;

    fetch('<?= BASEURL ?>/personal/will', {
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