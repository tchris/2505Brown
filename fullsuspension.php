<!DOCTYPE html>
<html lang="en">
<?php
require 'database.php'; // ✅ Connect once
$hardtails = $mysqli->query("SELECT * FROM Mountain_Bike WHERE Category = 'Hardtail' ORDER BY RAND() LIMIT 1");
$fatbikes = $mysqli->query("SELECT * FROM Mountain_Bike WHERE Category = 'Full Suspens' ORDER by RAND() LIMIT 1");
?>

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
   
   <!-- Page Content Wrapper -->
    <div class="page-wrapper">

        <!-- Hero Wrapper -->
        <div class="hero">
            <img src="img/CP-Bike.png" class="hero__image">
            <h1 class="hero__title"><a href="https://alittlespace.org/2505Chartreuse/">TRON Bike Shop</a></h1>
            <h2 class="hero__menu"><a href="/2505Chartreuse/hardtail.php">Hardtail</a> |<a href="/2505Chartreuse/fullsuspension.php"> Full Suspension</a> | <a href="/2505Chartreuse/accessories.php">Accessories Galore</a></h2>
        </div>
</body>