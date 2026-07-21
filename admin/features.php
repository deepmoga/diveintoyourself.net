<?php
require_once __DIR__ . '/includes/auth.php';

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postAction = $_POST['action'] ?? '';

    if ($postAction === 'delete') {
        $delId = (int)($_POST['id'] ?? 0);
        $stmt = $db->prepare("DELETE FROM why_features WHERE id = ?");
        $stmt->execute([$delId]);
        setFlash('success', 'Feature deleted successfully.');
        header('Location: ' . ADMIN_URL . '/features.php');
        exit;
    }

    $title = sanitize($_POST['title'] ?? '');
    $description = $_POST['description'] ?? '';
    $icon = sanitize($_POST['icon'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $status = isset($_POST['status']) ? 1 : 0;
    $editId = (int)($_POST['id'] ?? 0);

    if ($editId > 0) {
        $stmt = $db->prepare("UPDATE why_features SET title=?, description=?, icon=?, sort_order=?, status=? WHERE id=?");
        $stmt->execute([$title, $description, $icon, $sort_order, $status, $editId]);
        setFlash('success', 'Feature updated successfully.');
    } else {
        $stmt = $db->prepare("INSERT INTO why_features (title, description, icon, sort_order, status) VALUES (?,?,?,?,?)");
        $stmt->execute([$title, $description, $icon, $sort_order, $status]);
        setFlash('success', 'Feature added successfully.');
    }
    header('Location: ' . ADMIN_URL . '/features.php');
    exit;
}

$feature = null;
if ($action === 'edit' && $id > 0) {
    $feature = getFeature($id);
    if (!$feature) {
        setFlash('error', 'Feature not found.');
        header('Location: ' . ADMIN_URL . '/features.php');
        exit;
    }
}

include __DIR__ . '/includes/header.php';
?>

<?php if ($action === 'add' || $action === 'edit'): ?>

    <a href="<?= ADMIN_URL ?>/features.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Features</a>

    <div class="card">
        <div class="card-header">
            <h2><?= $action === 'edit' ? 'Edit Feature' : 'Add New Feature' ?></h2>
        </div>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $feature['id'] ?? 0 ?>">

            <div class="form-group">
                <label>Title <span class="required">*</span></label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($feature['title'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="editor"><?= $feature['description'] ?? '' ?></textarea>
            </div>

            <div class="form-group">
                <label>Icon Class</label>
                <input type="text" name="icon" class="form-control icon-input" value="<?= htmlspecialchars($feature['icon'] ?? '') ?>" placeholder="fas fa-check-circle">
                <div class="icon-preview"><i class="<?= htmlspecialchars($feature['icon'] ?? 'fas fa-question') ?>"></i> <span>Preview</span></div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= $feature['sort_order'] ?? 0 ?>">
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="form-check">
                        <input type="checkbox" name="status" id="status" value="1" <?= ($feature['status'] ?? 1) ? 'checked' : '' ?>>
                        <label for="status">Active</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?= $action === 'edit' ? 'Update Feature' : 'Add Feature' ?></button>
        </form>
    </div>

<?php else: ?>

    <div class="card">
        <div class="card-header">
            <h2>Why Choose Us Features</h2>
            <a href="<?= ADMIN_URL ?>/features.php?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Add Feature</a>
        </div>

        <?php
        $features = $db->query("SELECT * FROM why_features ORDER BY sort_order ASC")->fetchAll();
        if (empty($features)):
        ?>
            <div class="empty-state">
                <i class="fas fa-star"></i>
                <p>No features added yet.</p>
                <a href="<?= ADMIN_URL ?>/features.php?action=add" class="btn btn-primary">Add First Feature</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Title</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($features as $f): ?>
                        <tr>
                            <td><i class="<?= htmlspecialchars($f['icon']) ?>" style="font-size:20px;color:var(--primary);"></i></td>
                            <td><strong><?= htmlspecialchars($f['title']) ?></strong></td>
                            <td><?= $f['sort_order'] ?></td>
                            <td>
                                <span class="status <?= $f['status'] ? 'status-active' : 'status-inactive' ?>">
                                    <span class="status-dot"></span> <?= $f['status'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="<?= ADMIN_URL ?>/features.php?action=edit&id=<?= $f['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                    <form method="POST" class="delete-form" style="display:inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $f['id'] ?>">
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
