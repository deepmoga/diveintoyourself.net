<?php
require_once __DIR__ . '/includes/functions.php';

$page_slug = 'home';
$settings = getAllSettings();

include __DIR__ . '/includes/header.php';
?>

<main class="home-page">
    <section class="home-clean-hero">
        <div class="container">
            <div class="home-hero-copy">
                <span class="home-eyebrow">Dive Into Yourself</span>
                <h1>Understand Yourself. Find Calm. Live Better.</h1>
                <p>Personal counselling, breathing practices, meditation, yoga, and one-on-one support with Manpreet Singh Sangha.</p>
                <div class="home-hero-actions">
                    <a href="#document" class="btn btn-secondary">View PDF <i class="fas fa-file-pdf"></i></a>
                    <a href="#services" class="btn btn-outline">Services <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="home-hero-panel">
                <div class="home-credential">
                    <img src="<?php echo SITE_URL; ?>/images/home/yoga-certificate.jpeg" alt="Yoga teacher certification for Manpreet Singh Sangha">
                    <div>
                        <span>Certified Yoga Teacher</span>
                        <strong>Manpreet Singh Sangha</strong>
                    </div>
                </div>
                <div class="home-mini-photo">
                    <img src="<?php echo SITE_URL; ?>/images/home/yoga-bow-pose.jpeg" alt="Yoga practice">
                </div>
            </div>
        </div>
    </section>

    <section class="home-pdf-section" id="document">
        <div class="container">
            <div class="home-section-head">
                <span class="home-eyebrow">Profile Document</span>
                <h2>Attached PDF</h2>
                <p>The PDF is available below. Open it in a new tab for a larger view.</p>
            </div>
            <div class="home-pdf-card">
                <object data="<?php echo SITE_URL; ?>/files/docx-002.pdf" type="application/pdf">
                    <iframe src="<?php echo SITE_URL; ?>/files/docx-002.pdf" title="Dive Into Yourself PDF"></iframe>
                </object>
            </div>
            <div class="home-center-action">
                <a href="<?php echo SITE_URL; ?>/files/docx-002.pdf" class="btn btn-primary" target="_blank">Open PDF <i class="fas fa-up-right-from-square"></i></a>
            </div>
        </div>
    </section>

    <section class="home-about-brief" id="about-home">
        <div class="container">
            <div class="home-about-card">
                <div>
                    <span class="home-eyebrow">About Us</span>
                    <h2>A space for self-discovery and personal understanding.</h2>
                </div>
                <div class="home-about-text">
                    <p>Modern facilities have made life more convenient, but they have not always brought peace or lasting happiness. We still struggle to relax, communicate, and understand ourselves.</p>
                    <p>At Dive Into Yourself, the vision is to encourage self-discovery, restore the natural smile we may have lost, and help us reconnect with our original selves.</p>
                    <p><strong>Warm Regards,<br>Manpreet Singh Sangha</strong></p>
                </div>
            </div>
        </div>
    </section>

    <section class="home-services-brief" id="services">
        <div class="container">
            <div class="home-section-head">
                <span class="home-eyebrow">Services</span>
                <h2>What We Offer</h2>
                <p>Focused guidance for mental clarity, body movement, and personal growth.</p>
            </div>
            <div class="home-service-list">
                <div><i class="fas fa-comments"></i><span>Personal Counselling</span></div>
                <div><i class="fas fa-wind"></i><span>Breathing & Meditation Practices</span></div>
                <div><i class="fas fa-spa"></i><span>Yoga Training Sessions</span></div>
                <div><i class="fas fa-dumbbell"></i><span>One-on-One Personal Training</span></div>
                <div><i class="fas fa-video"></i><span>Online Counselling, Meditation & Yoga Classes</span></div>
            </div>
        </div>
    </section>

    <section class="home-photo-section">
        <div class="container">
            <div class="home-photo-grid">
                <figure>
                    <img src="<?php echo SITE_URL; ?>/images/home/yoga-bow-pose.jpeg" alt="Yoga bow pose">
                    <figcaption>Yoga Training</figcaption>
                </figure>
                <figure>
                    <img src="<?php echo SITE_URL; ?>/images/home/yoga-warrior-pose.jpeg" alt="Yoga warrior pose">
                    <figcaption>Mindful Movement</figcaption>
                </figure>
                <figure>
                    <img src="<?php echo SITE_URL; ?>/images/home/yoga-lunge-pose.jpeg" alt="Yoga lunge pose">
                    <figcaption>Personal Practice</figcaption>
                </figure>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
