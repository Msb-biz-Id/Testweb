// Main JavaScript for Testweb Jersey

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize lazy loading for images
    initLazyLoading();
    
    // Initialize search functionality
    initSearch();
    
    // Initialize smooth scrolling
    initSmoothScrolling();
    
    // Initialize tooltips
    initTooltips();
    
    // Initialize form validation
    initFormValidation();
    
    // Initialize WhatsApp button
    initWhatsAppButton();
});

// Lazy Loading Implementation
function initLazyLoading() {
    const lazyImages = document.querySelectorAll('img[data-src]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    img.classList.add('loaded');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        lazyImages.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback for older browsers
        lazyImages.forEach(img => {
            img.src = img.dataset.src;
            img.classList.remove('lazy');
            img.classList.add('loaded');
        });
    }
}

// Search Functionality
function initSearch() {
    const searchForm = document.querySelector('form[action="/search"]');
    const searchInput = document.querySelector('input[name="q"]');
    
    if (searchForm && searchInput) {
        // Add search suggestions (if needed)
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (query.length > 2) {
                // Implement search suggestions here
                // fetchSearchSuggestions(query);
            }
        });
        
        // Handle form submission
        searchForm.addEventListener('submit', function(e) {
            const query = searchInput.value.trim();
            if (!query) {
                e.preventDefault();
                searchInput.focus();
            }
        });
    }
}

// Smooth Scrolling
function initSmoothScrolling() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Initialize Bootstrap Tooltips
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Form Validation
function initFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
}

// WhatsApp Button Enhancement
function initWhatsAppButton() {
    const whatsappButtons = document.querySelectorAll('a[href*="wa.me"]');
    
    whatsappButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Track WhatsApp clicks (if analytics is available)
            if (typeof gtag !== 'undefined') {
                gtag('event', 'click', {
                    event_category: 'Contact',
                    event_label: 'WhatsApp'
                });
            }
        });
    });
}

// Utility Functions
function showLoading(element) {
    if (element) {
        element.innerHTML = '<span class="loading"></span> Loading...';
        element.disabled = true;
    }
}

function hideLoading(element, originalText) {
    if (element) {
        element.innerHTML = originalText;
        element.disabled = false;
    }
}

function showAlert(message, type = 'info') {
    const alertContainer = document.getElementById('alert-container') || createAlertContainer();
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    alertContainer.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

function createAlertContainer() {
    const container = document.createElement('div');
    container.id = 'alert-container';
    container.className = 'position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}

// AJAX Helper Functions
function makeRequest(url, options = {}) {
    const defaultOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };
    
    const config = { ...defaultOptions, ...options };
    
    return fetch(url, config)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .catch(error => {
            console.error('Request failed:', error);
            showAlert('Terjadi kesalahan. Silakan coba lagi.', 'danger');
            throw error;
        });
}

// Product Image Zoom (if needed)
function initImageZoom() {
    const productImages = document.querySelectorAll('.product-image');
    
    productImages.forEach(img => {
        img.addEventListener('click', function() {
            // Implement image zoom functionality
            showImageModal(this.src, this.alt);
        });
    });
}

function showImageModal(src, alt) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">${alt}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="${src}" alt="${alt}" class="img-fluid">
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    modal.addEventListener('hidden.bs.modal', function() {
        modal.remove();
    });
}

// Search Suggestions (if needed)
function fetchSearchSuggestions(query) {
    makeRequest(`/api/search/suggestions?q=${encodeURIComponent(query)}`)
        .then(data => {
            if (data.suggestions) {
                showSearchSuggestions(data.suggestions);
            }
        })
        .catch(error => {
            // Silently fail for suggestions
        });
}

function showSearchSuggestions(suggestions) {
    // Implement search suggestions display
    console.log('Search suggestions:', suggestions);
}

// Export functions for global use
window.TestwebJersey = {
    showLoading,
    hideLoading,
    showAlert,
    makeRequest,
    showImageModal
};