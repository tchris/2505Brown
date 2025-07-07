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
        header('Location: warehouse.php'); // ✅ Correct warehouse page redirect
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Warehouse Login - TRON Cycles</title>
    <link rel="stylesheet" href="static/base.css">
</head>
<body>
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
</body>
</html>
