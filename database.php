<!-- Initialize the Database -->

<?php

define("HOST", "localhost");     
define("USER", "space_2505Br5own_webuser");   
define("PASSWORD", "d00edjuhme");    
define("DATABASE", "space_2505Brown");    


$mysqli = mysqli_connect(HOST, USER, PASSWORD, DATABASE);


if (!$mysqli) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
