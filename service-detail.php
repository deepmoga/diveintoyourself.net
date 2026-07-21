<?php
require_once __DIR__ . '/includes/functions.php';

$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';
$service = getServiceBySlug($slug);

if (!$service) {
    header('HTTP/1.0 404 Not Found');
    $page_slug = 'services';
    include __DIR__ . '/includes/header.php';
    ?>
    <section class="page-banner">
        <div class="container">
            <h1>Service Not Found</h1>
            <div class="breadcrumb">
                <a href="<?php echo SITE_URL; ?>/">Home</a>
                <span class="separator">/</span>
                <a href="<?php echo SITE_URL; ?>/services">Services</a>
                <span class="separator">/</span>
                <span>Not Found</span>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="container" style="text-align: center; padding: 60px 0;">
            <div style="font-size: 4rem; color: var(--primary); margin-bottom: 20px;">
                <i class="fas fa-search"></i>
            </div>
            <h2 style="margin-bottom: 15px; color: var(--dark);">Service Not Found</h2>
            <p style="margin-bottom: 30px; max-width: 500px; margin-left: auto; margin-right: auto;">The service you are looking for does not exist or may have been removed. Please browse our available services below.</p>
            <a href="<?php echo SITE_URL; ?>/services" class="btn btn-primary">View All Services <i class="fas fa-arrow-right"></i></a>
        </div>
    </section>
    <?php
    include __DIR__ . '/includes/footer.php';
    exit;
}

// Set up page meta
$settings = getAllSettings();
$site_name = $settings['site_name'] ?? 'Dive Into Yourself';

$page_title = $service['title'] . ' | Best Professional ' . $service['title'] . ' in Truganina';
$page_meta_desc = !empty($service['meta_description']) ? $service['meta_description'] : 'Experience the best professional ' . $service['title'] . ' at Dive Into Yourself in Truganina, Victoria. Top-rated ' . strtolower($service['title']) . ' programs for inner peace and spiritual growth.';
$page_meta_keywords = !empty($service['meta_keywords']) ? $service['meta_keywords'] : 'best ' . strtolower($service['title']) . ', professional ' . strtolower($service['title']) . ' Truganina, top ' . strtolower($service['title']) . ' Victoria, ' . strtolower($service['title']) . ' near me';

// Set page_slug for nav highlight and override meta for header
$page_slug = 'services';
$override_title = $page_title;
$override_description = $page_meta_desc;
$override_keywords = $page_meta_keywords;
$canonical_url = SITE_URL . '/service/' . htmlspecialchars($slug);

include __DIR__ . '/includes/header.php';

// Get related services (other active services, excluding current)
$all_services = getActiveServices();
$related_services = [];
$other_services = [];
foreach ($all_services as $s) {
    if ($s['id'] != $service['id']) {
        $other_services[] = $s;
    }
}
$related_services = array_slice($other_services, 0, 3);

// Prepare description content
$description_content = !empty($service['long_description']) ? $service['long_description'] : $service['description'];
?>

<!-- Schema.org Service Markup -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Service",
    "name": "<?php echo htmlspecialchars($service['title']); ?>",
    "description": "<?php echo htmlspecialchars(strip_tags($description_content)); ?>",
    "provider": {
        "@type": "HealthAndBeautyBusiness",
        "name": "<?php echo htmlspecialchars($site_name); ?>",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Truganina",
            "addressRegion": "Victoria",
            "postalCode": "3029",
            "addressCountry": "AU"
        },
        "url": "<?php echo SITE_URL; ?>"
    },
    "areaServed": {
        "@type": "Place",
        "name": "Truganina, Victoria, Australia"
    },
    "url": "<?php echo SITE_URL . '/service/' . htmlspecialchars($slug); ?>"
    <?php if (!empty($service['image'])): ?>
    ,"image": "<?php echo SITE_URL . '/' . htmlspecialchars($service['image']); ?>"
    <?php endif; ?>
}
</script>

<!-- ============================================
     PAGE BANNER
     ============================================ -->
<section class="page-banner">
    <div class="container">
        <h1><?php echo htmlspecialchars($service['title']); ?></h1>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>/">Home</a>
            <span class="separator">/</span>
            <a href="<?php echo SITE_URL; ?>/services">Services</a>
            <span class="separator">/</span>
            <span><?php echo htmlspecialchars($service['title']); ?></span>
        </div>
    </div>
</section>

<!-- ============================================
     SERVICE DETAIL
     ============================================ -->
<section class="section">
    <div class="container">
        <div class="service-detail-grid">
            <!-- Main Content -->
            <div class="service-detail-content">
                <!-- Service Image -->
                <div class="service-detail-image">
                    <?php if (!empty($service['image'])): ?>
                        <img src="<?php echo SITE_URL . '/' . htmlspecialchars($service['image']); ?>" alt="<?php echo htmlspecialchars($service['title']); ?> - Best Professional <?php echo htmlspecialchars($service['title']); ?> in Truganina">
                    <?php else: ?>
                        <?php
                        $placeholders = ['placeholder-purple', 'placeholder-teal', 'placeholder-warm', 'placeholder-cool'];
                        $placeholder_class = $placeholders[crc32($service['title']) % count($placeholders)];
                        ?>
                        <div class="placeholder-img <?php echo $placeholder_class; ?>" style="height:400px; display:flex; align-items:center; justify-content:center;">
                            <i class="<?php echo htmlspecialchars($service['icon'] ?? 'fas fa-spa'); ?>" style="font-size: 4rem;"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Service Body Content -->
                <div class="service-detail-body">
                    <?php
                    // If long_description contains HTML, output it directly; otherwise wrap in paragraphs
                    if (!empty($service['long_description'])) {
                        if ($service['long_description'] !== strip_tags($service['long_description'])) {
                            echo $service['long_description'];
                        } else {
                            echo '<p>' . nl2br(htmlspecialchars($service['long_description'])) . '</p>';
                        }
                    } else {
                        echo '<p>' . nl2br(htmlspecialchars($service['description'])) . '</p>';
                    }
                    ?>
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="service-sidebar">
                <!-- Quick Info Card -->
                <div class="sidebar-card">
                    <h3><i class="fas fa-info-circle" style="color: var(--primary); margin-right: 8px;"></i> Quick Info</h3>
                    <?php if (!empty($service['icon'])): ?>
                    <div class="info-row">
                        <i class="<?php echo htmlspecialchars($service['icon']); ?>"></i>
                        <span><?php echo htmlspecialchars($service['title']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- CTA Card -->
                <div class="sidebar-card sidebar-cta">
                    <h3>Join This Program</h3>
                    <p>Ready to experience the best <?php echo htmlspecialchars(strtolower($service['title'])); ?> in Truganina? Get in touch with us today.</p>
                    <a href="<?php echo SITE_URL; ?>/contact" class="btn btn-outline" style="color: #fff; border-color: #fff;">Contact Us <i class="fas fa-arrow-right"></i></a>
                </div>

                <!-- Other Services -->
                <?php if (!empty($other_services)): ?>
                <div class="sidebar-card">
                    <h3><i class="fas fa-list" style="color: var(--primary); margin-right: 8px;"></i> Our Services</h3>
                    <ul class="sidebar-services-list">
                        <?php foreach ($other_services as $os): ?>
                        <li>
                            <a href="<?php echo SITE_URL . '/service/' . htmlspecialchars($os['slug'] ?? generateSlug($os['title'])); ?>" <?php echo ($os['id'] == $service['id']) ? 'class="active"' : ''; ?>>
                                <i class="fas fa-chevron-right"></i>
                                <?php echo htmlspecialchars($os['title']); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</section>

<!-- ============================================
     RELATED SERVICES
     ============================================ -->
<?php if (!empty($related_services)): ?>
<section class="related-services">
    <div class="container">
        <div class="section-header fade-in">
            <span class="sub-title">Explore More</span>
            <h2>Related <span>Services</span></h2>
            <p>Discover other top-rated programs to complement your wellness journey at Dive Into Yourself.</p>
        </div>

        <div class="services-grid">
            <?php
            $delay = 0;
            $placeholders = ['placeholder-purple', 'placeholder-teal', 'placeholder-warm', 'placeholder-cool', 'placeholder-nature', 'placeholder-mixed'];
            foreach ($related_services as $index => $rs):
                $delay = ($delay % 3) + 1;
                $placeholder_class = $placeholders[$index % count($placeholders)];
            ?>
            <div class="service-card fade-in delay-<?php echo $delay; ?>">
                <div class="service-card-image">
                    <?php if (!empty($rs['image'])): ?>
                        <img src="<?php echo SITE_URL . '/' . htmlspecialchars($rs['image']); ?>" alt="<?php echo htmlspecialchars($rs['title']); ?>" style="height:220px; object-fit:cover;">
                    <?php else: ?>
                        <div class="placeholder-img <?php echo $placeholder_class; ?>" style="height:220px;">
                            <i class="<?php echo htmlspecialchars($rs['icon'] ?? 'fas fa-spa'); ?>"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="service-card-body">
                    <h3><?php echo htmlspecialchars($rs['title']); ?></h3>
                    <p><?php echo htmlspecialchars($rs['description']); ?></p>
                    <a href="<?php echo SITE_URL . '/service/' . htmlspecialchars($rs['slug'] ?? generateSlug($rs['title'])); ?>" class="service-card-link">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
