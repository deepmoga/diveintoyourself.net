<?php
require_once __DIR__ . '/includes/functions.php';

$page_slug = 'about';
$settings = getAllSettings();

include __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <h1>About Us</h1>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>/">Home</a>
            <span class="separator">/</span>
            <span>About Us</span>
        </div>
    </div>
</section>

<main class="inner-clean-page">
    <section class="about-story-section">
        <div class="container">
            <div class="about-story-copy">
                <p>I've often wondered about the struggle that the present generation is facing despite having more facilities than previous generations. However, these facilities have not brought greater joy to our lives. Despite comfortable living, faster cars, and instant communication, we still struggle to find time to relax, rest, or communicate with each other. We always seem to be running out of time. While modern amenities offer convenience, they do not necessarily bring peace or lasting happiness.</p>
                <p>In my view, we can eliminate some of our life problems with better understanding of self and others. By doing so, we can also develop a peaceful relationship with other human beings, including the one with the self. Once we develop better self-understanding, we can then begin to understand others.</p>
                <p>At Dive into Yourself, my vision is to encourage self-discovery and personal understanding, to help restore the childish smile we may have lost and reconnect with our original selves before we became consumed by worldly distractions. It's about re-examining life and ourselves with fresh eyes.</p>
                <p>I look forward to connecting and exploring this journey of self-discovery together.</p>
                <div class="about-signature">
                    <span>Warm Regards,</span>
                    <strong>Manpreet Singh Sangha</strong>
                </div>
            </div>
        </div>
    </section>

    <section class="about-certificate-section">
        <div class="container">
            <div class="about-certificate-panel">
                <img src="<?php echo SITE_URL; ?>/images/home/yoga-certificate.jpeg" alt="Yoga teacher certification for Manpreet Singh Sangha">
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
