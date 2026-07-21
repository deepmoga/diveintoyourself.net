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
        $item = getGalleryImage($delId);
        if ($item) {
            if (!empty($item['image'])) deleteImage($item['image']);
            $stmt = $db->prepare("DELETE FROM gallery_images WHERE id = ?");
            $stmt->execute([$delId]);
            setFlash('success', 'Gallery image deleted successfully.');
        }
        header('Location: ' . ADMIN_URL . '/gallery.php');
        exit;
    }

    $title = sanitize($_POST['title'] ?? '');
    $alt_text = sanitize($_POST['alt_text'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $status = isset($_POST['status']) ? 1 : 0;
    $editId = (int)($_POST['id'] ?? 0);

    $image = null;
    if (!empty($_FILES['image']['tmp_name'])) {
        $image = uploadImage($_FILES['image'], 'gallery');
    }

    if ($editId > 0) {
        $existing = getGalleryImage($editId);
        if ($image) {
            if (!empty($existing['image'])) deleteImage($existing['image']);
        } else {
            $image = $existing['image'] ?? '';
        }
        $stmt = $db->prepare("UPDATE gallery_images SET title=?, image=?, alt_text=?, sort_order=?, status=? WHERE id=?");
        $stmt->execute([$title, $image, $alt_text, $sort_order, $status, $editId]);
        setFlash('success', 'Gallery image updated successfully.');
    } else {
        if (!$image) {
            setFlash('error', 'Please upload an image.');
            header('Location: ' . ADMIN_URL . '/gallery.php?action=add');
            exit;
        }
        $stmt = $db->prepare("INSERT INTO gallery_images (title, image, alt_text, sort_order, status) VALUES (?,?,?,?,?)");
        $stmt->execute([$title, $image, $alt_text, $sort_order, $status]);
        setFlash('success', 'Gallery image added successfully.');
    }
    header('Location: ' . ADMIN_URL . '/gallery.php');
    exit;
}

$item = null;
if ($action === 'edit' && $id > 0) {
    $item = getGalleryImage($id);
    if (!$item) {
        setFlash('error', 'Gallery image not found.');
        header('Location: ' . ADMIN_URL . '/gallery.php');
        exit;
    }
}

include __DIR__ . '/includes/header.php';
?>

<?php if ($action === 'add' || $action === 'edit'): ?>

    <a href="<?= ADMIN_URL ?>/gallery.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Gallery</a>

    <div class="card">
        <div class="card-header">
            <h2><?= $action === 'edit' ? 'Edit Gallery Image' : 'Add Gallery Image' ?></h2>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $item['id'] ?? 0 ?>">

            <div class="form-group">
                <label>Title <span class="required">*</span></label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($item['title'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Image <?= $action === 'add' ? '<span class="required">*</span>' : '' ?></label>
                <?php if (!empty($item['image'])): ?>
                    <div class="current-image">
                        <p>Current:</p>
                        <div class="image-preview"><img src="<?= SITE_URL ?>/<?= $item['image'] ?>" id="image_preview"></div>
                    </div>
                <?php endif; ?>
                <input type="file" name="image" class="form-control" accept="image/*" data-preview="image_preview" <?= $action === 'add' ? 'required' : '' ?>>
                <?php if (empty($item['image'])): ?>
                    <div class="image-preview"><img id="image_preview" style="display:none;"></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Alt Text</label>
                <input type="text" name="alt_text" class="form-control" value="<?= htmlspecialchars($item['alt_text'] ?? '') ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= $item['sort_order'] ?? 0 ?>">
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="form-check">
                        <input type="checkbox" name="status" id="status" value="1" <?= ($item['status'] ?? 1) ? 'checked' : '' ?>>
                        <label for="status">Active</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?= $action === 'edit' ? 'Update Image' : 'Add Image' ?></button>
        </form>
    </div>

<?php else: ?>

    <div class="card">
        <div class="card-header">
            <h2>All Gallery Images</h2>
            <a href="<?= ADMIN_URL ?>/gallery.php?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Add Image</a>
        </div>

        <?php
        $items = $db->query("SELECT * FROM gallery_images ORDER BY sort_order ASC, id DESC")->fetchAll();
        if (empty($items)):
        ?>
            <div class="empty-state">
                <i class="fas fa-images"></i>
                <p>No gallery images added yet.</p>
                <a href="<?= ADMIN_URL ?>/gallery.php?action=add" class="btn btn-primary">Add First Image</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Alt Text</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $g): ?>
                        <tr>
                            <td><img src="<?= SITE_URL ?>/<?= $g['image'] ?>" class="thumb" alt=""></td>
                            <td><strong><?= htmlspecialchars($g['title']) ?></strong></td>
                            <td><?= htmlspecialchars($g['alt_text'] ?: '-') ?></td>
                            <td><?= $g['sort_order'] ?></td>
                            <td>
                                <span class="status <?= $g['status'] ? 'status-active' : 'status-inactive' ?>">
                                    <span class="status-dot"></span> <?= $g['status'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="<?= ADMIN_URL ?>/gallery.php?action=edit&id=<?= $g['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                    <form method="POST" class="delete-form" style="display:inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $g['id'] ?>">
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
