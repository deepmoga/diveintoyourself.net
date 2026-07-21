<?php
require_once __DIR__ . '/includes/functions.php';

$page_slug = 'gallery';
$gallery_images = getActiveGalleryImages();

include __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <h1>Gallery</h1>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>/">Home</a>
            <span class="separator">/</span>
            <span>Gallery</span>
        </div>
    </div>
</section>

<section class="section gallery-page-section">
    <div class="container">
        <div class="section-header fade-in">
            <span class="sub-title">Moments</span>
            <h2>Our <span>Gallery</span></h2>
            <p>Explore moments from our meditation, mindfulness, and wellness journey.</p>
        </div>

        <?php if (!empty($gallery_images)): ?>
            <div class="gallery-grid">
                <?php foreach ($gallery_images as $item): ?>
                    <figure class="gallery-item fade-in">
                        <img src="<?php echo SITE_URL . '/' . htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['alt_text'] ?: $item['title']); ?>">
                    </figure>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-public-state">
                <i class="fas fa-images"></i>
                <h3>Gallery coming soon</h3>
                <p>New images will appear here once they are added from the admin portal.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
