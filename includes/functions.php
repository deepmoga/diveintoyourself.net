<?php

require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function getSetting($key, $default = '') {
    $db = getDB();
    $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $row = $stmt->fetch();
    return $row ? $row['setting_value'] : $default;
}

function getAllSettings() {
    static $settings = null;
    if ($settings === null) {
        $db = getDB();
        $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
    return $settings;
}

function updateSetting($key, $value) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
    $stmt->execute([$key, $value, $value]);
}

function getPageMeta($slug) {
    $db = getDB();
    $stmt = $db->prepare("SELECT page_title, meta_description, meta_keywords FROM pages WHERE page_slug = ?");
    $stmt->execute([$slug]);
    $row = $stmt->fetch();
    return $row ?: ['page_title' => '', 'meta_description' => '', 'meta_keywords' => ''];
}

function updatePageMeta($slug, $title, $desc, $keywords) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO pages (page_slug, page_title, meta_description, meta_keywords) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE page_title = ?, meta_description = ?, meta_keywords = ?");
    $stmt->execute([$slug, $title, $desc, $keywords, $title, $desc, $keywords]);
}

function getActiveServices($limit = 0) {
    $db = getDB();
    $sql = "SELECT * FROM services WHERE status = 1 ORDER BY sort_order ASC";
    if ($limit > 0) {
        $sql .= " LIMIT " . (int)$limit;
    }
    return $db->query($sql)->fetchAll();
}

function getService($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getActiveTestimonials($limit = 0) {
    $db = getDB();
    $sql = "SELECT * FROM testimonials WHERE status = 1 ORDER BY sort_order ASC";
    if ($limit > 0) {
        $sql .= " LIMIT " . (int)$limit;
    }
    return $db->query($sql)->fetchAll();
}

function getTestimonial($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM testimonials WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getStats() {
    $db = getDB();
    return $db->query("SELECT * FROM stats ORDER BY sort_order ASC")->fetchAll();
}

function getStat($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM stats WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getWhyFeatures() {
    $db = getDB();
    return $db->query("SELECT * FROM why_features WHERE status = 1 ORDER BY sort_order ASC")->fetchAll();
}

function getFeature($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM why_features WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function ensureGalleryBlogTables() {
    static $ready = false;
    if ($ready) return;

    $db = getDB();
    $db->exec("CREATE TABLE IF NOT EXISTS gallery_images (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        image VARCHAR(255) NOT NULL,
        alt_text VARCHAR(255),
        sort_order INT DEFAULT 0,
        status TINYINT DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->exec("CREATE TABLE IF NOT EXISTS blogs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL,
        excerpt TEXT,
        content LONGTEXT,
        image VARCHAR(255),
        meta_description TEXT,
        meta_keywords TEXT,
        sort_order INT DEFAULT 0,
        status TINYINT DEFAULT 1,
        published_at DATE NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_slug (slug)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $ready = true;
}

function getActiveGalleryImages($limit = 0) {
    ensureGalleryBlogTables();
    $db = getDB();
    $sql = "SELECT * FROM gallery_images WHERE status = 1 ORDER BY sort_order ASC, id DESC";
    if ($limit > 0) {
        $sql .= " LIMIT " . (int)$limit;
    }
    return $db->query($sql)->fetchAll();
}

function getGalleryImage($id) {
    ensureGalleryBlogTables();
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM gallery_images WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getActiveBlogs($limit = 0) {
    ensureGalleryBlogTables();
    $db = getDB();
    $sql = "SELECT * FROM blogs WHERE status = 1 ORDER BY COALESCE(published_at, DATE(created_at)) DESC, sort_order ASC, id DESC";
    if ($limit > 0) {
        $sql .= " LIMIT " . (int)$limit;
    }
    return $db->query($sql)->fetchAll();
}

function getBlog($id) {
    ensureGalleryBlogTables();
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM blogs WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getBlogBySlug($slug) {
    ensureGalleryBlogTables();
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM blogs WHERE slug = ? AND status = 1");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function uploadImage($file, $folder = 'uploads') {
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
    $maxSize = 5 * 1024 * 1024;

    if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    if ($file['size'] > $maxSize) {
        return false;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt)) {
        return false;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, $allowed)) {
        return false;
    }

    $targetDir = BASE_PATH . '/images/' . $folder . '/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $filename = uniqid('img_', true) . '.' . $ext;
    $targetPath = $targetDir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return false;
    }

    return 'images/' . $folder . '/' . $filename;
}

function deleteImage($path) {
    if (empty($path)) return;
    $fullPath = BASE_PATH . '/' . $path;
    if (file_exists($fullPath) && is_file($fullPath)) {
        unlink($fullPath);
    }
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function isLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . ADMIN_URL . '/login.php');
        exit;
    }
}

function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function generateSlug($text) {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9-]/', '-', $text);
    $text = preg_replace('/-+/', '-', $text);
    return trim($text, '-');
}

function getServiceBySlug($slug) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM services WHERE slug = ? AND status = 1");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function sendMail($to, $subject, $htmlBody, $replyTo = '') {
    require_once BASE_PATH . '/phpmailer/autoload.php';

    $settings = getAllSettings();

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $settings['smtp_host'] ?? 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $settings['smtp_username'] ?? '';
        $mail->Password = $settings['smtp_password'] ?? '';
        $mail->SMTPSecure = $settings['smtp_encryption'] ?? 'tls';
        $mail->Port = intval($settings['smtp_port'] ?? 587);

        $mail->setFrom(
            $settings['smtp_from_email'] ?? 'noreply@example.com',
            $settings['smtp_from_name'] ?? 'Dive Into Yourself'
        );
        $mail->addAddress($to);

        if ($replyTo) {
            $mail->addReplyTo($replyTo);
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $htmlBody));

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Mail error: ' . $mail->ErrorInfo);
        return false;
    }
}

function getEmailTemplate($title, $content, $siteName = '') {
    if (!$siteName) $siteName = getSetting('site_name', 'Dive Into Yourself');
    $siteUrl = SITE_URL;

    return '<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background:#f4f0f8;font-family:Arial,Helvetica,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f0f8;padding:40px 20px;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(111,72,148,0.1);">
<!-- Header -->
<tr><td style="background:linear-gradient(135deg,#6f4894,#0a9dae);padding:30px 40px;text-align:center;">
<h1 style="color:#ffffff;margin:0;font-size:24px;font-weight:700;">' . htmlspecialchars($siteName) . '</h1>
<p style="color:rgba(255,255,255,0.8);margin:5px 0 0;font-size:14px;">Harmonising Your Inner Self &amp; Your Body</p>
</td></tr>
<!-- Title -->
<tr><td style="padding:30px 40px 10px;">
<h2 style="color:#1a1a2e;margin:0;font-size:20px;font-weight:700;border-bottom:2px solid #6f4894;padding-bottom:12px;">' . $title . '</h2>
</td></tr>
<!-- Content -->
<tr><td style="padding:15px 40px 30px;color:#555555;font-size:15px;line-height:1.7;">
' . $content . '
</td></tr>
<!-- Footer -->
<tr><td style="background:#1a1a2e;padding:25px 40px;text-align:center;">
<p style="color:rgba(255,255,255,0.6);margin:0;font-size:13px;">&copy; ' . date('Y') . ' ' . htmlspecialchars($siteName) . '. All Rights Reserved.</p>
<p style="color:rgba(255,255,255,0.4);margin:8px 0 0;font-size:12px;">Unit 36/20 Property Street, Truganina, Victoria 3029</p>
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>';
}
