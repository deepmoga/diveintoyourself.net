<?php
require_once __DIR__ . '/includes/functions.php';

$page_slug = 'blogs';
$blogs = getActiveBlogs();

include __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <h1>Blogs</h1>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>/">Home</a>
            <span class="separator">/</span>
            <span>Blogs</span>
        </div>
    </div>
</section>

<section class="section blog-page-section">
    <div class="container">
        <div class="section-header fade-in">
            <span class="sub-title">Insights</span>
            <h2>Latest <span>Blogs</span></h2>
            <p>Guidance, reflections, and wellness thoughts from Dive Into Yourself.</p>
        </div>

        <?php if (!empty($blogs)): ?>
            <div class="blog-grid">
                <?php foreach ($blogs as $blog): ?>
                    <article class="blog-card fade-in">
                        <?php if (!empty($blog['image'])): ?>
                            <a href="<?php echo SITE_URL . '/blog/' . htmlspecialchars($blog['slug']); ?>" class="blog-card-image">
                                <img src="<?php echo SITE_URL . '/' . htmlspecialchars($blog['image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                            </a>
                        <?php endif; ?>
                        <div class="blog-card-body">
                            <?php if (!empty($blog['published_at'])): ?>
                                <time><?php echo date('F j, Y', strtotime($blog['published_at'])); ?></time>
                            <?php endif; ?>
                            <h3><a href="<?php echo SITE_URL . '/blog/' . htmlspecialchars($blog['slug']); ?>"><?php echo htmlspecialchars($blog['title']); ?></a></h3>
                            <p><?php echo htmlspecialchars($blog['excerpt'] ?: substr(strip_tags($blog['content']), 0, 150)); ?></p>
                            <a href="<?php echo SITE_URL . '/blog/' . htmlspecialchars($blog['slug']); ?>" class="service-card-link">Read More <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-public-state">
                <i class="fas fa-blog"></i>
                <h3>Blogs coming soon</h3>
                <p>New articles will appear here once they are uploaded from the admin portal.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
