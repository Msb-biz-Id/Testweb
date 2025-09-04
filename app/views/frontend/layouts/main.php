<!DOCTYPE html>
<html lang="id" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- SEO Meta Tags -->
    <title><?= htmlspecialchars($pageTitle ?? 'Testweb Jersey') ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription ?? 'Jersey berkualitas tinggi dengan desain terbaik') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($pageKeywords ?? 'jersey, baju olahraga, jersey sepak bola, jersey basket, jersey custom') ?>">
    <meta name="author" content="<?= htmlspecialchars($config['site_name'] ?? 'Testweb Jersey') ?>">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Indonesian">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle ?? 'Testweb Jersey') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription ?? 'Jersey berkualitas tinggi dengan desain terbaik') ?>">
    <meta property="og:type" content="<?= htmlspecialchars($pageType ?? 'website') ?>">
    <meta property="og:url" content="<?= htmlspecialchars($pageUrl ?? 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($pageImage ?? '/assets/images/og-image.jpg') ?>">
    <meta property="og:site_name" content="<?= htmlspecialchars($config['site_name'] ?? 'Testweb Jersey') ?>">
    <meta property="og:locale" content="id_ID">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle ?? 'Testweb Jersey') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($pageDescription ?? 'Jersey berkualitas tinggi dengan desain terbaik') ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($pageImage ?? '/assets/images/og-image.jpg') ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?= htmlspecialchars($pageUrl ?? 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
    <link rel="manifest" href="/assets/images/site.webmanifest">
    
    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    
    <!-- Structured Data -->
    <?php if (isset($structuredData)): ?>
        <?= $structuredData ?>
    <?php endif; ?>
    
    <!-- Google Analytics -->
    <?php if (isset($config['google_analytics_id']) && !empty($config['google_analytics_id'])): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($config['google_analytics_id']) ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?= htmlspecialchars($config['google_analytics_id']) ?>', {
            page_title: '<?= htmlspecialchars($pageTitle ?? 'Testweb Jersey') ?>',
            page_location: '<?= htmlspecialchars($pageUrl ?? 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>'
        });
    </script>
    <?php endif; ?>
</head>
<body>
    <!-- Skip to content link for accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>
    
    <!-- Header -->
    <header class="navbar navbar-expand-lg navbar-light bg-white shadow-sm" role="banner">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="/">
                <i class="fas fa-tshirt me-2"></i>
                <?= htmlspecialchars($config['site_name'] ?? 'Testweb Jersey') ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <nav class="navbar-nav me-auto" role="navigation" aria-label="Main navigation">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link <?= $_SERVER['REQUEST_URI'] === '/' ? 'active' : '' ?>" href="/" aria-current="<?= $_SERVER['REQUEST_URI'] === '/' ? 'page' : 'false' ?>">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/produk') === 0 ? 'active' : '' ?>" href="/produk" aria-current="<?= strpos($_SERVER['REQUEST_URI'], '/produk') === 0 ? 'page' : 'false' ?>">Produk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/blog') === 0 ? 'active' : '' ?>" href="/blog" aria-current="<?= strpos($_SERVER['REQUEST_URI'], '/blog') === 0 ? 'page' : 'false' ?>">Blog</a>
                        </li>
                    </ul>
                </nav>
                
                <!-- Search Form -->
                <form class="d-flex me-3" action="/search" method="GET" role="search">
                    <label for="search-input" class="visually-hidden">Search</label>
                    <input id="search-input" class="form-control form-control-sm" type="search" name="q" placeholder="Cari produk atau artikel..." value="<?= htmlspecialchars($this->request->get('q', '')) ?>" aria-label="Search">
                    <button class="btn btn-outline-primary btn-sm ms-2" type="submit" aria-label="Search">
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </button>
                </form>
                
                <!-- WhatsApp Button -->
                <a href="https://wa.me/<?= htmlspecialchars($config['whatsapp_number'] ?? '6281234567890') ?>?text=<?= urlencode($config['whatsapp_message'] ?? 'Halo, saya tertarik dengan produk jersey Anda') ?>" 
                   class="btn btn-success" target="_blank" rel="noopener noreferrer" aria-label="Contact us via WhatsApp">
                    <i class="fab fa-whatsapp me-2" aria-hidden="true"></i>
                    Hubungi Kami
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main id="main-content" role="main">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-tshirt me-2"></i>
                        <?= htmlspecialchars($config['site_name'] ?? 'Testweb Jersey') ?>
                    </h5>
                    <p class="text-muted">
                        <?= htmlspecialchars($config['site_description'] ?? 'Jersey berkualitas tinggi dengan desain terbaik') ?>
                    </p>
                </div>
                
                <div class="col-md-4">
                    <h6 class="fw-bold mb-3">Menu</h6>
                    <ul class="list-unstyled">
                        <li><a href="/" class="text-muted text-decoration-none">Beranda</a></li>
                        <li><a href="/produk" class="text-muted text-decoration-none">Produk</a></li>
                        <li><a href="/blog" class="text-muted text-decoration-none">Blog</a></li>
                    </ul>
                </div>
                
                <div class="col-md-4">
                    <h6 class="fw-bold mb-3">Kontak</h6>
                    <p class="text-muted mb-2">
                        <i class="fab fa-whatsapp me-2"></i>
                        <a href="https://wa.me/<?= htmlspecialchars($config['whatsapp_number'] ?? '6281234567890') ?>" 
                           class="text-muted text-decoration-none" target="_blank">
                            <?= htmlspecialchars($config['whatsapp_number'] ?? '6281234567890') ?>
                        </a>
                    </p>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">
                        &copy; <?= date('Y') ?> <?= htmlspecialchars($config['site_name'] ?? 'Testweb Jersey') ?>. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="/admin/login" class="text-muted text-decoration-none">
                        <i class="fas fa-lock me-1"></i>
                        Admin
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/main.js"></script>
    
    <!-- reCAPTCHA -->
    <?php if (isset($config['recaptcha_enabled']) && $config['recaptcha_enabled'] && isset($config['recaptcha_site_key'])): ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>
</body>
</html>