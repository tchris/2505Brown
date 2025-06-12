<?php

define("HOST", "localhost");     // The host you want to connect to.
define("USER", "space_2505Br5own_webuser");    // The database username. 
define("PASSWORD", "EM420CTU");    // The database password. 
define("DATABASE", "space_2505Brown");    // The database name.

// Create connection
$mysqli = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

// Check connection
if (!$mysqli) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
