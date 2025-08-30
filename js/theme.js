// Theme toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const icon = themeToggle.querySelector('i');
    const body = document.body;
    const navbar = document.querySelector('.navbar');

    // Function to set theme
    function setTheme(isDark) {
        if (isDark) {
            body.classList.add('dark-mode');
            icon.className = 'fas fa-sun';
            navbar.style.background = 'rgba(28, 28, 31, 0.97)';
            navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.2)';
        } else {
            body.classList.remove('dark-mode');
            icon.className = 'fas fa-moon';
            navbar.style.background = 'rgba(255, 255, 255, 0.98)';
            navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
        }
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    }

    // Set initial theme
    const isDark = localStorage.getItem('theme') === 'dark';
    setTheme(isDark);

    // Toggle theme on button click
    themeToggle.addEventListener('click', () => {
        const isDark = body.classList.contains('dark-mode');
        setTheme(!isDark);
        // Update navbar immediately
        if (window.scrollY > 50) {
            navbar.style.background = isDark ? 'rgba(255, 255, 255, 0.98)' : 'rgba(28, 28, 31, 0.97)';
            navbar.style.boxShadow = isDark 
                ? '0 4px 20px rgba(0, 0, 0, 0.1)' 
                : '0 4px 20px rgba(0, 0, 0, 0.2)';
        }
    });
});
