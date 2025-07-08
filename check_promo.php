
<?php
require 'database.php';
header('Content-Type: application/json');
/*
// Check for DB connection issues first
if ($mysqli->connect_error) {
    echo json_encode([
        'valid' => false,
        'error' => 'Database connection failed',
        'details' => $mysqli->connect_error
    ]);
    exit;
}

$code = $_GET['code'] ?? '';
$now = date('Y-m-d');

$stmt = $mysqli->prepare("SELECT discount_pct FROM promotions WHERE code = ? AND start_date <= ? AND end_date >= ?");
if (!$stmt) {
    echo json_encode([
        'valid' => false,
        'error' => 'Prepare failed',
        'details' => $mysqli->error
    ]);
    exit;
}

$stmt->bind_param("sss", $code, $now, $now);
$stmt->execute();
$stmt->bind_result($pct);
$valid = $stmt->fetch();
$stmt->close();

if ($valid) {
    echo json_encode(['valid' => true, 'discount_pct' => $pct]);
} else {
    echo json_encode([
        'valid' => false,
        'debug' => [
            'code' => $code,
            'date_now' => $now
        ]
    ]);
}
