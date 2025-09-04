<?php
$pageTitle = $career['title'] . ' - Testweb Jersey';
$pageDescription = substr(strip_tags($career['description']), 0, 160);
$pageKeywords = $career['title'] . ', ' . $career['department'] . ', ' . $career['employment_type'];
?>

<!-- Breadcrumb -->
<section class="breadcrumb-section py-3 bg-light">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <?php foreach ($breadcrumbs as $index => $breadcrumb): ?>
                    <?php if ($index === count($breadcrumbs) - 1): ?>
                        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($breadcrumb['title']) ?></li>
                    <?php else: ?>
                        <li class="breadcrumb-item">
                            <a href="<?= htmlspecialchars($breadcrumb['url']) ?>"><?= htmlspecialchars($breadcrumb['title']) ?></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ol>
        </nav>
    </div>
</section>

<!-- Career Detail Section -->
<section class="career-detail py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="career-content">
                    <!-- Career Header -->
                    <div class="career-header mb-4">
                        <h1 data-aos="fade-up"><?= htmlspecialchars($career['title']) ?></h1>
                        <div class="career-meta-large" data-aos="fade-up" data-aos-delay="200">
                            <div class="meta-item">
                                <i class="fas fa-building"></i>
                                <span><?= htmlspecialchars($career['department']) ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?= htmlspecialchars($career['location']) ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span><?= ucfirst($career['employment_type']) ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-user-graduate"></i>
                                <span><?= ucfirst($career['experience_level']) ?> Level</span>
                            </div>
                        </div>
                        
                        <?php if ($career['salary_min'] && $career['salary_max']): ?>
                        <div class="salary-info" data-aos="fade-up" data-aos-delay="300">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Rp <?= number_format($career['salary_min'], 0, ',', '.') ?> - <?= number_format($career['salary_max'], 0, ',', '.') ?> per bulan</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Career Description -->
                    <div class="career-section" data-aos="fade-up" data-aos-delay="400">
                        <h3>Deskripsi Pekerjaan</h3>
                        <div class="content-text">
                            <?= nl2br(htmlspecialchars($career['description'])) ?>
                        </div>
                    </div>
                    
                    <!-- Responsibilities -->
                    <div class="career-section" data-aos="fade-up" data-aos-delay="500">
                        <h3>Tanggung Jawab</h3>
                        <div class="content-text">
                            <?= nl2br(htmlspecialchars($career['responsibilities'])) ?>
                        </div>
                    </div>
                    
                    <!-- Requirements -->
                    <div class="career-section" data-aos="fade-up" data-aos-delay="600">
                        <h3>Persyaratan</h3>
                        <div class="content-text">
                            <?= nl2br(htmlspecialchars($career['requirements'])) ?>
                        </div>
                    </div>
                    
                    <!-- Benefits -->
                    <?php if ($career['benefits']): ?>
                    <div class="career-section" data-aos="fade-up" data-aos-delay="700">
                        <h3>Benefit & Fasilitas</h3>
                        <div class="content-text">
                            <?= nl2br(htmlspecialchars($career['benefits'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Application Info -->
                    <div class="career-section" data-aos="fade-up" data-aos-delay="800">
                        <h3>Informasi Lamaran</h3>
                        <div class="application-info">
                            <?php if ($career['application_deadline']): ?>
                            <div class="info-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span><strong>Deadline:</strong> <?= date('d F Y', strtotime($career['application_deadline'])) ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($career['start_date']): ?>
                            <div class="info-item">
                                <i class="fas fa-calendar-check"></i>
                                <span><strong>Mulai Kerja:</strong> <?= date('d F Y', strtotime($career['start_date'])) ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="info-item">
                                <i class="fas fa-eye"></i>
                                <span><strong>Dilihat:</strong> <?= number_format($career['views']) ?> kali</span>
                            </div>
                            
                            <div class="info-item">
                                <i class="fas fa-users"></i>
                                <span><strong>Pelamar:</strong> <?= number_format($career['application_count']) ?> orang</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="career-sidebar">
                    <!-- Apply Button -->
                    <div class="apply-card" data-aos="fade-up" data-aos-delay="300">
                        <h4>Lamar Posisi Ini</h4>
                        <p>Bergabunglah dengan tim kami dan kembangkan karir Anda bersama Testweb Jersey.</p>
                        <button class="btn btn-primary btn-lg w-100" data-bs-toggle="modal" data-bs-target="#applicationModal">
                            <i class="fas fa-paper-plane"></i> Lamar Sekarang
                        </button>
                    </div>
                    
                    <!-- Career Info -->
                    <div class="info-card" data-aos="fade-up" data-aos-delay="400">
                        <h5>Informasi Posisi</h5>
                        <div class="info-list">
                            <div class="info-item">
                                <span class="label">Departemen:</span>
                                <span class="value"><?= htmlspecialchars($career['department']) ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Lokasi:</span>
                                <span class="value"><?= htmlspecialchars($career['location']) ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Tipe:</span>
                                <span class="value"><?= ucfirst($career['employment_type']) ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Level:</span>
                                <span class="value"><?= ucfirst($career['experience_level']) ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Status:</span>
                                <span class="value status-<?= $career['status'] ?>"><?= ucfirst($career['status']) ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Share -->
                    <div class="share-card" data-aos="fade-up" data-aos-delay="500">
                        <h5>Bagikan Lowongan</h5>
                        <div class="share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($this->config['site_url'] . '/careers/' . $career['slug']) ?>" target="_blank" class="share-btn facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?= urlencode($this->config['site_url'] . '/careers/' . $career['slug']) ?>&text=<?= urlencode($career['title']) ?>" target="_blank" class="share-btn twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode($this->config['site_url'] . '/careers/' . $career['slug']) ?>" target="_blank" class="share-btn linkedin">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://wa.me/?text=<?= urlencode('Check out this job: ' . $career['title'] . ' - ' . $this->config['site_url'] . '/careers/' . $career['slug']) ?>" target="_blank" class="share-btn whatsapp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Careers -->
<?php if (!empty($relatedCareers)): ?>
<section class="related-careers py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 data-aos="fade-up">Lowongan Terkait</h2>
            <p data-aos="fade-up" data-aos-delay="200">Posisi lain yang mungkin menarik untuk Anda</p>
        </div>
        
        <div class="row">
            <?php foreach ($relatedCareers as $index => $relatedCareer): ?>
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="<?= ($index + 1) * 100 ?>">
                <div class="career-card">
                    <div class="career-content">
                        <h3><a href="/careers/<?= $relatedCareer['slug'] ?>"><?= htmlspecialchars($relatedCareer['title']) ?></a></h3>
                        <div class="career-meta">
                            <span class="department"><i class="fas fa-building"></i> <?= htmlspecialchars($relatedCareer['department']) ?></span>
                            <span class="location"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($relatedCareer['location']) ?></span>
                            <span class="type"><i class="fas fa-clock"></i> <?= ucfirst($relatedCareer['employment_type']) ?></span>
                        </div>
                        <p><?= htmlspecialchars(substr(strip_tags($relatedCareer['description']), 0, 120)) ?>...</p>
                        <div class="career-footer">
                            <span class="experience-level"><?= ucfirst($relatedCareer['experience_level']) ?> Level</span>
                            <a href="/careers/<?= $relatedCareer['slug'] ?>" class="btn-apply">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Application Modal -->
<div class="modal fade" id="applicationModal" tabindex="-1" aria-labelledby="applicationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="applicationModalLabel">Lamar Posisi: <?= htmlspecialchars($career['title']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/careers/apply" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="career_id" value="<?= $career['id'] ?>">
                    <input type="hidden" name="career_slug" value="<?= $career['slug'] ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="full_name">Nama Lengkap *</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="email">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="phone">Nomor Telepon *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="expected_salary">Gaji yang Diharapkan</label>
                                <input type="number" class="form-control" id="expected_salary" name="expected_salary" placeholder="Contoh: 8000000">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="portfolio_url">Portfolio URL</label>
                                <input type="url" class="form-control" id="portfolio_url" name="portfolio_url" placeholder="https://yourportfolio.com">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="linkedin_url">LinkedIn URL</label>
                                <input type="url" class="form-control" id="linkedin_url" name="linkedin_url" placeholder="https://linkedin.com/in/yourprofile">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="availability_date">Tanggal Ketersediaan</label>
                        <input type="date" class="form-control" id="availability_date" name="availability_date">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="resume">Upload CV/Resume</label>
                        <input type="file" class="form-control" id="resume" name="resume" accept=".pdf,.doc,.docx">
                        <small class="form-text text-muted">Format yang diterima: PDF, DOC, DOCX (Max: 5MB)</small>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="cover_letter">Surat Lamaran *</label>
                        <textarea class="form-control" id="cover_letter" name="cover_letter" rows="6" required placeholder="Ceritakan mengapa Anda tertarik dengan posisi ini dan bagaimana Anda dapat berkontribusi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Lamaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Structured Data -->
<script type="application/ld+json">
<?= json_encode($structuredData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?>
</script>

<!-- Breadcrumb Schema -->
<script type="application/ld+json">
<?= json_encode($breadcrumbSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?>
</script>

<style>
/* Career Detail Styles */
.career-detail {
    padding-top: 2rem;
}

.career-header h1 {
    color: var(--primary-color);
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.career-meta-large {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 1.5rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #64748b;
    font-weight: 500;
}

.meta-item i {
    color: var(--primary-color);
    width: 20px;
}

.salary-info {
    display: flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, var(--success-color), #20c997);
    color: white;
    padding: 15px 20px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

.salary-info i {
    font-size: 1.3rem;
}

.career-section {
    margin-bottom: 3rem;
}

.career-section h3 {
    color: var(--primary-color);
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--primary-color);
}

.content-text {
    color: #64748b;
    line-height: 1.8;
    font-size: 1.1rem;
}

.application-info {
    background: var(--light-color);
    padding: 1.5rem;
    border-radius: 10px;
}

.application-info .info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 0.5rem;
    color: #64748b;
}

.application-info .info-item i {
    color: var(--primary-color);
    width: 20px;
}

.career-sidebar {
    position: sticky;
    top: 100px;
}

.apply-card, .info-card, .share-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: var(--shadow-md);
    margin-bottom: 2rem;
}

.apply-card h4, .info-card h5, .share-card h5 {
    color: var(--primary-color);
    margin-bottom: 1rem;
    font-weight: 600;
}

.info-list .info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.info-list .info-item:last-child {
    border-bottom: none;
}

.info-list .label {
    font-weight: 600;
    color: var(--dark-color);
}

.info-list .value {
    color: #64748b;
}

.status-active {
    color: var(--success-color);
    font-weight: 600;
}

.status-inactive {
    color: var(--warning-color);
    font-weight: 600;
}

.status-closed {
    color: var(--danger-color);
    font-weight: 600;
}

.share-buttons {
    display: flex;
    gap: 10px;
}

.share-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: white;
    transition: var(--transition-base);
}

.share-btn:hover {
    transform: translateY(-2px);
    color: white;
}

.share-btn.facebook {
    background: #1877f2;
}

.share-btn.twitter {
    background: #1da1f2;
}

.share-btn.linkedin {
    background: #0077b5;
}

.share-btn.whatsapp {
    background: #25d366;
}

.related-careers .career-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: var(--shadow-md);
    transition: var(--transition-base);
    height: 100%;
}

.related-careers .career-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.related-careers .career-content h3 {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.related-careers .career-content h3 a {
    color: var(--primary-color);
    text-decoration: none;
    transition: var(--transition-base);
}

.related-careers .career-content h3 a:hover {
    color: var(--secondary-color);
}

.related-careers .career-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 1rem;
    font-size: 0.85rem;
    color: #64748b;
}

.related-careers .career-meta span {
    display: flex;
    align-items: center;
    gap: 4px;
}

.related-careers .career-meta i {
    color: var(--primary-color);
}

.related-careers .career-content p {
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
}

.related-careers .career-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
}

.related-careers .experience-level {
    background: rgba(30, 58, 138, 0.1);
    color: var(--primary-color);
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.related-careers .btn-apply {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 6px 16px;
    border-radius: 20px;
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-base);
    font-size: 0.85rem;
}

.related-careers .btn-apply:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    color: white;
}

@media (max-width: 768px) {
    .career-header h1 {
        font-size: 2rem;
    }
    
    .career-meta-large {
        flex-direction: column;
        gap: 10px;
    }
    
    .career-sidebar {
        position: static;
        margin-top: 2rem;
    }
    
    .info-list .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
}
</style>