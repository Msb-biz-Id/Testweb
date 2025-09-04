<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Testweb Jersey - Premium Jersey Collection') ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?= htmlspecialchars($pageDescription ?? 'Jersey berkualitas tinggi dengan desain terbaik untuk sepak bola, basket, dan olahraga lainnya. Custom jersey dengan kualitas premium.') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($pageKeywords ?? 'jersey, baju olahraga, jersey sepak bola, jersey basket, jersey custom, jersey premium, jersey berkualitas') ?>">
    <meta name="author" content="Testweb Jersey">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle ?? 'Testweb Jersey - Premium Jersey Collection') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription ?? 'Jersey berkualitas tinggi dengan desain terbaik untuk sepak bola, basket, dan olahraga lainnya.') ?>">
    <meta property="og:type" content="<?= htmlspecialchars($pageType ?? 'website') ?>">
    <meta property="og:url" content="<?= htmlspecialchars($pageUrl ?? 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($pageImage ?? '/assets/images/og-image.jpg') ?>">
    <meta property="og:site_name" content="Testweb Jersey">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle ?? 'Testweb Jersey - Premium Jersey Collection') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($pageDescription ?? 'Jersey berkualitas tinggi dengan desain terbaik untuk sepak bola, basket, dan olahraga lainnya.') ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($pageImage ?? '/assets/images/og-image.jpg') ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?= htmlspecialchars($pageUrl ?? 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
    
    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/assets/images/site.webmanifest">
    
    <!-- Additional CSS -->
    <?php if (isset($additionalCSS)): ?>
        <?php foreach ($additionalCSS as $css): ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "Testweb Jersey",
        "url": "<?= htmlspecialchars('http://' . $_SERVER['HTTP_HOST']) ?>",
        "logo": "<?= htmlspecialchars('http://' . $_SERVER['HTTP_HOST'] . '/assets/images/logo.png') ?>",
        "description": "Jersey berkualitas tinggi dengan desain terbaik untuk sepak bola, basket, dan olahraga lainnya",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "ID"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+62-812-3456-7890",
            "contactType": "customer service"
        }
    }
    </script>
    
    <!-- Google Analytics -->
    <?php if (isset($config['google_analytics_id']) && !empty($config['google_analytics_id'])): ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($config['google_analytics_id']) ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?= htmlspecialchars($config['google_analytics_id']) ?>');
    </script>
    <?php endif; ?>
</head>
<body>
    <!-- Skip to main content for accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>
    
    <!-- Header -->
    <header id="header" class="header fixed-top">
        <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
            <a href="/" class="logo d-flex align-items-center">
                <img src="/assets/images/logo.png" alt="Testweb Jersey" height="40">
                <span class="logo-text">Testweb Jersey</span>
            </a>
            
            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
                    <li><a class="nav-link scrollto" href="#about">About</a></li>
                    <li><a class="nav-link scrollto" href="#products">Products</a></li>
                    <li><a class="nav-link scrollto" href="#testimonials">Testimonials</a></li>
                    <li><a class="nav-link scrollto" href="#blog">Blog</a></li>
                                            <li><a class="nav-link scrollto" href="#contact">Contact</a></li>
                        <li><a class="nav-link" href="/careers">Careers</a></li>
                    <li><a class="getstarted scrollto" href="#products">Get Started</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>
        </div>
    </header>
    
    <!-- Main Content -->
    <main id="main-content" role="main">
        <?= $content ?>
    </main>
    
    <!-- Footer -->
    <footer id="footer" class="footer">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-5 col-md-12 footer-info">
                    <a href="/" class="logo d-flex align-items-center">
                        <span>Testweb Jersey</span>
                    </a>
                    <p>Jersey berkualitas tinggi dengan desain terbaik untuk kebutuhan olahraga Anda. Kami menyediakan jersey custom dengan kualitas premium.</p>
                    <div class="social-links mt-3">
                        <a href="#" class="twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="linkedin"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-6 footer-links">
                    <h4>Useful Links</h4>
                    <ul>
                        <li><a href="#hero">Home</a></li>
                        <li><a href="#about">About us</a></li>
                        <li><a href="#products">Products</a></li>
                        <li><a href="#testimonials">Testimonials</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-6 footer-links">
                    <h4>Our Services</h4>
                    <ul>
                        <li><a href="#products">Custom Jersey</a></li>
                        <li><a href="#products">Team Jersey</a></li>
                        <li><a href="#products">Sports Apparel</a></li>
                        <li><a href="#contact">Design Service</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
                    <h4>Contact Us</h4>
                    <p>
                        A108 Adam Street <br>
                        New York, NY 535022<br>
                        United States <br><br>
                        <strong>Phone:</strong> +1 5589 55488 55<br>
                        <strong>Email:</strong> info@testwebjersey.com<br>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="container mt-4">
            <div class="copyright">
                &copy; Copyright <strong><span>Testweb Jersey</span></strong>. All Rights Reserved
            </div>
            <div class="credits">
                Designed by <a href="/">Testweb Jersey</a>
            </div>
        </div>
    </footer>
    
    <!-- WhatsApp Button -->
    <a href="https://wa.me/<?= htmlspecialchars($config['whatsapp_number'] ?? '6281234567890') ?>?text=<?= urlencode($config['whatsapp_message'] ?? 'Halo, saya tertarik dengan produk jersey Anda') ?>" 
       class="whatsapp-button" 
       target="_blank" 
       rel="noopener noreferrer"
       aria-label="Contact us on WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    
    <!-- Back to Top Button -->
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="fas fa-arrow-up"></i>
    </a>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS (Animate On Scroll) -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="/assets/js/main.js"></script>
    
    <!-- Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>
    
    <!-- Additional JS -->
    <?php if (isset($additionalJS)): ?>
        <?php foreach ($additionalJS as $js): ?>
            <script src="<?= htmlspecialchars($js) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>