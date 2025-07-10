<?php
session_start();

//Redirect if already logged in
if (isset($_SESSION['manager_logged_in'])) {
    header('Location: manager.php');
    exit;
}

// Check form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Manager Credentials
    $valid_username = 'admin';
    $valid_password = 'TestTest123!@#';

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['manager_logged_in'] = true;
        header('Location: manager.php');
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Hero Wrapper -->
        <?php include 'templates/hero.php'; ?>

    <title>Manager Login</title>
    <link rel="stylesheet" href="static/base.css">
    <link rel="stylesheet" href="static/layout.css">
    <link rel="stylesheet" href="static/components.css">
</head>
<body>
    <div class="page-wrapper">
        <h1>Manager Login</h1>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <label>Username:</label><br>
            <input type="text" name="username" required><br><br>
            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>
            <button type="submit" class="buy-button">Login</button>
        </form>
    </div>

    <hr class="cyberpunk-hr">

            <!-- Footer -->
            <?php include 'templates/footer.php'; ?>
            
</body>
</html>
