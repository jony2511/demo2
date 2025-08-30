// Navbar scroll handling
const navbar = document.querySelector('.navbar');
let lastScrollY = window.scrollY;

function updateNavbar() {
    const isDarkMode = document.body.classList.contains('dark-mode');
    
    // Show/hide navbar based on scroll direction
    if (window.scrollY > lastScrollY) {
        navbar.style.transform = 'translateY(-100%)';
    } else {
        navbar.style.transform = 'translateY(0)';
    }
    
    // Add shadow and adjust background opacity based on scroll position
    if (window.scrollY > 50) {
        if (isDarkMode) {
            navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.2)';
        } else {
            navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
        }
    } else {
        navbar.style.boxShadow = 'none';
    }
    
    lastScrollY = window.scrollY;
}

// Add scroll event listener
window.addEventListener('scroll', updateNavbar);

// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Form submission handling
document.getElementById('contact-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = new FormData(this);
    
    // Here you would typically send the data to a server
    // For now, just show a success message
    alert('Message sent successfully!');
    this.reset();
});

// Animate skill bars on scroll
const skillBars = document.querySelectorAll('.skill-progress');
const animateSkills = () => {
    skillBars.forEach(bar => {
        const rect = bar.getBoundingClientRect();
        if (rect.top < window.innerHeight) {
            bar.style.width = bar.parentElement.dataset.progress + '%';
        }
    });
};

window.addEventListener('scroll', animateSkills);

// Theme toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const icon = themeToggle.querySelector('i');
    const body = document.body;

    // Function to set theme
    function setTheme(isDark) {
        if (isDark) {
            body.classList.add('dark-mode');
            icon.className = 'fas fa-sun';
        } else {
            body.classList.remove('dark-mode');
            icon.className = 'fas fa-moon';
        }
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        
        // Force navbar update
        updateNavbar();
    }

    // Set initial theme from localStorage
    const savedTheme = localStorage.getItem('theme');
    setTheme(savedTheme === 'dark');

    // Toggle theme on button click
    themeToggle.addEventListener('click', () => {
        const isDark = body.classList.contains('dark-mode');
        setTheme(!isDark);
    });
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            const newTheme = e.matches ? 'dark' : 'light';
            setTheme(newTheme);
        });
    }
});
