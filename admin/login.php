<?php
require_once __DIR__ . '/../includes/functions.php';

if (isLoggedIn()) {
    header('Location: ' . ADMIN_URL . '/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        setFlash('error', 'Please enter both username and password.');
    } else {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            header('Location: ' . ADMIN_URL . '/index.php');
            exit;
        } else {
            setFlash('error', 'Invalid username or password.');
        }
    }
}

$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dive Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= ADMIN_URL ?>/css/admin.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-logo">
                <h1><i class="fas fa-spa"></i> Dive Into Yourself</h1>
                <p>Admin Panel</p>
            </div>

            <?php if ($flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>
