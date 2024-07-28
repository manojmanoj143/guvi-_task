<?php
header('Content-Type: application/json'); // Ensure response is JSON

include 'db_config.php'; // Include your database configuration

$response = array(); // Initialize response array

// Connect to Redis
$redis = new Redis();
$redis->connect('127.0.0.1', 6379); // Connect to Redis server

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email and password are provided
    if (empty($email) || empty($password)) {
        $response = ['status' => 'error', 'message' => 'Email and password are required.'];
    } else {
        // Prepare and execute SQL statement
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($userId, $hashedPassword);

        if ($stmt->fetch() && password_verify($password, $hashedPassword)) {
            // Login successful
            $sessionId = uniqid(); // Generate a unique session ID
            $redis->set("session:$sessionId", $userId, 3600); // Store session in Redis for 1 hour

            $response = [
                'status' => 'success',
                'session_id' => $sessionId,
                'user_id' => $userId
            ];
        } else {
            // Invalid credentials
            $response = ['status' => 'error', 'message' => 'Invalid email or password.'];
        }

        $stmt->close();
    }

    $conn->close();
} else {
    $response = ['status' => 'error', 'message' => 'Invalid request method.'];
}

// Output JSON response
echo json_encode($response);
?>
