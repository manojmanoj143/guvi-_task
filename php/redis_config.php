<?php
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

// Check connection
if ($redis->ping()) {
    echo "Connected to Redis";
} else {
    echo "Failed to connect to Redis";
}
?>
