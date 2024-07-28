<?php
header('Content-Type: application/json'); // Ensure response is JSON

require __DIR__ . '/../vendor/autoload.php'; // Adjust path to Composer's autoload file

// Connect to Redis
$redis = new Redis();
$redis->connect('127.0.0.1', 6379); // Connect to Redis server

// Connect to MongoDB
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$collection = $mongoClient->guvi->profile; // Replace 'guvi' with your actual database name

$response = array(); // Initialize response array

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sessionId = $_POST['session_id'] ?? '';
    $userId = $redis->get("session:$sessionId");

    if (!$userId) {
        $response = ['status' => 'error', 'message' => 'Invalid session'];
        echo json_encode($response);
        exit;
    }

    // Retrieve profile data from POST request
    $dob = $_POST['dob'] ?? '';
    $fathername = $_POST['fathername'] ?? '';
    $mothername = $_POST['mothername'] ?? '';
    $contact = $_POST['contact'] ?? '';

    // Prepare data for MongoDB
    $profileData = [
        'dob' => $dob,
        'fathername' => $fathername,
        'mothername' => $mothername,
        'contact' => $contact
    ];

    try {
        // Update or insert profile data in MongoDB
        $updateResult = $collection->updateOne(
            ['user_id' => (int)$userId],
            ['$set' => $profileData],
            ['upsert' => true] // Create a new document if no matching document is found
        );

        // Cache updated profile data in Redis using Hashes
        $redis->hMSet("profile:$userId", $profileData);
        $redis->expire("profile:$userId", 3600); // Cache for 1 hour

        $response = ['status' => 'success', 'message' => 'Profile updated successfully'];
    } catch (Exception $e) {
        // Handle any errors that may have occurred
        $response = ['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()];
    }
} else {
    $response = ['status' => 'error', 'message' => 'Invalid request method.'];
}

// Output JSON response
echo json_encode($response);
?>
