<?php

class DashboardController
{
    public function index(): void
    {
        requireAuth();
        $userId = $_SESSION['user_id'];

        // User's products
        $products = Database::fetchAll(
            "SELECT p.*, up.granted_at,
                    COALESCE(progress.total_lessons, 0) AS total_lessons,
                    COALESCE(progress.completed_lessons, 0) AS completed_lessons
             FROM user_products up
             JOIN products p ON up.product_id = p.id
             LEFT JOIN (
                SELECT m.product_id,
                       COUNT(DISTINCT l.id) AS total_lessons,
                       COUNT(DISTINCT lp.lesson_id) AS completed_lessons
                FROM modules m
                LEFT JOIN lessons l ON l.module_id = m.id
                LEFT JOIN lesson_progress lp
                    ON lp.lesson_id = l.id
                   AND lp.user_id = ?
                   AND lp.completed = 1
                GROUP BY m.product_id
             ) progress ON progress.product_id = p.id
             WHERE up.user_id = ? AND p.is_active = 1
             ORDER BY up.granted_at DESC",
            [$userId, $userId]
        );

        foreach ($products as &$product) {
            $product['total_lessons'] = (int) $product['total_lessons'];
            $product['completed_lessons'] = (int) $product['completed_lessons'];
            $product['progress'] = $product['total_lessons'] > 0
                ? (int) round(($product['completed_lessons'] / $product['total_lessons']) * 100)
                : 0;
        }
        unset($product);

        // Recent community posts
        $recentPosts = Database::fetchAll(
            "SELECT cp.*, u.anonymous_name,
                    COALESCE(comment_stats.comment_count, 0) AS comment_count
             FROM community_posts cp
             JOIN users u ON cp.user_id = u.id
             LEFT JOIN (
                SELECT post_id, COUNT(*) AS comment_count
                FROM community_comments
                WHERE is_visible = 1
                GROUP BY post_id
             ) comment_stats ON comment_stats.post_id = cp.id
             WHERE cp.is_visible = 1
             ORDER BY cp.is_pinned DESC, cp.created_at DESC
             LIMIT 3"
        );

        $pageTitle = 'Dashboard';
        $activePage = 'dashboard';
        require VIEWS_PATH . '/layouts/app.php';
        require VIEWS_PATH . '/dashboard/index.php';
        closeLayout();
    }
}
