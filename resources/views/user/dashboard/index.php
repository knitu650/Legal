<?php
$title = 'Dashboard';
$currentPage = 'dashboard';
$styles = ['/assets/css/user/dashboard.css'];
$scripts = ['/assets/js/user/dashboard.js'];

ob_start();
?>

<div class="dashboard-header mb-4">
    <h1>Welcome back, <?php echo htmlspecialchars(auth()->name); ?>!</h1>
    <p class="text-muted">Here's what's happening with your account</p>
</div>

<div class="stats-grid mb-4">
    <div class="stat-card">
        <div class="stat-label">Total Documents</div>
        <div class="stat-value" id="total-documents">
            <div class="spinner"></div>
        </div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i> +12% this month
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-label">Active Documents</div>
        <div class="stat-value" id="active-documents">
            <div class="spinner"></div>
        </div>
        <div class="stat-change">
            <i class="fas fa-minus"></i> No change
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-label">Signed Documents</div>
        <div class="stat-value" id="signed-documents">
            <div class="spinner"></div>
        </div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i> +5 this week
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-label">Consultations</div>
        <div class="stat-value">3</div>
        <div class="stat-change">
            <i class="fas fa-calendar"></i> 1 upcoming
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="col-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Documents</h3>
                <button id="refresh-stats" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-sync"></i> Refresh
                </button>
            </div>
            <div class="card-body">
                <div id="recent-documents">
                    <div class="text-center p-4">
                        <div class="spinner"></div>
                        <p class="text-muted mt-2">Loading documents...</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Activity Timeline</h3>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-icon bg-success">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="timeline-content">
                            <h5>Document Signed</h5>
                            <p class="text-muted">Employment Contract.pdf</p>
                            <small>2 hours ago</small>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-icon bg-primary">
                            <i class="fas fa-file"></i>
                        </div>
                        <div class="timeline-content">
                            <h5>Document Created</h5>
                            <p class="text-muted">Rental Agreement.pdf</p>
                            <small>1 day ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <a href="/user/documents/create" class="btn btn-primary btn-block mb-2">
                    <i class="fas fa-plus"></i> New Document
                </a>
                <a href="/user/consultation/book" class="btn btn-outline-primary btn-block mb-2">
                    <i class="fas fa-calendar"></i> Book Consultation
                </a>
                <a href="/user/documents" class="btn btn-outline-primary btn-block mb-2">
                    <i class="fas fa-folder"></i> Browse Documents
                </a>
                <a href="/user/subscription" class="btn btn-outline-primary btn-block">
                    <i class="fas fa-crown"></i> Upgrade Plan
                </a>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Storage Usage</h3>
            </div>
            <div class="card-body">
                <div class="progress mb-2" style="height: 20px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 45%;">
                        45%
                    </div>
                </div>
                <p class="text-muted text-sm">
                    <i class="fas fa-database"></i> 450 MB of 1 GB used
                </p>
                <a href="/user/subscription/upgrade" class="btn btn-sm btn-outline-primary">
                    Upgrade Storage
                </a>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Upcoming Events</h3>
            </div>
            <div class="card-body">
                <div class="event-item mb-3">
                    <div class="d-flex justify-between align-center">
                        <div>
                            <h6>Legal Consultation</h6>
                            <p class="text-muted text-sm">
                                <i class="fas fa-clock"></i> Tomorrow at 10:00 AM
                            </p>
                        </div>
                        <span class="badge badge-primary">Upcoming</span>
                    </div>
                </div>
                <div class="event-item">
                    <div class="d-flex justify-between align-center">
                        <div>
                            <h6>Subscription Renewal</h6>
                            <p class="text-muted text-sm">
                                <i class="fas fa-calendar"></i> In 5 days
                            </p>
                        </div>
                        <span class="badge badge-warning">Pending</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
?>
