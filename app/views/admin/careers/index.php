<?php
$pageTitle = 'Manage Careers - Testweb Jersey Admin';
$breadcrumbs = [
    ['title' => 'Careers']
];
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Manage Careers</h1>
        <p class="text-muted">Manage job postings and career opportunities</p>
    </div>
    <div>
        <a href="/admin/careers/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Career
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon primary">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0"><?= $stats['total'] ?? 0 ?></h3>
                        <p class="text-muted mb-0">Total Careers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0"><?= $stats['active'] ?? 0 ?></h3>
                        <p class="text-muted mb-0">Active</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon warning">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0"><?= $stats['featured'] ?? 0 ?></h3>
                        <p class="text-muted mb-0">Featured</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon danger">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0"><?= $stats['applications'] ?? 0 ?></h3>
                        <p class="text-muted mb-0">Applications</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/admin/careers" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    <option value="closed" <?= ($filters['status'] ?? '') === 'closed' ? 'selected' : '' ?>>Closed</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label class="form-label">Department</label>
                <select name="department" class="form-select">
                    <option value="">All Departments</option>
                    <?php if (!empty($stats['by_department'])): ?>
                        <?php foreach ($stats['by_department'] as $dept): ?>
                            <option value="<?= htmlspecialchars($dept['department']) ?>" <?= ($filters['department'] ?? '') === $dept['department'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dept['department']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search careers..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
            </div>
            
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Careers Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Careers List</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($careers)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Department</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Applications</th>
                            <th>Views</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($careers as $career): ?>
                        <tr>
                            <td>
                                <div>
                                    <h6 class="mb-1"><?= htmlspecialchars($career['title']) ?></h6>
                                    <small class="text-muted"><?= htmlspecialchars($career['location']) ?></small>
                                    <?php if ($career['featured']): ?>
                                        <span class="badge badge-warning ms-2">Featured</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($career['department']) ?></td>
                            <td>
                                <span class="badge badge-info"><?= ucfirst($career['employment_type']) ?></span>
                            </td>
                            <td>
                                <span class="badge badge-<?= $career['status'] === 'active' ? 'success' : ($career['status'] === 'inactive' ? 'warning' : 'secondary') ?>">
                                    <?= ucfirst($career['status']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-primary"><?= number_format($career['application_count']) ?></span>
                            </td>
                            <td><?= number_format($career['views']) ?></td>
                            <td><?= date('M d, Y', strtotime($career['created_at'])) ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/careers/<?= $career['slug'] ?>" class="btn btn-sm btn-outline-primary" target="_blank" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/admin/careers/edit/<?= $career['id'] ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/admin/careers/applications?career_id=<?= $career['id'] ?>" class="btn btn-sm btn-outline-info" title="Applications">
                                        <i class="fas fa-users"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteCareer(<?= $career['id'] ?>)" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                <h5>No careers found</h5>
                <p class="text-muted">Start by creating your first career posting.</p>
                <a href="/admin/careers/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Career
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this career posting? This action cannot be undone.</p>
                <p class="text-muted">All associated applications will also be deleted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
let careerIdToDelete = null;

function deleteCareer(id) {
    careerIdToDelete = id;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (careerIdToDelete) {
        window.location.href = '/admin/careers/delete/' + careerIdToDelete;
    }
});
</script>