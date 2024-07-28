<?php
header('Content-Type: application/json'); // Ensure response is JSON

// Connect to Redis
$redis = new Redis();
$redis->connect('127.0.0.1', 6379); // Connect to Redis server

$sessionId = $_GET['session_id'] ?? '';
$userId = $redis->get("session:$sessionId");

if (!$userId) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid session']);
    exit;
}

// Example profile data (in a real application, fetch this from a database)
$profile_data = [
    'user1' => ['dob' => '2000-01-01', 'fathername' => 'John', 'mothername' => 'Jane', 'contact' => '1234567890'],
    'user2' => ['dob' => '1990-02-02', 'fathername' => 'Robert', 'mothername' => 'Emily', 'contact' => '0987654321']
];

$data = $profile_data[$userId] ?? [];
echo json_encode(['status' => 'success', 'data' => $data]);
?>
