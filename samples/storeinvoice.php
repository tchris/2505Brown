<html>
<head>
 <title>Shopping Cart</title>
 <link href="tstore.css" rel="stylesheet" type = "text/css">
</head>
<body>
<h2>Confirm Order</h2>


<?php
require_once("database.php");
$invId = $_POST['invId'];

$dbProdRecords = $mysqli->query("select Title, FName, LName, Addy, City, State, Zip, phone, email from customer c inner join invoice i on i.custid = c.id where i.id = '$invId'");
echo "<table>";
while ($row = $dbProdRecords->fetch_assoc()){
    echo "<tr><h1>".$row["Title"]." ".$row["FName"]." ".$row["LName"] . "</h1></td></tr> ";
    echo "<tr><h2>".$row["Addy"] . "</h2></td></tr> ";
    echo "<tr><h2>".$row["City"].", ".$row["State"]." ".$row["Zip"]. "</h2></td></tr> ";
    echo "<tr><h2>".$row["Phone"] . "</h2></td></tr> ";
    echo "<tr><h2>".$row["Email"] . "</h2></td></tr> ";
}

//create table around the information
$dbProdRecords = $mysqli->query("SELECT ip.Qty, ThumbNail, Name, Price FROM ip inner join product p on ip.ProductId = p.Id where InvoiceId = '$invId' order by 1");
//create table around the information
echo "<table cellpadding=5><tr><td>Quantity</td><td>ThumbNail</td><td>Name</td><td>Price</td></tr>";
while ($row = $dbProdRecords->fetch_assoc()) {
    echo "<tr><td><h2>" . $row["Qty"] . "</h2></td> ";
    echo "<td><img src='img/" . $row["ThumbNail"] . "' width=200></td> ";
    echo "<td><h1>" . $row["Name"] . "</h1></td> ";
    echo "<td><h2>" . $row["Price"] . "</h2></td></tr>";
    $totalprice += $row["Price"];
}
echo "</table>";
$tax = $totalprice * .075;
$grandtotal = $totalprice + $tax;
echo "<h3>Total Price: $totalprice</h3><br>";
echo "<h3>Tax: $tax</h3><br>";
echo "<h3>Grand Total: $grandtotal</h3><br>";

?>
<a href="storethanks.php"><img src="img/checkout.png" width=200></a>
</body>

</html>
