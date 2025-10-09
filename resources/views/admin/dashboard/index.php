<div class="content-header">
    <h1>Dashboard</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(37,99,235,0.1); color: #2563eb;">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value"><?= number_format($stats['total_users'] ?? 0) ?></div>
            <div class="stat-label">Total Users</div>
            <div class="mt-2">
                <small class="text-success">
                    <i class="fas fa-arrow-up"></i> <?= $stats['users_growth'] ?? 0 ?>% this month
                </small>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(16,185,129,0.1); color: #10b981;">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-value"><?= number_format($stats['total_documents'] ?? 0) ?></div>
            <div class="stat-label">Documents</div>
            <div class="mt-2">
                <small class="text-success">
                    <i class="fas fa-arrow-up"></i> <?= $stats['docs_growth'] ?? 0 ?>% this month
                </small>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;">
                <i class="fas fa-rupee-sign"></i>
            </div>
            <div class="stat-value">₹<?= number_format($stats['total_revenue'] ?? 0, 2) ?></div>
            <div class="stat-label">Total Revenue</div>
            <div class="mt-2">
                <small class="text-success">
                    <i class="fas fa-arrow-up"></i> <?= $stats['revenue_growth'] ?? 0 ?>% this month
                </small>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(139,92,246,0.1); color: #8b5cf6;">
                <i class="fas fa-crown"></i>
            </div>
            <div class="stat-value"><?= number_format($stats['active_subscriptions'] ?? 0) ?></div>
            <div class="stat-label">Active Subscriptions</div>
            <div class="mt-2">
                <small class="text-success">
                    <i class="fas fa-arrow-up"></i> <?= $stats['subs_growth'] ?? 0 ?>% this month
                </small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Revenue Overview</h5>
                <select class="form-select form-select-sm" style="width: auto;" id="revenueFilter">
                    <option value="7">Last 7 Days</option>
                    <option value="30" selected>Last 30 Days</option>
                    <option value="90">Last 90 Days</option>
                    <option value="365">Last Year</option>
                </select>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Document Types</h5>
            </div>
            <div class="card-body">
                <canvas id="documentTypeChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Users</h5>
                <a href="/admin/users" class="btn btn-sm btn-link">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentUsers ?? [] as $user): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-2" style="width: 32px; height: 32px; font-size: 14px;">
                                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                        </div>
                                        <strong><?= htmlspecialchars($user['name']) ?></strong>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><span class="badge bg-primary"><?= htmlspecialchars($user['role']) ?></span></td>
                                <td>
                                    <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($user['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Transactions</h5>
                <a href="/admin/transactions" class="btn btn-sm btn-link">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentTransactions ?? [] as $txn): ?>
                            <tr>
                                <td><code><?= htmlspecialchars($txn['transaction_id']) ?></code></td>
                                <td><?= htmlspecialchars($txn['user_name']) ?></td>
                                <td><strong>₹<?= number_format($txn['amount'], 2) ?></strong></td>
                                <td>
                                    <span class="badge bg-<?= $txn['status'] === 'completed' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($txn['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y', strtotime($txn['created_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const ctx1 = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx1, {
    type: 'line',
    data: {
        labels: <?= json_encode($revenueLabels ?? []) ?>,
        datasets: [{
            label: 'Revenue',
            data: <?= json_encode($revenueData ?? []) ?>,
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37,99,235,0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₹' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

const ctx2 = document.getElementById('documentTypeChart').getContext('2d');
const documentTypeChart = new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($documentTypeLabels ?? []) ?>,
        datasets: [{
            data: <?= json_encode($documentTypeData ?? []) ?>,
            backgroundColor: [
                '#2563eb',
                '#10b981',
                '#f59e0b',
                '#8b5cf6',
                '#ef4444'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

document.getElementById('revenueFilter').addEventListener('change', function() {
    const days = this.value;
    fetch(`/admin/dashboard/revenue-data?days=${days}`)
        .then(response => response.json())
        .then(data => {
            revenueChart.data.labels = data.labels;
            revenueChart.data.datasets[0].data = data.data;
            revenueChart.update();
        });
});
</script>
