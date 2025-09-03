<?php
// Set content variable for layout
$content = ob_get_clean();
ob_start();
?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Jersey Berkualitas Tinggi
                </h1>
                <p class="lead mb-4">
                    Temukan koleksi jersey terbaik dengan desain eksklusif dan kualitas premium. 
                    Cocok untuk berbagai aktivitas olahraga dan fashion.
                </p>
                <a href="/produk" class="btn btn-light btn-lg">
                    <i class="fas fa-shopping-bag me-2"></i>
                    Lihat Produk
                </a>
            </div>
            <div class="col-lg-6">
                <img src="/assets/images/hero-jersey.jpg" alt="Jersey Premium" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<?php if (!empty($featuredProducts)): ?>
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-5">Produk Unggulan</h2>
            </div>
        </div>
        
        <div class="row">
            <?php foreach ($featuredProducts as $product): ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="/uploads/<?= htmlspecialchars($product['featured_image'] ?? 'default-product.jpg') ?>" 
                         class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" 
                         style="height: 250px; object-fit: cover;">
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="card-text text-muted flex-grow-1">
                            <?= htmlspecialchars($product['short_description'] ?? substr($product['description'], 0, 100) . '...') ?>
                        </p>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h5 text-primary mb-0">
                                    Rp <?= number_format($product['price'], 0, ',', '.') ?>
                                </span>
                                <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                                <span class="badge bg-danger">Sale</span>
                                <?php endif; ?>
                            </div>
                            
                            <a href="/produk/<?= htmlspecialchars($product['slug']) ?>" 
                               class="btn btn-primary w-100">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="/produk" class="btn btn-outline-primary btn-lg">
                Lihat Semua Produk
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Testimonials -->
<?php if (!empty($testimonials)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-5">Testimoni Pelanggan</h2>
            </div>
        </div>
        
        <div class="row">
            <?php foreach ($testimonials as $testimonial): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?= $i <= $testimonial['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        
                        <p class="card-text">"<?= htmlspecialchars($testimonial['testimonial']) ?>"</p>
                        
                        <div class="mt-auto">
                            <h6 class="card-title mb-1"><?= htmlspecialchars($testimonial['client_name']) ?></h6>
                            <small class="text-muted">
                                <?= htmlspecialchars($testimonial['client_position']) ?>
                                <?php if ($testimonial['client_company']): ?>
                                    - <?= htmlspecialchars($testimonial['client_company']) ?>
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Client Logos -->
<?php if (!empty($clientLogos)): ?>
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-5">Klien Kami</h2>
            </div>
        </div>
        
        <div class="row align-items-center">
            <?php foreach ($clientLogos as $logo): ?>
            <div class="col-lg-2 col-md-3 col-6 mb-4">
                <div class="text-center">
                    <img src="/uploads/<?= htmlspecialchars($logo['logo_path']) ?>" 
                         alt="<?= htmlspecialchars($logo['alt_text'] ?: $logo['company_name']) ?>" 
                         class="img-fluid" style="max-height: 80px; filter: grayscale(100%); opacity: 0.7;">
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Recent Posts -->
<?php if (!empty($recentPosts)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-5">Artikel Terbaru</h2>
            </div>
        </div>
        
        <div class="row">
            <?php foreach ($recentPosts as $post): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <?php if ($post['featured_image']): ?>
                    <img src="/uploads/<?= htmlspecialchars($post['featured_image']) ?>" 
                         class="card-img-top" alt="<?= htmlspecialchars($post['title']) ?>" 
                         style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
                        <p class="card-text text-muted flex-grow-1">
                            <?= htmlspecialchars($post['excerpt'] ?? substr(strip_tags($post['content']), 0, 120) . '...') ?>
                        </p>
                        
                        <div class="mt-auto">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                <?= date('d M Y', strtotime($post['published_at'] ?: $post['created_at'])) ?>
                            </small>
                            
                            <a href="/blog/<?= htmlspecialchars($post['slug']) ?>" 
                               class="btn btn-outline-primary btn-sm mt-2">
                                Baca Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="/blog" class="btn btn-outline-primary btn-lg">
                Lihat Semua Artikel
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>