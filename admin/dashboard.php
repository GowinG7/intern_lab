<?php

include("auth_check.php");
include("../database/connection.php");

$currentPage = basename(__FILE__);

/* Total Reports */

$total_query = mysqli_query(
    $conn,
    "SELECT * FROM reports"
);

$total_reports = mysqli_num_rows($total_query);



/* Completed Reports */

$completed_query = mysqli_query(
    $conn,
    "SELECT * FROM reports
     WHERE report_status='Completed'"
);

$completed_reports = mysqli_num_rows($completed_query);



/* Pending Reports */

$pending_query = mysqli_query(
    $conn,
    "SELECT * FROM reports
     WHERE report_status='Pending'"
);

$pending_reports = mysqli_num_rows($pending_query);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>

    <?php include("../shared/links.php"); ?>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>
    <?php include_once("header.php"); ?>

    <div class="admin-content">
        <div class="container">

            <div class="mb-4">

                <a href="report_form.php" class="btn btn-primary me-2">

                    Add Report

                </a>

                <a href="manage_reports.php" class="btn btn-dark">

                    Manage Reports

                </a>

            </div>

            <div class="row">

                <!-- Total Reports -->

                <div class="col-md-4 mb-3">

                    <div class="dashboard-card bg-blue">

                        <h4>Total Reports</h4>

                        <h2>

                            <?php echo $total_reports; ?>

                        </h2>

                    </div>

                </div>

                <!-- Completed Reports -->

                <div class="col-md-4 mb-3">

                    <div class="dashboard-card bg-green">

                        <h4>Completed Reports</h4>

                        <h2>

                            <?php echo $completed_reports; ?>

                        </h2>

                    </div>

                </div>

                <!-- Pending Reports -->

                <div class="col-md-4 mb-3">

                    <div class="dashboard-card bg-orange">

                        <h4>Pending Reports</h4>

                        <h2>

                            <?php echo $pending_reports; ?>

                        </h2>

                    </div>

                </div>

            </div>
        </div>
    </div>

</body>

</html>