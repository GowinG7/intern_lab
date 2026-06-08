<?php
$hname = "localhost";
$uname = "root";
$pass = "";
$db = "intern_lab"
    
$conn = mysqli_connect($hname, $uname, $pass, $db);

if (!$conn) {
    die("Database Connection Failed: ".mysqli_connect_error());
}

?>
