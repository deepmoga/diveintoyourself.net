<?php
require_once __DIR__ . '/includes/functions.php';

$page_slug = 'home';
$settings = getAllSettings();

include __DIR__ . '/includes/header.php';
?>

<main class="new-home">
    <section class="home-hero">
        <div class="home-hero-bg">
            <img src="<?php echo SITE_URL; ?>/images/home/yoga-warrior-pose.jpeg" alt="Yoga training session with Manpreet Singh Sangha">
        </div>
        <div class="home-hero-overlay"></div>
        <div class="container">
            <div class="home-hero-content">
                <span class="home-kicker">Dive Into Yourself</span>
                <h1>Rediscover Peace, Strength, and Your Original Self</h1>
                <p>Personal counselling, breathing practices, meditation, yoga, and one-on-one support for people who want to understand themselves more deeply and live with greater calm.</p>
                <div class="home-hero-actions">
                    <a href="#document" class="btn btn-secondary">View Profile <i class="fas fa-file-pdf"></i></a>
                    <a href="#services" class="btn btn-outline">Explore Services <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="home-hero-card">
                <img src="<?php echo SITE_URL; ?>/images/home/yoga-bow-pose.jpeg" alt="Breathing and yoga practice">
                <div class="home-hero-card-caption">
                    <strong>Guided by Manpreet Singh Sangha</strong>
                    <span>Yoga, meditation, counselling, and personal training</span>
                </div>
            </div>
        </div>
    </section>

    <section class="home-document-section" id="document">
        <div class="container">
            <div class="home-section-heading">
                <span class="home-kicker">Profile Document</span>
                <h2>View the Attached PDF</h2>
                <p>The original PDF is shown below for easy viewing. You can also open it directly in a new tab.</p>
            </div>

            <div class="home-document-layout">
                <div class="home-pdf-viewer">
                    <object data="<?php echo SITE_URL; ?>/files/docx-002.pdf" type="application/pdf">
                        <iframe src="<?php echo SITE_URL; ?>/files/docx-002.pdf" title="Dive Into Yourself PDF"></iframe>
                    </object>
                </div>
                <aside class="home-document-card">
                    <img src="<?php echo SITE_URL; ?>/images/home/yoga-certificate.jpeg" alt="Yoga teacher certification for Manpreet Singh Sangha">
                    <h3>Certified Yoga Teacher</h3>
                    <p>Yoga Alliance International certification for Manpreet Singh Sangha, supporting a grounded approach to yoga, meditation, breathing, and self-discovery.</p>
                    <a href="<?php echo SITE_URL; ?>/files/docx-002.pdf" class="btn btn-primary" target="_blank">Open PDF <i class="fas fa-up-right-from-square"></i></a>
                </aside>
            </div>
        </div>
    </section>

    <section class="home-about-section" id="about-home">
        <div class="container">
            <div class="home-about-grid">
                <div class="home-about-media">
                    <img src="<?php echo SITE_URL; ?>/images/home/yoga-lunge-pose.jpeg" alt="Yoga practice and personal training">
                </div>
                <div class="home-about-copy">
                    <span class="home-kicker">About Us</span>
                    <h2>A Space for Self-Discovery and Inner Understanding</h2>
                    <p>I've often wondered about the struggle that the present generation is facing despite having more facilities than previous generations. However, these facilities have not brought greater joy to our lives.</p>
                    <p>Despite comfortable living, faster cars, and instant communication, we still struggle to find time to relax, rest, or communicate with each other. Modern amenities offer convenience, but they do not necessarily bring peace or lasting happiness.</p>
                    <p>At Dive Into Yourself, the vision is to encourage self-discovery and personal understanding, to help restore the childish smile we may have lost and reconnect with our original selves before we became consumed by worldly distractions.</p>
                    <div class="home-signature">
                        <span>Warm Regards,</span>
                        <strong>Manpreet Singh Sangha</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-services-section" id="services">
        <div class="container">
            <div class="home-section-heading">
                <span class="home-kicker">Services</span>
                <h2>Practical Support for Mind, Body, and Self</h2>
                <p>Choose the support that fits your journey, whether in person or online.</p>
            </div>

            <div class="home-services-grid">
                <div class="home-service-card">
                    <i class="fas fa-comments"></i>
                    <h3>Personal Counselling</h3>
                    <p>Reflective conversations to understand yourself and develop healthier relationships.</p>
                </div>
                <div class="home-service-card">
                    <i class="fas fa-wind"></i>
                    <h3>Breathing & Meditation Practices</h3>
                    <p>Calming practices that help bring attention, clarity, and steadiness back into daily life.</p>
                </div>
                <div class="home-service-card">
                    <i class="fas fa-spa"></i>
                    <h3>Yoga Training Sessions</h3>
                    <p>Structured yoga sessions for balance, mobility, discipline, and inner connection.</p>
                </div>
                <div class="home-service-card">
                    <i class="fas fa-dumbbell"></i>
                    <h3>One-on-One Personal Training</h3>
                    <p>Personalized movement and fitness guidance shaped around your body and goals.</p>
                </div>
                <div class="home-service-card featured">
                    <i class="fas fa-video"></i>
                    <h3>Online Counselling, Meditation & Yoga</h3>
                    <p>Remote sessions for personal counselling, meditation, and yoga classes from wherever you are.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="home-gallery-strip">
        <div class="container">
            <div class="home-gallery-grid">
                <img src="<?php echo SITE_URL; ?>/images/home/yoga-bow-pose.jpeg" alt="Yoga bow pose">
                <img src="<?php echo SITE_URL; ?>/images/home/yoga-warrior-pose.jpeg" alt="Yoga warrior pose">
                <img src="<?php echo SITE_URL; ?>/images/home/yoga-lunge-pose.jpeg" alt="Yoga lunge pose">
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
