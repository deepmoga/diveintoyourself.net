/* ============================================
   DIVE INTO YOURSELF - Main JavaScript
   ============================================ */

document.addEventListener('DOMContentLoaded', function() {

    // --- Preloader ---
    const preloader = document.querySelector('.preloader');
    if (preloader) {
        window.addEventListener('load', function() {
            setTimeout(function() {
                preloader.classList.add('hidden');
            }, 500);
        });
        setTimeout(function() {
            preloader.classList.add('hidden');
        }, 3000);
    }

    // --- Sticky Header ---
    const header = document.querySelector('.header');
    function handleScroll() {
        if (window.scrollY > 80) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }
    window.addEventListener('scroll', handleScroll);
    handleScroll();

    // --- Mobile Menu Toggle ---
    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    const navLinks = document.querySelectorAll('.nav-menu a');

    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            navMenu.classList.toggle('active');
            document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
        });
    }

    navLinks.forEach(function(link) {
        if (!link.closest('.nav-dropdown') || link.closest('.dropdown-menu')) {
            link.addEventListener('click', function() {
                menuToggle.classList.remove('active');
                navMenu.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
    });

    // --- Mobile Dropdown Toggle ---
    var dropdowns = document.querySelectorAll('.nav-dropdown');
    dropdowns.forEach(function(dropdown) {
        var trigger = dropdown.querySelector(':scope > a');
        if (trigger) {
            trigger.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    dropdown.classList.toggle('open');
                }
            });
        }
    });

    // --- Smooth Scrolling ---
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                var offset = header.offsetHeight + 20;
                var targetPosition = target.getBoundingClientRect().top + window.pageYOffset - offset;
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // --- Scroll Animations ---
    var animatedElements = document.querySelectorAll('.fade-in, .fade-in-left, .fade-in-right, .scale-in');

    var observerOptions = {
        root: null,
        rootMargin: '0px 0px -60px 0px',
        threshold: 0.15
    };

    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    animatedElements.forEach(function(el) {
        observer.observe(el);
    });

    // --- Counter Animation ---
    var counters = document.querySelectorAll('.counter');
    var counterObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(function(counter) {
        counterObserver.observe(counter);
    });

    function animateCounter(el) {
        var target = parseInt(el.getAttribute('data-target'));
        var suffix = el.getAttribute('data-suffix') || '';
        var duration = 2000;
        var start = 0;
        var startTime = null;

        function updateCounter(timestamp) {
            if (!startTime) startTime = timestamp;
            var progress = Math.min((timestamp - startTime) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            var current = Math.floor(eased * target);
            el.textContent = current + suffix;
            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                el.textContent = target + suffix;
            }
        }
        requestAnimationFrame(updateCounter);
    }

    // --- Scroll to Top ---
    var scrollTopBtn = document.querySelector('.scroll-top');
    if (scrollTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 500) {
                scrollTopBtn.classList.add('visible');
            } else {
                scrollTopBtn.classList.remove('visible');
            }
        });

        scrollTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // --- Active Nav Link on Scroll ---
    var sections = document.querySelectorAll('section[id]');
    window.addEventListener('scroll', function() {
        var scrollY = window.pageYOffset;
        sections.forEach(function(section) {
            var sectionHeight = section.offsetHeight;
            var sectionTop = section.offsetTop - 150;
            var sectionId = section.getAttribute('id');
            var navLink = document.querySelector('.nav-menu a[href="#' + sectionId + '"]');
            if (navLink) {
                if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                    navLink.classList.add('active');
                } else {
                    navLink.classList.remove('active');
                }
            }
        });
    });

    // --- Hero Particles ---
    var particleContainer = document.querySelector('.hero-particles');
    if (particleContainer) {
        for (var i = 0; i < 20; i++) {
            var particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDuration = (Math.random() * 8 + 6) + 's';
            particle.style.animationDelay = (Math.random() * 5) + 's';
            particle.style.width = (Math.random() * 4 + 3) + 'px';
            particle.style.height = particle.style.width;
            particleContainer.appendChild(particle);
        }
    }

    // --- Testimonial Slider (simple auto-slide for mobile) ---
    var testimonialCards = document.querySelectorAll('.testimonial-card');
    if (window.innerWidth <= 768 && testimonialCards.length > 1) {
        var currentTestimonial = 0;
        setInterval(function() {
            testimonialCards[currentTestimonial].style.display = 'none';
            currentTestimonial = (currentTestimonial + 1) % testimonialCards.length;
            testimonialCards[currentTestimonial].style.display = 'block';
        }, 5000);
    }

});
