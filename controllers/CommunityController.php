<?php

class CommunityController
{
    public function index(): void
    {
        requireAuth();

        $category = $_GET['category'] ?? '';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 15;
        $offset = ($page - 1) * $perPage;

        $where = "WHERE cp.is_visible = 1";
        $params = [];

        if ($category && in_array($category, ['geral', 'desabafo', 'duvidas', 'conquistas', 'dicas'])) {
            $where .= " AND cp.category = ?";
            $params[] = $category;
        }

        $totalPosts = Database::count(
            "SELECT COUNT(*) FROM community_posts cp $where",
            $params
        );

        $posts = Database::fetchAll(
            "SELECT cp.*, u.anonymous_name,
                    (SELECT COUNT(*) FROM community_comments cc WHERE cc.post_id = cp.id AND cc.is_visible = 1) as comment_count,
                    (SELECT COUNT(*) FROM community_likes cl WHERE cl.post_id = cp.id) as like_count,
                    (SELECT COUNT(*) FROM community_likes cl WHERE cl.post_id = cp.id AND cl.user_id = ?) as user_liked
             FROM community_posts cp
             JOIN users u ON cp.user_id = u.id
             $where
             ORDER BY cp.is_pinned DESC, cp.created_at DESC
             LIMIT ? OFFSET ?",
            array_merge([$_SESSION['user_id']], $params, [$perPage, $offset])
        );

        $totalPages = ceil($totalPosts / $perPage);
        $categories = ['geral', 'desabafo', 'duvidas', 'conquistas', 'dicas'];

        $pageTitle = 'Comunidade';
        $activePage = 'community';
        require VIEWS_PATH . '/layouts/app.php';
        require VIEWS_PATH . '/community/index.php';
        closeLayout();
    }

    public function createForm(): void
    {
        requireAuth();
        $error = flash('error');
        $categories = ['geral', 'desabafo', 'duvidas', 'conquistas', 'dicas'];

        $pageTitle = 'Novo Tópico';
        $activePage = 'community';
        require VIEWS_PATH . '/layouts/app.php';
        require VIEWS_PATH . '/community/create.php';
        closeLayout();
    }

    public function create(): void
    {
        requireAuth();
        CSRF::check();

        $title = trim($_POST['title'] ?? '');
        $body = trim($_POST['body'] ?? '');
        $category = $_POST['category'] ?? 'geral';

        if (empty($title) || empty($body)) {
            flash('error', 'Preencha todos os campos.');
            redirect('community/new');
            return;
        }

        if (strlen($title) > 200) {
            flash('error', 'O título deve ter no máximo 200 caracteres.');
            redirect('community/new');
            return;
        }

        $validCategories = ['geral', 'desabafo', 'duvidas', 'conquistas', 'dicas'];
        if (!in_array($category, $validCategories)) {
            $category = 'geral';
        }

        $id = Database::insert(
            "INSERT INTO community_posts (user_id, category, title, body) VALUES (?, ?, ?, ?)",
            [$_SESSION['user_id'], $category, $title, $body]
        );

        flash('success', 'Tópico criado com sucesso!');
        redirect('community/topic/' . $id);
    }

    public function topic(string $id): void
    {
        requireAuth();
        $postId = (int) $id;

        $post = Database::fetch(
            "SELECT cp.*, u.anonymous_name,
                    (SELECT COUNT(*) FROM community_likes cl WHERE cl.post_id = cp.id) as like_count,
                    (SELECT COUNT(*) FROM community_likes cl WHERE cl.post_id = cp.id AND cl.user_id = ?) as user_liked
             FROM community_posts cp
             JOIN users u ON cp.user_id = u.id
             WHERE cp.id = ? AND cp.is_visible = 1",
            [$_SESSION['user_id'], $postId]
        );

        if (!$post) {
            flash('error', 'Tópico não encontrado.');
            redirect('community');
            return;
        }

        $comments = Database::fetchAll(
            "SELECT cc.*, u.anonymous_name,
                    (SELECT COUNT(*) FROM community_likes cl WHERE cl.comment_id = cc.id) as like_count,
                    (SELECT COUNT(*) FROM community_likes cl WHERE cl.comment_id = cc.id AND cl.user_id = ?) as user_liked
             FROM community_comments cc
             JOIN users u ON cc.user_id = u.id
             WHERE cc.post_id = ? AND cc.is_visible = 1
             ORDER BY cc.created_at ASC",
            [$_SESSION['user_id'], $postId]
        );

        $error = flash('error');
        $success = flash('success');

        $pageTitle = $post['title'];
        $activePage = 'community';
        require VIEWS_PATH . '/layouts/app.php';
        require VIEWS_PATH . '/community/topic.php';
        closeLayout();
    }

    public function comment(): void
    {
        requireAuth();
        CSRF::check();

        $postId = (int) ($_POST['post_id'] ?? 0);
        $body = trim($_POST['body'] ?? '');

        if (empty($body) || $postId < 1) {
            flash('error', 'Escreva seu comentário.');
            redirect('community/topic/' . $postId);
            return;
        }

        Database::insert(
            "INSERT INTO community_comments (post_id, user_id, body) VALUES (?, ?, ?)",
            [$postId, $_SESSION['user_id'], $body]
        );

        redirect('community/topic/' . $postId);
    }

    public function like(): void
    {
        requireAuth();
        CSRF::check();

        $postId = !empty($_POST['post_id']) ? (int) $_POST['post_id'] : null;
        $commentId = !empty($_POST['comment_id']) ? (int) $_POST['comment_id'] : null;
        $userId = $_SESSION['user_id'];
        $redirect = $_POST['redirect'] ?? 'community';
        // Prevent open redirect - only allow internal paths
        $redirect = ltrim($redirect, '/');
        if (preg_match('/^https?:\/\//i', $redirect) || str_contains($redirect, '..')) {
            $redirect = 'community';
        }

        if ($postId) {
            $existing = Database::fetch(
                "SELECT id FROM community_likes WHERE user_id = ? AND post_id = ?",
                [$userId, $postId]
            );
            if ($existing) {
                Database::query("DELETE FROM community_likes WHERE id = ?", [$existing['id']]);
            } else {
                Database::insert(
                    "INSERT INTO community_likes (user_id, post_id) VALUES (?, ?)",
                    [$userId, $postId]
                );
            }
        } elseif ($commentId) {
            $existing = Database::fetch(
                "SELECT id FROM community_likes WHERE user_id = ? AND comment_id = ?",
                [$userId, $commentId]
            );
            if ($existing) {
                Database::query("DELETE FROM community_likes WHERE id = ?", [$existing['id']]);
            } else {
                Database::insert(
                    "INSERT INTO community_likes (user_id, comment_id) VALUES (?, ?)",
                    [$userId, $commentId]
                );
            }
        }

        redirect($redirect);
    }
}
