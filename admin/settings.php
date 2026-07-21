<?php
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tab = $_POST['tab'] ?? 'general';

    if ($tab === 'general') {
        updateSetting('site_name', sanitize($_POST['site_name'] ?? ''));
        updateSetting('site_tagline', sanitize($_POST['site_tagline'] ?? ''));

        if (!empty($_FILES['logo']['tmp_name'])) {
            $oldLogo = getSetting('logo');
            $newLogo = uploadImage($_FILES['logo'], 'settings');
            if ($newLogo) {
                if ($oldLogo) deleteImage($oldLogo);
                updateSetting('logo', $newLogo);
            }
        }
    } elseif ($tab === 'contact') {
        $fields = ['phone1', 'phone2', 'email', 'address', 'map_embed'];
        foreach ($fields as $f) {
            $val = ($f === 'map_embed') ? ($_POST[$f] ?? '') : sanitize($_POST[$f] ?? '');
            updateSetting($f, $val);
        }
    } elseif ($tab === 'social') {
        $fields = ['facebook', 'instagram', 'youtube', 'twitter', 'linkedin', 'whatsapp'];
        foreach ($fields as $f) {
            updateSetting($f, sanitize($_POST[$f] ?? ''));
        }
    } elseif ($tab === 'footer') {
        updateSetting('footer_text', $_POST['footer_text'] ?? '');
        updateSetting('copyright_text', sanitize($_POST['copyright_text'] ?? ''));
    } elseif ($tab === 'email') {
        $emailFields = ['smtp_host', 'smtp_port', 'smtp_encryption', 'smtp_username', 'smtp_password', 'smtp_from_email', 'smtp_from_name', 'admin_email'];
        foreach ($emailFields as $f) {
            updateSetting($f, sanitize($_POST[$f] ?? ''));
        }
    }

    setFlash('success', 'Settings updated successfully.');
    header('Location: ' . ADMIN_URL . '/settings.php?tab=' . $tab);
    exit;
}

$s = getAllSettings();
$activeTab = $_GET['tab'] ?? 'general';

include __DIR__ . '/includes/header.php';
?>

<div class="card">
    <div class="tabs">
        <button class="tab-btn <?= $activeTab === 'general' ? 'active' : '' ?>" data-tab="tab-general">General</button>
        <button class="tab-btn <?= $activeTab === 'contact' ? 'active' : '' ?>" data-tab="tab-contact">Contact</button>
        <button class="tab-btn <?= $activeTab === 'social' ? 'active' : '' ?>" data-tab="tab-social">Social Media</button>
        <button class="tab-btn <?= $activeTab === 'footer' ? 'active' : '' ?>" data-tab="tab-footer">Footer</button>
        <button class="tab-btn <?= $activeTab === 'email' ? 'active' : '' ?>" data-tab="tab-email">Email / SMTP</button>
    </div>

    <!-- General Tab -->
    <div id="tab-general" class="tab-content <?= $activeTab === 'general' ? 'active' : '' ?>">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="tab" value="general">
            <div class="form-group">
                <label>Site Name</label>
                <input type="text" name="site_name" class="form-control" value="<?= htmlspecialchars($s['site_name'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Site Tagline</label>
                <input type="text" name="site_tagline" class="form-control" value="<?= htmlspecialchars($s['site_tagline'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Logo</label>
                <?php if (!empty($s['logo'])): ?>
                    <div class="current-image">
                        <p>Current Logo:</p>
                        <img src="<?= SITE_URL ?>/<?= $s['logo'] ?>" alt="Logo" id="logo-preview" style="max-height:80px; width:auto;">
                    </div>
                <?php endif; ?>
                <input type="file" name="logo" class="form-control" accept="image/*" data-preview="logo-preview">
                <?php if (empty($s['logo'])): ?>
                    <img id="logo-preview" style="display:none; max-height:80px; margin-top:10px;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        </form>
    </div>

    <!-- Contact Tab -->
    <div id="tab-contact" class="tab-content <?= $activeTab === 'contact' ? 'active' : '' ?>">
        <form method="POST">
            <input type="hidden" name="tab" value="contact">
            <div class="form-row">
                <div class="form-group">
                    <label>Phone 1</label>
                    <input type="text" name="phone1" class="form-control" value="<?= htmlspecialchars($s['phone1'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Phone 2</label>
                    <input type="text" name="phone2" class="form-control" value="<?= htmlspecialchars($s['phone2'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($s['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="3"><?= htmlspecialchars($s['address'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label>Map Embed Code</label>
                <textarea name="map_embed" class="form-control" rows="4"><?= htmlspecialchars($s['map_embed'] ?? '') ?></textarea>
                <p class="form-hint">Paste the full Google Maps iframe embed code here.</p>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        </form>
    </div>

    <!-- Social Media Tab -->
    <div id="tab-social" class="tab-content <?= $activeTab === 'social' ? 'active' : '' ?>">
        <form method="POST">
            <input type="hidden" name="tab" value="social">
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fab fa-facebook"></i> Facebook</label>
                    <input type="url" name="facebook" class="form-control" value="<?= htmlspecialchars($s['facebook'] ?? '') ?>" placeholder="https://facebook.com/...">
                </div>
                <div class="form-group">
                    <label><i class="fab fa-instagram"></i> Instagram</label>
                    <input type="url" name="instagram" class="form-control" value="<?= htmlspecialchars($s['instagram'] ?? '') ?>" placeholder="https://instagram.com/...">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fab fa-youtube"></i> YouTube</label>
                    <input type="url" name="youtube" class="form-control" value="<?= htmlspecialchars($s['youtube'] ?? '') ?>" placeholder="https://youtube.com/...">
                </div>
                <div class="form-group">
                    <label><i class="fab fa-twitter"></i> Twitter / X</label>
                    <input type="url" name="twitter" class="form-control" value="<?= htmlspecialchars($s['twitter'] ?? '') ?>" placeholder="https://x.com/...">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fab fa-linkedin"></i> LinkedIn</label>
                    <input type="url" name="linkedin" class="form-control" value="<?= htmlspecialchars($s['linkedin'] ?? '') ?>" placeholder="https://linkedin.com/...">
                </div>
                <div class="form-group">
                    <label><i class="fab fa-whatsapp"></i> WhatsApp</label>
                    <input type="url" name="whatsapp" class="form-control" value="<?= htmlspecialchars($s['whatsapp'] ?? '') ?>" placeholder="https://wa.me/...">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        </form>
    </div>

    <!-- Footer Tab -->
    <div id="tab-footer" class="tab-content <?= $activeTab === 'footer' ? 'active' : '' ?>">
        <form method="POST">
            <input type="hidden" name="tab" value="footer">
            <div class="form-group">
                <label>Footer Text</label>
                <textarea name="footer_text" class="editor"><?= $s['footer_text'] ?? '' ?></textarea>
            </div>
            <div class="form-group">
                <label>Copyright Text</label>
                <input type="text" name="copyright_text" class="form-control" value="<?= htmlspecialchars($s['copyright_text'] ?? '') ?>">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        </form>
    </div>

    <!-- Email / SMTP Tab -->
    <div id="tab-email" class="tab-content <?= $activeTab === 'email' ? 'active' : '' ?>">
        <form method="POST">
            <input type="hidden" name="tab" value="email">

            <div class="form-row">
                <div class="form-group">
                    <label>SMTP Host</label>
                    <input type="text" name="smtp_host" class="form-control" value="<?= htmlspecialchars($s['smtp_host'] ?? 'smtp.gmail.com') ?>" placeholder="smtp.gmail.com">
                </div>
                <div class="form-group">
                    <label>SMTP Port</label>
                    <input type="text" name="smtp_port" class="form-control" value="<?= htmlspecialchars($s['smtp_port'] ?? '587') ?>" placeholder="587">
                </div>
            </div>

            <div class="form-group">
                <label>SMTP Encryption</label>
                <select name="smtp_encryption" class="form-control">
                    <option value="tls" <?= ($s['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' ?>>TLS</option>
                    <option value="ssl" <?= ($s['smtp_encryption'] ?? 'tls') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>SMTP Username</label>
                    <input type="text" name="smtp_username" class="form-control" value="<?= htmlspecialchars($s['smtp_username'] ?? '') ?>" placeholder="your@gmail.com">
                </div>
                <div class="form-group">
                    <label>SMTP Password</label>
                    <input type="password" name="smtp_password" class="form-control" value="<?= htmlspecialchars($s['smtp_password'] ?? '') ?>" placeholder="App password">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>From Email</label>
                    <input type="text" name="smtp_from_email" class="form-control" value="<?= htmlspecialchars($s['smtp_from_email'] ?? '') ?>" placeholder="noreply@example.com">
                </div>
                <div class="form-group">
                    <label>From Name</label>
                    <input type="text" name="smtp_from_name" class="form-control" value="<?= htmlspecialchars($s['smtp_from_name'] ?? '') ?>" placeholder="Dive Into Yourself">
                </div>
            </div>

            <div class="form-group">
                <label>Admin Email (receives contact form submissions)</label>
                <input type="text" name="admin_email" class="form-control" value="<?= htmlspecialchars($s['admin_email'] ?? '') ?>" placeholder="admin@example.com">
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
