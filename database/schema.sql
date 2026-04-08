-- =============================================
-- Plataforma Sunyan Nunes - Database Schema
-- Execute via phpMyAdmin na Hostinger
-- =============================================

CREATE DATABASE IF NOT EXISTS `u525832347_Mulherespiral`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `u525832347_Mulherespiral`;

-- =============================================
-- Users
-- =============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    anonymous_name VARCHAR(50) UNIQUE DEFAULT NULL,
    role ENUM('member', 'admin') DEFAULT 'member',
    is_active TINYINT(1) DEFAULT 1,
    reset_token VARCHAR(64) DEFAULT NULL,
    reset_expires DATETIME DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_reset_token (reset_token)
) ENGINE=InnoDB;

-- =============================================
-- Products
-- =============================================
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    description TEXT,
    short_description VARCHAR(500) DEFAULT NULL,
    cover_image VARCHAR(255) DEFAULT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    stripe_price_id VARCHAR(100) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_active (is_active)
) ENGINE=InnoDB;

-- =============================================
-- Modules (sections within a product)
-- =============================================
CREATE TABLE modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product_sort (product_id, sort_order)
) ENGINE=InnoDB;

-- =============================================
-- Lessons (content within a module)
-- =============================================
CREATE TABLE lessons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    module_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    content_type ENUM('video', 'text', 'pdf', 'audio') DEFAULT 'video',
    content_url VARCHAR(500) DEFAULT NULL,
    content_body TEXT DEFAULT NULL,
    duration_minutes INT DEFAULT 0,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE,
    INDEX idx_module_sort (module_id, sort_order)
) ENGINE=InnoDB;

-- =============================================
-- User Product Access
-- =============================================
CREATE TABLE user_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    granted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_product (user_id, product_id),
    INDEX idx_product_user (product_id, user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- Lesson Progress
-- =============================================
CREATE TABLE lesson_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    lesson_id INT NOT NULL,
    completed TINYINT(1) DEFAULT 0,
    completed_at DATETIME DEFAULT NULL,
    UNIQUE KEY uq_user_lesson (user_id, lesson_id),
    INDEX idx_user_completed_lesson (user_id, completed, lesson_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- Orders (Stripe payments)
-- =============================================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    product_id INT NOT NULL,
    stripe_session_id VARCHAR(255) UNIQUE NOT NULL,
    stripe_payment_intent VARCHAR(255) DEFAULT NULL,
    customer_email VARCHAR(150) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'brl',
    status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    paid_at DATETIME DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_session (stripe_session_id),
    INDEX idx_status (status),
    INDEX idx_customer_email (customer_email)
) ENGINE=InnoDB;

-- =============================================
-- Community Posts
-- =============================================
CREATE TABLE community_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category ENUM('geral', 'desabafo', 'duvidas', 'conquistas', 'dicas') DEFAULT 'geral',
    title VARCHAR(200) NOT NULL,
    body TEXT NOT NULL,
    is_pinned TINYINT(1) DEFAULT 0,
    is_visible TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_category (category),
    INDEX idx_visible (is_visible, created_at)
) ENGINE=InnoDB;

-- =============================================
-- Community Comments
-- =============================================
CREATE TABLE community_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    body TEXT NOT NULL,
    is_visible TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES community_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_post_visible_created (post_id, is_visible, created_at)
) ENGINE=InnoDB;

-- =============================================
-- Community Likes
-- =============================================
CREATE TABLE community_likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT DEFAULT NULL,
    comment_id INT DEFAULT NULL,
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_post_like (user_id, post_id),
    UNIQUE KEY uq_comment_like (user_id, comment_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_post_lookup (post_id, user_id),
    INDEX idx_comment_lookup (comment_id, user_id)
) ENGINE=InnoDB;

-- =============================================
-- Insert default admin user (change password hash!)
-- Password: admin123 (CHANGE THIS!)
-- Generate with: php -r "echo password_hash('sua_senha', PASSWORD_DEFAULT);"
-- =============================================
-- Admin user is auto-created by Database.php on first run
-- Email: sunyan@mulherespiral.shop | Password: @Telemed123
