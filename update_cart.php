🧱 2. Create/Update update_cart.php
php
Copy
Edit
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $action = $_POST['action'] ?? null;

    if (is_numeric($id)) {
        $id = (int)$id;

        switch ($action) {
            case 'increase':
                $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
                break;

            case 'decrease':
                if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['cart'][$id]--;
                    if ($_SESSION['cart'][$id] <= 0) {
                        unset($_SESSION['cart'][$id]);
                    }
                }
                break;

            case 'remove':
                unset($_SESSION['cart'][$id]);
                break;
        }
    }
}

header("Location: cart.php");
exit;