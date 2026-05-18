<?php

session_start();

include("../database/connection.php");

if (isset($_POST['login_btn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM admin_credens
              WHERE username='$username'
              AND password='$password'";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['admin_username'] = $username;

        header("location: dashboard.php");
    } else {
        echo "
        <script>
        alert('Invalid Login');
        window.location.href='login.php';
        </script>
        ";
    }
}

?>