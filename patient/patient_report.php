<?php

include("../database/connection.php");

$hospital_number = isset($_GET['hospital_number']) ? trim($_GET['hospital_number']) : '';
$record_number = isset($_GET['record_number']) ? trim($_GET['record_number']) : '';
$histopathology_number = isset($_GET['histopathology_number']) ? trim($_GET['histopathology_number']) : '';
$report_year = isset($_GET['report_year']) ? trim($_GET['report_year']) : '';

// Build the query with proper WHERE conditions
$where_conditions = array();
$params = array();
$types = '';

// Search by hospital number
if (!empty($hospital_number)) {
    $where_conditions[] = "hospital_number = ?";
    $params[] = $hospital_number;
    $types .= 's';
}

// If searching by histopathology (combination of letter, number, year)
// Reconstruct the full record_number from letter + number (e.g., "A" + "4000" = "A4000")
if (!empty($histopathology_number) && !empty($record_number) && !empty($report_year)) {
    $full_record_number = $histopathology_number . $record_number;
    $where_conditions[] = "(record_number = ? AND report_year = ?)";
    $params[] = $full_record_number;
    $params[] = $report_year;
    $types .= 'si';
}

// Ensure we have at least one search criterion
if (empty($where_conditions)) {
    $query = null;
} else {
    $where_clause = '(' . implode(' OR ', $where_conditions) . ') AND report_status = "Completed"';

    $sql = "SELECT * FROM reports WHERE " . $where_clause;
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $query = $stmt->get_result();
    } else {
        $query = null;
    }
}

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

        if ($query === null) {
            ?>

            <div class="alert alert-warning">

                Please provide a search criterion.

            </div>

            <?php
        } elseif ($query->num_rows > 0) {
            $row = $query->fetch_assoc();

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