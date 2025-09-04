<?php
$pageTitle = 'Dashboard - Testweb Jersey Admin';
$breadcrumbs = [
    ['title' => 'Dashboard']
];
?>

<!-- Dashboard Stats -->
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon primary">
                        <i class="fas fa-tshirt"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0"><?= $stats['total_products'] ?? 0 ?></h3>
                        <p class="text-muted mb-0">Total Products</p>
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
                        <i class="fas fa-blog"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0"><?= $stats['total_posts'] ?? 0 ?></h3>
                        <p class="text-muted mb-0">Blog Posts</p>
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
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0"><?= $stats['total_users'] ?? 0 ?></h3>
                        <p class="text-muted mb-0">Total Users</p>
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
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0"><?= $stats['total_views'] ?? 0 ?></h3>
                        <p class="text-muted mb-0">Page Views</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mt-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sales Overview</h3>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Product Categories</h3>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="productsChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row mt-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Products</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($recent_products) && !empty($recent_products)): ?>
                                <?php foreach ($recent_products as $product): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?= htmlspecialchars($product['image'] ?? '/assets/images/no-image.jpg') ?>" 
                                                     alt="<?= htmlspecialchars($product['name']) ?>" 
                                                     class="img-thumbnail me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0"><?= htmlspecialchars($product['name']) ?></h6>
                                                    <small class="text-muted"><?= htmlspecialchars($product['sku'] ?? '') ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></td>
                                        <td>
                                            <span class="badge badge-<?= $product['status'] === 'active' ? 'success' : 'secondary' ?>">
                                                <?= ucfirst($product['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($product['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No recent products</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Blog Posts</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($recent_posts) && !empty($recent_posts)): ?>
                                <?php foreach ($recent_posts as $post): ?>
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-0"><?= htmlspecialchars($post['title']) ?></h6>
                                                <small class="text-muted"><?= htmlspecialchars(substr($post['excerpt'], 0, 50)) ?>...</small>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($post['author_name'] ?? 'Admin') ?></td>
                                        <td>
                                            <span class="badge badge-<?= $post['status'] === 'published' ? 'success' : 'warning' ?>">
                                                <?= ucfirst($post['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($post['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No recent posts</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="/admin/products/create" class="btn btn-primary w-100">
                            <i class="fas fa-plus me-2"></i>Add New Product
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="/admin/posts/create" class="btn btn-success w-100">
                            <i class="fas fa-edit me-2"></i>Create Blog Post
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="/admin/media" class="btn btn-info w-100">
                            <i class="fas fa-images me-2"></i>Manage Media
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="/admin/settings" class="btn btn-warning w-100">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Information -->
<div class="row mt-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">System Information</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <strong>PHP Version:</strong>
                    </div>
                    <div class="col-6">
                        <?= PHP_VERSION ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <strong>Server:</strong>
                    </div>
                    <div class="col-6">
                        <?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <strong>Database:</strong>
                    </div>
                    <div class="col-6">
                        MySQL
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <strong>Last Backup:</strong>
                    </div>
                    <div class="col-6">
                        <?= date('M d, Y H:i') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Activity</h3>
            </div>
            <div class="card-body">
                <div class="activity-feed">
                    <?php if (isset($recent_activity) && !empty($recent_activity)): ?>
                        <?php foreach ($recent_activity as $activity): ?>
                            <div class="activity-item d-flex align-items-start mb-3">
                                <div class="activity-icon me-3">
                                    <i class="fas fa-<?= $activity['icon'] ?? 'circle' ?> text-<?= $activity['color'] ?? 'primary' ?>"></i>
                                </div>
                                <div class="activity-content">
                                    <p class="mb-1"><?= htmlspecialchars($activity['description']) ?></p>
                                    <small class="text-muted"><?= date('M d, Y H:i', strtotime($activity['created_at'])) ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center text-muted">
                            <i class="fas fa-history fa-2x mb-2"></i>
                            <p>No recent activity</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional JavaScript for Charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    if (typeof Chart !== 'undefined') {
        // Sales Chart
        const salesCtx = document.getElementById('salesChart');
        if (salesCtx) {
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Sales',
                        data: [12, 19, 3, 5, 2, 3],
                        borderColor: '#1e3a8a',
                        backgroundColor: 'rgba(30, 58, 138, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Products Chart
        const productsCtx = document.getElementById('productsChart');
        if (productsCtx) {
            new Chart(productsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Football', 'Basketball', 'Custom', 'Training'],
                    datasets: [{
                        data: [30, 25, 20, 25],
                        backgroundColor: [
                            '#1e3a8a',
                            '#dc2626',
                            '#f59e0b',
                            '#10b981'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }
});
</script>