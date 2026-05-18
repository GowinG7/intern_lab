<?php
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BKMCH Patient Report Lookup</title>

    <?php include("../shared/links.php"); ?>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-7">

                <div class="lookup-box">

                    <h2 class="text-center mb-4">
                        Find Your Report
                    </h2>

                    <form action="patient_report.php" method="GET">

                        <div class="mb-3">

                            <label>Hospital Number</label>

                            <input type="text" name="hospital_number" class="form-control">

                        </div>

                        <div class="text-center mb-3">
                            OR
                        </div>

                        <div class="mb-3">

                            <label>Record / Histo Number</label>

                            <input type="text" name="record_number" class="form-control">

                        </div>

                        <button type="submit" class="btn btn-danger w-100">

                            Find My Report

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</body>

</html>