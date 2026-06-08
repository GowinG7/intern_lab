<?php

session_start();
include("../database/connection.php");

if (isset($_POST['login_btn'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM admin_creden WHERE username=?"
    );

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {

        if (password_verify($password, $row['password'])) {

            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_username'] = $row['username'];
            $_SESSION['full_name'] = $row['full_name'];

            // ADD THIS LINE
            $_SESSION['role'] = $row['role'];

            header("Location: dashboard.php");
            exit;
        }
    }

    echo "
    <script>
        alert('Invalid Login');
        window.location.href='login.php';
    </script>
    ";
}
?>