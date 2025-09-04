-- Database schema for Testweb Jersey Website
-- Created according to README specifications

CREATE DATABASE IF NOT EXISTS testweb_jersey;
USE testweb_jersey;

-- Users table for admin authentication
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'editor') DEFAULT 'admin',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Pages table for dynamic content (including homepage)
CREATE TABLE pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content JSON NOT NULL, -- Drag-and-drop editor content
    meta_title VARCHAR(255),
    meta_description TEXT,
    is_homepage BOOLEAN DEFAULT FALSE,
    is_published BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    short_description VARCHAR(500),
    price DECIMAL(10,2),
    sale_price DECIMAL(10,2),
    sku VARCHAR(100) UNIQUE,
    stock_quantity INT DEFAULT 0,
    is_featured BOOLEAN DEFAULT FALSE,
    is_published BOOLEAN DEFAULT TRUE,
    marketplace_url VARCHAR(500), -- External marketplace URL
    meta_title VARCHAR(255),
    meta_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Product images table
CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255),
    sort_order INT DEFAULT 0,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Categories table for posts
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tags table for posts
CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Posts table for blog
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content LONGTEXT NOT NULL,
    excerpt TEXT,
    featured_image VARCHAR(500),
    category_id INT,
    author_id INT NOT NULL,
    is_published BOOLEAN DEFAULT TRUE,
    published_at TIMESTAMP NULL,
    meta_title VARCHAR(255),
    meta_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Post tags pivot table
CREATE TABLE post_tags (
    post_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (post_id, tag_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- Testimonials table
CREATE TABLE testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    client_position VARCHAR(100),
    client_company VARCHAR(100),
    testimonial TEXT NOT NULL,
    client_photo VARCHAR(500),
    rating INT DEFAULT 5 CHECK (rating >= 1 AND rating <= 5),
    is_featured BOOLEAN DEFAULT FALSE,
    is_published BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Client logos table
CREATE TABLE client_logos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(100) NOT NULL,
    logo_path VARCHAR(500) NOT NULL,
    website_url VARCHAR(500),
    alt_text VARCHAR(255),
    is_published BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Comments table for blog posts
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    author_name VARCHAR(100) NOT NULL,
    author_email VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    is_approved BOOLEAN DEFAULT FALSE,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

-- Global configurations table
CREATE TABLE configurations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    config_key VARCHAR(100) UNIQUE NOT NULL,
    config_value TEXT,
    config_type ENUM('string', 'boolean', 'integer', 'json') DEFAULT 'string',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default configurations
INSERT INTO configurations (config_key, config_value, config_type, description) VALUES
('site_name', 'Testweb Jersey', 'string', 'Website name'),
('site_description', 'Jersey berkualitas tinggi dengan desain terbaik', 'string', 'Website description'),
('meta_title_default', 'Testweb Jersey - Jersey Berkualitas Tinggi', 'string', 'Default meta title'),
('meta_description_default', 'Temukan jersey berkualitas tinggi dengan desain terbaik di Testweb Jersey', 'string', 'Default meta description'),
('google_analytics_id', '', 'string', 'Google Analytics tracking ID'),
('recaptcha_site_key', '', 'string', 'reCAPTCHA site key'),
('recaptcha_secret_key', '', 'string', 'reCAPTCHA secret key'),
('recaptcha_enabled', 'false', 'boolean', 'Enable/disable reCAPTCHA'),
('image_quality', '85', 'integer', 'Image compression quality (1-100)'),
('max_upload_size', '5242880', 'integer', 'Maximum file upload size in bytes (5MB)'),
('whatsapp_number', '+6281234567890', 'string', 'WhatsApp contact number'),
('whatsapp_message', 'Halo, saya tertarik dengan produk jersey Anda', 'string', 'Default WhatsApp message');

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password, full_name, role) VALUES
('admin', 'admin@testweb.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin');

-- Insert sample categories
INSERT INTO categories (name, slug, description) VALUES
('Jersey Sepak Bola', 'jersey-sepak-bola', 'Koleksi jersey sepak bola berbagai klub'),
('Jersey Basket', 'jersey-basket', 'Jersey basket dengan kualitas premium'),
('Jersey Custom', 'jersey-custom', 'Jersey dengan desain custom sesuai keinginan');

-- Insert sample tags
INSERT INTO tags (name, slug) VALUES
('premium', 'premium'),
('custom', 'custom'),
('original', 'original'),
('limited', 'limited'),
('new', 'new');

-- Table structure for table `careers`
CREATE TABLE `careers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `requirements` text NOT NULL,
  `responsibilities` text NOT NULL,
  `benefits` text,
  `employment_type` enum('full_time','part_time','contract','internship','freelance') NOT NULL DEFAULT 'full_time',
  `experience_level` enum('entry','mid','senior','executive') NOT NULL DEFAULT 'entry',
  `department` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `salary_min` decimal(10,2) DEFAULT NULL,
  `salary_max` decimal(10,2) DEFAULT NULL,
  `currency` varchar(3) DEFAULT 'IDR',
  `application_deadline` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `status` enum('active','inactive','closed') NOT NULL DEFAULT 'active',
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `application_count` int(11) NOT NULL DEFAULT 0,
  `views` int(11) NOT NULL DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text,
  `meta_keywords` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `status` (`status`),
  KEY `employment_type` (`employment_type`),
  KEY `department` (`department`),
  KEY `featured` (`featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `job_applications`
CREATE TABLE `job_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `career_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `cover_letter` text,
  `resume_file` varchar(255) DEFAULT NULL,
  `portfolio_url` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `expected_salary` decimal(10,2) DEFAULT NULL,
  `availability_date` date DEFAULT NULL,
  `status` enum('pending','reviewed','shortlisted','interviewed','accepted','rejected') NOT NULL DEFAULT 'pending',
  `notes` text,
  `applied_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `career_id` (`career_id`),
  KEY `status` (`status`),
  KEY `applied_at` (`applied_at`),
  FOREIGN KEY (`career_id`) REFERENCES `careers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `seo_analytics`
CREATE TABLE `seo_analytics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_url` varchar(500) NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `seo_score` int(11) NOT NULL DEFAULT 0,
  `issues` text,
  `suggestions` text,
  `last_analyzed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_url` (`page_url`),
  KEY `seo_score` (`seo_score`),
  KEY `last_analyzed` (`last_analyzed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample career positions
INSERT INTO careers (title, slug, description, requirements, responsibilities, benefits, employment_type, experience_level, department, location, salary_min, salary_max, status, featured) VALUES
('Frontend Developer', 'frontend-developer', 'Kami mencari Frontend Developer yang berpengalaman untuk mengembangkan website dan aplikasi web yang modern dan responsif.', 'Minimal 2 tahun pengalaman dalam pengembangan frontend, Menguasai HTML, CSS, JavaScript, React/Vue.js, Familiar dengan responsive design dan cross-browser compatibility', 'Mengembangkan dan memelihara website perusahaan, Membuat komponen UI yang reusable, Optimasi performa website, Berkolaborasi dengan tim design dan backend', 'Gaji kompetitif, Asuransi kesehatan, Bonus kinerja, Work from home fleksibel, Pelatihan dan pengembangan karir', 'full_time', 'mid', 'Technology', 'Jakarta Selatan', 8000000, 15000000, 'active', 1),
('Marketing Specialist', 'marketing-specialist', 'Bergabunglah dengan tim marketing kami untuk mengembangkan strategi pemasaran digital dan meningkatkan brand awareness Testweb Jersey.', 'Minimal 1 tahun pengalaman di bidang digital marketing, Menguasai social media marketing, SEO, dan Google Ads, Kreatif dan memiliki analytical thinking', 'Mengelola social media accounts, Membuat konten marketing, Melakukan SEO optimization, Menganalisis campaign performance', 'Gaji kompetitif, Komisi penjualan, Asuransi kesehatan, Bonus kinerja, Pelatihan marketing tools', 'full_time', 'entry', 'Marketing', 'Jakarta Selatan', 6000000, 10000000, 'active', 1),
('Graphic Designer', 'graphic-designer', 'Kami mencari Graphic Designer yang kreatif untuk membuat desain jersey dan materi marketing yang menarik.', 'Minimal 1 tahun pengalaman sebagai graphic designer, Menguasai Adobe Creative Suite (Photoshop, Illustrator, InDesign), Memiliki portfolio yang kuat', 'Mendesain jersey dan produk apparel, Membuat materi marketing dan branding, Berkolaborasi dengan tim marketing, Memastikan konsistensi brand', 'Gaji kompetitif, Asuransi kesehatan, Bonus kinerja, Creative freedom, Pelatihan design tools', 'full_time', 'entry', 'Design', 'Jakarta Selatan', 5000000, 9000000, 'active', 0);