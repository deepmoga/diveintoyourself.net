<?php
require_once __DIR__ . '/includes/auth.php';

$pageSlugs = ['home', 'about', 'services', 'contact'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slug = sanitize($_POST['slug'] ?? '');
    if (in_array($slug, $pageSlugs)) {
        updatePageMeta(
            $slug,
            sanitize($_POST['page_title'] ?? ''),
            $_POST['meta_description'] ?? '',
            sanitize($_POST['meta_keywords'] ?? '')
        );
        setFlash('success', 'Page meta for "' . ucfirst($slug) . '" updated successfully.');
    }
    header('Location: ' . ADMIN_URL . '/pages.php');
    exit;
}

$editSlug = $_GET['edit'] ?? '';

include __DIR__ . '/includes/header.php';
?>

<?php if ($editSlug && in_array($editSlug, $pageSlugs)):
    $meta = getPageMeta($editSlug);
?>
    <a href="<?= ADMIN_URL ?>/pages.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Pages</a>

    <div class="card">
        <div class="card-header">
            <h2>Edit SEO Meta - <?= ucfirst($editSlug) ?> Page</h2>
        </div>
        <form method="POST">
            <input type="hidden" name="slug" value="<?= $editSlug ?>">
            <div class="form-group">
                <label>Page Title</label>
                <input type="text" name="page_title" class="form-control" value="<?= htmlspecialchars($meta['page_title']) ?>">
            </div>
            <div class="form-group">
                <label>Meta Description</label>
                <textarea name="meta_description" class="editor"><?= htmlspecialchars($meta['meta_description']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Meta Keywords</label>
                <textarea name="meta_keywords" class="form-control" rows="3"><?= htmlspecialchars($meta['meta_keywords']) ?></textarea>
                <p class="form-hint">Comma-separated keywords for search engines.</p>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        </form>
    </div>

<?php else: ?>

    <div class="card">
        <div class="card-header">
            <h2>All Pages</h2>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Page</th>
                        <th>Title</th>
                        <th>Meta Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pageSlugs as $slug):
                        $meta = getPageMeta($slug);
                    ?>
                    <tr>
                        <td><strong><?= ucfirst($slug) ?></strong></td>
                        <td><?= htmlspecialchars($meta['page_title'] ?: '(not set)') ?></td>
                        <td><?= htmlspecialchars(mb_strimwidth(strip_tags($meta['meta_description']), 0, 80, '...') ?: '(not set)') ?></td>
                        <td>
                            <a href="<?= ADMIN_URL ?>/pages.php?edit=<?= $slug ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
