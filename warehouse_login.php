<?php
session_start();

// You can hardcode allowed users or check against a database
$allowed_users = [
    'warehouse1' => 'password123',
    'warehouse2' => 'securepass'
];

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (isset($allowed_users[$username]) && $allowed_users[$username] === $password) {
        $_SESSION['warehouse_logged_in'] = true;
        header('Location: index_warehouse.php'); // ✅ Correct warehouse page redirect
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>TRON Cycles – Bike Shop of the Future</title>
    <link rel="stylesheet" href="static/base.css">
    <link rel="stylesheet" href="static/layout.css">
    <link rel="stylesheet" href="static/components.css">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
</head>
<body>
    <!-- Hero Wrapper -->
        <?php include 'templates/hero.php'; ?>

    <div class="login-wrapper">
        <h1>Warehouse Login</h1>
        <?php if ($error): ?>
            <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="username">Username:</label><br>
            <input type="text" name="username" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" name="password" required><br><br>

            <button type="submit">Login</button>
        </form>
    </div>
    <hr class="cyberpunk-hr">

            <!-- Footer -->
            <?php include 'templates/footer.php'; ?>
        
</body>
</html>
