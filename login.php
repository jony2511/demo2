<?php
require_once 'config.php';

// Verify database connection
if (!verify_db_connection()) {
    die("Database connection error. Please try again later.");
}

// Redirect if already logged in
if (is_logged_in()) {
    redirect('admin.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        
        // Set remember me cookie if requested
        if (isset($_POST['remember']) && $_POST['remember'] === '1') {
            setcookie('remember_token', password_hash($user['id'] . $user['password'], PASSWORD_DEFAULT), time() + (86400 * 30), '/');
        }

        set_flash_message('success', 'Welcome back!');
        redirect('admin.php');
    } else {
        set_flash_message('error', 'Invalid credentials');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portfolio Admin</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .login-form {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-width: 400px;
            width: 90%;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }
        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .back-to-home {
            text-align: center;
            margin-top: 1rem;
        }
        .back-to-home a {
            color: #764ba2;
            text-decoration: none;
        }
        .back-to-home a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <form method="POST" class="login-form">
            <div class="login-header">
                <h2>Admin Login</h2>
                <p>Enter your credentials to access the admin panel</p>
            </div>

            <?php echo get_flash_message(); ?>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember" value="1">
                <label for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <i class="fas fa-sign-in-alt"></i>
                Login
            </button>

            <div class="back-to-home">
                <a href="index.php">
                    <i class="fas fa-arrow-left"></i>
                    Back to Portfolio
                </a>
            </div>
        </form>
    </div>

    <script>
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            
            if (!username || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
            }
        });

        // Auto-hide flash messages
        setTimeout(() => {
            const flashMessage = document.querySelector('.alert');
            if (flashMessage) {
                flashMessage.style.display = 'none';
            }
        }, 5000);
    </script>
</body>
</html>
