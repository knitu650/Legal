<?php ob_start(); ?>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Total Documents</p>
                        <h3 class="mb-0"><?= $stats['total_documents'] ?? 0 ?></h3>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Draft Documents</p>
                        <h3 class="mb-0"><?= $stats['draft_documents'] ?? 0 ?></h3>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-edit fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Completed</p>
                        <h3 class="mb-0"><?= $stats['completed_documents'] ?? 0 ?></h3>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Signed</p>
                        <h3 class="mb-0"><?= $stats['signed_documents'] ?? 0 ?></h3>
                    </div>
                    <div class="text-info">
                        <i class="fas fa-signature fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Documents -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Documents</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentDocuments ?? [] as $doc): ?>
                            <tr>
                                <td><?= sanitize($doc->title) ?></td>
                                <td>
                                    <span class="badge bg-<?= $doc->status === 'completed' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($doc->status) ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y', strtotime($doc->created_at)) ?></td>
                                <td>
                                    <a href="/user/documents/<?= $doc->id ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <a href="/user/documents" class="btn btn-outline-primary">View All Documents</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/user/documents/create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create New Document
                    </a>
                    <a href="/templates" class="btn btn-outline-primary">
                        <i class="fas fa-file-alt"></i> Browse Templates
                    </a>
                    <a href="/user/consultations/book" class="btn btn-outline-primary">
                        <i class="fas fa-user-tie"></i> Book Consultation
                    </a>
                    <a href="/user/subscription" class="btn btn-outline-primary">
                        <i class="fas fa-crown"></i> Upgrade Plan
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Subscription Info -->
        <?php if ($activeSubscription ?? null): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Current Plan</h5>
            </div>
            <div class="card-body">
                <h4 class="text-primary">Premium Plan</h4>
                <p class="text-muted mb-2">
                    Expires: <?= date('M d, Y', strtotime($activeSubscription->ends_at)) ?>
                </p>
                <a href="/user/subscription" class="btn btn-sm btn-outline-primary">
                    Manage Subscription
                </a>
            </div>
        </div>
        <?php else: ?>
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body text-center">
                <i class="fas fa-crown text-warning fa-3x mb-3"></i>
                <h5>Upgrade to Premium</h5>
                <p class="text-muted">Get unlimited access to all templates</p>
                <a href="/user/subscription/plans" class="btn btn-warning">
                    View Plans
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php 
$content = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
?>
