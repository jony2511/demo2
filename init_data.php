<?php
require_once 'config.php';

try {
    // Clear existing data
    $pdo->exec("TRUNCATE TABLE projects");
    $pdo->exec("TRUNCATE TABLE skills");

    // Insert projects
    $projects = [
        [
            'title' => 'Coaching Management System',
            'description' => 'Our Coaching Management App is your ultimate companion for academic success. Designed to streamline the learning process, our app integrates essential tools to help students, teachers, and institutions stay organized, efficient, and focused on achieving their goals.',
            'technologies' => 'PHP,MySQL,Laravel,Bootstrap',
            'github_link' => 'https://github.com/jony2511/Coaching-Management'
        ],
        [
            'title' => 'My First Project',
            'description' => 'This represents my journey into programming and development. My first project holds special significance as it marks the beginning of my coding adventure and showcases my initial steps in software development.',
            'technologies' => 'Programming',
            'github_link' => 'https://github.com/jony2511/FirstProject'
        ],
        [
            'title' => 'Numerical Methods Implementation',
            'description' => 'A collection of numerical methods implementations for computational mathematics. This project demonstrates various algorithms and mathematical concepts essential for engineering and scientific computations.',
            'technologies' => 'C++,Algorithms,Mathematics',
            'github_link' => 'https://github.com/jony2511/CSE-2208'
        ]
    ];

    $stmt = $pdo->prepare("INSERT INTO projects (title, description, technologies, github_link) VALUES (?, ?, ?, ?)");
    foreach ($projects as $project) {
        $stmt->execute([
            $project['title'],
            $project['description'],
            $project['technologies'],
            $project['github_link']
        ]);
    }

    // Insert skills
    $skills = [
        ['C', 85, 'Programming Languages', 'fas fa-code'],
        ['C++', 90, 'Programming Languages', 'fas fa-code'],
        ['Java', 80, 'Programming Languages', 'fab fa-java'],
        ['JavaScript', 85, 'Web Development', 'fab fa-js'],
        ['HTML', 95, 'Web Development', 'fab fa-html5'],
        ['CSS', 90, 'Web Development', 'fab fa-css3-alt'],
        ['PHP', 85, 'Web Development', 'fab fa-php'],
        ['Laravel', 80, 'Frameworks', 'fab fa-laravel'],
        ['Arduino', 75, 'Hardware', 'fas fa-microchip']
    ];

    $stmt = $pdo->prepare("INSERT INTO skills (name, percentage, category, icon_class) VALUES (?, ?, ?, ?)");
    foreach ($skills as $skill) {
        $stmt->execute($skill);
    }

    echo "Data initialized successfully!";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
