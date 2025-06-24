<?php
// Enable error output
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connect to DB
require 'database.php';

if (!$mysqli) {
    die("Database connection failed.");
}

echo "<h1>✅ Connected to the database successfully!</h1>";

// Try a test query
$result = $mysqli->query("SELECT id, name, price FROM Mountain_Bike LIMIT 5");

if (!$result) {
    die("❌ Query failed: " . $mysqli->error);
}

echo "<h2>Sample Products:</h2><ul>";
while ($row = $result->fetch_assoc()) {
    echo "<li>ID: {$row['id']} – {$row['name']} – $ {$row['price']}</li>";
}
echo "</ul>";
