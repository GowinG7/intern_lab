<?php

include("auth_check.php");
include("../database/connection.php");

$id = $_GET['id'];

mysqli_query(
    $conn,
    "DELETE FROM reports WHERE id='$id'"
);

header("location: manage_reports.php");

?>