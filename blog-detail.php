<?php
require_once __DIR__ . '/includes/functions.php';

$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';
$blog = getBlogBySlug($slug);

if (!$blog) {
    header('HTTP/1.0 404 Not Found');
    $page_slug = 'blogs';
    include __DIR__ . '/includes/header.php';
    ?>
    <section class="page-banner">
        <div class="container">
            <h1>Blog Not Found</h1>
            <div class="breadcrumb">
                <a href="<?php echo SITE_URL; ?>/">Home</a>
                <span class="separator">/</span>
                <a href="<?php echo SITE_URL; ?>/blogs">Blogs</a>
                <span class="separator">/</span>
                <span>Not Found</span>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="empty-public-state">
                <i class="fas fa-search"></i>
                <h3>Blog Not Found</h3>
                <p>The blog you are looking for does not exist or may have been removed.</p>
                <a href="<?php echo SITE_URL; ?>/blogs" class="btn btn-primary">View Blogs</a>
            </div>
        </div>
    </section>
    <?php
    include __DIR__ . '/includes/footer.php';
    exit;
}

$page_slug = 'blogs';
$override_title = $blog['title'] . ' | Dive Into Yourself';
$override_description = $blog['meta_description'] ?: ($blog['excerpt'] ?: substr(strip_tags($blog['content']), 0, 155));
$override_keywords = $blog['meta_keywords'] ?: '';
$canonical_url = SITE_URL . '/blog/' . htmlspecialchars($slug);

include __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <h1><?php echo htmlspecialchars($blog['title']); ?></h1>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>/">Home</a>
            <span class="separator">/</span>
            <a href="<?php echo SITE_URL; ?>/blogs">Blogs</a>
            <span class="separator">/</span>
            <span><?php echo htmlspecialchars($blog['title']); ?></span>
        </div>
    </div>
</section>

<section class="section blog-detail-section">
    <div class="container narrow-container">
        <?php if (!empty($blog['image'])): ?>
            <div class="blog-detail-image">
                <img src="<?php echo SITE_URL . '/' . htmlspecialchars($blog['image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>">
            </div>
        <?php endif; ?>
        <?php if (!empty($blog['published_at'])): ?>
            <time class="blog-detail-date"><?php echo date('F j, Y', strtotime($blog['published_at'])); ?></time>
        <?php endif; ?>
        <div class="blog-detail-content">
            <?php echo $blog['content'] !== strip_tags($blog['content']) ? $blog['content'] : '<p>' . nl2br(htmlspecialchars($blog['content'])) . '</p>'; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
