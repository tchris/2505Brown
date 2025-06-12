<html>
<head>
 <title>Lab 03</title>
 <link href="lab02.css" rel="stylesheet" type = "text/css">
</head>
<body>
    <h2>Update Products<h2>
<?php
require_once("database.php");

$prodId = $_POST['Id'];
$prodName = $_POST['Name'];
$prodDesc = $_POST['desc'];
$prodQty = $_POST['Qty'];
$prodCost = $_POST['Cost'];

if ($_POST['Id'] <> '')
{
$sqlupdate = "update products set Name = '$prodName', Cost = '$prodCost', Decription = '$prodDesc', Quantity = '$prodQty' where Id = '$prodId'";
//echo "in if stmt";
$resultupdate = mysqli_query($mysqli,$sqlupdate);
}
//create table around the information
echo "<table cellpadding=5>";
echo "<tr><th>ID</th><th>Name of Product</th><th>Cost</th><th>Description</th><th>Quantity</th></tr>";

        $sql = "SELECT * FROM products order by 1";
            $result = mysqli_query($mysqli,$sql);
            //var_dump($result);
            if (!empty($result)) {
                while ($row = mysqli_fetch_assoc($result))
                {
                    echo "<form action = '" . $_SERVER["PHP_SELF"] . "' method = 'POST'>";
                    echo "<tr><td><input type='text' value='" . $row['Id'] . "' name = 'Id'/></td> ";
                    echo "<td><input type='text' value='" . $row['Name'] . "' name = 'Name'/></td> ";
                    echo "<td><input type='text' value='" . $row['Cost'] . "' name = 'Cost'/></td> ";
                    echo "<td><input type='text' value='" . $row['Decription'] . "' name = 'desc'/></td> ";
                    echo "<td><input type='text' value='" . $row['Quantity'] . "' name = 'Qty'/></td>";
                    echo "<td><input type='Submit' value = 'Update Products'></td></tr></form>";
                }
            }

echo "</table>";

?>
<a href="index.php"><img src="img/home.png"></a>
<a href="lab03.php"><img src="img/three.jpg"></a>
</body>
</html>