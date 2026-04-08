<?php

class ProductController
{
    public function index(): void
    {
        requireAuth();
        $userId = $_SESSION['user_id'];

        $products = $this->getUserProductsWithProgress($userId, 'p.sort_order ASC, up.granted_at DESC');

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
        $modules = $this->loadModulesWithLessons($product['id'], $userId);

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

        $modules = $this->loadModulesWithLessons($product['id'], $userId);
        $currentLesson = $this->findLessonById($modules, (int) $id);
        if (!$currentLesson) { redirect('products/' . $slug); return; }

        $pageTitle = $currentLesson['title'];
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
            Database::query(
                "INSERT INTO lesson_progress (user_id, lesson_id, completed, completed_at)
                 VALUES (?, ?, 1, NOW())
                 ON DUPLICATE KEY UPDATE
                    completed = IF(completed = 1, 0, 1),
                    completed_at = IF(completed = 1, NULL, NOW())",
                [$userId, $lessonId]
            );
        }

        redirect('products/' . $slug);
    }

    private function getUserProductsWithProgress(int $userId, string $orderBy): array
    {
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
             ORDER BY {$orderBy}",
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

        return $products;
    }

    private function loadModulesWithLessons(int $productId, int $userId): array
    {
        $modules = Database::fetchAll(
            "SELECT * FROM modules WHERE product_id = ? ORDER BY sort_order ASC, id ASC",
            [$productId]
        );

        if (!$modules) {
            return [];
        }

        $lessons = Database::fetchAll(
            "SELECT l.*, m.title AS module_title, m.id AS module_id,
                    COALESCE(lp.completed, 0) AS is_completed
             FROM lessons l
             JOIN modules m ON l.module_id = m.id
             LEFT JOIN lesson_progress lp
                ON lp.lesson_id = l.id
               AND lp.user_id = ?
             WHERE m.product_id = ?
             ORDER BY m.sort_order ASC, m.id ASC, l.sort_order ASC, l.id ASC",
            [$userId, $productId]
        );

        $lessonsByModule = [];
        foreach ($lessons as $lesson) {
            $lesson['is_completed'] = (int) $lesson['is_completed'];
            $lessonsByModule[$lesson['module_id']][] = $lesson;
        }

        foreach ($modules as &$module) {
            $module['lessons'] = $lessonsByModule[$module['id']] ?? [];
        }
        unset($module);

        return $modules;
    }

    private function findLessonById(array $modules, int $lessonId): ?array
    {
        foreach ($modules as $module) {
            foreach ($module['lessons'] as $lesson) {
                if ((int) $lesson['id'] === $lessonId) {
                    return $lesson;
                }
            }
        }

        return null;
    }
}
