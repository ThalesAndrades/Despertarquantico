<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Admin') ?> - <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">
    <style>
        .admin-nav { display:flex; gap:6px; margin-bottom:32px; flex-wrap:wrap; }
        .admin-nav a {
            padding:10px 18px; border-radius:10px; font-size:13px; font-weight:600;
            text-decoration:none; color:#86868B; background:#fff;
            box-shadow:0 1px 2px rgba(0,0,0,0.04); transition:all 0.2s;
        }
        .admin-nav a:hover { color:#1D1D1F; background:#F5F5F7; }
        .admin-nav a.active { color:#fff; background:#6B21A8; }
    </style>
</head>
<body class="app-body">
    <?php $user = currentUser(); ?>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="<?= url('admin') ?>" class="sidebar-logo">✦ <span>Admin</span></a>
        </div>
        <nav class="sidebar-nav">
            <a href="<?= url('admin') ?>" class="sidebar-link <?= ($adminPage ?? '') === 'dashboard' ? 'active' : '' ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                <span>Dashboard</span>
            </a>
            <a href="<?= url('admin/users') ?>" class="sidebar-link <?= ($adminPage ?? '') === 'users' ? 'active' : '' ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                <span>Usuários</span>
            </a>
            <a href="<?= url('admin/products') ?>" class="sidebar-link <?= ($adminPage ?? '') === 'products' ? 'active' : '' ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                <span>Produtos</span>
            </a>
            <a href="<?= url('admin/orders') ?>" class="sidebar-link <?= ($adminPage ?? '') === 'orders' ? 'active' : '' ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                <span>Pedidos</span>
            </a>
            <a href="<?= url('admin/community') ?>" class="sidebar-link <?= ($adminPage ?? '') === 'community' ? 'active' : '' ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <span>Comunidade</span>
            </a>
            <div class="sidebar-divider"></div>
            <a href="<?= url('dashboard') ?>" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                <span>Área de Membros</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="<?= url('logout') ?>" class="sidebar-link sidebar-logout">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                <span>Sair</span>
            </a>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <button class="topbar-toggle" id="sidebarToggle" aria-label="Menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <h1 class="topbar-title"><?= e($pageTitle ?? 'Admin') ?></h1>
        </header>
        <div class="main-inner">
            <?php $flashError = flash('error'); $flashSuccess = flash('success'); ?>
            <?php if ($flashError): ?><div class="alert alert-error"><?= e($flashError) ?></div><?php endif; ?>
            <?php if ($flashSuccess): ?><div class="alert alert-success"><?= e($flashSuccess) ?></div><?php endif; ?>
