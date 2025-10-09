<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
        <h3>Legal Docs</h3>
        <small>Admin Panel</small>
    </div>

    <nav class="sidebar-menu">
        <div class="menu-section-title">Main</div>
        <a href="/admin/dashboard" class="menu-item <?= $activeMenu === 'dashboard' ? 'active' : '' ?>">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>

        <div class="menu-section-title">User Management</div>
        <a href="/admin/users" class="menu-item <?= $activeMenu === 'users' ? 'active' : '' ?>">
            <i class="fas fa-users"></i>
            <span>Users</span>
            <?php if (isset($pendingUsers) && $pendingUsers > 0): ?>
                <span class="badge bg-warning"><?= $pendingUsers ?></span>
            <?php endif; ?>
        </a>
        <a href="/admin/roles" class="menu-item <?= $activeMenu === 'roles' ? 'active' : '' ?>">
            <i class="fas fa-shield-alt"></i>
            <span>Roles & Permissions</span>
        </a>

        <div class="menu-section-title">Content Management</div>
        <a href="/admin/documents" class="menu-item <?= $activeMenu === 'documents' ? 'active' : '' ?>">
            <i class="fas fa-file-alt"></i>
            <span>Documents</span>
            <?php if (isset($pendingDocs) && $pendingDocs > 0): ?>
                <span class="badge bg-info"><?= $pendingDocs ?></span>
            <?php endif; ?>
        </a>
        <a href="/admin/templates" class="menu-item <?= $activeMenu === 'templates' ? 'active' : '' ?>">
            <i class="fas fa-file-code"></i>
            <span>Templates</span>
        </a>

        <div class="menu-section-title">Business</div>
        <a href="/admin/subscriptions" class="menu-item <?= $activeMenu === 'subscriptions' ? 'active' : '' ?>">
            <i class="fas fa-crown"></i>
            <span>Subscriptions</span>
        </a>
        <a href="/admin/transactions" class="menu-item <?= $activeMenu === 'transactions' ? 'active' : '' ?>">
            <i class="fas fa-credit-card"></i>
            <span>Transactions</span>
        </a>
        <a href="/admin/coupons" class="menu-item <?= $activeMenu === 'coupons' ? 'active' : '' ?>">
            <i class="fas fa-tags"></i>
            <span>Coupons</span>
        </a>

        <div class="menu-section-title">Services</div>
        <a href="/admin/lawyers" class="menu-item <?= $activeMenu === 'lawyers' ? 'active' : '' ?>">
            <i class="fas fa-gavel"></i>
            <span>Lawyers</span>
            <?php if (isset($pendingLawyers) && $pendingLawyers > 0): ?>
                <span class="badge bg-warning"><?= $pendingLawyers ?></span>
            <?php endif; ?>
        </a>
        <a href="/admin/franchises" class="menu-item <?= $activeMenu === 'franchises' ? 'active' : '' ?>">
            <i class="fas fa-store"></i>
            <span>Franchises</span>
            <?php if (isset($pendingFranchises) && $pendingFranchises > 0): ?>
                <span class="badge bg-warning"><?= $pendingFranchises ?></span>
            <?php endif; ?>
        </a>
        <a href="/admin/locations" class="menu-item <?= $activeMenu === 'locations' ? 'active' : '' ?>">
            <i class="fas fa-map-marker-alt"></i>
            <span>Locations</span>
        </a>

        <div class="menu-section-title">Reports & Analytics</div>
        <a href="/admin/reports" class="menu-item <?= $activeMenu === 'reports' ? 'active' : '' ?>">
            <i class="fas fa-chart-bar"></i>
            <span>Reports</span>
        </a>

        <div class="menu-section-title">Support</div>
        <a href="/admin/support" class="menu-item <?= $activeMenu === 'support' ? 'active' : '' ?>">
            <i class="fas fa-headset"></i>
            <span>Support Tickets</span>
            <?php if (isset($openTickets) && $openTickets > 0): ?>
                <span class="badge bg-danger"><?= $openTickets ?></span>
            <?php endif; ?>
        </a>

        <div class="menu-section-title">System</div>
        <a href="/admin/settings" class="menu-item <?= $activeMenu === 'settings' ? 'active' : '' ?>">
            <i class="fas fa-cog"></i>
            <span>Settings</span>
        </a>
    </nav>
</aside>
