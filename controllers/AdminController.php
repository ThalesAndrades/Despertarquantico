<?php

class AdminController
{
    public function dashboard(): void
    {
        requireAdmin();

        $totalUsers = Database::count("SELECT COUNT(*) FROM users WHERE role = 'member'");
        $totalProducts = Database::count("SELECT COUNT(*) FROM products");
        $totalOrders = Database::count("SELECT COUNT(*) FROM orders WHERE status = 'paid'");
        $totalRevenue = Database::fetch("SELECT COALESCE(SUM(amount), 0) as total FROM orders WHERE status = 'paid'")['total'];
        $recentOrders = Database::fetchAll(
            "SELECT o.*, p.title as product_title FROM orders o
             JOIN products p ON o.product_id = p.id
             ORDER BY o.created_at DESC LIMIT 10"
        );
        $recentUsers = Database::fetchAll("SELECT * FROM users ORDER BY created_at DESC LIMIT 5");
        $totalPosts = Database::count("SELECT COUNT(*) FROM community_posts");

        $pageTitle = 'Admin Dashboard';
        $adminPage = 'dashboard';
        require VIEWS_PATH . '/layouts/admin.php';
        require VIEWS_PATH . '/admin/dashboard.php';
        closeLayout();
    }

    public function users(): void
    {
        requireAdmin();
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $search = trim($_GET['search'] ?? '');

        $where = '';
        $params = [];
        if ($search) {
            $where = "WHERE name LIKE ? OR email LIKE ? OR anonymous_name LIKE ?";
            $params = ["%$search%", "%$search%", "%$search%"];
        }

        $totalUsers = Database::count("SELECT COUNT(*) FROM users $where", $params);
        $users = Database::fetchAll("SELECT * FROM users $where ORDER BY created_at DESC LIMIT ? OFFSET ?", array_merge($params, [$perPage, $offset]));
        $totalPages = ceil($totalUsers / $perPage);

        // Get products for grant access dropdown
        $products = Database::fetchAll("SELECT id, title FROM products WHERE is_active = 1 ORDER BY title");

        $pageTitle = 'Gerenciar Usuários';
        $adminPage = 'users';
        require VIEWS_PATH . '/layouts/admin.php';
        require VIEWS_PATH . '/admin/users.php';
        closeLayout();
    }

    public function toggleUser(): void
    {
        requireAdmin();
        CSRF::check();
        $userId = (int) ($_POST['user_id'] ?? 0);
        if ($userId > 0) {
            Database::query("UPDATE users SET is_active = NOT is_active WHERE id = ? AND role != 'admin'", [$userId]);
            flash('success', 'Status do usuário atualizado.');
        }
        redirect('admin/users');
    }

    public function grantAccess(): void
    {
        requireAdmin();
        CSRF::check();
        $userId = (int) ($_POST['user_id'] ?? 0);
        $productId = (int) ($_POST['product_id'] ?? 0);
        if ($userId > 0 && $productId > 0) {
            Database::query("INSERT IGNORE INTO user_products (user_id, product_id) VALUES (?, ?)", [$userId, $productId]);
            flash('success', 'Acesso concedido com sucesso.');
        }
        redirect('admin/users');
    }

    public function products(): void
    {
        requireAdmin();
        $products = Database::fetchAll(
            "SELECT p.*, COALESCE(access_stats.student_count, 0) AS student_count
             FROM products p
             LEFT JOIN (
                SELECT product_id, COUNT(*) AS student_count
                FROM user_products
                GROUP BY product_id
             ) access_stats ON access_stats.product_id = p.id
             ORDER BY p.sort_order ASC, p.created_at DESC"
        );

        $pageTitle = 'Gerenciar Produtos';
        $adminPage = 'products';
        require VIEWS_PATH . '/layouts/admin.php';
        require VIEWS_PATH . '/admin/products.php';
        closeLayout();
    }

    public function productForm(string $id = ''): void
    {
        requireAdmin();
        $product = null;
        if ($id) {
            $product = Database::fetch("SELECT * FROM products WHERE id = ?", [(int) $id]);
        }
        $error = flash('error');

        $pageTitle = $product ? 'Editar Produto' : 'Novo Produto';
        $adminPage = 'products';
        require VIEWS_PATH . '/layouts/admin.php';
        require VIEWS_PATH . '/admin/product-form.php';
        closeLayout();
    }

    public function productSave(string $id = ''): void
    {
        requireAdmin();
        CSRF::check();

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $shortDescription = trim($_POST['short_description'] ?? '');
        $price = (float) ($_POST['price'] ?? 0);
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $slug = slugify($title);

        if (empty($title)) {
            flash('error', 'O título é obrigatório.');
            redirect($id ? "admin/products/edit/$id" : 'admin/products/create');
            return;
        }

        $coverImage = null;
        if (!empty($_FILES['cover_image']['name'])) {
            $coverImage = uploadImage($_FILES['cover_image'], 'products');
        }

        if ($id) {
            $sql = "UPDATE products SET title=?, slug=?, description=?, short_description=?, price=?, sort_order=?, is_active=?";
            $params = [$title, $slug, $description, $shortDescription, $price, $sortOrder, $isActive];
            if ($coverImage) {
                $sql .= ", cover_image=?";
                $params[] = $coverImage;
            }
            $sql .= " WHERE id=?";
            $params[] = (int) $id;
            Database::query($sql, $params);
            flash('success', 'Produto atualizado!');
            redirect("admin/products/edit/$id");
        } else {
            $params = [$title, $slug, $description, $shortDescription, $coverImage, $price, $sortOrder, $isActive];
            $newId = Database::insert(
                "INSERT INTO products (title, slug, description, short_description, cover_image, price, sort_order, is_active) VALUES (?,?,?,?,?,?,?,?)",
                $params
            );
            flash('success', 'Produto criado!');
            redirect("admin/products/edit/$newId");
        }
    }

    public function productDelete(string $id): void
    {
        requireAdmin();
        CSRF::check();
        Database::query("DELETE FROM products WHERE id = ?", [(int) $id]);
        flash('success', 'Produto excluído.');
        redirect('admin/products');
    }

    public function content(string $id): void
    {
        requireAdmin();
        $product = Database::fetch("SELECT * FROM products WHERE id = ?", [(int) $id]);
        if (!$product) { redirect('admin/products'); return; }

        $modules = $this->loadModulesWithLessons($product['id']);

        $error = flash('error');
        $success = flash('success');

        $pageTitle = 'Conteúdo: ' . $product['title'];
        $adminPage = 'products';
        require VIEWS_PATH . '/layouts/admin.php';
        require VIEWS_PATH . '/admin/content.php';
        closeLayout();
    }

    public function moduleSave(): void
    {
        requireAdmin();
        CSRF::check();
        $productId = (int) ($_POST['product_id'] ?? 0);
        $moduleId = (int) ($_POST['module_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);

        if (empty($title) || $productId < 1) {
            flash('error', 'Título é obrigatório.');
            redirect("admin/products/$productId/content");
            return;
        }

        if ($moduleId > 0) {
            Database::query("UPDATE modules SET title=?, sort_order=? WHERE id=?", [$title, $sortOrder, $moduleId]);
        } else {
            Database::insert("INSERT INTO modules (product_id, title, sort_order) VALUES (?,?,?)", [$productId, $title, $sortOrder]);
        }
        flash('success', 'Módulo salvo!');
        redirect("admin/products/$productId/content");
    }

    public function lessonSave(): void
    {
        requireAdmin();
        CSRF::check();
        $moduleId = (int) ($_POST['module_id'] ?? 0);
        $lessonId = (int) ($_POST['lesson_id'] ?? 0);
        $productId = (int) ($_POST['product_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $contentType = $_POST['content_type'] ?? 'video';
        $contentUrl = trim($_POST['content_url'] ?? '');
        $contentBody = trim($_POST['content_body'] ?? '');
        $duration = (int) ($_POST['duration_minutes'] ?? 0);
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);

        if (empty($title) || $moduleId < 1) {
            flash('error', 'Título e módulo são obrigatórios.');
            redirect("admin/products/$productId/content");
            return;
        }

        if ($lessonId > 0) {
            Database::query(
                "UPDATE lessons SET title=?, content_type=?, content_url=?, content_body=?, duration_minutes=?, sort_order=? WHERE id=?",
                [$title, $contentType, $contentUrl, $contentBody, $duration, $sortOrder, $lessonId]
            );
        } else {
            Database::insert(
                "INSERT INTO lessons (module_id, title, content_type, content_url, content_body, duration_minutes, sort_order) VALUES (?,?,?,?,?,?,?)",
                [$moduleId, $title, $contentType, $contentUrl, $contentBody, $duration, $sortOrder]
            );
        }
        flash('success', 'Aula salva!');
        redirect("admin/products/$productId/content");
    }

    public function lessonDelete(string $id): void
    {
        requireAdmin();
        CSRF::check();
        $lesson = Database::fetch("SELECT l.*, m.product_id FROM lessons l JOIN modules m ON l.module_id = m.id WHERE l.id = ?", [(int) $id]);
        if ($lesson) {
            Database::query("DELETE FROM lessons WHERE id = ?", [(int) $id]);
            flash('success', 'Aula excluída.');
            redirect("admin/products/{$lesson['product_id']}/content");
        } else {
            redirect('admin/products');
        }
    }

    public function orders(): void
    {
        requireAdmin();
        $orders = Database::fetchAll(
            "SELECT o.*, p.title as product_title, u.name as user_name, u.email as user_email
             FROM orders o
             JOIN products p ON o.product_id = p.id
             LEFT JOIN users u ON o.user_id = u.id
             ORDER BY o.created_at DESC
             LIMIT 100"
        );

        $pageTitle = 'Pedidos';
        $adminPage = 'orders';
        require VIEWS_PATH . '/layouts/admin.php';
        require VIEWS_PATH . '/admin/orders.php';
        closeLayout();
    }

    public function applications(): void
    {
        requireAdmin();
        $status = trim($_GET['status'] ?? '');
        $params = [];
        $where = '';
        if ($status !== '' && in_array($status, ['new', 'contacted', 'qualified', 'unqualified'], true)) {
            $where = "WHERE status = ?";
            $params[] = $status;
        }

        $applications = Database::fetchAll(
            "SELECT * FROM high_ticket_applications
             $where
             ORDER BY created_at DESC
             LIMIT 200",
            $params
        );

        $pageTitle = 'Aplicações (High Ticket)';
        $adminPage = 'applications';
        require VIEWS_PATH . '/layouts/admin.php';
        require VIEWS_PATH . '/admin/applications.php';
        closeLayout();
    }

    public function community(): void
    {
        requireAdmin();
        $posts = Database::fetchAll(
            "SELECT cp.*, u.anonymous_name, u.name as real_name, u.email,
                    COALESCE(comment_stats.comment_count, 0) AS comment_count
             FROM community_posts cp
             JOIN users u ON cp.user_id = u.id
             LEFT JOIN (
                SELECT post_id, COUNT(*) AS comment_count
                FROM community_comments
                GROUP BY post_id
             ) comment_stats ON comment_stats.post_id = cp.id
             ORDER BY cp.created_at DESC
             LIMIT 100"
        );

        $pageTitle = 'Moderar Comunidade';
        $adminPage = 'community';
        require VIEWS_PATH . '/layouts/admin.php';
        require VIEWS_PATH . '/admin/community.php';
        closeLayout();
    }

    public function togglePost(string $id): void
    {
        requireAdmin();
        CSRF::check();
        Database::query("UPDATE community_posts SET is_visible = NOT is_visible WHERE id = ?", [(int) $id]);
        flash('success', 'Visibilidade do post atualizada.');
        redirect('admin/community');
    }

    private function loadModulesWithLessons(int $productId): array
    {
        $modules = Database::fetchAll(
            "SELECT * FROM modules WHERE product_id = ? ORDER BY sort_order ASC, id ASC",
            [$productId]
        );

        if (!$modules) {
            return [];
        }

        $lessons = Database::fetchAll(
            "SELECT l.*
             FROM lessons l
             JOIN modules m ON l.module_id = m.id
             WHERE m.product_id = ?
             ORDER BY m.sort_order ASC, m.id ASC, l.sort_order ASC, l.id ASC",
            [$productId]
        );

        $lessonsByModule = [];
        foreach ($lessons as $lesson) {
            $lessonsByModule[$lesson['module_id']][] = $lesson;
        }

        foreach ($modules as &$module) {
            $module['lessons'] = $lessonsByModule[$module['id']] ?? [];
        }
        unset($module);

        return $modules;
    }
}
