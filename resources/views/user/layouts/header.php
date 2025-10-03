<div class="bg-white border-bottom p-3">
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><?= $pageTitle ?? 'Dashboard' ?></h4>
        <div class="d-flex align-items-center gap-3">
            <a href="/user/documents/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Document
            </a>
            <div class="dropdown">
                <button class="btn btn-link text-decoration-none position-relative" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        3
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header">Notifications</h6></li>
                    <li><a class="dropdown-item" href="#">Document signed</a></li>
                    <li><a class="dropdown-item" href="#">Payment successful</a></li>
                    <li><a class="dropdown-item" href="#">New template available</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-center" href="#">View All</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
