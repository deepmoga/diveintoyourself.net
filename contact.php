<?php
require_once __DIR__ . '/includes/functions.php';

$page_slug = 'contact';
$settings = getAllSettings();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        setFlash('error', 'Please fill in all required fields (Name, Email, Subject, and Message).');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setFlash('error', 'Please enter a valid email address.');
    } else {
        $site_name = $settings['site_name'] ?? 'Dive Into Yourself';
        $admin_email = $settings['admin_email'] ?? ($settings['email'] ?? '');

        // Build admin notification email content
        $adminContent = '
        <p style="margin:0 0 10px;"><strong>You have received a new contact form enquiry:</strong></p>
        <table style="width:100%;border-collapse:collapse;margin:15px 0;">
            <tr><td style="padding:10px 15px;background:#f4f0f8;border:1px solid #e8e0f0;font-weight:600;width:130px;">Name</td><td style="padding:10px 15px;border:1px solid #e8e0f0;">' . htmlspecialchars($name) . '</td></tr>
            <tr><td style="padding:10px 15px;background:#f4f0f8;border:1px solid #e8e0f0;font-weight:600;">Email</td><td style="padding:10px 15px;border:1px solid #e8e0f0;"><a href="mailto:' . htmlspecialchars($email) . '">' . htmlspecialchars($email) . '</a></td></tr>
            <tr><td style="padding:10px 15px;background:#f4f0f8;border:1px solid #e8e0f0;font-weight:600;">Phone</td><td style="padding:10px 15px;border:1px solid #e8e0f0;">' . (!empty($phone) ? htmlspecialchars($phone) : '<em>Not provided</em>') . '</td></tr>
            <tr><td style="padding:10px 15px;background:#f4f0f8;border:1px solid #e8e0f0;font-weight:600;">Subject</td><td style="padding:10px 15px;border:1px solid #e8e0f0;">' . htmlspecialchars($subject) . '</td></tr>
            <tr><td style="padding:10px 15px;background:#f4f0f8;border:1px solid #e8e0f0;font-weight:600;vertical-align:top;">Message</td><td style="padding:10px 15px;border:1px solid #e8e0f0;">' . nl2br(htmlspecialchars($message)) . '</td></tr>
        </table>
        <p style="margin:15px 0 0;font-size:13px;color:#888;">This enquiry was submitted via the contact form on your website.</p>';

        $adminHtml = getEmailTemplate('New Contact Enquiry', $adminContent, $site_name);

        // Build user confirmation email content
        $userContent = '
        <p>Dear <strong>' . htmlspecialchars($name) . '</strong>,</p>
        <p>Thank you for reaching out to us at <strong>' . htmlspecialchars($site_name) . '</strong>. We have received your message and will get back to you as soon as possible.</p>
        <p style="margin:15px 0;padding:15px 20px;background:#f4f0f8;border-left:4px solid #6f4894;border-radius:4px;"><strong>Your Subject:</strong> ' . htmlspecialchars($subject) . '</p>
        <p>If you need immediate assistance, please feel free to call us or visit our centre in Truganina, Victoria.</p>
        <p style="margin:20px 0 0;">Warm regards,<br><strong>' . htmlspecialchars($site_name) . ' Team</strong></p>';

        $userHtml = getEmailTemplate('Thank You for Contacting Us', $userContent, $site_name);

        // Send admin notification email
        $mailSent = false;
        if (!empty($admin_email)) {
            $mailSent = sendMail($admin_email, 'New Contact Enquiry: ' . $subject, $adminHtml, $email);
        }

        // Send confirmation email to user
        sendMail($email, 'Thank you for contacting ' . $site_name, $userHtml);

        if ($mailSent) {
            setFlash('success', 'Thank you for your message! We have received your enquiry and will get back to you shortly.');
        } else {
            setFlash('success', 'Thank you for your message! We will get back to you shortly.');
        }

        // PRG pattern - redirect to prevent duplicate submissions
        header('Location: ' . SITE_URL . '/contact');
        exit;
    }
}

$flash = getFlash();
$form_success = ($flash && $flash['type'] === 'success');
$form_error = ($flash && $flash['type'] === 'error') ? $flash['message'] : '';

include __DIR__ . '/includes/header.php';
?>

<!-- ============================================
     PAGE BANNER
     ============================================ -->
<section class="page-banner">
    <div class="container">
        <h1>Contact Us</h1>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>/">Home</a>
            <span class="separator">/</span>
            <span>Contact Us</span>
        </div>
    </div>
</section>

<!-- ============================================
     CONTACT SECTION
     ============================================ -->
<section class="section">
    <div class="container">
        <div class="section-header fade-in">
            <span class="sub-title"><?php echo htmlspecialchars($settings['contact_subtitle'] ?? 'Get In Touch'); ?></span>
            <h2><?php echo $settings['contact_title'] ?? 'We\'d Love to <span>Hear From You</span>'; ?></h2>
            <p><?php echo htmlspecialchars($settings['contact_description'] ?? 'Have a question or want to learn more about our programs? Reach out to us and we\'ll get back to you as soon as possible.'); ?></p>
        </div>

        <div class="contact-grid">
            <!-- Contact Form -->
            <div class="contact-form fade-in-left">
                <?php if ($form_success): ?>
                    <div class="contact-success">
                        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($flash['message']); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($form_error)): ?>
                    <div class="contact-success" style="background: #f8d7da; color: #721c24;">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($form_error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" placeholder="Your full name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" placeholder="Your email address" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="Your phone number" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <input type="text" id="subject" name="subject" placeholder="Message subject" required value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" placeholder="Write your message here..." required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    </div>
                    <button type="submit" name="contact_submit" class="btn btn-primary">Send Message <i class="fas fa-paper-plane"></i></button>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="contact-info-card fade-in-right">
                <h3>Contact Information</h3>

                <?php if (!empty($settings['address'])): ?>
                <div class="contact-info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <strong>Our Location</strong>
                        <p><?php echo nl2br(htmlspecialchars($settings['address'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($settings['phone1'])): ?>
                <div class="contact-info-item">
                    <i class="fas fa-phone-alt"></i>
                    <div>
                        <strong>Phone</strong>
                        <p>
                            <a href="tel:<?php echo htmlspecialchars($settings['phone1']); ?>"><?php echo htmlspecialchars($settings['phone1']); ?></a>
                            <?php if (!empty($settings['phone2'])): ?>
                                <br><a href="tel:<?php echo htmlspecialchars($settings['phone2']); ?>"><?php echo htmlspecialchars($settings['phone2']); ?></a>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($settings['email'])): ?>
                <div class="contact-info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <strong>Email</strong>
                        <p><a href="mailto:<?php echo htmlspecialchars($settings['email']); ?>"><?php echo htmlspecialchars($settings['email']); ?></a></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($settings['opening_hours'])): ?>
                <div class="contact-info-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <strong>Opening Hours</strong>
                        <p><?php echo nl2br(htmlspecialchars($settings['opening_hours'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($settings['map_embed'])): ?>
                <div class="map-container">
                    <?php echo $settings['map_embed']; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
