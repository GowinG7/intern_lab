<?php

include("../database/connection.php");

$hospital_number = $_GET['hospital_number'];
$record_number = $_GET['record_number'];

$query = mysqli_query(
    $conn,

    "SELECT * FROM reports

WHERE

(
hospital_number='$hospital_number'

OR

record_number='$record_number'
)

AND report_status='Completed'
"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Patient Report</title>

    <?php include("../shared/links.php"); ?>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <div class="container mt-5 mb-5">

        <?php

        if (mysqli_num_rows($query) > 0) {
            $row = mysqli_fetch_assoc($query);

            ?>

            <div class="card p-5 shadow">

                <h2 class="text-center mb-4">
                    Histopathology Report
                </h2>

                <hr>

                <div class="mb-3">

                    <h5>Patient Name</h5>

                    <p>

                        <?php

                        echo $row['first_name'] . " " .
                            $row['middle_name'] . " " .
                            $row['last_name'];

                        ?>

                    </p>

                </div>

                <div class="mb-3">

                    <h5>Hospital Number</h5>

                    <p><?php echo $row['hospital_number']; ?></p>

                </div>

                <div class="mb-3">

                    <h5>Record Number</h5>

                    <p><?php echo $row['record_number']; ?></p>

                </div>

                <div class="mb-3">

                    <h5>Diagnosis</h5>

                    <p><?php echo $row['diagnosis']; ?></p>

                </div>

                <div class="mb-3">

                    <h5>Microscopic Description</h5>

                    <p><?php echo $row['microscopic_description']; ?></p>

                </div>

                <div class="mb-3">

                    <h5>Pathologist</h5>

                    <p><?php echo $row['pathologist']; ?></p>

                </div>

                <button onclick="window.print()" class="btn btn-primary">

                    Print Report

                </button>

            </div>

            <?php
        } else {
            ?>

            <div class="alert alert-danger">

                Report Not Found

            </div>

            <?php
        }
        ?>

    </div>

</body>

</html>