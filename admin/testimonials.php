<?php
require_once __DIR__ . '/includes/auth.php';

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postAction = $_POST['action'] ?? '';

    if ($postAction === 'delete') {
        $delId = (int)($_POST['id'] ?? 0);
        $item = getTestimonial($delId);
        if ($item) {
            if (!empty($item['image'])) deleteImage($item['image']);
            $stmt = $db->prepare("DELETE FROM testimonials WHERE id = ?");
            $stmt->execute([$delId]);
            setFlash('success', 'Testimonial deleted successfully.');
        }
        header('Location: ' . ADMIN_URL . '/testimonials.php');
        exit;
    }

    $name = sanitize($_POST['name'] ?? '');
    $designation = sanitize($_POST['designation'] ?? '');
    $content = $_POST['content'] ?? '';
    $rating = max(1, min(5, (int)($_POST['rating'] ?? 5)));
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $status = isset($_POST['status']) ? 1 : 0;
    $editId = (int)($_POST['id'] ?? 0);

    $image = null;
    if (!empty($_FILES['image']['tmp_name'])) {
        $image = uploadImage($_FILES['image'], 'testimonials');
    }

    if ($editId > 0) {
        $existing = getTestimonial($editId);
        if ($image) {
            if (!empty($existing['image'])) deleteImage($existing['image']);
        } else {
            $image = $existing['image'] ?? '';
        }
        $stmt = $db->prepare("UPDATE testimonials SET name=?, designation=?, content=?, image=?, rating=?, sort_order=?, status=? WHERE id=?");
        $stmt->execute([$name, $designation, $content, $image, $rating, $sort_order, $status, $editId]);
        setFlash('success', 'Testimonial updated successfully.');
    } else {
        $stmt = $db->prepare("INSERT INTO testimonials (name, designation, content, image, rating, sort_order, status) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$name, $designation, $content, $image ?? '', $rating, $sort_order, $status]);
        setFlash('success', 'Testimonial added successfully.');
    }
    header('Location: ' . ADMIN_URL . '/testimonials.php');
    exit;
}

$testimonial = null;
if ($action === 'edit' && $id > 0) {
    $testimonial = getTestimonial($id);
    if (!$testimonial) {
        setFlash('error', 'Testimonial not found.');
        header('Location: ' . ADMIN_URL . '/testimonials.php');
        exit;
    }
}

include __DIR__ . '/includes/header.php';
?>

<?php if ($action === 'add' || $action === 'edit'): ?>

    <a href="<?= ADMIN_URL ?>/testimonials.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Testimonials</a>

    <div class="card">
        <div class="card-header">
            <h2><?= $action === 'edit' ? 'Edit Testimonial' : 'Add New Testimonial' ?></h2>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $testimonial['id'] ?? 0 ?>">

            <div class="form-row">
                <div class="form-group">
                    <label>Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($testimonial['name'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Designation</label>
                    <input type="text" name="designation" class="form-control" value="<?= htmlspecialchars($testimonial['designation'] ?? '') ?>" placeholder="e.g. CEO, Company Name">
                </div>
            </div>

            <div class="form-group">
                <label>Content</label>
                <textarea name="content" class="editor"><?= $testimonial['content'] ?? '' ?></textarea>
            </div>

            <div class="form-group">
                <label>Avatar Image</label>
                <?php if (!empty($testimonial['image'])): ?>
                    <div class="current-image">
                        <p>Current:</p>
                        <div class="image-preview"><img src="<?= SITE_URL ?>/<?= $testimonial['image'] ?>" id="image_preview"></div>
                    </div>
                <?php endif; ?>
                <input type="file" name="image" class="form-control" accept="image/*" data-preview="image_preview">
                <?php if (empty($testimonial['image'])): ?>
                    <div class="image-preview"><img id="image_preview" style="display:none;"></div>
                <?php endif; ?>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Rating</label>
                    <select name="rating" class="form-control">
                        <?php for ($r = 5; $r >= 1; $r--): ?>
                            <option value="<?= $r ?>" <?= ($testimonial['rating'] ?? 5) == $r ? 'selected' : '' ?>><?= $r ?> Star<?= $r > 1 ? 's' : '' ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= $testimonial['sort_order'] ?? 0 ?>">
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="form-check">
                        <input type="checkbox" name="status" id="status" value="1" <?= ($testimonial['status'] ?? 1) ? 'checked' : '' ?>>
                        <label for="status">Active</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?= $action === 'edit' ? 'Update Testimonial' : 'Add Testimonial' ?></button>
        </form>
    </div>

<?php else: ?>

    <div class="card">
        <div class="card-header">
            <h2>All Testimonials</h2>
            <a href="<?= ADMIN_URL ?>/testimonials.php?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Add Testimonial</a>
        </div>

        <?php
        $testimonials = $db->query("SELECT * FROM testimonials ORDER BY sort_order ASC")->fetchAll();
        if (empty($testimonials)):
        ?>
            <div class="empty-state">
                <i class="fas fa-quote-right"></i>
                <p>No testimonials added yet.</p>
                <a href="<?= ADMIN_URL ?>/testimonials.php?action=add" class="btn btn-primary">Add First Testimonial</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Avatar</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Rating</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($testimonials as $t): ?>
                        <tr>
                            <td>
                                <?php if (!empty($t['image'])): ?>
                                    <img src="<?= SITE_URL ?>/<?= $t['image'] ?>" class="thumb" style="border-radius:50%;" alt="">
                                <?php else: ?>
                                    <span style="color:var(--text-muted);font-size:12px;">No image</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= htmlspecialchars($t['name']) ?></strong></td>
                            <td><?= htmlspecialchars($t['designation'] ?: '-') ?></td>
                            <td>
                                <span class="rating-stars">
                                    <?php for ($r = 1; $r <= 5; $r++): ?>
                                        <i class="fa<?= $r <= $t['rating'] ? 's' : 'r' ?> fa-star"></i>
                                    <?php endfor; ?>
                                </span>
                            </td>
                            <td><?= $t['sort_order'] ?></td>
                            <td>
                                <span class="status <?= $t['status'] ? 'status-active' : 'status-inactive' ?>">
                                    <span class="status-dot"></span> <?= $t['status'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="<?= ADMIN_URL ?>/testimonials.php?action=edit&id=<?= $t['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                    <form method="POST" class="delete-form" style="display:inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $t['id'] ?>">
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
