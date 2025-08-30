<?php
require_once 'config.php';

if (!is_logged_in()) {
    redirect('login.php');
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle skill operations
    if (isset($_POST['add_skill'])) {
        $name = $_POST['skill_name'];
        $percentage = $_POST['percentage'];
        $category = $_POST['category'];
        $icon_class = $_POST['icon_class'];

        $sql = "INSERT INTO skills (name, percentage, category, icon_class) VALUES (?, ?, ?, ?)";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([$name, $percentage, $category, $icon_class]);
            set_flash_message('Skill added successfully!', 'success');
        } catch (PDOException $e) {
            set_flash_message('Error adding skill: ' . $e->getMessage(), 'error');
        }
    } elseif (isset($_POST['edit_skill'])) {
        $id = $_POST['skill_id'];
        $name = $_POST['skill_name'];
        $percentage = $_POST['percentage'];
        $category = $_POST['category'];
        $icon_class = $_POST['icon_class'];

        $sql = "UPDATE skills SET name = ?, percentage = ?, category = ?, icon_class = ? WHERE id = ?";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([$name, $percentage, $category, $icon_class, $id]);
            set_flash_message('Skill updated successfully!', 'success');
        } catch (PDOException $e) {
            set_flash_message('Error updating skill: ' . $e->getMessage(), 'error');
        }
    } elseif (isset($_POST['delete_skill'])) {
        $id = $_POST['skill_id'];

        $sql = "DELETE FROM skills WHERE id = ?";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([$id]);
            set_flash_message('Skill deleted successfully!', 'success');
        } catch (PDOException $e) {
            set_flash_message('Error deleting skill: ' . $e->getMessage(), 'error');
        }
    }

    // Handle project operations
    elseif (isset($_POST['add_project'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $technologies = $_POST['technologies'];
        $github_link = $_POST['github_link'];
        $image_url = $_POST['image_url'];

        $sql = "INSERT INTO projects (title, description, technologies, github_link, image_url) VALUES (?, ?, ?, ?, ?)";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([$title, $description, $technologies, $github_link, $image_url]);
            set_flash_message('Project added successfully!', 'success');
        } catch (PDOException $e) {
            set_flash_message('Error adding project: ' . $e->getMessage(), 'error');
        }
    } elseif (isset($_POST['edit_project'])) {
        $id = $_POST['project_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $technologies = $_POST['technologies'];
        $github_link = $_POST['github_link'];
        $image_url = $_POST['image_url'];

        $sql = "UPDATE projects SET title = ?, description = ?, technologies = ?, github_link = ?, image_url = ? WHERE id = ?";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([$title, $description, $technologies, $github_link, $image_url, $id]);
            set_flash_message('Project updated successfully!', 'success');
        } catch (PDOException $e) {
            set_flash_message('Error updating project: ' . $e->getMessage(), 'error');
        }
    } elseif (isset($_POST['delete_project'])) {
        $id = $_POST['project_id'];

        $sql = "DELETE FROM projects WHERE id = ?";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([$id]);
            set_flash_message('Project deleted successfully!', 'success');
        } catch (PDOException $e) {
            set_flash_message('Error deleting project: ' . $e->getMessage(), 'error');
        }
    }

    // Handle message operations
    elseif (isset($_POST['delete_message'])) {
        $id = $_POST['message_id'];

        $sql = "DELETE FROM messages WHERE id = ?";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([$id]);
            set_flash_message('Message deleted successfully!', 'success');
        } catch (PDOException $e) {
            set_flash_message('Error deleting message: ' . $e->getMessage(), 'error');
        }
    }

    // Redirect to avoid form resubmission
    redirect('admin.php');
}

// Fetch skills
$sql = "SELECT * FROM skills ORDER BY category, name";
$stmt = $db->prepare($sql);
$stmt->execute();
$skills = $stmt->fetchAll();

// Fetch projects
$sql = "SELECT * FROM projects ORDER BY title";
$stmt = $db->prepare($sql);
$stmt->execute();
$projects = $stmt->fetchAll();

// Fetch messages
$sql = "SELECT * FROM messages ORDER BY created_at DESC";
$stmt = $db->prepare($sql);
$stmt->execute();
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .admin-section {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .flash-message {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        h1 {
            color: #333;
            margin-bottom: 2rem;
            border-bottom: 2px solid #eee;
            padding-bottom: 1rem;
        }

        .tab-container {
            margin-top: 2rem;
        }

        .tab-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            border-bottom: 2px solid #eee;
            padding-bottom: 1rem;
        }

        .tab-button {
            padding: 0.75rem 1.5rem;
            border: none;
            background: #f8f9fa;
            cursor: pointer;
            border-radius: 4px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .tab-button:hover {
            background: #e9ecef;
        }

        .tab-button.active {
            background: #007bff;
            color: white;
        }

        .tab-content {
            display: none;
            padding: 1rem 0;
        }

        .tab-content.active {
            display: block;
        }

        .admin-form {
            max-width: 600px;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #495057;
        }

        .form-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
        }

        .btn-admin {
            background: #007bff;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s ease;
        }

        .btn-admin:hover {
            background: #0056b3;
        }

        .skills-list, .projects-list, .messages-list {
            margin-top: 2rem;
        }

        .skill-item, .project-item, .message-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .skill-progress-container {
            width: 200px;
            height: 10px;
            background: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .skill-progress-bar {
            height: 100%;
            background: #007bff;
            border-radius: 5px;
        }

        .skills-category {
            margin-bottom: 2rem;
        }

        .btn-edit, .btn-delete {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            font-size: 1rem;
        }

        .btn-edit {
            color: #007bff;
        }

        .btn-delete {
            color: #dc3545;
        }

        .btn-edit:hover {
            color: #0056b3;
        }

        .btn-delete:hover {
            color: #bd2130;
        }

        #edit-skill-form, #edit-project-form {
            display: none;
            margin-top: 2rem;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .icon-preview {
            margin-top: 0.5rem;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container" style="padding-top: 2rem;">
        <div class="admin-section">
            <h1>Admin Panel</h1>
            <?php if (isset($_SESSION['flash_message'])): ?>
                <div class="flash-message <?php echo $_SESSION['flash_type']; ?>">
                    <?php 
                    echo $_SESSION['flash_message'];
                    unset($_SESSION['flash_message']);
                    unset($_SESSION['flash_type']);
                    ?>
                </div>
            <?php endif; ?>

            <div class="tab-container">
                <div class="tab-buttons">
                    <button class="tab-button" data-tab="messages-tab">Messages</button>
                    <button class="tab-button" data-tab="projects-tab">Projects</button>
                    <button class="tab-button" data-tab="skills-tab">Skills</button>
                </div>

                <!-- Messages Tab -->
                <div id="messages-tab" class="tab-content">
                    <h2>Messages</h2>
                    <div class="messages-list">
                        <?php foreach ($messages as $message): ?>
                            <div class="message-item">
                                <div>
                                    <strong><?php echo htmlspecialchars($message['name']); ?></strong>
                                    <p><?php echo htmlspecialchars($message['email']); ?></p>
                                    <p><?php echo htmlspecialchars($message['message']); ?></p>
                                    <small><?php echo date('F j, Y g:i a', strtotime($message['created_at'])); ?></small>
                                </div>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                    <button type="submit" name="delete_message" class="btn-delete delete-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Projects Tab -->
                <div id="projects-tab" class="tab-content">
                    <h2>Projects</h2>
                    <form method="POST" class="admin-form" id="add-project-form">
                        <div class="form-group">
                            <label for="title">Project Title</label>
                            <input type="text" id="title" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="technologies">Technologies (comma-separated)</label>
                            <input type="text" id="technologies" name="technologies" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="github_link">GitHub Link</label>
                            <input type="url" id="github_link" name="github_link" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="image_url">Image URL (optional)</label>
                            <input type="url" id="image_url" name="image_url" class="form-control">
                        </div>
                        <button type="submit" name="add_project" class="btn-admin">Add Project</button>
                    </form>

                    <!-- Edit Project Form -->
                    <form method="POST" class="admin-form" id="edit-project-form">
                        <h3>Edit Project</h3>
                        <input type="hidden" id="edit_project_id" name="project_id">
                        <div class="form-group">
                            <label for="edit_title">Project Title</label>
                            <input type="text" id="edit_title" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_description">Description</label>
                            <textarea id="edit_description" name="description" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_technologies">Technologies (comma-separated)</label>
                            <input type="text" id="edit_technologies" name="technologies" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_github_link">GitHub Link</label>
                            <input type="url" id="edit_github_link" name="github_link" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_image_url">Image URL (optional)</label>
                            <input type="url" id="edit_image_url" name="image_url" class="form-control">
                        </div>
                        <button type="submit" name="edit_project" class="btn-admin">Update Project</button>
                        <button type="button" class="btn-admin" style="background: #6c757d;" onclick="document.getElementById('edit-project-form').style.display='none';document.getElementById('add-project-form').style.display='block';">Cancel</button>
                    </form>

                    <div class="projects-list">
                        <?php foreach ($projects as $project): ?>
                            <div class="project-item">
                                <div>
                                    <strong><?php echo htmlspecialchars($project['title']); ?></strong>
                                    <p><?php echo htmlspecialchars($project['description']); ?></p>
                                    <small>Technologies: <?php echo htmlspecialchars($project['technologies']); ?></small>
                                </div>
                                <div>
                                    <button class="btn-edit edit-project-btn" 
                                            data-id="<?php echo $project['id']; ?>"
                                            data-title="<?php echo htmlspecialchars($project['title']); ?>"
                                            data-description="<?php echo htmlspecialchars($project['description']); ?>"
                                            data-technologies="<?php echo htmlspecialchars($project['technologies']); ?>"
                                            data-github-link="<?php echo htmlspecialchars($project['github_link']); ?>"
                                            data-image-url="<?php echo htmlspecialchars($project['image_url']); ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
                                        <button type="submit" name="delete_project" class="btn-delete delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Skills Tab -->
                <div id="skills-tab" class="tab-content">
                    <h2>Skills</h2>
                    <form method="POST" class="admin-form" id="add-skill-form">
                        <div class="form-group">
                            <label for="skill_name">Skill Name</label>
                            <input type="text" id="skill_name" name="skill_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="percentage">Proficiency (%)</label>
                            <input type="number" id="percentage" name="percentage" min="0" max="100" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <input type="text" id="category" name="category" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="icon_class">Icon Class (Font Awesome)</label>
                            <input type="text" id="icon_class" name="icon_class" class="form-control" required>
                            <div id="icon_preview" class="icon-preview"></div>
                        </div>
                        <button type="submit" name="add_skill" class="btn-admin">Add Skill</button>
                    </form>

                    <!-- Edit Skill Form -->
                    <form method="POST" class="admin-form" id="edit-skill-form">
                        <h3>Edit Skill</h3>
                        <input type="hidden" id="edit_skill_id" name="skill_id">
                        <div class="form-group">
                            <label for="edit_skill_name">Skill Name</label>
                            <input type="text" id="edit_skill_name" name="skill_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_percentage">Proficiency (%)</label>
                            <input type="number" id="edit_percentage" name="percentage" min="0" max="100" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_category">Category</label>
                            <input type="text" id="edit_category" name="category" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_icon_class">Icon Class (Font Awesome)</label>
                            <input type="text" id="edit_icon_class" name="icon_class" class="form-control" required>
                            <div id="edit_icon_preview" class="icon-preview"></div>
                        </div>
                        <button type="submit" name="edit_skill" class="btn-admin">Update Skill</button>
                        <button type="button" class="btn-admin" style="background: #6c757d;" onclick="document.getElementById('edit-skill-form').style.display='none';document.getElementById('add-skill-form').style.display='block';">Cancel</button>
                    </form>

                    <div class="skills-list">
                        <?php 
                        $currentCategory = '';
                        foreach ($skills as $skill): 
                            if ($currentCategory != $skill['category']):
                                if ($currentCategory != '') echo '</div>';
                                $currentCategory = $skill['category'];
                                echo '<h3 style="margin-top: 2rem;">' . htmlspecialchars($currentCategory) . '</h3><div class="skills-category">';
                            endif;
                        ?>
                            <div class="skill-item">
                                <div>
                                    <i class="<?php echo htmlspecialchars($skill['icon_class']); ?>"></i>
                                    <strong><?php echo htmlspecialchars($skill['name']); ?></strong>
                                    <div class="skill-progress-container">
                                        <div class="skill-progress-bar" style="width: <?php echo $skill['percentage']; ?>%"></div>
                                    </div>
                                </div>
                                <div>
                                    <button class="btn-edit edit-skill-btn" 
                                            data-id="<?php echo $skill['id']; ?>"
                                            data-name="<?php echo htmlspecialchars($skill['name']); ?>"
                                            data-percentage="<?php echo $skill['percentage']; ?>"
                                            data-category="<?php echo htmlspecialchars($skill['category']); ?>"
                                            data-icon="<?php echo htmlspecialchars($skill['icon_class']); ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="skill_id" value="<?php echo $skill['id']; ?>">
                                        <button type="submit" name="delete_skill" class="btn-delete delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if ($currentCategory != '') echo '</div>'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/admin.js"></script>
</body>
</html>
