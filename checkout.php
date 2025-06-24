<!DOCTYPE html>
<html lang="en">
<?php
require 'database.php'; // ✅ Connect once
$hardtails = $mysqli->query("SELECT * FROM Mountain_Bike WHERE Category = 'Hardtail' ORDER BY RAND() LIMIT 1");
$fullsuspension = $mysqli->query("SELECT * FROM Mountain_Bike WHERE Category = 'Full Suspens' ORDER by RAND() LIMIT 1");
$accessories = $mysqli->query("SELECT * FROM Mountain_Bike WHERE Category = 'Accessories' ORDER by RAND() LIMIT 1");
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

<form action="storeinvoice.php" method="post">
  <h2>Billing Info</h2>
  <input name="cname" placeholder="Name" required>
  <input name="caddy" placeholder="Address" required>
  <input name="cstate" placeholder="State" required>
  <input name="czip" placeholder="Zip" required>
  <input name="cphone" placeholder="Phone" required>
  <input name="cemail" placeholder="Email" required>

  <h2>Shipping Info</h2>
  <input name="sname" placeholder="Name" required>
  <input name="saddy" placeholder="Address" required>
  <input name="sstate" placeholder="State" required>
  <input name="szip" placeholder="Zip" required>
  <input name="sphone" placeholder="Phone" required>
  <input name="semail" placeholder="Email" required>

  <button type="submit">Submit Order</button>
</form>
