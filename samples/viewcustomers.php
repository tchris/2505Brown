<html>
<head>
 <title>Lab 03</title>
 <link href="lab02.css" rel="stylesheet" type = "text/css">
</head>
<body>
<?php
require_once("database.php");
//create table around the information
echo "<table cellpadding=5>";
echo "<tr><th>ID</th><th>Title</th><th>First Name</th><th>Last Name</th></tr>";

        $sql = "SELECT * FROM customers order by 1";
            $result = mysqli_query($mysqli,$sql);
            //var_dump($result);
            if (!empty($result)) {
                while ($row = mysqli_fetch_assoc($result))
                {
                    echo "<tr><td>" . $row['Id'] . "</td> ";
                    echo "<td>" . $row['Title'] . "</td> ";
                    echo "<td>" . $row['Firstname'] . "</td> ";
                    echo "<td>" . $row['Surname'] . "</td></tr>";
                }
            }

echo "</table>";

?>
<a href="index.php"><img src="img/home.png"></a>
<a href="lab03.php"><img src="img/three.jpg"></a>
</body>
</html>