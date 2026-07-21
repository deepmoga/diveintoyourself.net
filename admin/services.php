<?php
require_once __DIR__ . '/includes/auth.php';

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postAction = $_POST['action'] ?? '';

    if ($postAction === 'delete') {
        $delId = (int)($_POST['id'] ?? 0);
        $service = getService($delId);
        if ($service) {
            if (!empty($service['image'])) deleteImage($service['image']);
            $stmt = $db->prepare("DELETE FROM services WHERE id = ?");
            $stmt->execute([$delId]);
            setFlash('success', 'Service deleted successfully.');
        }
        header('Location: ' . ADMIN_URL . '/services.php');
        exit;
    }

    $title = sanitize($_POST['title'] ?? '');
    $description = $_POST['description'] ?? '';
    $slug = sanitize($_POST['slug'] ?? '');
    $meta_description = sanitize($_POST['meta_description'] ?? '');
    $meta_keywords = sanitize($_POST['meta_keywords'] ?? '');
    $long_description = $_POST['long_description'] ?? '';
    $icon = sanitize($_POST['icon'] ?? '');
    $badge = sanitize($_POST['badge'] ?? '');
    $level = sanitize($_POST['level'] ?? '');
    $duration = sanitize($_POST['duration'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $status = isset($_POST['status']) ? 1 : 0;
    $editId = (int)($_POST['id'] ?? 0);

    // Auto-generate slug from title if empty
    if (empty($slug)) {
        $slug = generateSlug($title);
    }

    $image = null;
    if (!empty($_FILES['image']['tmp_name'])) {
        $image = uploadImage($_FILES['image'], 'services');
    }

    if ($editId > 0) {
        $existing = getService($editId);
        if ($image) {
            if (!empty($existing['image'])) deleteImage($existing['image']);
        } else {
            $image = $existing['image'] ?? '';
        }
        $stmt = $db->prepare("UPDATE services SET title=?, slug=?, meta_description=?, meta_keywords=?, description=?, long_description=?, image=?, icon=?, badge=?, level=?, duration=?, sort_order=?, status=? WHERE id=?");
        $stmt->execute([$title, $slug, $meta_description, $meta_keywords, $description, $long_description, $image, $icon, $badge, $level, $duration, $sort_order, $status, $editId]);
        setFlash('success', 'Service updated successfully.');
    } else {
        $stmt = $db->prepare("INSERT INTO services (title, slug, meta_description, meta_keywords, description, long_description, image, icon, badge, level, duration, sort_order, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$title, $slug, $meta_description, $meta_keywords, $description, $long_description, $image ?? '', $icon, $badge, $level, $duration, $sort_order, $status]);
        setFlash('success', 'Service added successfully.');
    }
    header('Location: ' . ADMIN_URL . '/services.php');
    exit;
}

$service = null;
if ($action === 'edit' && $id > 0) {
    $service = getService($id);
    if (!$service) {
        setFlash('error', 'Service not found.');
        header('Location: ' . ADMIN_URL . '/services.php');
        exit;
    }
}

include __DIR__ . '/includes/header.php';
?>

<?php if ($action === 'add' || $action === 'edit'): ?>

    <a href="<?= ADMIN_URL ?>/services.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Services</a>

    <div class="card">
        <div class="card-header">
            <h2><?= $action === 'edit' ? 'Edit Service' : 'Add New Service' ?></h2>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $service['id'] ?? 0 ?>">

            <div class="form-group">
                <label>Title <span class="required">*</span></label>
                <input type="text" name="title" id="serviceTitle" class="form-control" value="<?= htmlspecialchars($service['title'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>URL Slug</label>
                <input type="text" name="slug" id="serviceSlug" class="form-control" value="<?= htmlspecialchars($service['slug'] ?? '') ?>" placeholder="auto-generated-from-title">
                <p class="form-hint">Leave empty to auto-generate from title. Used in the service page URL.</p>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="editor"><?= $service['description'] ?? '' ?></textarea>
            </div>

            <div class="form-group">
                <label>Detailed Description (for single service page)</label>
                <textarea name="long_description" class="editor"><?= $service['long_description'] ?? '' ?></textarea>
            </div>

            <div class="form-group">
                <label>Image</label>
                <?php if (!empty($service['image'])): ?>
                    <div class="current-image">
                        <p>Current:</p>
                        <div class="image-preview"><img src="<?= SITE_URL ?>/<?= $service['image'] ?>" id="image_preview"></div>
                    </div>
                <?php endif; ?>
                <input type="file" name="image" class="form-control" accept="image/*" data-preview="image_preview">
                <?php if (empty($service['image'])): ?>
                    <div class="image-preview"><img id="image_preview" style="display:none;"></div>
                <?php endif; ?>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Icon Class</label>
                    <input type="text" name="icon" class="form-control icon-input" value="<?= htmlspecialchars($service['icon'] ?? '') ?>" placeholder="fas fa-heart">
                    <div class="icon-preview"><i class="<?= htmlspecialchars($service['icon'] ?? 'fas fa-question') ?>"></i> <span>Preview</span></div>
                </div>
                <div class="form-group">
                    <label>Badge</label>
                    <input type="text" name="badge" class="form-control" value="<?= htmlspecialchars($service['badge'] ?? '') ?>" placeholder="e.g. Popular">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Level</label>
                    <input type="text" name="level" class="form-control" value="<?= htmlspecialchars($service['level'] ?? '') ?>" placeholder="e.g. Beginner">
                </div>
                <div class="form-group">
                    <label>Duration</label>
                    <input type="text" name="duration" class="form-control" value="<?= htmlspecialchars($service['duration'] ?? '') ?>" placeholder="e.g. 60 min">
                </div>
            </div>

            <div class="form-group">
                <label>SEO Meta Description</label>
                <textarea name="meta_description" class="form-control" rows="3" placeholder="Brief description for search engines (recommended: 150-160 characters)"><?= htmlspecialchars($service['meta_description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>SEO Meta Keywords</label>
                <input type="text" name="meta_keywords" class="form-control" value="<?= htmlspecialchars($service['meta_keywords'] ?? '') ?>" placeholder="keyword1, keyword2, keyword3">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= $service['sort_order'] ?? 0 ?>">
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="form-check">
                        <input type="checkbox" name="status" id="status" value="1" <?= ($service['status'] ?? 1) ? 'checked' : '' ?>>
                        <label for="status">Active</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?= $action === 'edit' ? 'Update Service' : 'Add Service' ?></button>
        </form>
    </div>

    <script>
    document.getElementById('serviceTitle').addEventListener('input', function() {
        var slugField = document.getElementById('serviceSlug');
        if (!slugField.dataset.manual) {
            var slug = this.value.toLowerCase().trim()
                .replace(/[^a-z0-9-]/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
            slugField.value = slug;
        }
    });
    document.getElementById('serviceSlug').addEventListener('input', function() {
        this.dataset.manual = this.value ? '1' : '';
    });
    </script>

<?php else: ?>

    <div class="card">
        <div class="card-header">
            <h2>All Services</h2>
            <a href="<?= ADMIN_URL ?>/services.php?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Add Service</a>
        </div>

        <?php
        $services = $db->query("SELECT * FROM services ORDER BY sort_order ASC")->fetchAll();
        if (empty($services)):
        ?>
            <div class="empty-state">
                <i class="fas fa-concierge-bell"></i>
                <p>No services added yet.</p>
                <a href="<?= ADMIN_URL ?>/services.php?action=add" class="btn btn-primary">Add First Service</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Badge</th>
                            <th>Level</th>
                            <th>Duration</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $s): ?>
                        <tr>
                            <td>
                                <?php if (!empty($s['image'])): ?>
                                    <img src="<?= SITE_URL ?>/<?= $s['image'] ?>" class="thumb" alt="">
                                <?php else: ?>
                                    <span style="color:var(--text-muted);font-size:12px;">No image</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= htmlspecialchars($s['title']) ?></strong></td>
                            <td><?= $s['badge'] ? '<span class="badge badge-purple">' . htmlspecialchars($s['badge']) . '</span>' : '-' ?></td>
                            <td><?= htmlspecialchars($s['level'] ?: '-') ?></td>
                            <td><?= htmlspecialchars($s['duration'] ?: '-') ?></td>
                            <td><?= $s['sort_order'] ?></td>
                            <td>
                                <span class="status <?= $s['status'] ? 'status-active' : 'status-inactive' ?>">
                                    <span class="status-dot"></span> <?= $s['status'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <?php if (!empty($s['slug'])): ?>
                                        <a href="<?= SITE_URL ?>/service/<?= htmlspecialchars($s['slug']) ?>" class="btn btn-sm btn-success" target="_blank" title="View"><i class="fas fa-eye"></i></a>
                                    <?php endif; ?>
                                    <a href="<?= ADMIN_URL ?>/services.php?action=edit&id=<?= $s['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                    <form method="POST" class="delete-form" style="display:inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $s['id'] ?>">
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
