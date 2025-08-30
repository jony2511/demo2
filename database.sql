CREATE DATABASE IF NOT EXISTS portfolio_db;
USE portfolio_db;

-- Users table with additional fields
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(100),
    bio TEXT,
    profile_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP
);

-- Projects table with enhanced fields
CREATE TABLE IF NOT EXISTS projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    technologies VARCHAR(255),
    github_link VARCHAR(255),
    live_demo_link VARCHAR(255),
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Skills table with categories
CREATE TABLE IF NOT EXISTS skills (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    percentage INT NOT NULL,
    category VARCHAR(50) NOT NULL,
    icon_class VARCHAR(50)
);

-- Messages table for contact form
CREATE TABLE IF NOT EXISTS messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DEFAULT FALSE
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, email, full_name) 
VALUES ('admin', '$2y$10$8FPr0UguJsXMo1lL2zqpFeh2.XGrEE.pXcQVG4rWTyaQRUXDGXA.e', 'admin@example.com', 'Administrator')
ON DUPLICATE KEY UPDATE id=id;

-- Insert sample projects
INSERT INTO projects (title, description, technologies, github_link, featured) VALUES
('E-commerce Website', 'A full-stack e-commerce platform with shopping cart and payment integration', 'PHP, MySQL, JavaScript, Bootstrap', 'https://github.com/example/ecommerce', 1),
('Task Manager', 'A dynamic task management application with drag-and-drop interface', 'React, Node.js, MongoDB', 'https://github.com/example/taskmanager', 1)
ON DUPLICATE KEY UPDATE id=id;

-- Insert sample skills
INSERT INTO skills (name, percentage, category, icon_class) VALUES
('PHP', 85, 'Backend', 'fab fa-php'),
('JavaScript', 90, 'Frontend', 'fab fa-js'),
('HTML5', 95, 'Frontend', 'fab fa-html5'),
('CSS3', 90, 'Frontend', 'fab fa-css3-alt'),
('MySQL', 80, 'Database', 'fas fa-database'),
('React', 75, 'Frontend', 'fab fa-react')
ON DUPLICATE KEY UPDATE id=id;
