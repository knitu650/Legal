<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>User Management</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </nav>
        </div>
        <a href="/admin/users/create" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Add New User
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">All Users</h5>
            </div>
            <div class="col-md-6">
                <div class="d-flex gap-2 justify-content-md-end">
                    <select class="form-select form-select-sm" id="roleFilter" style="width: auto;">
                        <option value="">All Roles</option>
                        <option value="user">User</option>
                        <option value="lawyer">Lawyer</option>
                        <option value="franchise">Franchise</option>
                        <option value="admin">Admin</option>
                    </select>
                    <select class="form-select form-select-sm" id="statusFilter" style="width: auto;">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                    <button class="btn btn-sm btn-outline-secondary" id="exportBtn">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="usersTable">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Documents</th>
                        <th>Subscription</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users ?? [] as $user): ?>
                    <tr>
                        <td>
                            <input type="checkbox" class="user-checkbox" value="<?= $user['id'] ?>">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-2" style="width: 36px; height: 36px; font-size: 14px;">
                                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <strong><?= htmlspecialchars($user['name']) ?></strong>
                                    <?php if ($user['email_verified']): ?>
                                        <i class="fas fa-check-circle text-success ms-1" title="Verified"></i>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><span class="badge bg-primary"><?= ucfirst($user['role']) ?></span></td>
                        <td>
                            <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : ($user['status'] === 'suspended' ? 'danger' : 'secondary') ?>">
                                <?= ucfirst($user['status']) ?>
                            </span>
                        </td>
                        <td><?= $user['document_count'] ?></td>
                        <td>
                            <?php if ($user['subscription']): ?>
                                <span class="badge bg-warning"><?= ucfirst($user['subscription']) ?></span>
                            <?php else: ?>
                                <span class="text-muted">None</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="/admin/users/view/<?= $user['id'] ?>" class="btn btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/admin/users/edit/<?= $user['id'] ?>" class="btn btn-outline-secondary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-outline-danger" onclick="deleteUser(<?= $user['id'] ?>)" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
let table;

function initDataTable() {
    table = $('#usersTable').DataTable({
        pageLength: 25,
        order: [[7, 'desc']],
        columnDefs: [
            { orderable: false, targets: [0, 8] }
        ]
    });
}

document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.user-checkbox').forEach(cb => {
        cb.checked = this.checked;
    });
});

document.getElementById('roleFilter').addEventListener('change', function() {
    const role = this.value;
    table.column(3).search(role).draw();
});

document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    table.column(4).search(status).draw();
});

document.getElementById('exportBtn').addEventListener('click', function() {
    window.location.href = '/admin/users/export';
});

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        fetch(`/admin/users/delete/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('User deleted successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the user.');
        });
    }
}
</script>
