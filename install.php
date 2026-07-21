<?php

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $pdo->exec("CREATE DATABASE IF NOT EXISTS `dive_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `dive_db`");

        // Create tables
        $pdo->exec("CREATE TABLE IF NOT EXISTS admins (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            name VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB");

        $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) NOT NULL UNIQUE,
            setting_value TEXT
        ) ENGINE=InnoDB");

        $pdo->exec("CREATE TABLE IF NOT EXISTS pages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            page_slug VARCHAR(100) NOT NULL UNIQUE,
            page_title VARCHAR(255),
            meta_description TEXT,
            meta_keywords TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB");

        $pdo->exec("CREATE TABLE IF NOT EXISTS services (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            image VARCHAR(255),
            icon VARCHAR(100) DEFAULT 'fas fa-spa',
            badge VARCHAR(50),
            level VARCHAR(50),
            duration VARCHAR(50),
            sort_order INT DEFAULT 0,
            status TINYINT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB");

        $pdo->exec("CREATE TABLE IF NOT EXISTS testimonials (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            designation VARCHAR(100),
            content TEXT,
            image VARCHAR(255),
            rating INT DEFAULT 5,
            sort_order INT DEFAULT 0,
            status TINYINT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB");

        $pdo->exec("CREATE TABLE IF NOT EXISTS stats (
            id INT AUTO_INCREMENT PRIMARY KEY,
            icon VARCHAR(100),
            number INT DEFAULT 0,
            suffix VARCHAR(10) DEFAULT '+',
            label VARCHAR(100),
            sort_order INT DEFAULT 0
        ) ENGINE=InnoDB");

        $pdo->exec("CREATE TABLE IF NOT EXISTS why_features (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255),
            description TEXT,
            icon VARCHAR(100),
            sort_order INT DEFAULT 0,
            status TINYINT DEFAULT 1
        ) ENGINE=InnoDB");

        $pdo->exec("CREATE TABLE IF NOT EXISTS gallery_images (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            image VARCHAR(255) NOT NULL,
            alt_text VARCHAR(255),
            sort_order INT DEFAULT 0,
            status TINYINT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB");

        $pdo->exec("CREATE TABLE IF NOT EXISTS blogs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL,
            excerpt TEXT,
            content LONGTEXT,
            image VARCHAR(255),
            meta_description TEXT,
            meta_keywords TEXT,
            sort_order INT DEFAULT 0,
            status TINYINT DEFAULT 1,
            published_at DATE NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_slug (slug)
        ) ENGINE=InnoDB");

        // Seed admin
        $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = 'admin'");
        $stmt->execute();
        if (!$stmt->fetch()) {
            $stmt = $pdo->prepare("INSERT INTO admins (username, password, name) VALUES (?, ?, ?)");
            $stmt->execute(['admin', password_hash('admin123', PASSWORD_DEFAULT), 'Administrator']);
        }

        // Seed settings
        $settings = [
            'site_name' => 'Dive Into Yourself',
            'site_tagline' => 'Harmonising Your Inner Self & Your Body',
            'logo' => 'images/logo.png',
            'phone1' => '+61 XXX XXX XXX',
            'phone2' => '',
            'email' => 'info@diveintoyourself.com.au',
            'address' => 'Unit 36/20 Property Street, Truganina, Victoria 3029',
            'map_embed' => '',
            'facebook' => '',
            'instagram' => '',
            'youtube' => '',
            'twitter' => '',
            'linkedin' => '',
            'whatsapp' => '',
            'footer_text' => 'Dive Into Yourself is the best professional centre for meditation, mindfulness, and spiritual growth.',
            'copyright_text' => 'Dive Into Yourself',

            // Hero
            'hero_label' => 'Welcome to Dive Into Yourself',
            'hero_title' => 'Harmonising Your',
            'hero_highlight' => 'Inner Self',
            'hero_title2' => '& Your Body',
            'hero_description' => 'Embark on the best professional journey of self-discovery and inner peace. Our top-rated meditation and mindfulness programs help you look inward beyond the physical form — finding the peace that resides within you.',
            'hero_btn_text' => 'Get Started',
            'hero_btn_link' => '#about',
            'hero_btn2_text' => 'Our Services',
            'hero_btn2_link' => '#services',
            'hero_image' => '',
            'hero_stat1_number' => '500',
            'hero_stat1_suffix' => '+',
            'hero_stat1_label' => 'Happy Members',
            'hero_stat2_number' => '15',
            'hero_stat2_suffix' => '+',
            'hero_stat2_label' => 'Expert Instructors',
            'hero_stat3_number' => '30',
            'hero_stat3_suffix' => '+',
            'hero_stat3_label' => 'Professional Programs',

            // About
            'about_subtitle' => 'Who We Are',
            'about_title' => 'Discover the Best Path to',
            'about_highlight' => 'Inner Peace',
            'about_title2' => '& Self-Discovery',
            'about_description' => "Dive Into Yourself is about gaining a deeper understanding of this beautifully created wonderful piece of creation and looking inwards beyond the physical form of the body — the gender, colour, height, weight, and age.\n\nDiving into yourself is also about finding the peace inward which we normally seek outward and find it nowhere, but yet we forget to look inwards. Our professional and experienced team of instructors guide you through this transformative journey with the best holistic wellness practices.",
            'about_image' => '',
            'about_image2' => '',
            'about_exp_number' => '10',
            'about_exp_suffix' => '+',
            'about_exp_label' => 'Years of Excellence',
            'about_btn_text' => 'Explore Our Services',
            'about_btn_link' => '#services',
            'about_feature1_icon' => 'fas fa-brain',
            'about_feature1_title' => 'Mindfulness',
            'about_feature1_desc' => 'Top mindfulness practices for clarity and awareness',
            'about_feature2_icon' => 'fas fa-hand-holding-heart',
            'about_feature2_title' => 'Holistic Therapy',
            'about_feature2_desc' => 'Best therapeutic approaches for complete well-being',
            'about_feature3_icon' => 'fas fa-spa',
            'about_feature3_title' => 'Deep Relaxation',
            'about_feature3_desc' => 'Professional techniques for profound inner calm',
            'about_feature4_icon' => 'fas fa-praying-hands',
            'about_feature4_title' => 'Spiritual Growth',
            'about_feature4_desc' => 'Top guided paths to spiritual awakening',

            // Services section
            'services_subtitle' => 'What We Offer',
            'services_title' => 'Our Best Professional',
            'services_highlight' => 'Services',
            'services_description' => 'Explore our top-rated wellness programs designed to guide you on the best journey of self-discovery, inner healing, and spiritual transformation.',

            // Why Us section
            'whyus_subtitle' => 'Why Choose Us',
            'whyus_title' => 'The Best Reasons to',
            'whyus_highlight' => 'Dive In',
            'whyus_description' => 'Discover why hundreds trust us as the top professional centre for meditation, mindfulness, and spiritual growth in Victoria.',

            // Testimonials section
            'testimonials_subtitle' => 'Testimonials',
            'testimonials_title' => 'What Our',
            'testimonials_highlight' => 'Members',
            'testimonials_title2' => 'Say',
            'testimonials_description' => 'Hear from our community about their transformative experiences and how our best professional programs changed their lives.',

            // Quote section
            'quote_text' => 'To know the ocean, jump into the ocean.',
            'quote_author' => 'Ancient Wisdom for Modern Seekers',
            'quote_btn_text' => 'Interested to Dive In? Join Us Now',
            'quote_btn_link' => '#contact',
            'quote_bg_image' => '',

            // CTA 1
            'cta1_subtitle' => 'Ready to Transform?',
            'cta1_title' => 'Begin Your Best Journey Inward Today',
            'cta1_description' => 'Join our professional community and discover top mindfulness practices for lasting inner peace.',
            'cta1_btn_text' => 'Join Us Now',
            'cta1_btn_link' => '#contact',

            // CTA 2
            'cta2_subtitle' => 'Take the First Step',
            'cta2_title' => 'Your Best Self Awaits Within',
            'cta2_description' => 'Whether you are a beginner or experienced practitioner, our top professional programs are designed to meet you where you are.',
            'cta2_btn_text' => 'Start Your Journey',
            'cta2_btn_link' => '#contact',
        ];

        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = setting_value");
        foreach ($settings as $key => $value) {
            $stmt->execute([$key, $value]);
        }

        // Seed services
        $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM services");
        if ($stmt->fetch()['cnt'] == 0) {
            $services = [
                ['Guided Meditation', 'Experience the best professional guided meditation sessions that lead you deep within, helping you find clarity, calm, and a profound connection with your true self.', 'fas fa-brain', 'Best Seller', 'All Levels', '60 Min', 1],
                ['Mindfulness Training', 'Develop top mindfulness skills with our professional training programs. Learn to be present, aware, and fully engaged in every moment of your life.', 'fas fa-eye', 'Popular', 'Beginner', '45 Min', 2],
                ['Spiritual Counselling', 'Our best professional spiritual counsellors provide personalised guidance to help you navigate life\'s challenges, uncover purpose, and achieve spiritual clarity.', 'fas fa-comments', 'Top Rated', 'All Levels', '50 Min', 3],
                ['Holistic Therapy', 'Experience the best holistic therapy combining top mind-body healing techniques that address your complete well-being — physical, emotional, and spiritual.', 'fas fa-hand-holding-medical', 'Premium', 'Intermediate', '75 Min', 4],
                ['Yoga & Wellness', 'Join our top professional yoga and wellness sessions designed to harmonise your body and mind, promoting the best physical strength and inner tranquillity.', 'fas fa-child-reaching', 'Trending', 'All Levels', '60 Min', 5],
                ['Inner Peace Workshops', 'Attend our best professional workshops focused on cultivating lasting inner peace. Learn top proven techniques for stress relief, emotional balance, and self-awareness.', 'fas fa-users', 'New', 'Beginner', '90 Min', 6],
            ];
            $insert = $pdo->prepare("INSERT INTO services (title, description, icon, badge, level, duration, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
            foreach ($services as $s) {
                $insert->execute($s);
            }
        }

        // Seed stats
        $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM stats");
        if ($stmt->fetch()['cnt'] == 0) {
            $stats = [
                ['fas fa-users', 500, '+', 'Happy Members', 1],
                ['fas fa-award', 15, '+', 'Certified Instructors', 2],
                ['fas fa-calendar-check', 2000, '+', 'Sessions Completed', 3],
                ['fas fa-star', 98, '%', 'Satisfaction Rate', 4],
            ];
            $insert = $pdo->prepare("INSERT INTO stats (icon, number, suffix, label, sort_order) VALUES (?, ?, ?, ?, ?)");
            foreach ($stats as $s) {
                $insert->execute($s);
            }
        }

        // Seed why features
        $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM why_features");
        if ($stmt->fetch()['cnt'] == 0) {
            $features = [
                ['fas fa-heart-pulse', 'Healthy Body', 'Achieve the best physical vitality through our top professional yoga and wellness programs that strengthen, rejuvenate, and energise your entire being.', 1],
                ['fas fa-brain', 'Calm Mind', 'Experience the best mental clarity and profound calm through our top professional meditation and mindfulness techniques practised daily.', 2],
                ['fas fa-sun', 'Spiritual Awakening', 'Awaken your spirit with the best professional guidance. Our top spiritual growth programs lead you to deeper self-understanding and higher consciousness.', 3],
                ['fas fa-people-arrows', 'Better Relations', 'Build the best meaningful connections. Our top programs help you cultivate compassion, empathy, and professional communication for harmonious relationships.', 4],
            ];
            $insert = $pdo->prepare("INSERT INTO why_features (icon, title, description, sort_order) VALUES (?, ?, ?, ?)");
            foreach ($features as $f) {
                $insert->execute($f);
            }
        }

        // Seed testimonials
        $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM testimonials");
        if ($stmt->fetch()['cnt'] == 0) {
            $testimonials = [
                ['Sarah Mitchell', 'Yoga Practitioner', 'Dive Into Yourself is the best meditation centre I have ever been to. The professional instructors truly helped me find inner peace and clarity that I had been searching for years. Absolutely top-notch experience.', 5, 1],
                ['Raj Patel', 'Mindfulness Student', 'The best holistic therapy sessions here are truly transformative. The professional team creates a safe and nurturing space. I highly recommend this top wellness centre to anyone seeking genuine spiritual growth.', 5, 2],
                ['Emma Thompson', 'Wellness Enthusiast', 'I joined the best inner peace workshop and it completely changed my perspective on life. The top professional guidance helped me discover a sense of calm and purpose I never knew existed within me.', 5, 3],
            ];
            $insert = $pdo->prepare("INSERT INTO testimonials (name, designation, content, rating, sort_order) VALUES (?, ?, ?, ?, ?)");
            foreach ($testimonials as $t) {
                $insert->execute($t);
            }
        }

        // Seed pages
        $pages = [
            ['home', 'Best Meditation & Mindfulness Centre', 'Dive Into Yourself is the best professional meditation and mindfulness centre in Truganina, Victoria.', 'best meditation, professional mindfulness, top spiritual growth, inner peace, holistic wellness'],
            ['about', 'About Us - Best Meditation Centre', 'Learn about Dive Into Yourself, the best professional meditation centre in Truganina Victoria.', 'about us, best meditation centre, professional instructors, top wellness'],
            ['services', 'Our Professional Services', 'Explore our best professional meditation, mindfulness, yoga and holistic wellness services.', 'best meditation services, professional yoga, top mindfulness training'],
            ['gallery', 'Gallery', 'View photos and moments from Dive Into Yourself meditation and wellness programs.', 'gallery, meditation photos, wellness centre'],
            ['blogs', 'Blogs', 'Read meditation, mindfulness, and wellness articles from Dive Into Yourself.', 'blogs, meditation articles, mindfulness tips, wellness'],
            ['contact', 'Contact Us', 'Contact Dive Into Yourself meditation centre in Truganina Victoria 3029.', 'contact, meditation centre Truganina, phone, address'],
        ];
        $insert = $pdo->prepare("INSERT INTO pages (page_slug, page_title, meta_description, meta_keywords) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE page_title = page_title");
        foreach ($pages as $p) {
            $insert->execute($p);
        }

        $success = true;

    } catch (PDOException $e) {
        $errors[] = 'Database error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install - Dive Into Yourself</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #6f4894 0%, #0a9dae 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            max-width: 560px;
            width: 100%;
            padding: 48px 40px;
            text-align: center;
        }
        .logo-text {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #6f4894, #0a9dae);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }
        .tagline {
            color: #666;
            font-size: 14px;
            margin-bottom: 32px;
        }
        h1 {
            font-size: 22px;
            color: #333;
            margin-bottom: 12px;
        }
        p.desc {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 28px;
        }
        .btn {
            display: inline-block;
            padding: 14px 36px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #6f4894, #0a9dae);
            color: #fff;
        }
        .btn-outline {
            background: transparent;
            color: #6f4894;
            border: 2px solid #6f4894;
            padding: 12px 28px;
            margin: 0 6px;
        }
        .btn-outline:hover { background: #6f4894; color: #fff; }
        .btn-outline.teal { border-color: #0a9dae; color: #0a9dae; }
        .btn-outline.teal:hover { background: #0a9dae; color: #fff; }
        .success-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #6f4894, #0a9dae);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #fff;
            font-size: 28px;
        }
        .error {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: left;
        }
        .links { margin-top: 24px; }
        .info-box {
            background: #f8f6fb;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
            font-size: 13px;
            color: #555;
            text-align: left;
        }
        .info-box strong { color: #6f4894; }
        .checklist {
            text-align: left;
            margin: 20px 0;
            list-style: none;
        }
        .checklist li {
            padding: 8px 0;
            font-size: 14px;
            color: #555;
            border-bottom: 1px solid #f0f0f0;
        }
        .checklist li:last-child { border-bottom: none; }
        .checklist li::before {
            content: '\2713';
            display: inline-block;
            width: 22px;
            height: 22px;
            background: linear-gradient(135deg, #6f4894, #0a9dae);
            color: #fff;
            border-radius: 50%;
            text-align: center;
            line-height: 22px;
            font-size: 12px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-text">Dive Into Yourself</div>
        <div class="tagline">Harmonising Your Inner Self &amp; Your Body</div>

        <?php if ($success): ?>
            <div class="success-icon">&#10003;</div>
            <h1>Installation Complete</h1>
            <p class="desc">The database has been created and seeded with default data. Your site is ready to go.</p>

            <div class="info-box">
                <strong>Admin Login:</strong><br>
                Username: <code>admin</code><br>
                Password: <code>admin123</code><br><br>
                Please change the default password after your first login.
            </div>

            <ul class="checklist">
                <li>Database <strong>dive_db</strong> created</li>
                <li>All tables created successfully</li>
                <li>Default admin account created</li>
                <li>Site settings seeded</li>
                <li>Sample services, stats, testimonials added</li>
                <li>Gallery and blog tables ready</li>
                <li>Page meta data configured</li>
            </ul>

            <div class="links">
                <a href="/Github/Dive/admin/" class="btn btn-outline">Admin Panel</a>
                <a href="/Github/Dive/" class="btn btn-outline teal">View Site</a>
            </div>

        <?php elseif (!empty($errors)): ?>
            <?php foreach ($errors as $err): ?>
                <div class="error"><?= htmlspecialchars($err) ?></div>
            <?php endforeach; ?>
            <form method="post">
                <button type="submit" class="btn btn-primary">Try Again</button>
            </form>

        <?php else: ?>
            <h1>Database Installer</h1>
            <p class="desc">This will set up the database, create all required tables, and seed default content for your site.</p>

            <ul class="checklist">
                <li>Create <strong>dive_db</strong> database</li>
                <li>Create tables (admins, settings, pages, services, testimonials, stats, why_features, gallery, blogs)</li>
                <li>Seed default admin account</li>
                <li>Seed site settings &amp; content</li>
                <li>Seed sample services, stats, testimonials</li>
            </ul>

            <form method="post" style="margin-top: 28px;">
                <button type="submit" class="btn btn-primary">Install Now</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
