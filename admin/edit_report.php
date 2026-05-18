<?php

include("auth_check.php");
include("../database/connection.php");

$id = $_GET['id'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM reports WHERE id='$id'"
);

$row = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Edit Report</title>

    <?php include("../shared/links.php"); ?>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <div class="container mt-5">

        <h2>Edit Report</h2>

        <form action="update_report.php" method="POST">

            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <div class="mb-3">

                <label>Diagnosis</label>

                <textarea name="diagnosis" rows="5" class="form-control"><?php echo $row['diagnosis']; ?></textarea>

            </div>

            <div class="mb-3">

                <label>Microscopic Description</label>

                <textarea name="microscopic_description" rows="5"
                    class="form-control"><?php echo $row['microscopic_description']; ?></textarea>

            </div>

            <div class="mb-3">

                <label>Report Status</label>

                <select name="report_status" class="form-select">

                    <option value="Pending">Pending</option>

                    <option value="Completed">Completed</option>

                </select>

            </div>

            <button type="submit" name="update_report" class="btn btn-primary">

                Update Report

            </button>

        </form>

    </div>

</body>

</html>