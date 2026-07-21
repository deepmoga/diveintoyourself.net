<?php
/**
 * One-time Migration Script
 * Adds new columns to services table, generates slugs, and adds mail settings.
 * Safe to run multiple times - uses try/catch for columns and INSERT IGNORE for settings.
 */

require_once __DIR__ . '/includes/functions.php';

$db = getDB();
ensureGalleryBlogTables();
$results = [];
$results[] = ['success', 'Gallery and blog tables are ready.'];

// ─── 1. Add new columns to services table ───
$columns = [
    ['slug', "ALTER TABLE services ADD COLUMN slug VARCHAR(255) AFTER title"],
    ['meta_description', "ALTER TABLE services ADD COLUMN meta_description TEXT AFTER slug"],
    ['meta_keywords', "ALTER TABLE services ADD COLUMN meta_keywords TEXT AFTER meta_description"],
    ['long_description', "ALTER TABLE services ADD COLUMN long_description TEXT AFTER description"],
];

foreach ($columns as [$col, $sql]) {
    try {
        $db->exec($sql);
        $results[] = ['success', "Added column <strong>$col</strong> to services table."];
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
            $results[] = ['info', "Column <strong>$col</strong> already exists - skipped."];
        } else {
            $results[] = ['error', "Error adding column <strong>$col</strong>: " . htmlspecialchars($e->getMessage())];
        }
    }
}

// ─── 2. Generate slugs for existing services that don't have one ───
try {
    $services = $db->query("SELECT id, title, slug FROM services")->fetchAll(PDO::FETCH_ASSOC);
    $slugCount = 0;
    foreach ($services as $svc) {
        if (empty($svc['slug'])) {
            $slug = strtolower(trim($svc['title']));
            $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
            $slug = preg_replace('/-+/', '-', $slug);
            $slug = trim($slug, '-');

            $stmt = $db->prepare("UPDATE services SET slug = ? WHERE id = ?");
            $stmt->execute([$slug, $svc['id']]);
            $slugCount++;
        }
    }
    if ($slugCount > 0) {
        $results[] = ['success', "Generated slugs for <strong>$slugCount</strong> service(s)."];
    } else {
        $results[] = ['info', "All services already have slugs - no updates needed."];
    }
} catch (PDOException $e) {
    $results[] = ['error', "Error generating slugs: " . htmlspecialchars($e->getMessage())];
}

// ─── 3. Add mail settings ───
$mailSettings = [
    'smtp_host'       => 'smtp.gmail.com',
    'smtp_port'       => '587',
    'smtp_username'   => '',
    'smtp_password'   => '',
    'smtp_from_email' => 'noreply@example.com',
    'smtp_from_name'  => 'Dive Into Yourself',
    'admin_email'     => 'admin@example.com',
    'smtp_encryption' => 'tls',
];

$mailCount = 0;
foreach ($mailSettings as $key => $value) {
    try {
        $stmt = $db->prepare("INSERT IGNORE INTO settings (setting_key, setting_value) VALUES (?, ?)");
        $stmt->execute([$key, $value]);
        if ($stmt->rowCount() > 0) {
            $mailCount++;
        }
    } catch (PDOException $e) {
        $results[] = ['error', "Error inserting setting <strong>$key</strong>: " . htmlspecialchars($e->getMessage())];
    }
}
if ($mailCount > 0) {
    $results[] = ['success', "Added <strong>$mailCount</strong> new mail setting(s)."];
} else {
    $results[] = ['info', "All mail settings already exist - no inserts needed."];
}

// ─── Output results ───
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Migration - Dive Into Yourself</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f4f0f8 0%, #e8f6f7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .container {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 40px rgba(111, 72, 148, 0.12);
            max-width: 650px;
            width: 100%;
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #6f4894, #0a9dae);
            padding: 30px 40px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .header p {
            color: rgba(255,255,255,0.8);
            font-size: 14px;
        }
        .body {
            padding: 30px 40px;
        }
        .result {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .result-success {
            background: #e8f8f0;
            color: #1a7a4c;
            border-left: 4px solid #0a9dae;
        }
        .result-info {
            background: #f0ecf5;
            color: #6f4894;
            border-left: 4px solid #6f4894;
        }
        .result-error {
            background: #fdecea;
            color: #c62828;
            border-left: 4px solid #c62828;
        }
        .result .icon {
            font-size: 18px;
            flex-shrink: 0;
        }
        .footer {
            padding: 20px 40px 30px;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #6f4894, #0a9dae);
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: opacity 0.2s;
        }
        .btn:hover { opacity: 0.9; }
        .summary {
            background: #f9f9fb;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 15px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Database Migration</h1>
            <p>Dive Into Yourself - Schema Update</p>
        </div>
        <div class="body">
            <div class="summary">
                Migration completed with <strong><?= count($results) ?></strong> operation(s).
            </div>
            <?php foreach ($results as [$type, $msg]): ?>
                <div class="result result-<?= $type ?>">
                    <span class="icon">
                        <?php if ($type === 'success'): ?>&#10003;
                        <?php elseif ($type === 'info'): ?>&#8505;
                        <?php else: ?>&#10007;
                        <?php endif; ?>
                    </span>
                    <span><?= $msg ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="footer">
            <a href="<?= defined('ADMIN_URL') ? ADMIN_URL : '/Github/Dive/admin' ?>/index.php" class="btn">Go to Admin Panel</a>
        </div>
    </div>
</body>
</html>
