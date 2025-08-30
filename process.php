<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'contact':
                $name = sanitize_input($_POST['name']);
                $email = sanitize_input($_POST['email']);
                $message = sanitize_input($_POST['message']);

                try {
                    $stmt = $pdo->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
                    $stmt->execute([$name, $email, $message]);
                    
                    // Send email notification
                    $to = "mtijony2@gmail.com";
                    $subject = "New Contact Form Submission";
                    $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
                    $headers = "From: $email";
                    
                    mail($to, $subject, $body, $headers);
                    
                    echo json_encode(['status' => 'success', 'message' => 'Message sent successfully!']);
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => 'Error sending message. Please try again.']);
                }
                break;

            case 'login':
                $username = sanitize_input($_POST['username']);
                $password = $_POST['password'];

                try {
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
                    $stmt->execute([$username]);
                    $user = $stmt->fetch();

                    if ($user && password_verify($password, $user['password'])) {
                        $_SESSION['user_id'] = $user['id'];
                        echo json_encode(['status' => 'success', 'redirect' => 'admin.php']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
                    }
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => 'Login failed. Please try again.']);
                }
                break;
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
