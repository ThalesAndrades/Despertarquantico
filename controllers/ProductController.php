<?php

class ProductController
{
    public function index(): void
    {
        requireAuth();
        $userId = $_SESSION['user_id'];

        $products = Database::fetchAll(
            "SELECT p.*, up.granted_at FROM user_products up
             JOIN products p ON up.product_id = p.id
             WHERE up.user_id = ? AND p.is_active = 1
             ORDER BY p.sort_order ASC",
            [$userId]
        );

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

        $pageTitle = 'Meus Produtos';
        $activePage = 'products';
        require VIEWS_PATH . '/layouts/app.php';
        require VIEWS_PATH . '/products/index.php';
        closeLayout();
    }

    public function show(string $slug): void
    {
        requireAuth();
        $userId = $_SESSION['user_id'];

        $product = Database::fetch("SELECT * FROM products WHERE slug = ? AND is_active = 1", [$slug]);
        if (!$product) {
            flash('error', 'Produto não encontrado.');
            redirect('products');
            return;
        }

        // Check access
        $hasAccess = Database::fetch(
            "SELECT 1 FROM user_products WHERE user_id = ? AND product_id = ?",
            [$userId, $product['id']]
        );
        if (!$hasAccess) {
            flash('error', 'Você não tem acesso a este produto.');
            redirect('products');
            return;
        }

        // Get modules with lessons
        $modules = Database::fetchAll(
            "SELECT * FROM modules WHERE product_id = ? ORDER BY sort_order ASC",
            [$product['id']]
        );
        foreach ($modules as &$module) {
            $module['lessons'] = Database::fetchAll(
                "SELECT l.*,
                        (SELECT completed FROM lesson_progress WHERE user_id = ? AND lesson_id = l.id) as is_completed
                 FROM lessons l WHERE l.module_id = ? ORDER BY l.sort_order ASC",
                [$userId, $module['id']]
            );
        }
        unset($module);

        // Find first incomplete lesson
        $currentLesson = null;
        foreach ($modules as $mod) {
            foreach ($mod['lessons'] as $les) {
                if (!$les['is_completed']) {
                    $currentLesson = $les;
                    break 2;
                }
            }
        }
        if (!$currentLesson && !empty($modules) && !empty($modules[0]['lessons'])) {
            $currentLesson = $modules[0]['lessons'][0];
        }

        $pageTitle = $product['title'];
        $activePage = 'products';
        require VIEWS_PATH . '/layouts/app.php';
        require VIEWS_PATH . '/products/view.php';
        closeLayout();
    }

    public function lesson(string $slug, string $id): void
    {
        requireAuth();
        $userId = $_SESSION['user_id'];

        $product = Database::fetch("SELECT * FROM products WHERE slug = ?", [$slug]);
        if (!$product) { redirect('products'); return; }

        $hasAccess = Database::fetch("SELECT 1 FROM user_products WHERE user_id = ? AND product_id = ?", [$userId, $product['id']]);
        if (!$hasAccess) { redirect('products'); return; }

        $lesson = Database::fetch("SELECT l.*, m.title as module_title FROM lessons l JOIN modules m ON l.module_id = m.id WHERE l.id = ? AND m.product_id = ?", [(int)$id, $product['id']]);
        if (!$lesson) { redirect('products/' . $slug); return; }

        $modules = Database::fetchAll("SELECT * FROM modules WHERE product_id = ? ORDER BY sort_order ASC", [$product['id']]);
        foreach ($modules as &$module) {
            $module['lessons'] = Database::fetchAll(
                "SELECT l.*, (SELECT completed FROM lesson_progress WHERE user_id = ? AND lesson_id = l.id) as is_completed FROM lessons l WHERE l.module_id = ? ORDER BY l.sort_order ASC",
                [$userId, $module['id']]
            );
        }
        unset($module);

        $currentLesson = $lesson;

        $pageTitle = $lesson['title'];
        $activePage = 'products';
        require VIEWS_PATH . '/layouts/app.php';
        require VIEWS_PATH . '/products/view.php';
        closeLayout();
    }

    public function markProgress(): void
    {
        requireAuth();
        CSRF::check();

        $userId = $_SESSION['user_id'];
        $lessonId = (int) ($_POST['lesson_id'] ?? 0);
        $slug = $_POST['product_slug'] ?? '';

        if ($lessonId > 0) {
            $existing = Database::fetch(
                "SELECT * FROM lesson_progress WHERE user_id = ? AND lesson_id = ?",
                [$userId, $lessonId]
            );
            if ($existing) {
                $newState = $existing['completed'] ? 0 : 1;
                Database::query(
                    "UPDATE lesson_progress SET completed = ?, completed_at = ? WHERE id = ?",
                    [$newState, $newState ? date('Y-m-d H:i:s') : null, $existing['id']]
                );
            } else {
                Database::insert(
                    "INSERT INTO lesson_progress (user_id, lesson_id, completed, completed_at) VALUES (?, ?, 1, NOW())",
                    [$userId, $lessonId]
                );
            }
        }

        redirect('products/' . $slug);
    }
}
