<?php
$title = 'Admin Dashboard';
$currentPage = 'dashboard';
$styles = ['/assets/css/admin/dashboard.css'];
$scripts = ['/assets/js/admin/dashboard.js', 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js'];

ob_start();
?>

<div class="admin-dashboard">
    <div class="dashboard-header mb-4">
        <h1>Admin Dashboard</h1>
        <div class="dashboard-actions">
            <button class="btn btn-outline-primary" id="refresh-metrics">
                <i class="fas fa-sync"></i> Refresh
            </button>
            <a href="/admin/reports" class="btn btn-primary">
                <i class="fas fa-chart-bar"></i> View Reports
            </a>
        </div>
    </div>
    
    <div class="metrics-row mb-4">
        <div class="metric-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="metric-icon"><i class="fas fa-users"></i></div>
            <div class="metric-value" id="total-users">0</div>
            <div class="metric-label">Total Users</div>
        </div>
        
        <div class="metric-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="metric-icon"><i class="fas fa-file-alt"></i></div>
            <div class="metric-value" id="total-documents">0</div>
            <div class="metric-label">Total Documents</div>
        </div>
        
        <div class="metric-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="metric-icon"><i class="fas fa-rupee-sign"></i></div>
            <div class="metric-value" id="total-revenue">₹0</div>
            <div class="metric-label">Total Revenue</div>
        </div>
        
        <div class="metric-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="metric-icon"><i class="fas fa-user-check"></i></div>
            <div class="metric-value" id="active-users">0</div>
            <div class="metric-label">Active Users</div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">User Growth</h3>
                </div>
                <div class="card-body">
                    <canvas id="userGrowthChart" height="100"></canvas>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Transactions</h3>
                </div>
                <div class="card-body">
                    <div class="data-table-container">
                        <table class="table" id="recent-transactions-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="spinner"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Quick Stats</h3>
                </div>
                <div class="card-body">
                    <div class="stat-item mb-3">
                        <div class="d-flex justify-between">
                            <span>New Users (Today)</span>
                            <strong class="text-success">+25</strong>
                        </div>
                    </div>
                    <div class="stat-item mb-3">
                        <div class="d-flex justify-between">
                            <span>Documents (Today)</span>
                            <strong class="text-primary">+142</strong>
                        </div>
                    </div>
                    <div class="stat-item mb-3">
                        <div class="d-flex justify-between">
                            <span>Revenue (Today)</span>
                            <strong class="text-success">₹15,420</strong>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="d-flex justify-between">
                            <span>Pending Reviews</span>
                            <strong class="text-warning">8</strong>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Document Status</h3>
                </div>
                <div class="card-body">
                    <canvas id="documentStatsChart"></canvas>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">System Health</h3>
                </div>
                <div class="card-body">
                    <div class="health-item mb-2">
                        <div class="d-flex justify-between align-center">
                            <span>Database</span>
                            <span class="badge badge-success">Online</span>
                        </div>
                    </div>
                    <div class="health-item mb-2">
                        <div class="d-flex justify-between align-center">
                            <span>Storage</span>
                            <span class="badge badge-success">45% Used</span>
                        </div>
                    </div>
                    <div class="health-item mb-2">
                        <div class="d-flex justify-between align-center">
                            <span>Cache</span>
                            <span class="badge badge-success">Active</span>
                        </div>
                    </div>
                    <div class="health-item">
                        <div class="d-flex justify-between align-center">
                            <span>Email Service</span>
                            <span class="badge badge-success">Active</span>
                        </div>
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
