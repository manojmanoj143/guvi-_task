<?php
// MySQL connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "task";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// MongoDB connection
require '../vendor/autoload.php'; // Composer autoload
use MongoDB\Client as MongoClient;

// MongoDB connection
$mongoClient = new MongoClient("mongodb://localhost:27017"); // Adjust the URI as needed
$db = $mongoClient->selectDatabase('guvi'); // Replace 'user_profiles' with your database name
$profilesCollection = $db->selectCollection('profile'); // Replace 'profiles' with your collection name
?>
