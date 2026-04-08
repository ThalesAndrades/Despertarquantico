<?php

class HomeController
{
    public function index(): void
    {
        // Fetch the main product for the CTA button
        $product = Database::fetch(
            "SELECT * FROM products WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 1"
        );
        view('landing/index', compact('product'));
    }

    public function marketplace(): void
    {
        $products = Database::fetchAll(
            "SELECT p.*,
                    COALESCE(module_stats.module_count, 0) AS module_count,
                    COALESCE(module_stats.lesson_count, 0) AS lesson_count
             FROM products p
             LEFT JOIN (
                SELECT m.product_id,
                       COUNT(DISTINCT m.id) AS module_count,
                       COUNT(DISTINCT l.id) AS lesson_count
                FROM modules m
                LEFT JOIN lessons l ON l.module_id = m.id
                GROUP BY m.product_id
             ) module_stats ON module_stats.product_id = p.id
             WHERE p.is_active = 1
             ORDER BY p.sort_order ASC, p.created_at DESC"
        );

        view('marketplace/index', compact('products'));
    }

    public function marketplaceProduct(string $slug): void
    {
        $product = Database::fetch(
            "SELECT p.*,
                    COALESCE(module_stats.module_count, 0) AS module_count,
                    COALESCE(module_stats.lesson_count, 0) AS lesson_count
             FROM products p
             LEFT JOIN (
                SELECT m.product_id,
                       COUNT(DISTINCT m.id) AS module_count,
                       COUNT(DISTINCT l.id) AS lesson_count
                FROM modules m
                LEFT JOIN lessons l ON l.module_id = m.id
                GROUP BY m.product_id
             ) module_stats ON module_stats.product_id = p.id
             WHERE p.slug = ? AND p.is_active = 1",
            [$slug]
        );

        if (!$product) {
            http_response_code(404);
            require VIEWS_PATH . '/errors/404.php';
            return;
        }

        $modules = Database::fetchAll(
            "SELECT m.*,
                    COALESCE(lesson_stats.lesson_count, 0) AS lesson_count
             FROM modules m
             LEFT JOIN (
                SELECT module_id, COUNT(*) AS lesson_count
                FROM lessons
                GROUP BY module_id
             ) lesson_stats ON lesson_stats.module_id = m.id
             WHERE m.product_id = ?
             ORDER BY m.sort_order ASC, m.id ASC",
            [$product['id']]
        );

        view('marketplace/show', compact('product', 'modules'));
    }
}
