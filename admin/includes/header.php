<?php
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$adminName = $_SESSION['admin_name'] ?? 'Admin';

$pageTitles = [
    'index' => 'Dashboard',
    'settings' => 'Site Settings',
    'pages' => 'Page SEO Meta',
    'homepage' => 'Homepage Sections',
    'services' => 'Services',
    'testimonials' => 'Testimonials',
    'stats' => 'Stats',
    'features' => 'Why Choose Us',
    'gallery' => 'Gallery',
    'blogs' => 'Blogs',
];
$pageTitle = $pageTitles[$currentPage] ?? 'Admin Panel';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - Dive Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= ADMIN_URL ?>/css/admin.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <a href="<?= SITE_URL ?>">
            <span class="logo-text">Dive Into Yourself</span>
        </a>
    </div>
    <nav class="sidebar-nav">
        <a href="<?= ADMIN_URL ?>/index.php" class="nav-link <?= $currentPage === 'index' ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
        </a>
        <div class="nav-section-label">Content</div>
        <a href="<?= ADMIN_URL ?>/homepage.php" class="nav-link <?= $currentPage === 'homepage' ? 'active' : '' ?>">
            <i class="fas fa-home"></i><span>Homepage Sections</span>
        </a>
        <a href="<?= ADMIN_URL ?>/services.php" class="nav-link <?= $currentPage === 'services' ? 'active' : '' ?>">
            <i class="fas fa-concierge-bell"></i><span>Services</span>
        </a>
        <a href="<?= ADMIN_URL ?>/gallery.php" class="nav-link <?= $currentPage === 'gallery' ? 'active' : '' ?>">
            <i class="fas fa-images"></i><span>Gallery</span>
        </a>
        <a href="<?= ADMIN_URL ?>/blogs.php" class="nav-link <?= $currentPage === 'blogs' ? 'active' : '' ?>">
            <i class="fas fa-blog"></i><span>Blogs</span>
        </a>
        <a href="<?= ADMIN_URL ?>/testimonials.php" class="nav-link <?= $currentPage === 'testimonials' ? 'active' : '' ?>">
            <i class="fas fa-quote-right"></i><span>Testimonials</span>
        </a>
        <a href="<?= ADMIN_URL ?>/stats.php" class="nav-link <?= $currentPage === 'stats' ? 'active' : '' ?>">
            <i class="fas fa-chart-bar"></i><span>Stats</span>
        </a>
        <a href="<?= ADMIN_URL ?>/features.php" class="nav-link <?= $currentPage === 'features' ? 'active' : '' ?>">
            <i class="fas fa-star"></i><span>Why Choose Us</span>
        </a>
        <div class="nav-section-label">Settings</div>
        <a href="<?= ADMIN_URL ?>/settings.php" class="nav-link <?= $currentPage === 'settings' ? 'active' : '' ?>">
            <i class="fas fa-cog"></i><span>Site Settings</span>
        </a>
        <a href="<?= ADMIN_URL ?>/pages.php" class="nav-link <?= $currentPage === 'pages' ? 'active' : '' ?>">
            <i class="fas fa-file-alt"></i><span>Page SEO</span>
        </a>
    </nav>
    <div class="sidebar-footer">
        <a href="<?= ADMIN_URL ?>/logout.php" class="nav-link logout-link">
            <i class="fas fa-sign-out-alt"></i><span>Logout</span>
        </a>
    </div>
</aside>

<div class="main-wrapper">
    <header class="topbar">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="topbar-title"><?= htmlspecialchars($pageTitle) ?></h1>
        <div class="topbar-right">
            <span class="admin-name"><i class="fas fa-user-circle"></i> <?= htmlspecialchars($adminName) ?></span>
            <a href="<?= ADMIN_URL ?>/logout.php" class="btn btn-sm btn-danger">Logout</a>
        </div>
    </header>

    <main class="main-content">
        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <?= $flash['message'] ?>
                <button class="alert-close">&times;</button>
            </div>
        <?php endif; ?>
