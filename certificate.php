<?php
require_once __DIR__ . '/includes/functions.php';

$page_slug = 'certificate';
$settings = getAllSettings();

include __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <h1>Certificate</h1>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>/">Home</a>
            <span class="separator">/</span>
            <span>Certificate</span>
        </div>
    </div>
</section>

<main class="inner-clean-page">
    <section class="about-certificate-section">
        <div class="container">
            <div class="about-certificate-panel">
                <img src="<?php echo SITE_URL; ?>/images/home/yoga-certificate.jpeg" alt="Yoga teacher certification for Manpreet Singh Sangha">
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
