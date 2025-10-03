<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Business Legal Documents</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach ($documents as $key => $title): ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4"><?= $title ?></h3>
                    <p class="text-gray-600 mb-4">Create professional <?= strtolower($title) ?> with our legal document generator.</p>
                    <a href="<?= BASEURL ?>/business/<?= $key ?>" 
                       class="inline-block bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:opacity-90 transition-opacity">
                        Create Now
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>