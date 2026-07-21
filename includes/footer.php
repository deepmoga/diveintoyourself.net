<?php
$settings = getAllSettings();
?>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-box fade-in">
            <div class="cta-content">
                <span class="sub-title"><?php echo htmlspecialchars($settings['cta1_subtitle'] ?? 'Ready to Transform?'); ?></span>
                <h2><?php echo htmlspecialchars($settings['cta1_title'] ?? 'Begin Your Best Journey Inward Today'); ?></h2>
                <p><?php echo htmlspecialchars($settings['cta1_description'] ?? 'Join our professional community and discover top mindfulness practices for lasting inner peace.'); ?></p>
            </div>
            <a href="<?php echo htmlspecialchars($settings['cta1_btn_link'] ?? SITE_URL . '/contact'); ?>" class="btn btn-outline"><?php echo htmlspecialchars($settings['cta1_btn_text'] ?? 'Join Us Now'); ?> <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer" id="contact">
    <div class="footer-top">
        <div class="container">
            <div class="footer-grid">
                <!-- Footer About -->
                <div class="footer-about">
                    <a href="<?php echo SITE_URL; ?>/" class="logo">
                        <img src="<?php echo SITE_URL . '/' . htmlspecialchars($settings['logo'] ?? 'images/logo.png'); ?>" alt="<?php echo htmlspecialchars($settings['site_name'] ?? 'Dive Into Yourself'); ?>">
                    </a>
                    <p><?php echo htmlspecialchars($settings['footer_text'] ?? 'Dive Into Yourself is the best professional centre for meditation, mindfulness, and spiritual growth. We help you discover top inner peace through holistic wellness practices in Truganina, Victoria.'); ?></p>
                    <div class="footer-social">
                        <?php if (!empty($settings['facebook'])): ?>
                            <a href="<?php echo htmlspecialchars($settings['facebook']); ?>" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($settings['instagram'])): ?>
                            <a href="<?php echo htmlspecialchars($settings['instagram']); ?>" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($settings['youtube'])): ?>
                            <a href="<?php echo htmlspecialchars($settings['youtube']); ?>" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($settings['twitter'])): ?>
                            <a href="<?php echo htmlspecialchars($settings['twitter']); ?>" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($settings['linkedin'])): ?>
                            <a href="<?php echo htmlspecialchars($settings['linkedin']); ?>" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($settings['whatsapp'])): ?>
                            <a href="<?php echo htmlspecialchars($settings['whatsapp']); ?>" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-col">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="<?php echo SITE_URL; ?>/">Home</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/about">About Us</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/services">Our Services</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/gallery">Gallery</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/blogs">Blogs</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/#why-us">Why Choose Us</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/#testimonials">Testimonials</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/contact">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Our Services -->
                <div class="footer-col">
                    <h3>Our Services</h3>
                    <ul class="footer-links">
                        <?php
                        $footer_services = getActiveServices(6);
                        if (!empty($footer_services)):
                            foreach ($footer_services as $fs):
                        ?>
                            <li><a href="<?php echo SITE_URL . '/service/' . htmlspecialchars($fs['slug'] ?? generateSlug($fs['title'])); ?>"><?php echo htmlspecialchars($fs['title']); ?></a></li>
                        <?php
                            endforeach;
                        else:
                        ?>
                            <li><a href="<?php echo SITE_URL; ?>/services">Guided Meditation</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/services">Mindfulness Training</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/services">Spiritual Counselling</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/services">Holistic Therapy</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/services">Yoga & Wellness</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/services">Inner Peace Workshops</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="footer-col">
                    <h3>Get In Touch</h3>
                    <ul class="footer-contact">
                        <?php if (!empty($settings['address'])): ?>
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo nl2br(htmlspecialchars($settings['address'])); ?></span>
                        </li>
                        <?php endif; ?>
                        <?php if (!empty($settings['email'])): ?>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span><a href="mailto:<?php echo htmlspecialchars($settings['email']); ?>"><?php echo htmlspecialchars($settings['email']); ?></a></span>
                        </li>
                        <?php endif; ?>
                        <?php if (!empty($settings['phone1'])): ?>
                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <span><a href="tel:<?php echo htmlspecialchars($settings['phone1']); ?>"><?php echo htmlspecialchars($settings['phone1']); ?></a></span>
                        </li>
                        <?php endif; ?>
                        <?php if (!empty($settings['phone2'])): ?>
                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <span><a href="tel:<?php echo htmlspecialchars($settings['phone2']); ?>"><?php echo htmlspecialchars($settings['phone2']); ?></a></span>
                        </li>
                        <?php endif; ?>
                        <?php if (!empty($settings['opening_hours'])): ?>
                        <li>
                            <i class="fas fa-clock"></i>
                            <span><?php echo htmlspecialchars($settings['opening_hours']); ?></span>
                        </li>
                        <?php endif; ?>
                    </ul>
                    <?php if (!empty($settings['map_embed'])): ?>
                    <div class="footer-map">
                        <?php echo $settings['map_embed']; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <a href="<?php echo SITE_URL; ?>/"><?php echo htmlspecialchars($settings['site_name'] ?? 'Dive Into Yourself'); ?></a>. <?php echo htmlspecialchars($settings['copyright_text'] ?? 'All Rights Reserved.'); ?> | Crafted with <i class="fas fa-heart" style="color: #e74c3c;"></i> by Official Digital Marketing</p>
        </div>
    </div>
</footer>

<!-- WhatsApp Float Button -->
<?php
$whatsapp = $settings['whatsapp'] ?? '';
if (!empty($whatsapp)):
    $wa_number = preg_replace('/[^0-9]/', '', $whatsapp);
?>
<a href="https://wa.me/<?php echo $wa_number; ?>" target="_blank" class="whatsapp-float" aria-label="Chat on WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>
<?php endif; ?>

<!-- Scroll to Top -->
<button class="scroll-top" aria-label="Back to top">
    <i class="fas fa-chevron-up"></i>
</button>

<!-- Main JavaScript -->
<script src="<?php echo SITE_URL; ?>/js/script.js"></script>

</body>
</html>
