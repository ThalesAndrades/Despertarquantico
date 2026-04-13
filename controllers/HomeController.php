<?php

class HomeController
{
    public function index(): void
    {
        $product = null;
        try {
            $product = Database::fetch(
                "SELECT *
                 FROM products
                 WHERE is_active = 1
                 ORDER BY (CASE WHEN slug = 'mulher-espiral' THEN 0 ELSE 1 END) ASC, sort_order ASC, created_at DESC
                 LIMIT 1"
            );
        } catch (Throwable $e) {
            $product = null;
        }
        view('landing/index', compact('product'));
    }

    public function marketplace(): void
    {
        $q = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
        $params = [];

        $sql = "SELECT p.*,
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
                WHERE p.is_active = 1";

        if ($q !== '') {
            $sql .= " AND (p.title LIKE ? OR p.short_description LIKE ?)";
            $like = '%' . $q . '%';
            $params[] = $like;
            $params[] = $like;
        }

        $sql .= " ORDER BY p.sort_order ASC, p.created_at DESC";

        $products = [];
        try {
            $products = Database::fetchAll($sql, $params);
        } catch (Throwable $e) {
            $products = [];
        }

        view('marketplace/index', compact('products', 'q'));
    }

    public function marketplaceProduct(string $slug): void
    {
        try {
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
        } catch (Throwable $e) {
            http_response_code(503);
            require VIEWS_PATH . '/errors/500.php';
            return;
        }

        if (!$product) {
            http_response_code(404);
            require VIEWS_PATH . '/errors/404.php';
            return;
        }

        $modules = [];
        try {
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
        } catch (Throwable $e) {
            $modules = [];
        }

        view('marketplace/show', compact('product', 'modules'));
    }
}
