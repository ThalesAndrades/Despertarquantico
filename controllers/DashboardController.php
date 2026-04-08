<?php

class DashboardController
{
    public function index(): void
    {
        requireAuth();
        $userId = $_SESSION['user_id'];

        // User's products
        $products = Database::fetchAll(
            "SELECT p.*, up.granted_at FROM user_products up
             JOIN products p ON up.product_id = p.id
             WHERE up.user_id = ? AND p.is_active = 1
             ORDER BY up.granted_at DESC",
            [$userId]
        );

        // Calculate progress for each product
        foreach ($products as &$product) {
            $totalLessons = Database::count(
                "SELECT COUNT(*) FROM lessons l JOIN modules m ON l.module_id = m.id WHERE m.product_id = ?",
                [$product['id']]
            );
            $completedLessons = Database::count(
                "SELECT COUNT(*) FROM lesson_progress lp
                 JOIN lessons l ON lp.lesson_id = l.id
                 JOIN modules m ON l.module_id = m.id
                 WHERE m.product_id = ? AND lp.user_id = ? AND lp.completed = 1",
                [$product['id'], $userId]
            );
            $product['total_lessons'] = $totalLessons;
            $product['completed_lessons'] = $completedLessons;
            $product['progress'] = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
        }
        unset($product);

        // Recent community posts
        $recentPosts = Database::fetchAll(
            "SELECT cp.*, u.anonymous_name,
                    (SELECT COUNT(*) FROM community_comments cc WHERE cc.post_id = cp.id AND cc.is_visible = 1) as comment_count
             FROM community_posts cp
             JOIN users u ON cp.user_id = u.id
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
