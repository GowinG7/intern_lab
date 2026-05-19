<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>

    <title>Staff Login</title>

    <?php include("../shared/links.php"); ?>

</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-5">

                <div class="card p-4 shadow">

                    <h2 class="text-center mb-4">Staff Login</h2>

                    <form action="login_process.php" method="POST">

                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button type="submit" name="login_btn" class="btn btn-primary w-100">
                            Login
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</body>

</html>