<header class="admin-header">
    <button class="header-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="header-search">
        <input type="text" class="form-control" placeholder="Search users, documents, transactions..." id="globalSearch">
    </div>

    <div class="header-actions">
        <div class="header-icon" title="Notifications">
            <i class="fas fa-bell"></i>
            <?php if (isset($notificationCount) && $notificationCount > 0): ?>
                <span class="badge"><?= $notificationCount ?></span>
            <?php endif; ?>
        </div>

        <div class="header-icon" title="Messages">
            <i class="fas fa-envelope"></i>
            <?php if (isset($messageCount) && $messageCount > 0): ?>
                <span class="badge"><?= $messageCount ?></span>
            <?php endif; ?>
        </div>

        <div class="dropdown">
            <div class="user-menu" data-bs-toggle="dropdown">
                <div class="user-avatar">
                    <?= strtoupper(substr($_SESSION['user']['name'] ?? 'A', 0, 1)) ?>
                </div>
                <div class="user-info d-none d-md-block">
                    <div style="font-size: 14px; font-weight: 600; color: #1e293b;">
                        <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Admin') ?>
                    </div>
                    <div style="font-size: 12px; color: #64748b;">
                        <?= htmlspecialchars($_SESSION['user']['role'] ?? 'Administrator') ?>
                    </div>
                </div>
                <i class="fas fa-chevron-down ms-2" style="font-size: 12px; color: #64748b;"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="/admin/profile"><i class="fas fa-user me-2"></i> My Profile</a></li>
                <li><a class="dropdown-item" href="/admin/settings"><i class="fas fa-cog me-2"></i> Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="/logout"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</header>

<script>
document.getElementById('globalSearch').addEventListener('input', function(e) {
    const query = e.target.value;
    if (query.length > 2) {
        fetch(`/admin/search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                console.log('Search results:', data);
            });
    }
});
</script>
