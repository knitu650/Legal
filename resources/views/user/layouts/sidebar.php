<div class="p-3">
    <a href="/" class="d-flex align-items-center text-white text-decoration-none mb-4">
        <i class="fas fa-file-contract fs-4 me-2"></i>
        <span class="fs-5 fw-bold">Legal Docs</span>
    </a>
    
    <hr class="text-white">
    
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="/user/dashboard" class="nav-link">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="/user/documents" class="nav-link">
                <i class="fas fa-file-alt me-2"></i> My Documents
            </a>
        </li>
        <li>
            <a href="/user/documents/create" class="nav-link">
                <i class="fas fa-plus-circle me-2"></i> Create Document
            </a>
        </li>
        <li>
            <a href="/user/signatures" class="nav-link">
                <i class="fas fa-signature me-2"></i> Signatures
            </a>
        </li>
        <li>
            <a href="/user/subscription" class="nav-link">
                <i class="fas fa-crown me-2"></i> Subscription
            </a>
        </li>
        <li>
            <a href="/user/billing" class="nav-link">
                <i class="fas fa-credit-card me-2"></i> Billing
            </a>
        </li>
        <li>
            <a href="/user/consultations" class="nav-link">
                <i class="fas fa-user-tie me-2"></i> Consultations
            </a>
        </li>
        <li>
            <a href="/user/support" class="nav-link">
                <i class="fas fa-headset me-2"></i> Support
            </a>
        </li>
        <li>
            <a href="/user/settings" class="nav-link">
                <i class="fas fa-cog me-2"></i> Settings
            </a>
        </li>
    </ul>
    
    <hr class="text-white">
    
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" 
           data-bs-toggle="dropdown">
            <i class="fas fa-user-circle fs-4 me-2"></i>
            <strong><?= sanitize(auth()->name ?? 'User') ?></strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark">
            <li><a class="dropdown-item" href="/user/settings">Settings</a></li>
            <li><a class="dropdown-item" href="/">View Website</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="/logout">Sign out</a></li>
        </ul>
    </div>
</div>
