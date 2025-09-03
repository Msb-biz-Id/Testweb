<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Testweb Jersey') ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription ?? 'Jersey berkualitas tinggi dengan desain terbaik') ?>">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle ?? 'Testweb Jersey') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription ?? 'Jersey berkualitas tinggi dengan desain terbaik') ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    
    <!-- Google Analytics -->
    <?php if (isset($config['google_analytics_id']) && !empty($config['google_analytics_id'])): ?>
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
    <!-- Header -->
    <header class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="/">
                <i class="fas fa-tshirt me-2"></i>
                <?= htmlspecialchars($config['site_name'] ?? 'Testweb Jersey') ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/produk">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/blog">Blog</a>
                    </li>
                </ul>
                
                <!-- Search Form -->
                <form class="d-flex me-3" action="/search" method="GET">
                    <input class="form-control form-control-sm" type="search" name="q" placeholder="Cari produk atau artikel..." value="<?= htmlspecialchars($this->request->get('q', '')) ?>">
                    <button class="btn btn-outline-primary btn-sm ms-2" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                
                <!-- WhatsApp Button -->
                <a href="https://wa.me/<?= htmlspecialchars($config['whatsapp_number'] ?? '6281234567890') ?>?text=<?= urlencode($config['whatsapp_message'] ?? 'Halo, saya tertarik dengan produk jersey Anda') ?>" 
                   class="btn btn-success" target="_blank">
                    <i class="fab fa-whatsapp me-2"></i>
                    Hubungi Kami
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
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