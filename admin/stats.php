<?php
require_once __DIR__ . '/includes/auth.php';

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postAction = $_POST['action'] ?? '';

    if ($postAction === 'delete') {
        $delId = (int)($_POST['id'] ?? 0);
        $stmt = $db->prepare("DELETE FROM stats WHERE id = ?");
        $stmt->execute([$delId]);
        setFlash('success', 'Stat deleted successfully.');
        header('Location: ' . ADMIN_URL . '/stats.php');
        exit;
    }

    $icon = sanitize($_POST['icon'] ?? '');
    $number = sanitize($_POST['number'] ?? '');
    $suffix = sanitize($_POST['suffix'] ?? '');
    $label = sanitize($_POST['label'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $editId = (int)($_POST['id'] ?? 0);

    if ($editId > 0) {
        $stmt = $db->prepare("UPDATE stats SET icon=?, number=?, suffix=?, label=?, sort_order=? WHERE id=?");
        $stmt->execute([$icon, $number, $suffix, $label, $sort_order, $editId]);
        setFlash('success', 'Stat updated successfully.');
    } else {
        $stmt = $db->prepare("INSERT INTO stats (icon, number, suffix, label, sort_order) VALUES (?,?,?,?,?)");
        $stmt->execute([$icon, $number, $suffix, $label, $sort_order]);
        setFlash('success', 'Stat added successfully.');
    }
    header('Location: ' . ADMIN_URL . '/stats.php');
    exit;
}

$stat = null;
if ($action === 'edit' && $id > 0) {
    $stat = getStat($id);
    if (!$stat) {
        setFlash('error', 'Stat not found.');
        header('Location: ' . ADMIN_URL . '/stats.php');
        exit;
    }
}

include __DIR__ . '/includes/header.php';
?>

<?php if ($action === 'add' || $action === 'edit'): ?>

    <a href="<?= ADMIN_URL ?>/stats.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Stats</a>

    <div class="card">
        <div class="card-header">
            <h2><?= $action === 'edit' ? 'Edit Stat' : 'Add New Stat' ?></h2>
        </div>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $stat['id'] ?? 0 ?>">

            <div class="form-group">
                <label>Icon Class</label>
                <input type="text" name="icon" class="form-control icon-input" value="<?= htmlspecialchars($stat['icon'] ?? '') ?>" placeholder="fas fa-users">
                <div class="icon-preview"><i class="<?= htmlspecialchars($stat['icon'] ?? 'fas fa-question') ?>"></i> <span>Preview</span></div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Number <span class="required">*</span></label>
                    <input type="text" name="number" class="form-control" value="<?= htmlspecialchars($stat['number'] ?? '') ?>" required placeholder="e.g. 500">
                </div>
                <div class="form-group">
                    <label>Suffix</label>
                    <input type="text" name="suffix" class="form-control" value="<?= htmlspecialchars($stat['suffix'] ?? '') ?>" placeholder="e.g. +, K, %">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Label <span class="required">*</span></label>
                    <input type="text" name="label" class="form-control" value="<?= htmlspecialchars($stat['label'] ?? '') ?>" required placeholder="e.g. Happy Clients">
                </div>
                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= $stat['sort_order'] ?? 0 ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?= $action === 'edit' ? 'Update Stat' : 'Add Stat' ?></button>
        </form>
    </div>

<?php else: ?>

    <div class="card">
        <div class="card-header">
            <h2>All Stats</h2>
            <a href="<?= ADMIN_URL ?>/stats.php?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Add Stat</a>
        </div>

        <?php
        $stats = getStats();
        if (empty($stats)):
        ?>
            <div class="empty-state">
                <i class="fas fa-chart-bar"></i>
                <p>No stats added yet.</p>
                <a href="<?= ADMIN_URL ?>/stats.php?action=add" class="btn btn-primary">Add First Stat</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Number</th>
                            <th>Label</th>
                            <th>Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats as $st): ?>
                        <tr>
                            <td><i class="<?= htmlspecialchars($st['icon']) ?>" style="font-size:20px;color:var(--primary);"></i></td>
                            <td><strong><?= htmlspecialchars($st['number']) ?><?= htmlspecialchars($st['suffix']) ?></strong></td>
                            <td><?= htmlspecialchars($st['label']) ?></td>
                            <td><?= $st['sort_order'] ?></td>
                            <td>
                                <div class="actions">
                                    <a href="<?= ADMIN_URL ?>/stats.php?action=edit&id=<?= $st['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                    <form method="POST" class="delete-form" style="display:inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $st['id'] ?>">
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
