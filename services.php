<?php
require_once __DIR__ . '/includes/functions.php';

$page_slug = 'services';
$settings = getAllSettings();
$services = getActiveServices();

include __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <h1>Services</h1>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>/">Home</a>
            <span class="separator">/</span>
            <span>Services</span>
        </div>
    </div>
</section>

<main class="inner-clean-page">
    <section class="section services-page-new">
        <div class="container">
            <div class="section-header">
                <span class="sub-title">Services</span>
                <h2>Personal Support for Inner and Outer Wellbeing</h2>
                <p>Explore practical, grounded sessions for self-understanding, calm, movement, and personal growth.</p>
            </div>

            <div class="services-clean-grid">
                <?php foreach ($services as $index => $service): ?>
                <a class="service-clean-card <?php echo $index === 4 ? 'featured' : ''; ?>" href="<?php echo SITE_URL . '/service/' . htmlspecialchars($service['slug'] ?? generateSlug($service['title'])); ?>">
                    <i class="<?php echo htmlspecialchars($service['icon'] ?? 'fas fa-spa'); ?>"></i>
                    <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                    <p><?php echo htmlspecialchars($service['description']); ?></p>
                    <span class="service-clean-link">Learn More <i class="fas fa-arrow-right"></i></span>
                </a>
                <?php endforeach; ?>
            </div>

            <div class="services-page-image">
                <img src="<?php echo SITE_URL; ?>/images/home/yoga-bow-pose.jpeg" alt="Yoga training session">
                <img src="<?php echo SITE_URL; ?>/images/home/yoga-lunge-pose.jpeg" alt="Personal yoga practice">
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
