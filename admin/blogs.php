<?php
require_once __DIR__ . '/includes/auth.php';

$db = getDB();
ensureGalleryBlogTables();
$action = $_GET['action'] ?? 'list';
$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postAction = $_POST['action'] ?? '';

    if ($postAction === 'delete') {
        $delId = (int)($_POST['id'] ?? 0);
        $blog = getBlog($delId);
        if ($blog) {
            if (!empty($blog['image'])) deleteImage($blog['image']);
            $stmt = $db->prepare("DELETE FROM blogs WHERE id = ?");
            $stmt->execute([$delId]);
            setFlash('success', 'Blog deleted successfully.');
        }
        header('Location: ' . ADMIN_URL . '/blogs.php');
        exit;
    }

    $title = sanitize($_POST['title'] ?? '');
    $slug = sanitize($_POST['slug'] ?? '');
    $excerpt = $_POST['excerpt'] ?? '';
    $content = $_POST['content'] ?? '';
    $meta_description = sanitize($_POST['meta_description'] ?? '');
    $meta_keywords = sanitize($_POST['meta_keywords'] ?? '');
    $published_at = !empty($_POST['published_at']) ? $_POST['published_at'] : null;
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $status = isset($_POST['status']) ? 1 : 0;
    $editId = (int)($_POST['id'] ?? 0);

    if (empty($slug)) {
        $slug = generateSlug($title);
    }

    $image = null;
    if (!empty($_FILES['image']['tmp_name'])) {
        $image = uploadImage($_FILES['image'], 'blogs');
    }

    if ($editId > 0) {
        $existing = getBlog($editId);
        if ($image) {
            if (!empty($existing['image'])) deleteImage($existing['image']);
        } else {
            $image = $existing['image'] ?? '';
        }
        $stmt = $db->prepare("UPDATE blogs SET title=?, slug=?, excerpt=?, content=?, image=?, meta_description=?, meta_keywords=?, published_at=?, sort_order=?, status=? WHERE id=?");
        $stmt->execute([$title, $slug, $excerpt, $content, $image, $meta_description, $meta_keywords, $published_at, $sort_order, $status, $editId]);
        setFlash('success', 'Blog updated successfully.');
    } else {
        $stmt = $db->prepare("INSERT INTO blogs (title, slug, excerpt, content, image, meta_description, meta_keywords, published_at, sort_order, status) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$title, $slug, $excerpt, $content, $image ?? '', $meta_description, $meta_keywords, $published_at, $sort_order, $status]);
        setFlash('success', 'Blog added successfully.');
    }
    header('Location: ' . ADMIN_URL . '/blogs.php');
    exit;
}

$blog = null;
if ($action === 'edit' && $id > 0) {
    $blog = getBlog($id);
    if (!$blog) {
        setFlash('error', 'Blog not found.');
        header('Location: ' . ADMIN_URL . '/blogs.php');
        exit;
    }
}

include __DIR__ . '/includes/header.php';
?>

<?php if ($action === 'add' || $action === 'edit'): ?>

    <a href="<?= ADMIN_URL ?>/blogs.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Blogs</a>

    <div class="card">
        <div class="card-header">
            <h2><?= $action === 'edit' ? 'Edit Blog' : 'Add New Blog' ?></h2>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $blog['id'] ?? 0 ?>">

            <div class="form-group">
                <label>Title <span class="required">*</span></label>
                <input type="text" name="title" id="blogTitle" class="form-control" value="<?= htmlspecialchars($blog['title'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>URL Slug</label>
                <input type="text" name="slug" id="blogSlug" class="form-control" value="<?= htmlspecialchars($blog['slug'] ?? '') ?>" placeholder="auto-generated-from-title">
            </div>

            <div class="form-group">
                <label>Featured Image</label>
                <?php if (!empty($blog['image'])): ?>
                    <div class="current-image">
                        <p>Current:</p>
                        <div class="image-preview"><img src="<?= SITE_URL ?>/<?= $blog['image'] ?>" id="image_preview"></div>
                    </div>
                <?php endif; ?>
                <input type="file" name="image" class="form-control" accept="image/*" data-preview="image_preview">
                <?php if (empty($blog['image'])): ?>
                    <div class="image-preview"><img id="image_preview" style="display:none;"></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Excerpt</label>
                <textarea name="excerpt" class="form-control" rows="3"><?= htmlspecialchars($blog['excerpt'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>Content</label>
                <textarea name="content" class="editor"><?= $blog['content'] ?? '' ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Publish Date</label>
                    <input type="date" name="published_at" class="form-control" value="<?= htmlspecialchars($blog['published_at'] ?? date('Y-m-d')) ?>">
                </div>
                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= $blog['sort_order'] ?? 0 ?>">
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="form-check">
                        <input type="checkbox" name="status" id="status" value="1" <?= ($blog['status'] ?? 1) ? 'checked' : '' ?>>
                        <label for="status">Active</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>SEO Meta Description</label>
                <textarea name="meta_description" class="form-control" rows="3"><?= htmlspecialchars($blog['meta_description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>SEO Meta Keywords</label>
                <input type="text" name="meta_keywords" class="form-control" value="<?= htmlspecialchars($blog['meta_keywords'] ?? '') ?>">
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?= $action === 'edit' ? 'Update Blog' : 'Add Blog' ?></button>
        </form>
    </div>

    <script>
    document.getElementById('blogTitle').addEventListener('input', function() {
        var slugField = document.getElementById('blogSlug');
        if (!slugField.dataset.manual) {
            var slug = this.value.toLowerCase().trim()
                .replace(/[^a-z0-9-]/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
            slugField.value = slug;
        }
    });
    document.getElementById('blogSlug').addEventListener('input', function() {
        this.dataset.manual = this.value ? '1' : '';
    });
    </script>

<?php else: ?>

    <div class="card">
        <div class="card-header">
            <h2>All Blogs</h2>
            <a href="<?= ADMIN_URL ?>/blogs.php?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Add Blog</a>
        </div>

        <?php
        $blogs = $db->query("SELECT * FROM blogs ORDER BY COALESCE(published_at, DATE(created_at)) DESC, sort_order ASC, id DESC")->fetchAll();
        if (empty($blogs)):
        ?>
            <div class="empty-state">
                <i class="fas fa-blog"></i>
                <p>No blogs added yet.</p>
                <a href="<?= ADMIN_URL ?>/blogs.php?action=add" class="btn btn-primary">Add First Blog</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($blogs as $b): ?>
                        <tr>
                            <td>
                                <?php if (!empty($b['image'])): ?>
                                    <img src="<?= SITE_URL ?>/<?= $b['image'] ?>" class="thumb" alt="">
                                <?php else: ?>
                                    <span style="color:var(--text-muted);font-size:12px;">No image</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= htmlspecialchars($b['title']) ?></strong></td>
                            <td><?= $b['published_at'] ? date('M j, Y', strtotime($b['published_at'])) : '-' ?></td>
                            <td><?= $b['sort_order'] ?></td>
                            <td>
                                <span class="status <?= $b['status'] ? 'status-active' : 'status-inactive' ?>">
                                    <span class="status-dot"></span> <?= $b['status'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <?php if (!empty($b['slug'])): ?>
                                        <a href="<?= SITE_URL ?>/blog/<?= htmlspecialchars($b['slug']) ?>" class="btn btn-sm btn-success" target="_blank" title="View"><i class="fas fa-eye"></i></a>
                                    <?php endif; ?>
                                    <a href="<?= ADMIN_URL ?>/blogs.php?action=edit&id=<?= $b['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                    <form method="POST" class="delete-form" style="display:inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
