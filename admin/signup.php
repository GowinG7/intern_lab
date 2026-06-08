<?php
session_start();
include("../database/connection.php");

if (isset($_POST['signup_btn'])) {

    $full_name = $_POST['full_name'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'pathologist';

    // check duplicate username
    $check = mysqli_prepare($conn, "SELECT id FROM admin_creden WHERE username=?");
    mysqli_stmt_bind_param($check, "s", $username);
    mysqli_stmt_execute($check);
    $res = mysqli_stmt_get_result($check);

    if (mysqli_fetch_assoc($res)) {
        $_SESSION['error'] = "Username already exists";
        header("Location: signup.php");
        exit;
    }

    mysqli_stmt_close($check);

    // password hash
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // insert user
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO admin_creden (full_name, username, password, role)
         VALUES (?,?,?,?)"
    );

    mysqli_stmt_bind_param($stmt, "ssss", $full_name, $username, $hashedPassword, $role);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Account created successfully";
        header("Location: signup.php");
        exit;
    } else {
        $_SESSION['error'] = "Signup failed";
        header("Location: signup.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Signup</title>
    <?php include("../shared/links.php"); ?>
</head>

<body class="container mt-5">

    <div class="row justify-content-center">
        <div class="col-md-5">

            <h3 class="text-center mb-3">Staff Signup</h3>

            <?php if (isset($_SESSION['error'])) { ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php } ?>

            <?php if (isset($_SESSION['success'])) { ?>
                <div class="alert alert-success">
                    <?= $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </div>
            <?php } ?>

            <form method="POST">

                <input type="text" name="full_name" class="form-control mb-2" placeholder="Full Name" required>

                <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>

                <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>

                <select name="role" class="form-control mb-2" required>
                    <option value="pathologist">Pathologist</option>
                    <option value="admin">Admin</option>
                </select>

                <button type="submit" name="signup_btn" class="btn btn-primary w-100">
                    Create Account
                </button>

            </form>

        </div>
    </div>

</body>

</html>