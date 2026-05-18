<?php

session_start();

if (!isset($_SESSION['admin_username'])) {
    header("location: login.php");
}

?>