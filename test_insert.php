<?php
// Show errors if any
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'database.php'; // Make sure this connects to your db_test database

// Sample data
$name = "Test User";
$phone = 5551234567;
$date = date('Y-m-d'); // today's date
$description = "This is a test description inserted by test_insert.php";

// Prepare and insert
$stmt = $mysqli->prepare("INSERT INTO test (Name, Phone, Date, Description) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}

$stmt->bind_param("siss", $name, $phone, $date, $description);

if ($stmt->execute()) {
    echo "<h2>✅ Insert successful</h2>";
    echo "<p>Inserted: $name, $phone, $date</p>";
} else {
    echo "<h2>❌ Insert failed: " . $stmt->error . "</h2>";
}
