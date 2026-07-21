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
                <h1>Dive Into Yourself: Rediscovering the Wonders Within</h1>
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

    <section class="home-meaning-section" id="meaning">
        <div class="container">
            <div class="home-section-head">
                <h2>What Does It Mean to “Dive Into Yourself”?</h2>
            </div>

            <div class="home-story-card">
                <p>In a world that’s always nudging us to look outward—chasing achievements, chasing approval, always scanning the horizon—it’s easy to forget that some of the greatest treasures lie within. Dive into yourself is more than just a phrase; it’s an invitation to embark on a personal voyage of self-exploration, guided by no one but yourself.</p>
                <p>At Dive Into Yourself, we offer a gentle compass for your journey inward with:</p>
            </div>

            <div class="home-practice-grid">
                <article class="home-practice-card">
                    <i class="fas fa-spa"></i>
                    <p><strong>Yoga:</strong> Move beyond the physical poses to explore the subtle energies and stillness within your body.</p>
                </article>
                <article class="home-practice-card">
                    <i class="fas fa-wind"></i>
                    <p><strong>Breathing Meditation:</strong> Discover how a few conscious breaths can quiet the mind and open the door to self-understanding.</p>
                </article>
                <article class="home-practice-card">
                    <i class="fas fa-comments"></i>
                    <p><strong>Personal Counselling:</strong> Connect one-on-one to unravel your thoughts, emotions, and patterns, and reconnect with your original self.</p>
                </article>
            </div>

            <div class="home-story-card home-story-card-after">
                <p>These practices are designed to help you peel away life’s noise and reconnect with your inner core—your truest, most peaceful self.</p>
            </div>
        </div>
    </section>

    <section class="home-inward-section">
        <div class="container">
            <div class="home-inward-grid">
                <div class="home-inward-card">
                    <h2>Why Look Inward?</h2>
                    <p>Many of us chase happiness in places or things, only to find it elusive. “Dive into yourself” is an invitation to reverse that search. It’s about getting quiet, tuning in, and noticing the contentment that’s always been within reach.</p>
                </div>
                <div class="home-points-card">
                    <p>This inner journey can reconnect us to our original self—the unfiltered “you” that may have gotten lost along the way. There’s power in remembering who you are beneath the roles, routines, and expectations. The more you practice looking inward, the easier it becomes to carry that sense of peace with you, regardless of external chaos.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="home-journey-section">
        <div class="container">
            <div class="home-journey-card">
                <h2>Join the Journey</h2>
                <p>Let’s consider making space for a small inward dive—whether that’s rolling out your yoga mat, sitting quietly with your breath, or having a compassionate conversation with yourself. You might be surprised by what you find when you listen inwards instead of outwards.</p>
                <p>Together, let’s rediscover the wonder within, honor our individuality, and nurture a foundation of peace that radiates outward into the world.</p>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
