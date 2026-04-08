<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Área de Membros') ?> - <?= APP_NAME ?></title>
    <meta name="theme-color" content="#0A0A0A" id="themeColorMeta">
    <?= themeInitScript() ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">
</head>
<body class="app-body">
    <?php $user = currentUser(); ?>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="<?= url('dashboard') ?>" class="sidebar-logo">✦ <span>Sunyan</span></a>
        </div>
        <nav class="sidebar-nav">
            <a href="<?= url('dashboard') ?>" class="sidebar-link <?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                <span>Dashboard</span>
            </a>
            <a href="<?= url('products') ?>" class="sidebar-link <?= ($activePage ?? '') === 'products' ? 'active' : '' ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                <span>Meus Produtos</span>
            </a>
            <a href="<?= url('community') ?>" class="sidebar-link <?= ($activePage ?? '') === 'community' ? 'active' : '' ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span>Comunidade</span>
            </a>
            <?php if (($user['role'] ?? '') === 'admin'): ?>
                <div class="sidebar-divider"></div>
                <a href="<?= url('admin') ?>" class="sidebar-link <?= ($activePage ?? '') === 'admin' ? 'active' : '' ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    <span>Admin</span>
                </a>
            <?php endif; ?>
        </nav>
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-avatar"><?= e(mb_substr($user['name'] ?? 'U', 0, 1)) ?></div>
                <div class="sidebar-user-info">
                    <span class="sidebar-user-name"><?= e($user['name'] ?? '') ?></span>
                    <span class="sidebar-user-anon"><?= e($user['anonymous_name'] ?? '') ?></span>
                </div>
            </div>
            <a href="<?= url('logout') ?>" class="sidebar-link sidebar-logout">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                <span>Sair</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="topbar">
            <button class="topbar-toggle" id="sidebarToggle" aria-label="Abrir menu" aria-controls="sidebar" aria-expanded="false">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <h1 class="topbar-title"><?= e($pageTitle ?? '') ?></h1>
            <div class="topbar-right">
                <span class="topbar-greeting">Olá, <?= e($user['name'] ?? '') ?></span>
                <?= themeToggleButton('theme-toggle theme-toggle-topbar', 'Modo claro') ?>
            </div>
        </header>
        <div class="main-inner">
            <?php $flashError = flash('error'); $flashSuccess = flash('success'); ?>
            <?php if ($flashError): ?>
                <div class="alert alert-error"><?= e($flashError) ?></div>
            <?php endif; ?>
            <?php if ($flashSuccess): ?>
                <div class="alert alert-success"><?= e($flashSuccess) ?></div>
            <?php endif; ?>
