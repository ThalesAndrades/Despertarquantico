<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Admin') ?> - <?= APP_NAME ?></title>
    <meta name="theme-color" content="#0A0A0A" id="themeColorMeta">
    <?= themeInitScript() ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">
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
            <a href="<?= url('admin/applications') ?>" class="sidebar-link <?= ($adminPage ?? '') === 'applications' ? 'active' : '' ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M8 13h8"/><path d="M8 17h8"/></svg>
                <span>Aplicações</span>
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
            <button class="topbar-toggle" id="sidebarToggle" aria-label="Abrir menu" aria-controls="sidebar" aria-expanded="false">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <h1 class="topbar-title"><?= e($pageTitle ?? 'Admin') ?></h1>
            <div class="topbar-right">
                <?= themeToggleButton('theme-toggle theme-toggle-topbar', 'Modo claro') ?>
            </div>
        </header>
        <div class="main-inner">
            <?php $flashError = flash('error'); $flashSuccess = flash('success'); ?>
            <?php if ($flashError): ?><div class="alert alert-error"><?= e($flashError) ?></div><?php endif; ?>
            <?php if ($flashSuccess): ?><div class="alert alert-success"><?= e($flashSuccess) ?></div><?php endif; ?>
