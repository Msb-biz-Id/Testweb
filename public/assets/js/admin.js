/**
 * Testweb Jersey Admin JavaScript
 * Enhanced admin functionality with modern UX
 */

$(document).ready(function() {
    'use strict';

    // Initialize all admin features
    initAdminFeatures();
    initDataTables();
    initFileUpload();
    initImagePreview();
    initFormValidation();
    initDashboard();
    initNotifications();
    initSearch();
    initTooltips();
    initConfirmations();
    initAutoSave();
    initKeyboardShortcuts();
});

/**
 * Initialize all admin features
 */
function initAdminFeatures() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Add loading states to buttons
    $('.btn').on('click', function() {
        if ($(this).hasClass('btn-loading')) {
            $(this).prop('disabled', true);
            $(this).html('<span class="spinner"></span> Loading...');
        }
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Initialize popovers
    $('[data-toggle="popover"]').popover();
}

/**
 * Initialize DataTables with enhanced features
 */
function initDataTables() {
    if ($.fn.DataTable) {
        $('.data-table').DataTable({
            responsive: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                infoFiltered: "(filtered from _MAX_ total entries)",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            initComplete: function() {
                // Add custom styling
                $('.dataTables_wrapper').addClass('fade-in');
            }
        });
    }
}

/**
 * Initialize file upload with drag and drop
 */
function initFileUpload() {
    const uploadArea = $('.file-upload-area');
    
    if (uploadArea.length) {
        // Drag and drop events
        uploadArea.on('dragover', function(e) {
            e.preventDefault();
            $(this).addClass('dragover');
        });

        uploadArea.on('dragleave', function(e) {
            e.preventDefault();
            $(this).removeClass('dragover');
        });

        uploadArea.on('drop', function(e) {
            e.preventDefault();
            $(this).removeClass('dragover');
            
            const files = e.originalEvent.dataTransfer.files;
            handleFileUpload(files);
        });

        // File input change
        $('.file-input').on('change', function() {
            const files = this.files;
            handleFileUpload(files);
        });

        // Click to upload
        uploadArea.on('click', function() {
            $('.file-input').click();
        });
    }
}

/**
 * Handle file upload
 */
function handleFileUpload(files) {
    if (files.length > 0) {
        const file = files[0];
        const maxSize = 5 * 1024 * 1024; // 5MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (file.size > maxSize) {
            showAlert('File size must be less than 5MB', 'danger');
            return;
        }

        if (!allowedTypes.includes(file.type)) {
            showAlert('Please select a valid image file (JPEG, PNG, GIF, WebP)', 'danger');
            return;
        }

        // Show preview
        showImagePreview(file);
        
        // Update file input
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        $('.file-input')[0].files = dataTransfer.files;
    }
}

/**
 * Initialize image preview
 */
function initImagePreview() {
    $('.image-preview-container').on('click', '.remove-image', function() {
        $(this).closest('.image-preview-item').fadeOut(300, function() {
            $(this).remove();
        });
    });
}

/**
 * Show image preview
 */
function showImagePreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const previewHtml = `
            <div class="image-preview-item position-relative d-inline-block mr-2 mb-2">
                <img src="${e.target.result}" class="image-preview" alt="Preview">
                <button type="button" class="btn btn-danger btn-sm remove-image position-absolute" style="top: -5px; right: -5px;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        $('.image-preview-container').append(previewHtml);
    };
    reader.readAsDataURL(file);
}

/**
 * Initialize form validation
 */
function initFormValidation() {
    // Real-time validation
    $('.form-control').on('blur', function() {
        validateField($(this));
    });

    // Form submission validation
    $('form').on('submit', function(e) {
        let isValid = true;
        const form = $(this);

        form.find('.form-control[required]').each(function() {
            if (!validateField($(this))) {
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            showAlert('Please fix the errors before submitting', 'danger');
        }
    });
}

/**
 * Validate individual field
 */
function validateField(field) {
    const value = field.val().trim();
    const type = field.attr('type');
    const required = field.attr('required');
    let isValid = true;
    let message = '';

    // Remove existing validation classes
    field.removeClass('is-valid is-invalid');
    field.siblings('.invalid-feedback').remove();

    // Required validation
    if (required && !value) {
        isValid = false;
        message = 'This field is required';
    }

    // Type-specific validation
    if (value && type === 'email') {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            isValid = false;
            message = 'Please enter a valid email address';
        }
    }

    if (value && type === 'url') {
        try {
            new URL(value);
        } catch {
            isValid = false;
            message = 'Please enter a valid URL';
        }
    }

    // Apply validation classes
    if (isValid) {
        field.addClass('is-valid');
    } else {
        field.addClass('is-invalid');
        field.after(`<div class="invalid-feedback">${message}</div>`);
    }

    return isValid;
}

/**
 * Initialize dashboard features
 */
function initDashboard() {
    // Animate stats cards
    $('.stats-card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
        $(this).addClass('fade-in');
    });

    // Initialize charts if Chart.js is available
    if (typeof Chart !== 'undefined') {
        initCharts();
    }

    // Real-time updates
    setInterval(updateDashboardStats, 30000); // Update every 30 seconds
}

/**
 * Initialize charts
 */
function initCharts() {
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
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
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

/**
 * Update dashboard stats
 */
function updateDashboardStats() {
    // This would typically make an AJAX call to get updated stats
    // For now, we'll just add a subtle animation
    $('.stats-number').addClass('pulse');
    setTimeout(() => {
        $('.stats-number').removeClass('pulse');
    }, 1000);
}

/**
 * Initialize notifications
 */
function initNotifications() {
    // Mark notifications as read
    $('.notification-item').on('click', function() {
        $(this).addClass('read');
    });

    // Auto-refresh notifications
    setInterval(refreshNotifications, 60000); // Every minute
}

/**
 * Refresh notifications
 */
function refreshNotifications() {
    // This would typically make an AJAX call to get new notifications
    console.log('Refreshing notifications...');
}

/**
 * Initialize search functionality
 */
function initSearch() {
    const searchInput = $('.search-input');
    
    if (searchInput.length) {
        let searchTimeout;
        
        searchInput.on('input', function() {
            clearTimeout(searchTimeout);
            const query = $(this).val();
            
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });
    }
}

/**
 * Perform search
 */
function performSearch(query) {
    if (query.length < 2) {
        $('.search-results').hide();
        return;
    }

    // This would typically make an AJAX call
    console.log('Searching for:', query);
}

/**
 * Initialize tooltips
 */
function initTooltips() {
    $('[data-toggle="tooltip"]').tooltip({
        trigger: 'hover',
        placement: 'top'
    });
}

/**
 * Initialize confirmation dialogs
 */
function initConfirmations() {
    $('.btn-delete, .btn-danger').on('click', function(e) {
        if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
            e.preventDefault();
        }
    });
}

/**
 * Initialize auto-save functionality
 */
function initAutoSave() {
    const autoSaveForms = $('.auto-save');
    
    autoSaveForms.find('input, textarea, select').on('change', function() {
        const form = $(this).closest('form');
        autoSaveForm(form);
    });
}

/**
 * Auto-save form data
 */
function autoSaveForm(form) {
    const formData = new FormData(form[0]);
    
    // This would typically make an AJAX call to save the form
    console.log('Auto-saving form...', formData);
    
    // Show auto-save indicator
    showAutoSaveIndicator();
}

/**
 * Show auto-save indicator
 */
function showAutoSaveIndicator() {
    const indicator = $('<div class="auto-save-indicator">Auto-saved</div>');
    $('body').append(indicator);
    
    setTimeout(() => {
        indicator.fadeOut(() => indicator.remove());
    }, 2000);
}

/**
 * Initialize keyboard shortcuts
 */
function initKeyboardShortcuts() {
    $(document).on('keydown', function(e) {
        // Ctrl+S to save
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            $('form').submit();
        }
        
        // Ctrl+N for new item
        if (e.ctrlKey && e.key === 'n') {
            e.preventDefault();
            window.location.href = window.location.pathname + '/create';
        }
        
        // Escape to close modals
        if (e.key === 'Escape') {
            $('.modal').modal('hide');
        }
    });
}

/**
 * Show alert message
 */
function showAlert(message, type = 'info') {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="fas fa-${getAlertIcon(type)}"></i> ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
    
    $('.content').prepend(alertHtml);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        $('.alert').fadeOut('slow');
    }, 5000);
}

/**
 * Get alert icon based on type
 */
function getAlertIcon(type) {
    const icons = {
        'success': 'check-circle',
        'danger': 'exclamation-circle',
        'warning': 'exclamation-triangle',
        'info': 'info-circle'
    };
    return icons[type] || 'info-circle';
}

/**
 * Utility function to format currency
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(amount);
}

/**
 * Utility function to format date
 */
function formatDate(date) {
    return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }).format(new Date(date));
}

/**
 * Utility function to truncate text
 */
function truncateText(text, length = 100) {
    if (text.length <= length) return text;
    return text.substr(0, length) + '...';
}

// Export functions for global use
window.TestwebJerseyAdmin = {
    showAlert,
    formatCurrency,
    formatDate,
    truncateText
};