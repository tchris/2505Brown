<html>
<head>
 <title>Lab 03</title>
 <link href="lab02.css" rel="stylesheet" type = "text/css">
</head>
<body>
    <form action="<?php $_SERVER['PHP_SELF'];?>" method="POST">

    <h2>Insert Into Purchases<h2>
    ID: <input type="text" name="Id" />
    Cust ID: <input type="text" name="CustId" />
    Month: <input type="text" name="Month" />
    Day: <input type="text" name="Day" />
    Year: <input type="text" name="Year" />
    
    <input type="submit" value="Insert Purchase"/>
    </form>

<?php
require_once("database.php");

$purId = $_POST['Id'];
$purCustId = $_POST['CustId'];
$purMonth = $_POST['Month'];
$purDay = $_POST['Day'];
$purYear = $_POST['Year'];

if ($_POST['Id'] <> '')
{
$sqlinsert = "insert into purchases (Id, customers_Id, Month, Day, Year) values ('$purId', '$purCustId', '$purMonth', '$purDay', '$purYear')";
//echo "in if stmt";
$resultinsert = mysqli_query($mysqli,$sqlinsert);
}
//create table around the information
echo "<table cellpadding=5>";
echo "<tr><th>ID</th><th>Cust_ID</th><th>Month</th><th>Day</th><th>Year</th></tr>";

        $sql = "SELECT * FROM purchases order by 1";
            $result = mysqli_query($mysqli,$sql);
            //var_dump($result);
            if (!empty($result)) {
                while ($row = mysqli_fetch_assoc($result))
                {
                    echo "<tr><td>" . $row['Id'] . "</td> ";
                    echo "<td>" . $row['customers_Id'] . "</td> ";
                    echo "<td>" . $row['Month'] . "</td> ";
                    echo "<td>" . $row['Day'] . "</td> ";
                    echo "<td>" . $row['Year'] . "</td></tr>";
                }
            }

echo "</table>";

?>
<a href="index.php"><img src="img/home.png"></a>
<a href="lab03.php"><img src="img/three.jpg"></a>
</body>
</html>