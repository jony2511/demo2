<?php
// Prevent any output before headers
ob_start();

// Disable error display but keep logging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');

require_once 'config.php';
require_once 'includes/mail_simple.php';

// Set headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Clean any output buffers
while (ob_get_level()) {
    ob_end_clean();
}

// Function to send JSON response and exit
function send_json_response($status, $message) {
    echo json_encode([
        'status' => $status,
        'message' => $message
    ]);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'contact':
                // Check if all required fields are present
                if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['message'])) {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Missing required fields'
                    ]);
                    exit;
                }

                $name = sanitize_input($_POST['name']);
                $email = sanitize_input($_POST['email']);
                $message = sanitize_input($_POST['message']);

                try {
                    // Validate inputs
                    if (empty($name) || empty($email) || empty($message)) {
                        send_json_response('error', 'Please fill in all fields.');
                    }
                    
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        send_json_response('error', 'Please enter a valid email address.');
                    }

                    // Store in database
                    try {
                        $stmt = $db->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
                        $result = $stmt->execute([$name, $email, $message]);
                        
                        if (!$result) {
                            throw new PDOException('Failed to save message');
                        }
                        
                        // Send success response
                        send_json_response('success', 'Thank you! Your message has been received.');
                        
                    } catch (PDOException $e) {
                        error_log("Database error: " . $e->getMessage());
                        send_json_response('error', 'There was an error saving your message. Please try again.');
                    }
                    
                } catch (Exception $e) {
                    error_log("Contact form error: " . $e->getMessage());
                    send_json_response('error', $e->getMessage());
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
