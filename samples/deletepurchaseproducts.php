<html>
<head>
 <title>Lab 03</title>
 <link href="lab02.css" rel="stylesheet" type = "text/css">
</head>
<body>
<?php
require_once("database.php");
if ($_POST['ProdId']<>'')
{
    $sqldelete = "DELETE FROM purchaseproducts where purchases_Id = '" . $_POST['PurId'] . "' and products_Id = " . $_POST['ProdId'];
            $resultdelete = mysqli_query($mysqli,$sqldelete);
}
//create table around the information
echo "<table cellpadding=5>";
echo "<tr><th>Purchase ID</th><th>Product ID</th><th>Quantity</th><th>Cost</th><th>Total</th></tr>";

        $sql = "SELECT * FROM purchaseproducts order by purchases_id";
            $result = mysqli_query($mysqli,$sql);
            //var_dump($result);
            if (!empty($result)) {
                while ($row = mysqli_fetch_assoc($result))
                {
                    echo "<form action = '" . $_SERVER["PHP_SELF"] . "' method = 'POST'>";
                    echo "<tr><td>" . $row['purchases_Id'] . "</td> ";
                    echo "<td>" . $row['products_Id'] . "</td> ";
                    echo "<td>" . $row['Quantity'] . "</td> ";
                    echo "<td>" . $row['Cost'] . "</td> ";
                    echo "<td>" . $row['Quantity'] * $row['Cost'] . "</td>";
                    echo "<input type = 'hidden' name= 'PurId' value = '" . $row['purchases_Id'] . "'/>";
                    echo "<input type = 'hidden' name= 'ProdId' value = '" . $row['products_Id'] . "'/>";
                    echo "<td><input type='Submit' value = 'Delete Product'></td></tr></form>";
                }
            }

echo "</table>";

?>
<a href="index.php"><img src="img/home.png"></a>
<a href="lab03.php"><img src="img/three.jpg"></a>
</body>
</html>