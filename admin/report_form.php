<?php
include("auth_check.php");

// ========== HANDLE AJAX REQUEST FOR RECORD NUMBER ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set JSON header immediately for any POST request
    header('Content-Type: application/json');
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Check if it's a valid AJAX request with required fields
    if (!is_array($input) || !isset($input['histopathology_number']) || !isset($input['year'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid or missing parameters',
            'debug' => [
                'input_type' => gettype($input),
                'has_histopathology' => isset($input['histopathology_number']) ?? false,
                'has_year' => isset($input['year']) ?? false
            ]
        ]);
        exit;
    }
    
    include("../database/connection.php");
    
    $histopathology_number = $input['histopathology_number'];
    $year = $input['year'];
    
    // Validate inputs
    if (!preg_match('/^[A-Z]$/', $histopathology_number)) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid histopathology number'
        ]);
        exit;
    }
    
    if (!preg_match('/^\d{4}$/', $year)) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid year'
        ]);
        exit;
    }
    
    // Find the max NUMERIC value for records of this series and year
    // Query the reports table filtered by histopathology_number (A or B) and year
    $stmt = mysqli_prepare($conn, 
        "SELECT MAX(CAST(record_number AS UNSIGNED)) as max_numeric_value 
         FROM reports 
         WHERE histopathology_number = ? 
         AND report_year = ?"
    );
    
    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . mysqli_error($conn)
        ]);
        exit;
    }
    
    // Bind parameters - filter by the specific series (A or B) and year
    mysqli_stmt_bind_param($stmt, "ss", $histopathology_number, $year);
    
    // Execute query
    if (!mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => false,
            'message' => 'Query execution error: ' . mysqli_error($conn)
        ]);
        mysqli_stmt_close($stmt);
        exit;
    }
    
    // Get result
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $max_numeric_value = $row['max_numeric_value'];
    mysqli_stmt_close($stmt);
    
    // Start from 4000 if no records exist for this series, otherwise increment
    $next_numeric = $max_numeric_value ? ($max_numeric_value + 1) : 4000;
    
    // Format the record number: prepend the letter (A4000, B4001, etc.)
    $formatted_record_number = $histopathology_number . $next_numeric;
    
    // Check if this formatted number already exists (safety check for uniqueness)
    $stmt_check = mysqli_prepare($conn, 
        "SELECT COUNT(*) as count FROM reports WHERE record_number = ?"
    );
    
    if (!$stmt_check) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error checking availability: ' . mysqli_error($conn)
        ]);
        exit;
    }
    
    $attempts = 0;
    $max_attempts = 10000; // Prevent infinite loops
    
    // Safety loop: increment if collision detected (shouldn't happen with proper formatting)
    while ($attempts < $max_attempts) {
        mysqli_stmt_bind_param($stmt_check, "s", $formatted_record_number);
        mysqli_stmt_execute($stmt_check);
        $check_result = mysqli_stmt_get_result($stmt_check);
        $check_row = mysqli_fetch_assoc($check_result);
        
        // If this number doesn't exist globally, we can use it
        if ($check_row['count'] == 0) {
            break;
        }
        
        // Otherwise, try the next number
        $next_numeric++;
        $formatted_record_number = $histopathology_number . $next_numeric;
        $attempts++;
    }
    
    mysqli_stmt_close($stmt_check);
    
    if ($attempts >= $max_attempts) {
        echo json_encode([
            'success' => false,
            'message' => 'Unable to generate a unique record number. Database limit reached.'
        ]);
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'next_record_number' => $formatted_record_number,
        'message' => 'Record number generated successfully'
    ]);
    exit;
}
// ========== END AJAX HANDLER ==========

// Include links for HTML page display only
include("../shared/links.php");

$currentPage = basename(__FILE__);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BKMCH Histopathology Report System</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/auto_record_number.js"></script>

</head>

<body>
    <?php include_once("header.php"); ?>
    
    <div class="admin-content">
        <div class="container">
            <div class="main-box mx-auto">

            <h2 class="report-title">Histopathology Report</h2>

            <form id="reportForm" action="save_report.php" method="POST">

                <!-- Report Information -->

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Histopathology Number</label>
                        <div class="d-flex align-items-center gap-2">
                            <select id="histopathology_number" name="histopathology_number" class="form-select"
                                style="max-width: 110px; min-width: 90px;">
                                <option value="A" selected>A</option>
                                <option value="B">B</option>
                            </select>

                            <input type="text" name="record_number" id="record_number" class="form-control"
                                style="max-width: 290px;" placeholder="Auto-generated">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="fw-bold">Hospital Number</label>
                        <input type="text" name="hospital_number" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold">Year</label>
                        <input type="text" name="year" class="form-control" value="2026">
                    </div>


                </div>


                <!-- Patient Information -->

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Middle Name</label>
                        <input type="text" name="middle_name" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-control">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Gender</label>

                        <select name="gender" class="form-select">
                            <option>Select</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>

                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Age</label>
                        <input type="number" name="age" class="form-control">
                    </div>


                    <div class="col-md-4 mb-3">
                        <label>Date of Receipt</label>
                        <input type="date" name="date_receipt" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Date of Dispatch</label>
                        <input type="date" name="date_dispatch" class="form-control">
                    </div>

                </div>

                <!-- Referring Physician -->

                <div class="row">

                    <div class="col-md-12 mb-3">
                        <label>Referring Physician</label>
                        <input type="text" name="referring_physician" class="form-control">
                    </div>

                </div>

                <!-- Clinical Features -->

                <div class="row">

                    <div class="col-md-12 mb-3">
                        <label>Clinical Features</label>
                        <textarea name="clinical_features" rows="3" class="form-control"></textarea>
                    </div>

                </div>

                <!-- Biopsy and Procedure -->

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Biopsy Site</label>
                        <input type="text" name="biopsy_site" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Procedure Performed</label>
                        <input type="text" name="procedure_performed" class="form-control">
                    </div>

                </div>

                <!-- Gross Description -->

                <div class="row">

                    <div class="col-md-12 mb-3">
                        <label>Gross Description</label>
                        <textarea name="gross_description" rows="4" class="form-control"></textarea>
                    </div>

                </div>

                <!-- Microscopic Description and Diagnosis -->

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Microscopic Description</label>
                        <textarea name="microscopic_description" rows="6" class="form-control"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Diagnosis</label>
                        <textarea name="diagnosis" rows="6" class="form-control"></textarea>
                    </div>

                </div>

                <!-- Pathologist -->

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label>Pathologist</label>

                        <select id="patho" name="pathologist" class="form-select">
                            <option value="Dr.GG">Dr.GG</option>
                            <option value="Dr.SS">Dr.SS</option>
                            <option value="Dr.BT">Dr.BT</option>
                            <option value="Dr.BG">Dr.BG</option>
                        </select>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Consultant Pathologist</label>

                        <select id="conpatho" name="consultant_pathologist" class="form-select">
                            <option value="Dr.GG">Dr.GG</option>
                            <option value="Dr.SS">Dr.SS</option>
                            <option value="Dr.BT">Dr.BT</option>
                            <option value="Dr.BG">Dr.BG</option>
                        </select>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Report Status</label>

                        <select id="report_status" name="report_status" class="form-select">
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                        </select>

                    </div>
                </div>

                <!-- Comment -->

                <div class="row">

                    <div class="col-md-12 mb-3">
                        <label>Comment</label>
                        <textarea id="comment" name="comment" rows="3" class="form-control"></textarea>
                    </div>

                </div>
                <div class="text-center mt-4">
                    <button type="submit" name="save_report" class="btn btn-primary">
                        Save Report
                    </button>
                </div>

            </form>
            </div>
        </div>
    </div>

</body>

</html>