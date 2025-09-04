<?php
$pageTitle = 'Job Applications - Testweb Jersey Admin';
$breadcrumbs = [
    ['title' => 'Careers', 'url' => '/admin/careers'],
    ['title' => 'Applications']
];
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Job Applications</h1>
        <p class="text-muted">Manage and review job applications</p>
    </div>
    <div>
        <a href="/admin/careers" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Careers
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
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0"><?= $stats['total'] ?? 0 ?></h3>
                        <p class="text-muted mb-0">Total Applications</p>
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
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0"><?= $stats['by_status'][0]['count'] ?? 0 ?></h3>
                        <p class="text-muted mb-0">Pending</p>
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
                        <h3 class="mb-0"><?= $stats['recent'] ?? 0 ?></h3>
                        <p class="text-muted mb-0">This Month</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon info">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0"><?= count($stats['by_career'] ?? []) ?></h3>
                        <p class="text-muted mb-0">Positions</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/admin/careers/applications" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Career Position</label>
                <select name="career_id" class="form-select">
                    <option value="">All Positions</option>
                    <?php if (!empty($careers)): ?>
                        <?php foreach ($careers as $career): ?>
                            <option value="<?= $career['id'] ?>" <?= ($filters['career_id'] ?? '') == $career['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($career['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="reviewed" <?= ($filters['status'] ?? '') === 'reviewed' ? 'selected' : '' ?>>Reviewed</option>
                    <option value="shortlisted" <?= ($filters['status'] ?? '') === 'shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
                    <option value="interviewed" <?= ($filters['status'] ?? '') === 'interviewed' ? 'selected' : '' ?>>Interviewed</option>
                    <option value="accepted" <?= ($filters['status'] ?? '') === 'accepted' ? 'selected' : '' ?>>Accepted</option>
                    <option value="rejected" <?= ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search by name, email, or position..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
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

<!-- Applications Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Applications List</h5>
        <div>
            <button class="btn btn-success btn-sm" onclick="exportApplications()">
                <i class="fas fa-download"></i> Export CSV
            </button>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($applications)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Applied Date</th>
                            <th>Expected Salary</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $application): ?>
                        <tr>
                            <td>
                                <div>
                                    <h6 class="mb-1"><?= htmlspecialchars($application['full_name']) ?></h6>
                                    <small class="text-muted"><?= htmlspecialchars($application['email']) ?></small>
                                    <br>
                                    <small class="text-muted"><?= htmlspecialchars($application['phone']) ?></small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <h6 class="mb-1"><?= htmlspecialchars($application['career_title']) ?></h6>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($application['department']) ?></td>
                            <td>
                                <span class="badge badge-<?= $application['status'] === 'pending' ? 'warning' : ($application['status'] === 'accepted' ? 'success' : ($application['status'] === 'rejected' ? 'danger' : 'info')) ?>">
                                    <?= ucfirst($application['status']) ?>
                                </span>
                            </td>
                            <td><?= date('M d, Y', strtotime($application['applied_at'])) ?></td>
                            <td>
                                <?php if ($application['expected_salary']): ?>
                                    Rp <?= number_format($application['expected_salary'], 0, ',', '.') ?>
                                <?php else: ?>
                                    <span class="text-muted">Not specified</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/admin/careers/applications/view/<?= $application['id'] ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="updateStatus(<?= $application['id'] ?>, '<?= $application['status'] ?>')" title="Update Status">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <?php if ($application['resume_file']): ?>
                                        <a href="/uploads/resumes/<?= $application['resume_file'] ?>" class="btn btn-sm btn-outline-info" target="_blank" title="View Resume">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteApplication(<?= $application['id'] ?>)" title="Delete">
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
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <h5>No applications found</h5>
                <p class="text-muted">No job applications match your current filters.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Update Application Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/admin/careers/applications/update-status" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="applicationId" name="id">
                    
                    <div class="form-group mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="reviewed">Reviewed</option>
                            <option value="shortlisted">Shortlisted</option>
                            <option value="interviewed">Interviewed</option>
                            <option value="accepted">Accepted</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Add any notes about this application..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
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
                <p>Are you sure you want to delete this application? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
let applicationIdToDelete = null;

function updateStatus(id, currentStatus) {
    document.getElementById('applicationId').value = id;
    document.getElementById('status').value = currentStatus;
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

function deleteApplication(id) {
    applicationIdToDelete = id;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function exportApplications() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = '/admin/careers/applications/export?' + params.toString();
}

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (applicationIdToDelete) {
        window.location.href = '/admin/careers/applications/delete/' + applicationIdToDelete;
    }
});
</script>