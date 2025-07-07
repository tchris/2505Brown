<?php
session_start();
unset($_SESSION['warehouse_logged_in']);
session_destroy();
header('Location: warehouse_login.php');
exit;
?>
