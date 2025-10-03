<div class="animate__animated animate__fadeIn">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Legal Documents Management System</h1>
        <p class="text-xl text-gray-600">Get Stamp Paper + Legal Documents Delivered. All Online!</p>
    </div>

    <div class="max-w-4xl mx-auto mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="relative">
                <input type="text" 
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Search Legal Documents">
                <button class="absolute right-3 top-3 text-blue-500">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Affidavits Card -->
        <div class="bg-gradient-to-br from-cyan-400 to-blue-500 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-300">
            <a href="<?= BASEURL ?>/affidavits" class="block text-center">
                <i class="fas fa-file-alt text-4xl mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Affidavits</h3>
                <p class="text-sm">& More...</p>
            </a>
        </div>

        <!-- Rental Agreement Card -->
        <div class="bg-gradient-to-br from-blue-400 to-indigo-500 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-300">
            <a href="<?= BASEURL ?>/rental" class="block text-center">
                <i class="fas fa-home text-4xl mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Rental Agreement</h3>
                <p class="text-sm">& More...</p>
            </a>
        </div>

        <!-- Name Change Card -->
        <div class="bg-gradient-to-br from-indigo-400 to-purple-500 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-300">
            <a href="<?= BASEURL ?>/namechange" class="block text-center">
                <i class="fas fa-user-edit text-4xl mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Name Change</h3>
                <p class="text-sm">& More...</p>
            </a>
        </div>

        <!-- Contracts Card -->
        <div class="bg-gradient-to-br from-purple-400 to-pink-500 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-300">
            <a href="<?= BASEURL ?>/contracts" class="block text-center">
                <i class="fas fa-file-contract text-4xl mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Contract & Agreements</h3>
                <p class="text-sm">& More...</p>
            </a>
        </div>
    </div>
</div>