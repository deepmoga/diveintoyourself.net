<?php
require_once __DIR__ . '/functions.php';

$settings = getAllSettings();
$page_meta = getPageMeta($page_slug ?? 'home');

$site_name = $settings['site_name'] ?? 'Dive Into Yourself';
$meta_title = !empty($page_meta['page_title']) ? htmlspecialchars($page_meta['page_title']) . ' | ' . $site_name : $site_name . ' - Best Meditation & Mindfulness Centre in Truganina, Victoria';
$meta_desc = !empty($page_meta['meta_description']) ? htmlspecialchars($page_meta['meta_description']) : ($settings['meta_description'] ?? 'Dive Into Yourself is the best professional meditation and mindfulness centre in Truganina, Victoria. Discover top holistic wellness programs, spiritual growth sessions, and inner peace workshops near you.');
$meta_keywords = !empty($page_meta['meta_keywords']) ? htmlspecialchars($page_meta['meta_keywords']) : ($settings['meta_keywords'] ?? 'best meditation centre, professional mindfulness, top spiritual growth, inner peace workshops, holistic wellness Truganina, best yoga Victoria');

// Allow pages to override meta tags (e.g. service detail pages)
if (isset($override_title)) $meta_title = htmlspecialchars($override_title);
if (isset($override_description)) $meta_desc = htmlspecialchars($override_description);
if (isset($override_keywords)) $meta_keywords = htmlspecialchars($override_keywords);
$logo = $settings['logo'] ?? 'images/logo.png';
$favicon = $settings['favicon'] ?? $logo;
$og_image = $settings['og_image'] ?? $logo;
$site_email = $settings['email'] ?? '';
$site_address = $settings['address'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- SEO Meta Tags -->
    <title><?php echo $meta_title; ?></title>
    <meta name="description" content="<?php echo $meta_desc; ?>">
    <meta name="keywords" content="<?php echo $meta_keywords; ?>">
    <meta name="author" content="<?php echo htmlspecialchars($site_name); ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo isset($canonical_url) ? $canonical_url : ''; ?>">

    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo $meta_title; ?>">
    <meta property="og:description" content="<?php echo $meta_desc; ?>">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?php echo SITE_URL . '/' . htmlspecialchars($og_image); ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo SITE_URL . '/' . htmlspecialchars($favicon); ?>">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/css/style.css">
    <?php if (($page_slug ?? '') === 'home'): ?>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/css/home.css">
    <?php endif; ?>

    <!-- Schema Markup -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "HealthAndBeautyBusiness",
        "name": "<?php echo htmlspecialchars($site_name); ?>",
        "description": "<?php echo htmlspecialchars($meta_desc); ?>",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "<?php echo htmlspecialchars($site_address); ?>",
            "addressLocality": "Truganina",
            "addressRegion": "Victoria",
            "postalCode": "3029",
            "addressCountry": "AU"
        },
        "url": "<?php echo SITE_URL; ?>",
        "image": "<?php echo SITE_URL . '/' . htmlspecialchars($og_image); ?>"
    }
    </script>
</head>
<body>

<!-- Preloader -->
<div class="preloader">
    <div class="preloader-spinner"></div>
</div>

<!-- Header -->
<header class="header" id="header">
    <div class="container">
        <a href="<?php echo SITE_URL; ?>/" class="logo">
            <img src="<?php echo SITE_URL . '/' . htmlspecialchars($logo); ?>" alt="<?php echo htmlspecialchars($site_name); ?> - Best Meditation Centre" title="<?php echo htmlspecialchars($site_name); ?>">
        </a>

        <?php $nav_services = getActiveServices(6); ?>
        <nav class="nav-menu" id="navMenu">
            <a href="<?php echo SITE_URL; ?>/" <?php echo ($page_slug ?? '') === 'home' ? 'class="active"' : ''; ?>>Home</a>
            <a href="<?php echo SITE_URL; ?>/about" <?php echo ($page_slug ?? '') === 'about' ? 'class="active"' : ''; ?>>About Us</a>
            <div class="nav-dropdown">
                <a href="<?php echo SITE_URL; ?>/services" <?php echo ($page_slug ?? '') === 'services' ? 'class="active"' : ''; ?>>Our Services <i class="fas fa-chevron-down"></i></a>
                <div class="dropdown-menu">
                    <?php foreach ($nav_services as $ns): ?>
                    <a href="<?php echo SITE_URL . '/service/' . htmlspecialchars($ns['slug'] ?? ''); ?>">
                        <i class="<?php echo htmlspecialchars($ns['icon'] ?? 'fas fa-spa'); ?>"></i>
                        <?php echo htmlspecialchars($ns['title']); ?>
                    </a>
                    <?php endforeach; ?>
                    <a href="<?php echo SITE_URL; ?>/services" class="dropdown-all">View All Services <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <a href="<?php echo SITE_URL; ?>/gallery" <?php echo ($page_slug ?? '') === 'gallery' ? 'class="active"' : ''; ?>>Gallery</a>
            <a href="<?php echo SITE_URL; ?>/blogs" <?php echo ($page_slug ?? '') === 'blogs' ? 'class="active"' : ''; ?>>Blogs</a>
            <a href="<?php echo SITE_URL; ?>/contact" <?php echo ($page_slug ?? '') === 'contact' ? 'class="active"' : ''; ?>>Contact</a>
        </nav>

        <div class="header-cta">
            <a href="<?php echo SITE_URL; ?>/contact" class="btn btn-primary">Join Us Now</a>
        </div>

        <div class="menu-toggle" id="menuToggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</header>
