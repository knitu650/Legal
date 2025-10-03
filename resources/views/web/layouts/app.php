<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title><?= $pageTitle ?? 'Legal Document Management System' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= asset('css/web/main.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/common/variables.css') ?>">
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    
    <?php if (flash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= sanitize(flash('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (flash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= sanitize(flash('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <main>
        <?php echo $content ?? '' ?>
    </main>
    
    <?php include __DIR__ . '/footer.php'; ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Custom JS -->
    <script src="<?= asset('js/web/main.js') ?>"></script>
    <script src="<?= asset('js/common/ajax.js') ?>"></script>
</body>
</html>
