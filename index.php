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
                    <a href="#meaning" class="btn btn-secondary">Start Inward <i class="fas fa-arrow-right"></i></a>
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

    <section class="home-meaning-section" id="meaning">
        <div class="container">
            <div class="home-section-head">
                <span class="home-eyebrow">Rediscovering the Wonders Within</span>
                <h2>Dive Into Yourself: Rediscovering the Wonders Within</h2>
                <p>In a world that is always nudging us to look outward, it is easy to forget that some of the greatest treasures lie within.</p>
            </div>

            <div class="home-story-card">
                <h3>What Does It Mean to "Dive Into Yourself"?</h3>
                <p><strong>Dive into yourself</strong> is more than just a phrase; it is an invitation to embark on a personal voyage of self-exploration, guided by no one but yourself.</p>
                <p>At Dive Into Yourself, we offer a gentle compass for your journey inward. These practices are designed to help you peel away life's noise and reconnect with your inner core - your truest, most peaceful self.</p>
            </div>

            <div class="home-practice-grid">
                <article class="home-practice-card">
                    <i class="fas fa-spa"></i>
                    <h3>Yoga</h3>
                    <p>Move beyond the physical poses to explore the subtle energies and stillness within your body.</p>
                </article>
                <article class="home-practice-card">
                    <i class="fas fa-wind"></i>
                    <h3>Breathing Meditation</h3>
                    <p>Discover how a few conscious breaths can quiet the mind and open the door to self-understanding.</p>
                </article>
                <article class="home-practice-card">
                    <i class="fas fa-comments"></i>
                    <h3>Personal Counselling</h3>
                    <p>Connect one-on-one to unravel your thoughts, emotions, and patterns, and reconnect with your original self.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="home-inward-section">
        <div class="container">
            <div class="home-inward-grid">
                <div class="home-inward-card">
                    <span class="home-eyebrow">Why Look Inward?</span>
                    <h2>Reverse the search for happiness.</h2>
                    <p>Many of us chase happiness in places or things, only to find it elusive. "Dive into yourself" is an invitation to reverse that search. It is about getting quiet, tuning in, and noticing the contentment that has always been within reach.</p>
                </div>
                <div class="home-points-card">
                    <h3>This journey helps you:</h3>
                    <ul>
                        <li>Reconnect with your original, unfiltered self.</li>
                        <li>Remember who you are beneath roles, routines, and expectations.</li>
                        <li>Carry a sense of peace even when life outside feels chaotic.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="home-journey-section">
        <div class="container">
            <div class="home-journey-card">
                <span class="home-eyebrow">Join the Journey</span>
                <h2>Make space for a small inward dive.</h2>
                <p>Whether that means rolling out your yoga mat, sitting quietly with your breath, or having a compassionate conversation with yourself, you might be surprised by what you find when you listen inwards instead of outwards.</p>
                <p>Together, let's rediscover the wonder within, honor our individuality, and nurture a foundation of peace that radiates outward into the world.</p>
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
