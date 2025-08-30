<?php
require_once 'config.php';

// Initialize variables
$projects = [];
$skills = [];
$skillsByCategory = [];

try {
    // Check if we have any data
    $stmt = $db->query("SELECT COUNT(*) FROM projects");
    $projectCount = $stmt->fetchColumn();
    
    $stmt = $db->query("SELECT COUNT(*) FROM skills");
    $skillCount = $stmt->fetchColumn();
    
    // If no data exists, initialize it
    if ($projectCount == 0 || $skillCount == 0) {
        require_once 'init_data.php';
    }

    // Fetch projects
    $stmt = $db->prepare("SELECT * FROM projects ORDER BY created_at DESC");
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch skills
    $stmt = $db->prepare("SELECT * FROM skills ORDER BY category, percentage DESC");
    $stmt->execute();
    $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Group skills by category
    foreach ($skills as $skill) {
        $skillsByCategory[$skill['category']][] = $skill;
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    set_flash_message('error', 'An error occurred while loading the content.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tariful Islam Jony - Computer Science Student & Developer Portfolio">
    <meta name="keywords" content="portfolio, web development, programming, KUET, computer science">
    <meta name="author" content="Tariful Islam Jony">
    <title>Tariful Islam Jony - Portfolio</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style.dark.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="js/style.js" defer></script>
    <script src="js/theme.js" defer></script>
    <style>
        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .loading-indicator {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .category-title {
            text-align: center;
            margin: 2rem 0;
            color: var(--primary-color);
            font-size: 1.5rem;
        }
        .skill-progress-container {
            width: 100%;
            height: 10px;
            background: #f0f0f0;
            border-radius: 5px;
            margin-top: 0.5rem;
            overflow: hidden;
        }
        .skill-progress-bar {
            height: 100%;
            background: var(--secondary-color);
            width: 0;
            transition: width 1s ease-in-out;
        }
    </style>
</head>
<body>
    <!-- Loading Indicator -->
    <div class="loading-indicator">
        <div class="loading-spinner"></div>
        <p>Sending message...</p>
    </div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">Tariful Islam Jony</div>
            <ul class="nav-menu">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#skills">Skills</a></li>
                <li><a href="#projects">Projects</a></li>
                <li><a href="#contact">Contact</a></li>
                <!-- <?php if (is_logged_in()): ?>
                    <li><a href="admin.php">Admin</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?> -->
                <li>
                    <button id="theme-toggle" title="Toggle dark mode">
                        <i class="fas fa-moon"></i>
                    </button>
                </li>
            </ul>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-container">
            <div class="hero-content" data-aos="fade-right">
                <h1 class="typing-text">Hi, I'm Tariful Islam Jony</h1>
                <p class="subtitle">Computer Science Student & Developer</p>
                <p>Currently pursuing CSE at KUET with a passion for web development, programming, and innovative technology solutions.</p>
                <div class="cta-buttons">
                    <a href="#projects" class="btn btn-primary">
                        <i class="fas fa-code"></i>
                        View My Work
                    </a>
                    <a href="#contact" class="btn btn-outline">
                        <i class="fas fa-envelope"></i>
                        Get In Touch
                    </a>
                </div>
            </div>
            <div class="hero-image" data-aos="fade-left">
                <img src="images/jony.png" alt="Tariful Islam Jony" class="profile-img">
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section about">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">About Me</h2>
            <div class="about-content">
                <div class="about-text" data-aos="fade-up">
                    <p>I'm Tariful Islam Jony, a passionate Computer Science student at Khulna University of Engineering & Technology (KUET) in Bangladesh. My journey in technology started with curiosity and has evolved into a deep commitment to creating innovative solutions.</p>
                    <br>
                    <p>I specialize in full-stack web development with strong expertise in PHP, Laravel, and modern JavaScript frameworks. From crafting responsive user interfaces to building robust server-side applications with database management, I enjoy every aspect of bringing ideas to life through code.</p>
                </div>

                <div class="about-stats" data-aos="fade-up">
                    <div class="stat-box" data-aos="zoom-in" data-aos-delay="100">
                        <div class="stat-number">0+</div>
                        <div class="stat-label">Technologies</div>
                    </div>
                    <div class="stat-box" data-aos="zoom-in" data-aos-delay="200">
                        <div class="stat-number">0+</div>
                        <div class="stat-label">Projects</div>
                    </div>
                    <div class="stat-box" data-aos="zoom-in" data-aos-delay="300">
                        <div class="stat-number">KUET</div>
                        <div class="stat-label">University</div>
                    </div>
                    <div class="stat-box" data-aos="zoom-in" data-aos-delay="400">
                        <div class="stat-number">CSE</div>
                        <div class="stat-label">Major</div>
                    </div>
                </div>
                        </ul>
                        <br>
                        <p>My academic journey at KUET has equipped me with strong theoretical foundations, while my hands-on projects have given me practical experience in modern web technologies like PHP, Laravel, JavaScript, and more. I'm particularly interested in educational technology, as demonstrated by my Coaching Management System project.</p>
                        <br>
                        <p>Beyond coding, I'm passionate about:</p>
                        <ul class="about-interests">
                            <li><i class="fas fa-graduation-cap"></i> Continuous learning and growth</li>
                            <li><i class="fas fa-users"></i> Collaborating with fellow developers</li>
                            <li><i class="fas fa-robot"></i> Experimenting with hardware projects</li>
                            <li><i class="fas fa-code-branch"></i> Contributing to open-source</li>
                        </ul>
                    </div>
                </div>
                <div class="about-stats" data-aos="fade-left">
                    <div class="stat-item" data-aos="zoom-in" data-aos-delay="100">
                        <div class="stat-number"><?php echo count($skills); ?>+</div>
                        <div class="stat-label">Technical Skills</div>
                        <div class="stat-icon"><i class="fas fa-code"></i></div>
                    </div>
                    <div class="stat-item" data-aos="zoom-in" data-aos-delay="200">
                        <div class="stat-number"><?php echo count($projects); ?>+</div>
                        <div class="stat-label">Projects Completed</div>
                        <div class="stat-icon"><i class="fas fa-project-diagram"></i></div>
                    </div>
                    <div class="stat-item" data-aos="zoom-in" data-aos-delay="300">
                        <div class="stat-number">KUET</div>
                        <div class="stat-label">Premier Engineering University</div>
                        <div class="stat-icon"><i class="fas fa-university"></i></div>
                    </div>
                    <div class="stat-item" data-aos="zoom-in" data-aos-delay="400">
                        <div class="stat-number">CSE</div>
                        <div class="stat-label">Computer Science & Engineering</div>
                        <div class="stat-icon"><i class="fas fa-laptop-code"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="skills" class="section">
        <div class="container">
            <!-- Education Section -->
            <h2 class="section-title" data-aos="fade-up">Education & Certifications</h2>
            <div class="skills-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
                <!-- University Education -->
                <div class="skill-item" data-aos="fade-up">
                    <div class="skill-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="skill-name">Undergraduate</div>
                    <p class="skill-description">Computer Science & Engineering</p>
                    <p class="skill-subtitle">Khulna University of Engineering & Technology (KUET)</p>
                </div>
                
                <!-- SSC -->
                <div class="skill-item" data-aos="fade-up">
                    <div class="skill-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="skill-name">SSC</div>
                    <p class="skill-description">Secondary School Certificate</p>
                </div>

                <!-- HSC -->
                <div class="skill-item" data-aos="fade-up">
                    <div class="skill-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="skill-name">HSC</div>
                    <p class="skill-description">Higher Secondary Certificate</p>
                </div>

                <!-- Competitions -->
                <div class="skill-item" data-aos="fade-up">
                    <div class="skill-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="skill-name">Competition Certificates</div>
                    <p class="skill-description">Various Programming & Technical Competitions</p>
                </div>
            </div>

            <!-- Skills Section -->
            <h2 class="section-title" data-aos="fade-up" style="margin-top: 4rem;">Technical Skills</h2>
            <?php foreach ($skillsByCategory as $category => $categorySkills): ?>
                <h3 class="category-title" data-aos="fade-up"><?php echo htmlspecialchars($category); ?></h3>
                <div class="skills-grid">
                    <?php foreach ($categorySkills as $skill): ?>
                        <div class="skill-item" data-aos="fade-up">
                            <div class="skill-icon">
                                <i class="<?php echo htmlspecialchars($skill['icon_class']); ?>"></i>
                            </div>
                            <div class="skill-name"><?php echo htmlspecialchars($skill['name']); ?></div>
                            <div class="skill-progress-container">
                                <div class="skill-progress-bar" data-progress="<?php echo htmlspecialchars($skill['percentage']); ?>"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="section projects">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Featured Projects</h2>
            <div class="projects-grid">
                <?php foreach ($projects as $project): ?>
                    <div class="project-card" data-aos="fade-up">
                        <?php if ($project['image_url']): ?>
                            <div class="project-image">
                                <img src="<?php echo htmlspecialchars($project['image_url']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                            </div>
                        <?php endif; ?>
                        <div class="project-header">
                            <h3 class="project-title"><?php echo htmlspecialchars($project['title']); ?></h3>
                        </div>
                        <div class="project-body">
                            <p class="project-description"><?php echo htmlspecialchars($project['description']); ?></p>
                            <div class="project-technologies">
                                <?php
                                $technologies = explode(',', $project['technologies']);
                                foreach ($technologies as $tech):
                                    $tech = trim($tech);
                                    if (!empty($tech)):
                                ?>
                                    <span class="tech-tag"><?php echo htmlspecialchars($tech); ?></span>
                                <?php
                                    endif;
                                endforeach;
                                ?>
                            </div>
                            <div class="project-links">
                                <?php if ($project['github_link']): ?>
                                    <a href="<?php echo htmlspecialchars($project['github_link']); ?>" target="_blank" class="project-link">
                                        <i class="fab fa-github"></i>
                                        View Code
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Get In Touch</h2>
            <div class="contact-container">
                <div class="contact-info" data-aos="fade-right">
                    <h3>Contact Information</h3>
                    
                    <div class="contact-item">
                        <i class="fas fa-envelope contact-icon"></i>
                        <div>
                            <h4>Email</h4>
                            <p>mtijony2@gmail.com</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <i class="fas fa-phone contact-icon"></i>
                        <div>
                            <h4>Phone</h4>
                            <p>013*******</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt contact-icon"></i>
                        <div>
                            <h4>Location</h4>
                            <p>Bangladesh</p>
                        </div>
                    </div>

                    <div class="social-links">
                        <a href="https://github.com/jony2511" target="_blank" class="social-link">
                            <i class="fab fa-github"></i>
                        </a>
                        <a href="https://www.linkedin.com/in/md-tariful-islam-jony-582a20352/" target="_blank" class="social-link">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="https://www.facebook.com/mdtarifulislam.jony/" target="_blank" class="social-link">
                            <i class="fab fa-facebook"></i>
                        </a>
                    </div>
                </div>

                <form id="contact-form" class="contact-form" data-aos="fade-left">
                    <div id="form-messages"></div>
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Your Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Your Message</label>
                        <textarea id="message" name="message" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-paper-plane"></i>
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Tariful Islam Jony. All rights reserved.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        // Create particles background
        function createParticles() {
            const hero = document.querySelector('.hero');
            const particleCount = 50;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.style.position = 'absolute';
                particle.style.width = Math.random() * 4 + 2 + 'px';
                particle.style.height = particle.style.width;
                particle.style.background = 'rgba(255, 255, 255, 0.1)';
                particle.style.borderRadius = '50%';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.pointerEvents = 'none';
                particle.style.animation = `float ${Math.random() * 3 + 2}s ease-in-out infinite`;
                particle.style.animationDelay = Math.random() * 2 + 's';
                hero.appendChild(particle);
            }
        }

        // Initialize particles
        createParticles();

        // Add scroll progress indicator
        const scrollProgress = document.createElement('div');
        scrollProgress.style.position = 'fixed';
        scrollProgress.style.top = '0';
        scrollProgress.style.left = '0';
        scrollProgress.style.width = '0%';
        scrollProgress.style.height = '3px';
        scrollProgress.style.background = 'linear-gradient(90deg, #3498db, #e74c3c)';
        scrollProgress.style.zIndex = '9999';
        scrollProgress.style.transition = 'width 0.3s ease';
        document.body.appendChild(scrollProgress);

        window.addEventListener('scroll', () => {
            const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrolled = (window.scrollY / scrollHeight) * 100;
            scrollProgress.style.width = scrolled + '%';
        });

        // Add click ripple effect
        document.addEventListener('click', (e) => {
            const ripple = document.createElement('div');
            ripple.style.position = 'fixed';
            ripple.style.left = e.clientX - 10 + 'px';
            ripple.style.top = e.clientY - 10 + 'px';
            ripple.style.width = '20px';
            ripple.style.height = '20px';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(52, 152, 219, 0.3)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s ease-out';
            ripple.style.pointerEvents = 'none';
            ripple.style.zIndex = '9999';
            
            document.body.appendChild(ripple);
            
            setTimeout(() => {
                document.body.removeChild(ripple);
            }, 600);
        });

        // Add Konami code easter egg
        let konamiCode = [];
        const correctCode = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65]; // Up Up Down Down Left Right Left Right B A

        document.addEventListener('keydown', (e) => {
            konamiCode.push(e.keyCode);
            if (konamiCode.length > correctCode.length) {
                konamiCode.shift();
            }
            
            if (konamiCode.length === correctCode.length && 
                konamiCode.every((val, index) => val === correctCode[index])) {
                document.body.style.animation = 'rainbow 2s ease-in-out';
                setTimeout(() => {
                    alert('🎉 Konami Code activated! You found the secret! 🎉');
                    document.body.style.animation = '';
                }, 1000);
            }
        });

        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true
        });

        // Mobile menu toggle
        const hamburger = document.querySelector('.hamburger');
        const navMenu = document.querySelector('.nav-menu');

        hamburger.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            hamburger.classList.toggle('active');
        });

        // Close mobile menu when clicking on a link
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
                hamburger.classList.remove('active');
            });
        });

        // Contact form handling with AJAX
        document.getElementById('contact-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formMessages = document.getElementById('form-messages');
            const form = this;
            
            // Get form values for validation
            const name = form.querySelector('[name="name"]').value.trim();
            const email = form.querySelector('[name="email"]').value.trim();
            const message = form.querySelector('[name="message"]').value.trim();
            
            // Clear previous messages
            formMessages.innerHTML = '';
            
            // Client-side validation
            if (!name || !email || !message) {
                formMessages.innerHTML = '<div class="alert alert-error">Please fill in all fields.</div>';
                return;
            }

            try {
                formMessages.innerHTML = '<div class="alert alert-info">Sending message...</div>';

                const formData = new FormData(form);
                formData.append('action', 'contact');

                // Log form data for debugging
                console.log('Sending form data:', {
                    name: formData.get('name'),
                    email: formData.get('email'),
                    message: formData.get('message'),
                    action: formData.get('action')
                });

                const response = await fetch('process.php', {
                    method: 'POST',
                    body: formData
                });

                console.log('Response status:', response.status);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                let data;
                try {
                    data = await response.json();
                } catch (jsonError) {
                    // If JSON parsing fails, show the raw response for debugging
                    const rawText = await response.text();
                    console.error('Raw server response:', rawText);
                    formMessages.innerHTML = `<div class="alert alert-error">Server error: <pre>${rawText}</pre></div>`;
                    return;
                }
                console.log('Server response:', data);

                const alertClass = data.status === 'success' ? 'success' : 'error';
                formMessages.innerHTML = `<div class="alert alert-${alertClass}">${data.message}</div>`;

                if (data.status === 'success') {
                    form.reset();
                    setTimeout(() => {
                        formMessages.innerHTML = '';
                    }, 5000);
                }
            } catch (error) {
                console.error('Form submission error:', error);
                formMessages.innerHTML = `<div class="alert alert-error">An error occurred. Please try again later.<br><pre>${error}</pre></div>`;
            }
        });

        // Animate skill bars when they come into view
        const animateSkillBars = () => {
            document.querySelectorAll('.skill-progress-bar').forEach(bar => {
                const progress = bar.getAttribute('data-progress');
                bar.style.width = progress + '%';
            });
        };

        // Initialize Intersection Observer for skill bars
        const skillObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateSkillBars();
                    skillObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        document.querySelectorAll('.skills-grid').forEach(grid => {
            skillObserver.observe(grid);
        });

        // Typing animation for hero section
        const typeWriter = (element, text, speed = 100) => {
            let i = 0;
            element.innerHTML = '';
            function typing() {
                if (i < text.length) {
                    element.innerHTML += text.charAt(i);
                    i++;
                    setTimeout(typing, speed);
                }
            }
            typing();
        };

        // Initialize typing animation after page load
        window.addEventListener('load', () => {
            const heroTitle = document.querySelector('.typing-text');
            const originalText = heroTitle.textContent;
            typeWriter(heroTitle, originalText, 150);
        });

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar background on scroll
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 100) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                navbar.style.boxShadow = 'none';
            }
        });

        // Project card hover effects
        document.querySelectorAll('.project-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>
