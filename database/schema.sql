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