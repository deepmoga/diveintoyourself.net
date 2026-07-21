<?php
require_once __DIR__ . '/includes/functions.php';

$page_slug = 'services';
$settings = getAllSettings();

include __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <h1>Services</h1>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>/">Home</a>
            <span class="separator">/</span>
            <span>Services</span>
        </div>
    </div>
</section>

<main class="new-home">
    <section class="home-services-section services-page-new">
        <div class="container">
            <div class="home-section-heading">
                <span class="home-kicker">Services</span>
                <h2>Personal Support for Inner and Outer Wellbeing</h2>
                <p>Explore practical, grounded sessions for self-understanding, calm, movement, and personal growth.</p>
            </div>

            <div class="home-services-grid">
                <div class="home-service-card">
                    <i class="fas fa-comments"></i>
                    <h3>Personal Counselling</h3>
                    <p>Supportive one-on-one conversations to understand yourself, your emotions, and your relationships with more clarity.</p>
                </div>
                <div class="home-service-card">
                    <i class="fas fa-wind"></i>
                    <h3>Breathing & Meditation Practices</h3>
                    <p>Simple, steady practices designed to calm the nervous system, sharpen awareness, and create inner quiet.</p>
                </div>
                <div class="home-service-card">
                    <i class="fas fa-spa"></i>
                    <h3>Yoga Training Sessions</h3>
                    <p>Guided yoga sessions focused on flexibility, balance, strength, posture, and mindful movement.</p>
                </div>
                <div class="home-service-card">
                    <i class="fas fa-dumbbell"></i>
                    <h3>One-on-One Personal Training</h3>
                    <p>Personalized fitness and movement guidance tailored to your body, comfort level, and long-term wellbeing.</p>
                </div>
                <div class="home-service-card featured">
                    <i class="fas fa-video"></i>
                    <h3>Online Personal Counselling, Meditation & Yoga Classes</h3>
                    <p>Flexible online sessions for people who want personal guidance from home or while travelling.</p>
                </div>
            </div>

            <div class="services-page-image">
                <img src="<?php echo SITE_URL; ?>/images/home/yoga-bow-pose.jpeg" alt="Yoga training session">
                <img src="<?php echo SITE_URL; ?>/images/home/yoga-lunge-pose.jpeg" alt="Personal yoga practice">
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
