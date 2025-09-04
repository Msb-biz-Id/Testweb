<?php
$pageTitle = 'Karir & Lowongan Kerja - Testweb Jersey';
$pageDescription = 'Bergabunglah dengan tim Testweb Jersey! Temukan lowongan kerja terbaru di bidang teknologi, marketing, design, dan lainnya.';
$pageKeywords = 'karir, lowongan kerja, testweb jersey, job vacancy, career opportunity';
?>

<!-- Hero Section -->
<section id="hero" class="d-flex align-items-center" style="background: linear-gradient(135deg, rgba(30, 58, 138, 0.9), rgba(220, 38, 38, 0.8)), url('/assets/images/career-hero-bg.jpg'); background-size: cover; background-position: center; padding-top: 100px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 data-aos="fade-up">Bergabunglah dengan Tim Kami</h1>
                <h2 data-aos="fade-up" data-aos-delay="200">Temukan peluang karir yang tepat dan kembangkan potensi Anda bersama Testweb Jersey</h2>
                <div data-aos="fade-up" data-aos-delay="400">
                    <a href="#careers" class="btn-get-started scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                        <span>Lihat Lowongan</span>
                        <i class="fas fa-arrow-down"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h3><?= $stats['active'] ?? 0 ?></h3>
                    <p>Lowongan Aktif</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3><?= $stats['applications'] ?? 0 ?></h3>
                    <p>Pelamar</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3><?= count($stats['by_department'] ?? []) ?></h3>
                    <p>Departemen</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3><?= $stats['featured'] ?? 0 ?></h3>
                    <p>Lowongan Unggulan</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Careers Section -->
<?php if (!empty($featuredCareers)): ?>
<section class="featured-careers py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 data-aos="fade-up">Lowongan Unggulan</h2>
            <p data-aos="fade-up" data-aos-delay="200">Posisi-posisi terbaik yang sedang kami buka</p>
        </div>
        
        <div class="row">
            <?php foreach ($featuredCareers as $index => $career): ?>
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="<?= ($index + 1) * 100 ?>">
                <div class="career-card featured">
                    <div class="career-badge">
                        <i class="fas fa-star"></i> Featured
                    </div>
                    <div class="career-content">
                        <h3><a href="/careers/<?= $career['slug'] ?>"><?= htmlspecialchars($career['title']) ?></a></h3>
                        <div class="career-meta">
                            <span class="department"><i class="fas fa-building"></i> <?= htmlspecialchars($career['department']) ?></span>
                            <span class="location"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($career['location']) ?></span>
                            <span class="type"><i class="fas fa-clock"></i> <?= ucfirst($career['employment_type']) ?></span>
                        </div>
                        <p><?= htmlspecialchars(substr(strip_tags($career['description']), 0, 120)) ?>...</p>
                        <div class="career-footer">
                            <span class="experience-level"><?= ucfirst($career['experience_level']) ?> Level</span>
                            <a href="/careers/<?= $career['slug'] ?>" class="btn-apply">Apply Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Search & Filter Section -->
<section id="careers" class="careers-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="filter-sidebar">
                    <h4>Filter Lowongan</h4>
                    
                    <form method="GET" action="/careers" class="filter-form">
                        <!-- Search -->
                        <div class="form-group mb-3">
                            <label>Pencarian</label>
                            <input type="text" name="search" class="form-control" placeholder="Cari posisi..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                        </div>
                        
                        <!-- Employment Type -->
                        <div class="form-group mb-3">
                            <label>Tipe Pekerjaan</label>
                            <select name="employment_type" class="form-control">
                                <option value="">Semua Tipe</option>
                                <option value="full_time" <?= ($filters['employment_type'] ?? '') === 'full_time' ? 'selected' : '' ?>>Full Time</option>
                                <option value="part_time" <?= ($filters['employment_type'] ?? '') === 'part_time' ? 'selected' : '' ?>>Part Time</option>
                                <option value="contract" <?= ($filters['employment_type'] ?? '') === 'contract' ? 'selected' : '' ?>>Contract</option>
                                <option value="internship" <?= ($filters['employment_type'] ?? '') === 'internship' ? 'selected' : '' ?>>Internship</option>
                                <option value="freelance" <?= ($filters['employment_type'] ?? '') === 'freelance' ? 'selected' : '' ?>>Freelance</option>
                            </select>
                        </div>
                        
                        <!-- Department -->
                        <div class="form-group mb-3">
                            <label>Departemen</label>
                            <select name="department" class="form-control">
                                <option value="">Semua Departemen</option>
                                <?php if (!empty($stats['by_department'])): ?>
                                    <?php foreach ($stats['by_department'] as $dept): ?>
                                        <option value="<?= htmlspecialchars($dept['department']) ?>" <?= ($filters['department'] ?? '') === $dept['department'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($dept['department']) ?> (<?= $dept['count'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <!-- Experience Level -->
                        <div class="form-group mb-3">
                            <label>Level Pengalaman</label>
                            <select name="experience_level" class="form-control">
                                <option value="">Semua Level</option>
                                <option value="entry" <?= ($filters['experience_level'] ?? '') === 'entry' ? 'selected' : '' ?>>Entry Level</option>
                                <option value="mid" <?= ($filters['experience_level'] ?? '') === 'mid' ? 'selected' : '' ?>>Mid Level</option>
                                <option value="senior" <?= ($filters['experience_level'] ?? '') === 'senior' ? 'selected' : '' ?>>Senior Level</option>
                                <option value="executive" <?= ($filters['experience_level'] ?? '') === 'executive' ? 'selected' : '' ?>>Executive</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                        <a href="/careers" class="btn btn-outline-secondary w-100 mt-2">Reset</a>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="careers-listing">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4>Semua Lowongan Kerja</h4>
                        <span class="text-muted"><?= count($careers) ?> posisi tersedia</span>
                    </div>
                    
                    <?php if (!empty($careers)): ?>
                        <div class="careers-grid">
                            <?php foreach ($careers as $index => $career): ?>
                            <div class="career-card" data-aos="fade-up" data-aos-delay="<?= ($index + 1) * 100 ?>">
                                <div class="career-content">
                                    <h3><a href="/careers/<?= $career['slug'] ?>"><?= htmlspecialchars($career['title']) ?></a></h3>
                                    <div class="career-meta">
                                        <span class="department"><i class="fas fa-building"></i> <?= htmlspecialchars($career['department']) ?></span>
                                        <span class="location"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($career['location']) ?></span>
                                        <span class="type"><i class="fas fa-clock"></i> <?= ucfirst($career['employment_type']) ?></span>
                                    </div>
                                    <p><?= htmlspecialchars(substr(strip_tags($career['description']), 0, 150)) ?>...</p>
                                    <div class="career-footer">
                                        <div class="career-info">
                                            <span class="experience-level"><?= ucfirst($career['experience_level']) ?> Level</span>
                                            <?php if ($career['salary_min'] && $career['salary_max']): ?>
                                                <span class="salary">Rp <?= number_format($career['salary_min'], 0, ',', '.') ?> - <?= number_format($career['salary_max'], 0, ',', '.') ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <a href="/careers/<?= $career['slug'] ?>" class="btn-apply">Lihat Detail</a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-results text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5>Tidak ada lowongan yang ditemukan</h5>
                            <p class="text-muted">Coba ubah filter pencarian Anda atau lihat semua lowongan yang tersedia.</p>
                            <a href="/careers" class="btn btn-primary">Lihat Semua Lowongan</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Join Us Section -->
<section class="why-join-us py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 data-aos="fade-up">Mengapa Bergabung dengan Kami?</h2>
            <p data-aos="fade-up" data-aos-delay="200">Kami menawarkan lingkungan kerja yang mendukung pertumbuhan dan pengembangan karir Anda</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h4>Growth Opportunity</h4>
                    <p>Kesempatan untuk berkembang dan belajar teknologi terbaru dalam industri jersey dan fashion.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4>Great Team</h4>
                    <p>Bergabung dengan tim yang kolaboratif, kreatif, dan saling mendukung satu sama lain.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h4>Work Life Balance</h4>
                    <p>Fleksibilitas kerja dan keseimbangan antara kehidupan pribadi dan profesional.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h4>Learning & Development</h4>
                    <p>Program pelatihan dan pengembangan skill untuk meningkatkan kemampuan Anda.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="500">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h4>Competitive Benefits</h4>
                    <p>Paket kompensasi yang kompetitif dengan benefit kesehatan dan bonus kinerja.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="600">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4>Company Culture</h4>
                    <p>Budaya perusahaan yang inklusif, inovatif, dan menghargai kontribusi setiap anggota tim.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container text-center">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="text-white mb-3" data-aos="fade-up">Siap Memulai Karir Baru?</h2>
                <p class="text-white mb-4" data-aos="fade-up" data-aos-delay="200">Bergabunglah dengan tim Testweb Jersey dan jadilah bagian dari inovasi dalam industri jersey dan fashion.</p>
                <div data-aos="fade-up" data-aos-delay="400">
                    <a href="#careers" class="btn btn-light btn-lg me-3">Lihat Lowongan</a>
                    <a href="/contact" class="btn btn-outline-light btn-lg">Hubungi Kami</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Structured Data -->
<script type="application/ld+json">
<?= json_encode($structuredData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?>
</script>

<style>
/* Career-specific styles */
.stats-section .stat-item {
    text-align: center;
    padding: 2rem 1rem;
}

.stats-section .stat-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 2rem;
}

.stats-section h3 {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.career-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: var(--shadow-md);
    transition: var(--transition-base);
    height: 100%;
    position: relative;
}

.career-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.career-card.featured {
    border: 2px solid var(--accent-color);
}

.career-badge {
    position: absolute;
    top: -10px;
    right: 20px;
    background: var(--accent-color);
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.career-content h3 {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.career-content h3 a {
    color: var(--primary-color);
    text-decoration: none;
    transition: var(--transition-base);
}

.career-content h3 a:hover {
    color: var(--secondary-color);
}

.career-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    color: #64748b;
}

.career-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.career-meta i {
    color: var(--primary-color);
}

.career-content p {
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.career-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
}

.career-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.experience-level {
    background: rgba(30, 58, 138, 0.1);
    color: var(--primary-color);
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
    width: fit-content;
}

.salary {
    color: var(--success-color);
    font-weight: 600;
    font-size: 0.9rem;
}

.btn-apply {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 8px 20px;
    border-radius: 20px;
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-base);
    font-size: 0.9rem;
}

.btn-apply:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    color: white;
}

.filter-sidebar {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: var(--shadow-md);
    height: fit-content;
    position: sticky;
    top: 100px;
}

.filter-sidebar h4 {
    color: var(--primary-color);
    margin-bottom: 1.5rem;
    font-weight: 600;
}

.filter-form .form-group label {
    font-weight: 600;
    color: var(--dark-color);
    margin-bottom: 0.5rem;
    display: block;
}

.benefit-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: var(--shadow-md);
    text-align: center;
    height: 100%;
    transition: var(--transition-base);
}

.benefit-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.benefit-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: white;
    font-size: 2rem;
}

.benefit-card h4 {
    color: var(--primary-color);
    margin-bottom: 1rem;
    font-weight: 600;
}

.benefit-card p {
    color: #64748b;
    line-height: 1.6;
}

.no-results {
    background: white;
    border-radius: 15px;
    padding: 3rem;
    box-shadow: var(--shadow-md);
}

@media (max-width: 768px) {
    .career-meta {
        flex-direction: column;
        gap: 8px;
    }
    
    .career-footer {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
    }
    
    .filter-sidebar {
        position: static;
        margin-bottom: 2rem;
    }
}
</style>