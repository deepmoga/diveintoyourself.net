<?php
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tab = $_POST['tab'] ?? 'hero';

    if ($tab === 'hero') {
        $fields = [
            'hero_label', 'hero_title', 'hero_highlight', 'hero_title2', 'hero_description',
            'hero_btn_text', 'hero_btn_link', 'hero_btn2_text', 'hero_btn2_link',
            'hero_stat1_number', 'hero_stat1_suffix', 'hero_stat1_label',
            'hero_stat2_number', 'hero_stat2_suffix', 'hero_stat2_label',
            'hero_stat3_number', 'hero_stat3_suffix', 'hero_stat3_label',
        ];
        foreach ($fields as $f) {
            $val = ($f === 'hero_description') ? ($_POST[$f] ?? '') : sanitize($_POST[$f] ?? '');
            updateSetting($f, $val);
        }
        if (!empty($_FILES['hero_image']['tmp_name'])) {
            $old = getSetting('hero_image');
            $new = uploadImage($_FILES['hero_image'], 'hero');
            if ($new) {
                if ($old) deleteImage($old);
                updateSetting('hero_image', $new);
            }
        }
    } elseif ($tab === 'about') {
        $fields = [
            'about_subtitle', 'about_title', 'about_highlight', 'about_title2', 'about_description',
            'about_exp_number', 'about_exp_suffix', 'about_exp_label',
            'about_btn_text', 'about_btn_link',
        ];
        foreach ($fields as $f) {
            $val = ($f === 'about_description') ? ($_POST[$f] ?? '') : sanitize($_POST[$f] ?? '');
            updateSetting($f, $val);
        }
        for ($i = 1; $i <= 4; $i++) {
            updateSetting("about_feature{$i}_icon", sanitize($_POST["about_feature{$i}_icon"] ?? ''));
            updateSetting("about_feature{$i}_title", sanitize($_POST["about_feature{$i}_title"] ?? ''));
            updateSetting("about_feature{$i}_desc", sanitize($_POST["about_feature{$i}_desc"] ?? ''));
        }
        foreach (['about_image', 'about_image2'] as $imgField) {
            if (!empty($_FILES[$imgField]['tmp_name'])) {
                $old = getSetting($imgField);
                $new = uploadImage($_FILES[$imgField], 'about');
                if ($new) {
                    if ($old) deleteImage($old);
                    updateSetting($imgField, $new);
                }
            }
        }
    } elseif ($tab === 'headers') {
        $sections = ['services', 'whyus', 'testimonials'];
        foreach ($sections as $sec) {
            $headerFields = ['subtitle', 'title', 'highlight', 'description'];
            if ($sec === 'testimonials') {
                $headerFields[] = 'title2';
            }
            foreach ($headerFields as $hf) {
                $key = "{$sec}_{$hf}";
                $val = ($hf === 'description') ? ($_POST[$key] ?? '') : sanitize($_POST[$key] ?? '');
                updateSetting($key, $val);
            }
        }
    } elseif ($tab === 'quote') {
        $fields = ['quote_text', 'quote_author', 'quote_btn_text', 'quote_btn_link'];
        foreach ($fields as $f) {
            updateSetting($f, sanitize($_POST[$f] ?? ''));
        }
        if (!empty($_FILES['quote_bg_image']['tmp_name'])) {
            $old = getSetting('quote_bg_image');
            $new = uploadImage($_FILES['quote_bg_image'], 'quote');
            if ($new) {
                if ($old) deleteImage($old);
                updateSetting('quote_bg_image', $new);
            }
        }
    } elseif ($tab === 'cta') {
        for ($i = 1; $i <= 2; $i++) {
            $fields = ["cta{$i}_subtitle", "cta{$i}_title", "cta{$i}_description", "cta{$i}_btn_text", "cta{$i}_btn_link"];
            foreach ($fields as $f) {
                $val = (strpos($f, 'description') !== false) ? ($_POST[$f] ?? '') : sanitize($_POST[$f] ?? '');
                updateSetting($f, $val);
            }
        }
    }

    setFlash('success', 'Homepage section updated successfully.');
    header('Location: ' . ADMIN_URL . '/homepage.php?tab=' . $tab);
    exit;
}

$s = getAllSettings();
$activeTab = $_GET['tab'] ?? 'hero';

include __DIR__ . '/includes/header.php';
?>

<div class="card">
    <div class="tabs">
        <button class="tab-btn <?= $activeTab === 'hero' ? 'active' : '' ?>" data-tab="tab-hero">Hero</button>
        <button class="tab-btn <?= $activeTab === 'about' ? 'active' : '' ?>" data-tab="tab-about">About</button>
        <button class="tab-btn <?= $activeTab === 'headers' ? 'active' : '' ?>" data-tab="tab-headers">Section Headers</button>
        <button class="tab-btn <?= $activeTab === 'quote' ? 'active' : '' ?>" data-tab="tab-quote">Quote</button>
        <button class="tab-btn <?= $activeTab === 'cta' ? 'active' : '' ?>" data-tab="tab-cta">CTA Sections</button>
    </div>

    <!-- Hero Tab -->
    <div id="tab-hero" class="tab-content <?= $activeTab === 'hero' ? 'active' : '' ?>">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="tab" value="hero">
            <div class="form-group">
                <label>Hero Label</label>
                <input type="text" name="hero_label" class="form-control" value="<?= htmlspecialchars($s['hero_label'] ?? '') ?>">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Hero Title (Part 1)</label>
                    <input type="text" name="hero_title" class="form-control" value="<?= htmlspecialchars($s['hero_title'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Highlight Text</label>
                    <input type="text" name="hero_highlight" class="form-control" value="<?= htmlspecialchars($s['hero_highlight'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Title (Part 2)</label>
                    <input type="text" name="hero_title2" class="form-control" value="<?= htmlspecialchars($s['hero_title2'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Hero Description</label>
                <textarea name="hero_description" class="editor"><?= $s['hero_description'] ?? '' ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Button 1 Text</label>
                    <input type="text" name="hero_btn_text" class="form-control" value="<?= htmlspecialchars($s['hero_btn_text'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Button 1 Link</label>
                    <input type="text" name="hero_btn_link" class="form-control" value="<?= htmlspecialchars($s['hero_btn_link'] ?? '') ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Button 2 Text</label>
                    <input type="text" name="hero_btn2_text" class="form-control" value="<?= htmlspecialchars($s['hero_btn2_text'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Button 2 Link</label>
                    <input type="text" name="hero_btn2_link" class="form-control" value="<?= htmlspecialchars($s['hero_btn2_link'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Hero Image</label>
                <?php if (!empty($s['hero_image'])): ?>
                    <div class="current-image">
                        <p>Current:</p>
                        <div class="image-preview"><img src="<?= SITE_URL ?>/<?= $s['hero_image'] ?>" id="hero_image_preview"></div>
                    </div>
                <?php endif; ?>
                <input type="file" name="hero_image" class="form-control" accept="image/*" data-preview="hero_image_preview">
                <?php if (empty($s['hero_image'])): ?>
                    <div class="image-preview"><img id="hero_image_preview" style="display:none;"></div>
                <?php endif; ?>
            </div>

            <h3 class="section-title">Hero Stats</h3>
            <?php for ($i = 1; $i <= 3; $i++): ?>
            <div class="form-row">
                <div class="form-group">
                    <label>Stat <?= $i ?> Number</label>
                    <input type="text" name="hero_stat<?= $i ?>_number" class="form-control" value="<?= htmlspecialchars($s["hero_stat{$i}_number"] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Stat <?= $i ?> Suffix</label>
                    <input type="text" name="hero_stat<?= $i ?>_suffix" class="form-control" value="<?= htmlspecialchars($s["hero_stat{$i}_suffix"] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Stat <?= $i ?> Label</label>
                    <input type="text" name="hero_stat<?= $i ?>_label" class="form-control" value="<?= htmlspecialchars($s["hero_stat{$i}_label"] ?? '') ?>">
                </div>
            </div>
            <?php endfor; ?>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Hero Section</button>
        </form>
    </div>

    <!-- About Tab -->
    <div id="tab-about" class="tab-content <?= $activeTab === 'about' ? 'active' : '' ?>">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="tab" value="about">
            <div class="form-group">
                <label>Subtitle</label>
                <input type="text" name="about_subtitle" class="form-control" value="<?= htmlspecialchars($s['about_subtitle'] ?? '') ?>">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Title (Part 1)</label>
                    <input type="text" name="about_title" class="form-control" value="<?= htmlspecialchars($s['about_title'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Highlight Text</label>
                    <input type="text" name="about_highlight" class="form-control" value="<?= htmlspecialchars($s['about_highlight'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Title (Part 2)</label>
                    <input type="text" name="about_title2" class="form-control" value="<?= htmlspecialchars($s['about_title2'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="about_description" class="editor"><?= $s['about_description'] ?? '' ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>About Image 1</label>
                    <?php if (!empty($s['about_image'])): ?>
                        <div class="current-image">
                            <p>Current:</p>
                            <div class="image-preview"><img src="<?= SITE_URL ?>/<?= $s['about_image'] ?>" id="about_image_preview"></div>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="about_image" class="form-control" accept="image/*" data-preview="about_image_preview">
                    <?php if (empty($s['about_image'])): ?>
                        <div class="image-preview"><img id="about_image_preview" style="display:none;"></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label>About Image 2</label>
                    <?php if (!empty($s['about_image2'])): ?>
                        <div class="current-image">
                            <p>Current:</p>
                            <div class="image-preview"><img src="<?= SITE_URL ?>/<?= $s['about_image2'] ?>" id="about_image2_preview"></div>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="about_image2" class="form-control" accept="image/*" data-preview="about_image2_preview">
                    <?php if (empty($s['about_image2'])): ?>
                        <div class="image-preview"><img id="about_image2_preview" style="display:none;"></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Experience Number</label>
                    <input type="text" name="about_exp_number" class="form-control" value="<?= htmlspecialchars($s['about_exp_number'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Experience Suffix</label>
                    <input type="text" name="about_exp_suffix" class="form-control" value="<?= htmlspecialchars($s['about_exp_suffix'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Experience Label</label>
                    <input type="text" name="about_exp_label" class="form-control" value="<?= htmlspecialchars($s['about_exp_label'] ?? '') ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Button Text</label>
                    <input type="text" name="about_btn_text" class="form-control" value="<?= htmlspecialchars($s['about_btn_text'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Button Link</label>
                    <input type="text" name="about_btn_link" class="form-control" value="<?= htmlspecialchars($s['about_btn_link'] ?? '') ?>">
                </div>
            </div>

            <h3 class="section-title">About Features</h3>
            <?php for ($i = 1; $i <= 4; $i++): ?>
            <div class="form-row">
                <div class="form-group">
                    <label>Feature <?= $i ?> Icon</label>
                    <input type="text" name="about_feature<?= $i ?>_icon" class="form-control icon-input" value="<?= htmlspecialchars($s["about_feature{$i}_icon"] ?? '') ?>" placeholder="fas fa-heart">
                    <div class="icon-preview"><i class="<?= htmlspecialchars($s["about_feature{$i}_icon"] ?? 'fas fa-question') ?>"></i> <span>Preview</span></div>
                </div>
                <div class="form-group">
                    <label>Feature <?= $i ?> Title</label>
                    <input type="text" name="about_feature<?= $i ?>_title" class="form-control" value="<?= htmlspecialchars($s["about_feature{$i}_title"] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Feature <?= $i ?> Description</label>
                    <input type="text" name="about_feature<?= $i ?>_desc" class="form-control" value="<?= htmlspecialchars($s["about_feature{$i}_desc"] ?? '') ?>">
                </div>
            </div>
            <?php endfor; ?>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save About Section</button>
        </form>
    </div>

    <!-- Section Headers Tab -->
    <div id="tab-headers" class="tab-content <?= $activeTab === 'headers' ? 'active' : '' ?>">
        <form method="POST">
            <input type="hidden" name="tab" value="headers">

            <h3 class="section-title">Services Section Header</h3>
            <div class="form-group">
                <label>Subtitle</label>
                <input type="text" name="services_subtitle" class="form-control" value="<?= htmlspecialchars($s['services_subtitle'] ?? '') ?>">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="services_title" class="form-control" value="<?= htmlspecialchars($s['services_title'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Highlight</label>
                    <input type="text" name="services_highlight" class="form-control" value="<?= htmlspecialchars($s['services_highlight'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="services_description" class="editor"><?= $s['services_description'] ?? '' ?></textarea>
            </div>

            <h3 class="section-title">Why Choose Us Header</h3>
            <div class="form-group">
                <label>Subtitle</label>
                <input type="text" name="whyus_subtitle" class="form-control" value="<?= htmlspecialchars($s['whyus_subtitle'] ?? '') ?>">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="whyus_title" class="form-control" value="<?= htmlspecialchars($s['whyus_title'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Highlight</label>
                    <input type="text" name="whyus_highlight" class="form-control" value="<?= htmlspecialchars($s['whyus_highlight'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="whyus_description" class="editor"><?= $s['whyus_description'] ?? '' ?></textarea>
            </div>

            <h3 class="section-title">Testimonials Header</h3>
            <div class="form-group">
                <label>Subtitle</label>
                <input type="text" name="testimonials_subtitle" class="form-control" value="<?= htmlspecialchars($s['testimonials_subtitle'] ?? '') ?>">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="testimonials_title" class="form-control" value="<?= htmlspecialchars($s['testimonials_title'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Highlight</label>
                    <input type="text" name="testimonials_highlight" class="form-control" value="<?= htmlspecialchars($s['testimonials_highlight'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Title (Part 2)</label>
                <input type="text" name="testimonials_title2" class="form-control" value="<?= htmlspecialchars($s['testimonials_title2'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="testimonials_description" class="editor"><?= $s['testimonials_description'] ?? '' ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Section Headers</button>
        </form>
    </div>

    <!-- Quote Tab -->
    <div id="tab-quote" class="tab-content <?= $activeTab === 'quote' ? 'active' : '' ?>">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="tab" value="quote">
            <div class="form-group">
                <label>Quote Text</label>
                <input type="text" name="quote_text" class="form-control" value="<?= htmlspecialchars($s['quote_text'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Quote Author</label>
                <input type="text" name="quote_author" class="form-control" value="<?= htmlspecialchars($s['quote_author'] ?? '') ?>">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Button Text</label>
                    <input type="text" name="quote_btn_text" class="form-control" value="<?= htmlspecialchars($s['quote_btn_text'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Button Link</label>
                    <input type="text" name="quote_btn_link" class="form-control" value="<?= htmlspecialchars($s['quote_btn_link'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Background Image</label>
                <?php if (!empty($s['quote_bg_image'])): ?>
                    <div class="current-image">
                        <p>Current:</p>
                        <div class="image-preview"><img src="<?= SITE_URL ?>/<?= $s['quote_bg_image'] ?>" id="quote_bg_preview"></div>
                    </div>
                <?php endif; ?>
                <input type="file" name="quote_bg_image" class="form-control" accept="image/*" data-preview="quote_bg_preview">
                <?php if (empty($s['quote_bg_image'])): ?>
                    <div class="image-preview"><img id="quote_bg_preview" style="display:none;"></div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Quote Section</button>
        </form>
    </div>

    <!-- CTA Tab -->
    <div id="tab-cta" class="tab-content <?= $activeTab === 'cta' ? 'active' : '' ?>">
        <form method="POST">
            <input type="hidden" name="tab" value="cta">

            <h3 class="section-title">CTA Section 1</h3>
            <div class="form-group">
                <label>Subtitle</label>
                <input type="text" name="cta1_subtitle" class="form-control" value="<?= htmlspecialchars($s['cta1_subtitle'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="cta1_title" class="form-control" value="<?= htmlspecialchars($s['cta1_title'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="cta1_description" class="editor"><?= $s['cta1_description'] ?? '' ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Button Text</label>
                    <input type="text" name="cta1_btn_text" class="form-control" value="<?= htmlspecialchars($s['cta1_btn_text'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Button Link</label>
                    <input type="text" name="cta1_btn_link" class="form-control" value="<?= htmlspecialchars($s['cta1_btn_link'] ?? '') ?>">
                </div>
            </div>

            <h3 class="section-title">CTA Section 2</h3>
            <div class="form-group">
                <label>Subtitle</label>
                <input type="text" name="cta2_subtitle" class="form-control" value="<?= htmlspecialchars($s['cta2_subtitle'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="cta2_title" class="form-control" value="<?= htmlspecialchars($s['cta2_title'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="cta2_description" class="editor"><?= $s['cta2_description'] ?? '' ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Button Text</label>
                    <input type="text" name="cta2_btn_text" class="form-control" value="<?= htmlspecialchars($s['cta2_btn_text'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Button Link</label>
                    <input type="text" name="cta2_btn_link" class="form-control" value="<?= htmlspecialchars($s['cta2_btn_link'] ?? '') ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save CTA Sections</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
