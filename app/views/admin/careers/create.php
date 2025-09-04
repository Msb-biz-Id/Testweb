<?php
$pageTitle = 'Create New Career - Testweb Jersey Admin';
$breadcrumbs = [
    ['title' => 'Careers', 'url' => '/admin/careers'],
    ['title' => 'Create']
];
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Create New Career</h1>
        <p class="text-muted">Add a new job posting to attract talented candidates</p>
    </div>
    <div>
        <a href="/admin/careers" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Careers
        </a>
    </div>
</div>

<!-- Career Form -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Career Information</h5>
            </div>
            <div class="card-body">
                <form action="/admin/careers/store" method="POST" id="careerForm">
                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label for="title" class="form-label">Job Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                                <div class="form-text">Enter the job title (e.g., "Frontend Developer")</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="department" class="form-label">Department *</label>
                                <input type="text" class="form-control" id="department" name="department" required>
                                <div class="form-text">e.g., Technology, Marketing, Design</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="location" class="form-label">Location *</label>
                                <input type="text" class="form-control" id="location" name="location" required>
                                <div class="form-text">e.g., Jakarta Selatan, Remote</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="employment_type" class="form-label">Employment Type *</label>
                                <select class="form-select" id="employment_type" name="employment_type" required>
                                    <option value="full_time">Full Time</option>
                                    <option value="part_time">Part Time</option>
                                    <option value="contract">Contract</option>
                                    <option value="internship">Internship</option>
                                    <option value="freelance">Freelance</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="experience_level" class="form-label">Experience Level *</label>
                                <select class="form-select" id="experience_level" name="experience_level" required>
                                    <option value="entry">Entry Level</option>
                                    <option value="mid">Mid Level</option>
                                    <option value="senior">Senior Level</option>
                                    <option value="executive">Executive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Salary Information -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="salary_min" class="form-label">Minimum Salary</label>
                                <input type="number" class="form-control" id="salary_min" name="salary_min" min="0">
                                <div class="form-text">Leave empty if not specified</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="salary_max" class="form-label">Maximum Salary</label>
                                <input type="number" class="form-control" id="salary_max" name="salary_max" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="currency" class="form-label">Currency</label>
                                <select class="form-select" id="currency" name="currency">
                                    <option value="IDR">IDR (Indonesian Rupiah)</option>
                                    <option value="USD">USD (US Dollar)</option>
                                    <option value="EUR">EUR (Euro)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dates -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="application_deadline" class="form-label">Application Deadline</label>
                                <input type="date" class="form-control" id="application_deadline" name="application_deadline">
                                <div class="form-text">Leave empty for no deadline</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="start_date" class="form-label">Expected Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Featured -->
                    <div class="form-group mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1">
                            <label class="form-check-label" for="featured">
                                Featured Career
                            </label>
                            <div class="form-text">Featured careers appear prominently on the careers page</div>
                        </div>
                    </div>
                    
                    <!-- Job Description -->
                    <div class="form-group mb-3">
                        <label for="description" class="form-label">Job Description *</label>
                        <textarea class="form-control" id="description" name="description" rows="6" required></textarea>
                        <div class="form-text">Provide a detailed description of the role and what the candidate will be doing</div>
                    </div>
                    
                    <!-- Requirements -->
                    <div class="form-group mb-3">
                        <label for="requirements" class="form-label">Requirements *</label>
                        <textarea class="form-control" id="requirements" name="requirements" rows="6" required></textarea>
                        <div class="form-text">List the skills, qualifications, and experience required for this position</div>
                    </div>
                    
                    <!-- Responsibilities -->
                    <div class="form-group mb-3">
                        <label for="responsibilities" class="form-label">Responsibilities *</label>
                        <textarea class="form-control" id="responsibilities" name="responsibilities" rows="6" required></textarea>
                        <div class="form-text">Describe the main duties and responsibilities of this role</div>
                    </div>
                    
                    <!-- Benefits -->
                    <div class="form-group mb-3">
                        <label for="benefits" class="form-label">Benefits & Perks</label>
                        <textarea class="form-control" id="benefits" name="benefits" rows="4"></textarea>
                        <div class="form-text">List the benefits, perks, and advantages of working in this position</div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="/admin/careers" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Career
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- SEO Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">SEO Settings</h5>
            </div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="meta_title" class="form-label">Meta Title</label>
                    <input type="text" class="form-control" id="meta_title" name="meta_title">
                    <div class="form-text">Custom title for search engines (optional)</div>
                </div>
                
                <div class="form-group mb-3">
                    <label for="meta_description" class="form-label">Meta Description</label>
                    <textarea class="form-control" id="meta_description" name="meta_description" rows="3"></textarea>
                    <div class="form-text">Brief description for search engines (optional)</div>
                </div>
                
                <div class="form-group mb-3">
                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords">
                    <div class="form-text">Comma-separated keywords (optional)</div>
                </div>
            </div>
        </div>
        
        <!-- Preview -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Preview</h5>
            </div>
            <div class="card-body">
                <div class="preview-content">
                    <h6 id="preview-title">Job Title</h6>
                    <p class="text-muted mb-2">
                        <span id="preview-department">Department</span> • 
                        <span id="preview-location">Location</span> • 
                        <span id="preview-type">Employment Type</span>
                    </p>
                    <p class="small text-muted" id="preview-description">Job description will appear here...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-generate slug from title
document.getElementById('title').addEventListener('input', function() {
    const title = this.value;
    const slug = title.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    
    // Update preview
    document.getElementById('preview-title').textContent = title || 'Job Title';
});

// Update preview when form fields change
function updatePreview() {
    const title = document.getElementById('title').value;
    const department = document.getElementById('department').value;
    const location = document.getElementById('location').value;
    const employmentType = document.getElementById('employment_type').value;
    const description = document.getElementById('description').value;
    
    document.getElementById('preview-title').textContent = title || 'Job Title';
    document.getElementById('preview-department').textContent = department || 'Department';
    document.getElementById('preview-location').textContent = location || 'Location';
    document.getElementById('preview-type').textContent = employmentType.replace('_', ' ') || 'Employment Type';
    document.getElementById('preview-description').textContent = description ? description.substring(0, 100) + '...' : 'Job description will appear here...';
}

// Add event listeners to form fields
['title', 'department', 'location', 'employment_type', 'description'].forEach(function(fieldId) {
    document.getElementById(fieldId).addEventListener('input', updatePreview);
});

// Form validation
document.getElementById('careerForm').addEventListener('submit', function(e) {
    const requiredFields = ['title', 'department', 'location', 'employment_type', 'experience_level', 'description', 'requirements', 'responsibilities'];
    let isValid = true;
    
    requiredFields.forEach(function(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Please fill in all required fields.');
    }
});
</script>