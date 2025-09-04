<?php
$pageTitle = 'SEO Dashboard - Testweb Jersey Admin';
$breadcrumbs = [
    ['title' => 'SEO Dashboard']
];
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">SEO Dashboard</h1>
        <p class="text-muted">RankMath-style SEO analytics and optimization</p>
    </div>
    <div>
        <button class="btn btn-primary" onclick="refreshSEOData()">
            <i class="fas fa-sync-alt"></i> Refresh Data
        </button>
    </div>
</div>

<!-- SEO Score Overview -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon success">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0">85%</h3>
                        <p class="text-muted mb-0">Overall SEO Score</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon primary">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0">25</h3>
                        <p class="text-muted mb-0">Indexed Pages</p>
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
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0">2</h3>
                        <p class="text-muted mb-0">Issues Found</p>
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
                        <i class="fas fa-link"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0">5</h3>
                        <p class="text-muted mb-0">Active Redirects</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SEO Analysis -->
<div class="row">
    <div class="col-lg-8">
        <!-- Page Analysis -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Page SEO Analysis</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Page</th>
                                <th>SEO Score</th>
                                <th>Issues</th>
                                <th>Last Analyzed</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div>
                                        <h6 class="mb-1">Homepage</h6>
                                        <small class="text-muted">/</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-success">95%</span>
                                </td>
                                <td>
                                    <span class="badge badge-warning">1</span>
                                </td>
                                <td>2 hours ago</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="analyzePage('/')">
                                        <i class="fas fa-search"></i> Analyze
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <h6 class="mb-1">Products</h6>
                                        <small class="text-muted">/products</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-success">88%</span>
                                </td>
                                <td>
                                    <span class="badge badge-success">0</span>
                                </td>
                                <td>1 day ago</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="analyzePage('/products')">
                                        <i class="fas fa-search"></i> Analyze
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <h6 class="mb-1">Careers</h6>
                                        <small class="text-muted">/careers</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-warning">75%</span>
                                </td>
                                <td>
                                    <span class="badge badge-danger">3</span>
                                </td>
                                <td>3 days ago</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="analyzePage('/careers')">
                                        <i class="fas fa-search"></i> Analyze
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Recent Issues -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent SEO Issues</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Missing meta descriptions</h6>
                            <p class="mb-1 text-muted">3 pages are missing meta descriptions</p>
                            <small class="text-muted">2 hours ago</small>
                        </div>
                        <span class="badge badge-warning">Medium</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Images without alt tags</h6>
                            <p class="mb-1 text-muted">2 images are missing alt attributes</p>
                            <small class="text-muted">1 day ago</small>
                        </div>
                        <span class="badge badge-danger">High</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Broken internal link</h6>
                            <p class="mb-1 text-muted">1 internal link is broken</p>
                            <small class="text-muted">3 days ago</small>
                        </div>
                        <span class="badge badge-danger">High</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- SEO Tools -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">SEO Tools</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary" onclick="generateSitemap()">
                        <i class="fas fa-sitemap"></i> Generate Sitemap
                    </button>
                    <button class="btn btn-outline-secondary" onclick="checkBrokenLinks()">
                        <i class="fas fa-link"></i> Check Broken Links
                    </button>
                    <button class="btn btn-outline-info" onclick="optimizeImages()">
                        <i class="fas fa-images"></i> Optimize Images
                    </button>
                    <button class="btn btn-outline-warning" onclick="analyzeKeywords()">
                        <i class="fas fa-key"></i> Keyword Analysis
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Schema Markup -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Schema Markup</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Website Schema</span>
                        <span class="badge badge-success">Active</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Organization Schema</span>
                        <span class="badge badge-success">Active</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Product Schema</span>
                        <span class="badge badge-success">Active</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Job Posting Schema</span>
                        <span class="badge badge-success">Active</span>
                    </div>
                </div>
                <button class="btn btn-sm btn-primary w-100" onclick="manageSchema()">
                    <i class="fas fa-cog"></i> Manage Schema
                </button>
            </div>
        </div>
        
        <!-- Redirect Manager -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Redirect Manager</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Active Redirects</span>
                        <span class="badge badge-primary">5</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>404 Errors</span>
                        <span class="badge badge-warning">2</span>
                    </div>
                </div>
                <button class="btn btn-sm btn-primary w-100" onclick="manageRedirects()">
                    <i class="fas fa-exchange-alt"></i> Manage Redirects
                </button>
            </div>
        </div>
    </div>
</div>

<!-- SEO Analysis Modal -->
<div class="modal fade" id="seoAnalysisModal" tabindex="-1" aria-labelledby="seoAnalysisModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="seoAnalysisModalLabel">SEO Analysis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="seoAnalysisContent">
                    <!-- Analysis content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="fixIssues()">Fix Issues</button>
            </div>
        </div>
    </div>
</div>

<script>
function refreshSEOData() {
    // Refresh SEO data
    location.reload();
}

function analyzePage(url) {
    // Show analysis modal
    const modal = new bootstrap.Modal(document.getElementById('seoAnalysisModal'));
    document.getElementById('seoAnalysisModalLabel').textContent = 'SEO Analysis - ' + url;
    
    // Load analysis content
    document.getElementById('seoAnalysisContent').innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Analyzing page SEO...</p>
        </div>
    `;
    
    modal.show();
    
    // Simulate analysis
    setTimeout(() => {
        document.getElementById('seoAnalysisContent').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>SEO Score: <span class="badge badge-success">85%</span></h6>
                    <h6>Grade: <span class="badge badge-success">B</span></h6>
                </div>
                <div class="col-md-6">
                    <h6>Issues Found: <span class="badge badge-warning">2</span></h6>
                    <h6>Good Practices: <span class="badge badge-success">8</span></h6>
                </div>
            </div>
            <hr>
            <h6>Issues:</h6>
            <ul class="list-unstyled">
                <li><i class="fas fa-exclamation-triangle text-warning"></i> Meta description too short</li>
                <li><i class="fas fa-exclamation-triangle text-warning"></i> Missing H2 heading</li>
            </ul>
            <h6>Suggestions:</h6>
            <ul class="list-unstyled">
                <li><i class="fas fa-lightbulb text-info"></i> Add more internal links</li>
                <li><i class="fas fa-lightbulb text-info"></i> Optimize images for faster loading</li>
            </ul>
        `;
    }, 2000);
}

function generateSitemap() {
    // Generate sitemap
    alert('Sitemap generated successfully!');
}

function checkBrokenLinks() {
    // Check broken links
    alert('Broken links check completed!');
}

function optimizeImages() {
    // Optimize images
    alert('Image optimization completed!');
}

function analyzeKeywords() {
    // Analyze keywords
    alert('Keyword analysis completed!');
}

function manageSchema() {
    // Manage schema markup
    alert('Schema management opened!');
}

function manageRedirects() {
    // Manage redirects
    alert('Redirect manager opened!');
}

function fixIssues() {
    // Fix SEO issues
    alert('Issues fixed successfully!');
}
</script>