<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legal Documents Management System</title>
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?= BASEURL ?>/public/css/style.css" rel="stylesheet">
    
    <!-- Animation CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-cyan-500 to-blue-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="<?= BASEURL ?>" class="text-white text-2xl font-bold">Legal Docs</a>
            <div class="space-x-4">
                <a href="<?= BASEURL ?>" class="text-white hover:text-gray-200 transition">Home</a>
                <a href="<?= BASEURL ?>/documents" class="text-white hover:text-gray-200 transition">Documents</a>
                <a href="<?= BASEURL ?>/affidavits" class="text-white hover:text-gray-200 transition">Affidavits</a>
                <a href="<?= BASEURL ?>/rental" class="text-white hover:text-gray-200 transition">Rental</a>
                <a href="<?= BASEURL ?>/contracts" class="text-white hover:text-gray-200 transition">Contracts</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto py-8">
        <?php require_once $content; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white py-6">
        <div class="container mx-auto text-center">
            <p>&copy; <?= date('Y') ?> Legal Documents Management System. All rights reserved.</p>
        </div>
    </footer>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="<?= BASEURL ?>/public/js/main.js"></script>
</body>
</html>