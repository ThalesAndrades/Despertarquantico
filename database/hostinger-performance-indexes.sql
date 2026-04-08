-- =============================================
-- Hostinger performance patch for existing DB
-- Safe to run in phpMyAdmin on the current database
-- Adds only missing indexes used by the optimized code paths
-- =============================================

-- modules.idx_product_sort
SET @index_exists := (
    SELECT COUNT(1)
    FROM information_schema.statistics
    WHERE table_schema = DATABASE()
      AND table_name = 'modules'
      AND index_name = 'idx_product_sort'
);
SET @sql := IF(
    @index_exists = 0,
    'ALTER TABLE modules ADD INDEX idx_product_sort (product_id, sort_order)',
    'SELECT ''idx_product_sort already exists'''
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- lessons.idx_module_sort
SET @index_exists := (
    SELECT COUNT(1)
    FROM information_schema.statistics
    WHERE table_schema = DATABASE()
      AND table_name = 'lessons'
      AND index_name = 'idx_module_sort'
);
SET @sql := IF(
    @index_exists = 0,
    'ALTER TABLE lessons ADD INDEX idx_module_sort (module_id, sort_order)',
    'SELECT ''idx_module_sort already exists'''
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- user_products.idx_product_user
SET @index_exists := (
    SELECT COUNT(1)
    FROM information_schema.statistics
    WHERE table_schema = DATABASE()
      AND table_name = 'user_products'
      AND index_name = 'idx_product_user'
);
SET @sql := IF(
    @index_exists = 0,
    'ALTER TABLE user_products ADD INDEX idx_product_user (product_id, user_id)',
    'SELECT ''idx_product_user already exists'''
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- lesson_progress.idx_user_completed_lesson
SET @index_exists := (
    SELECT COUNT(1)
    FROM information_schema.statistics
    WHERE table_schema = DATABASE()
      AND table_name = 'lesson_progress'
      AND index_name = 'idx_user_completed_lesson'
);
SET @sql := IF(
    @index_exists = 0,
    'ALTER TABLE lesson_progress ADD INDEX idx_user_completed_lesson (user_id, completed, lesson_id)',
    'SELECT ''idx_user_completed_lesson already exists'''
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- orders.idx_customer_email
SET @index_exists := (
    SELECT COUNT(1)
    FROM information_schema.statistics
    WHERE table_schema = DATABASE()
      AND table_name = 'orders'
      AND index_name = 'idx_customer_email'
);
SET @sql := IF(
    @index_exists = 0,
    'ALTER TABLE orders ADD INDEX idx_customer_email (customer_email)',
    'SELECT ''idx_customer_email already exists'''
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- community_comments.idx_post_visible_created
SET @index_exists := (
    SELECT COUNT(1)
    FROM information_schema.statistics
    WHERE table_schema = DATABASE()
      AND table_name = 'community_comments'
      AND index_name = 'idx_post_visible_created'
);
SET @sql := IF(
    @index_exists = 0,
    'ALTER TABLE community_comments ADD INDEX idx_post_visible_created (post_id, is_visible, created_at)',
    'SELECT ''idx_post_visible_created already exists'''
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- community_likes.idx_post_lookup
SET @index_exists := (
    SELECT COUNT(1)
    FROM information_schema.statistics
    WHERE table_schema = DATABASE()
      AND table_name = 'community_likes'
      AND index_name = 'idx_post_lookup'
);
SET @sql := IF(
    @index_exists = 0,
    'ALTER TABLE community_likes ADD INDEX idx_post_lookup (post_id, user_id)',
    'SELECT ''idx_post_lookup already exists'''
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- community_likes.idx_comment_lookup
SET @index_exists := (
    SELECT COUNT(1)
    FROM information_schema.statistics
    WHERE table_schema = DATABASE()
      AND table_name = 'community_likes'
      AND index_name = 'idx_comment_lookup'
);
SET @sql := IF(
    @index_exists = 0,
    'ALTER TABLE community_likes ADD INDEX idx_comment_lookup (comment_id, user_id)',
    'SELECT ''idx_comment_lookup already exists'''
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
