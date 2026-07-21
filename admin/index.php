<?php
require_once __DIR__ . '/includes/auth.php';

$db = getDB();
ensureGalleryBlogTables();
$totalServices = $db->query("SELECT COUNT(*) FROM services")->fetchColumn();
$totalTestimonials = $db->query("SELECT COUNT(*) FROM testimonials")->fetchColumn();
$totalGallery = $db->query("SELECT COUNT(*) FROM gallery_images")->fetchColumn();
$totalBlogs = $db->query("SELECT COUNT(*) FROM blogs")->fetchColumn();
$totalPages = $db->query("SELECT COUNT(*) FROM pages")->fetchColumn();
$totalStats = $db->query("SELECT COUNT(*) FROM stats")->fetchColumn();

include __DIR__ . '/includes/header.php';
?>

<div class="card-grid">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-concierge-bell"></i></div>
        <div class="stat-info">
            <h3><?= $totalServices ?></h3>
            <p>Total Services</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon teal"><i class="fas fa-quote-right"></i></div>
        <div class="stat-info">
            <h3><?= $totalTestimonials ?></h3>
            <p>Total Testimonials</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-file-alt"></i></div>
        <div class="stat-info">
            <h3><?= $totalPages ?></h3>
            <p>Total Pages</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-images"></i></div>
        <div class="stat-info">
            <h3><?= $totalGallery ?></h3>
            <p>Total Gallery Images</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon teal"><i class="fas fa-blog"></i></div>
        <div class="stat-info">
            <h3><?= $totalBlogs ?></h3>
            <p>Total Blogs</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-chart-bar"></i></div>
        <div class="stat-info">
            <h3><?= $totalStats ?></h3>
            <p>Total Stats</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Quick Actions</h2>
    </div>
    <div class="quick-links">
        <a href="<?= ADMIN_URL ?>/homepage.php" class="quick-link">
            <i class="fas fa-home"></i>
            <span>Edit Homepage</span>
        </a>
        <a href="<?= ADMIN_URL ?>/services.php?action=add" class="quick-link">
            <i class="fas fa-plus"></i>
            <span>Add Service</span>
        </a>
        <a href="<?= ADMIN_URL ?>/gallery.php?action=add" class="quick-link">
            <i class="fas fa-images"></i>
            <span>Add Gallery Image</span>
        </a>
        <a href="<?= ADMIN_URL ?>/blogs.php?action=add" class="quick-link">
            <i class="fas fa-blog"></i>
            <span>Add Blog</span>
        </a>
        <a href="<?= ADMIN_URL ?>/testimonials.php?action=add" class="quick-link">
            <i class="fas fa-plus"></i>
            <span>Add Testimonial</span>
        </a>
        <a href="<?= ADMIN_URL ?>/settings.php" class="quick-link">
            <i class="fas fa-cog"></i>
            <span>Site Settings</span>
        </a>
        <a href="<?= ADMIN_URL ?>/pages.php" class="quick-link">
            <i class="fas fa-search"></i>
            <span>Manage SEO</span>
        </a>
        <a href="<?= SITE_URL ?>" class="quick-link" target="_blank">
            <i class="fas fa-external-link-alt"></i>
            <span>View Website</span>
        </a>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
