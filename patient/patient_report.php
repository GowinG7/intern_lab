<?php

include("../database/connection.php");

$hospital_number = isset($_REQUEST['hospital_number']) ? trim($_REQUEST['hospital_number']) : '';
$record_number_year = isset($_REQUEST['record_number_year']) ? trim($_REQUEST['record_number_year']) : '';

// Enable errors temporarily for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

$query = null;
$stmt = null; // track last prepared statement for debug

// Build the query based on which search parameter is provided
if (!empty($hospital_number)) {
    $sql = "SELECT `id`, `record_number`, `hospital_number`, `report_year`, `first_name`, `last_name`, `report_status` FROM reports WHERE hospital_number = ? AND report_status = 'Completed' ORDER BY report_year ASC, CAST(SUBSTRING(record_number, 2) AS UNSIGNED) ASC";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('s', $hospital_number);
        $stmt->execute();
        $query = $stmt->get_result();
    }
} elseif (!empty($record_number_year)) {
    // Parse the record_number/year format (e.g., B4001/2026)
    if (strpos($record_number_year, '/') !== false) {
        list($record_number, $year) = explode('/', $record_number_year, 2);
        $record_number = trim($record_number);
        $year = trim($year);
        
        $sql = "SELECT `id`, `record_number`, `hospital_number`, `report_year`, `first_name`, `last_name`, `report_status` FROM reports WHERE record_number = ? AND report_year = ? AND report_status = 'Completed'";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('ss', $record_number, $year);
            $stmt->execute();
            $query = $stmt->get_result();
        }
    }
}

?>

    <?php
    // Debug output - remove when issue is resolved
    echo "<!-- DEBUG: ";
    echo "REQ=" . htmlspecialchars(print_r($_REQUEST, true));
    echo " hospital_number=" . htmlspecialchars($hospital_number);
    echo " record_number_year=" . htmlspecialchars($record_number_year);
    echo " stmt_prepared=" . ($stmt ? 'yes' : 'no');
    echo " -->";
    ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Your Histopathology Reports</title>

    <?php include("../shared/links.php"); ?>

    <link rel="stylesheet" href="../css/style.css">

    <style>
        .find-report-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto 40px;
            border-top: 4px solid #0d6efd;
        }

        .find-report-container h2 {
            text-align: center;
            margin-bottom: 10px;
            color: #333;
            font-weight: 700;
            font-size: 28px;
        }

        .find-report-container .divider {
            height: 3px;
            background-color: #0d6efd;
            margin-bottom: 30px;
        }

        .form-group-label {
            font-weight: 600;
            margin-bottom: 12px;
            color: #333;
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 14px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-group input[type="text"]:focus {
            outline: none;
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
            background-color: #fff;
        }

        .example-text {
            font-size: 12px;
            color: #6c757d;
            margin-top: 6px;
            font-style: italic;
        }

        .or-divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
            color: #999;
            font-weight: 600;
        }

        .or-divider::before,
        .or-divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 35%;
            height: 1px;
            background-color: #dee2e6;
        }

        .or-divider::before {
            left: 0;
        }

        .or-divider::after {
            right: 0;
        }

        .find-report-container .btn-find {
            width: 100%;
            padding: 15px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .find-report-container .btn-find:hover {
            background-color: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        }

        .search-again-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .btn-search-again {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-search-again:hover {
            background-color: #5a6268;
            text-decoration: none;
            color: white;
        }

        table {
            margin-top: 20px;
        }

        table thead {
            background-color: #f8f9fa;
        }

        table th {
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
            color: #333;
        }

        table td {
            padding: 12px;
            vertical-align: middle;
        }

        table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn-view {
            background-color: #28a745;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-view:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
            text-decoration: none;
            color: white;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 12px;
        }

        /* PROFESSIONAL REPORT STYLES */
        .print-container {
            background-color: white;
            width: 100%;
            max-width: 850px;
            margin: 20px auto 30px;
            padding: 30px 35px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-size: 12px;
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.3;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            text-decoration: underline;
            letter-spacing: 1px;
            margin: 0;
        }

        .top-section {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 12px;
            font-size: 11px;
        }

        .top-col {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .top-row {
            display: flex;
            gap: 8px;
        }

        .top-label {
            font-weight: bold;
            min-width: 110px;
            line-height: 1.2;
        }

        .top-value {
            flex: 1;
            line-height: 1.2;
        }

        .divider {
            border-top: 2px solid #000;
            margin: 10px 0;
        }

        .fields-section {
            margin-bottom: 8px;
        }

        .field-label {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .field-content {
            border: none;
            padding: 6px;
            min-height: 35px;
            font-size: 11px;
            line-height: 1.3;
            margin-bottom: 8px;
            word-wrap: break-word;
            overflow: hidden;
        }

        .two-col-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 8px;
        }

        .two-col-field {
            display: flex;
            flex-direction: column;
        }

        .two-col-field .field-content {
            min-height: 35px;
        }

        .full-width-section {
            margin-bottom: 8px;
        }

        .full-width-section .field-content {
            min-height: 40px;
        }

        .signature-section {
            margin-top: 12px;
            margin-bottom: 12px;
        }

        .sig-container {
            display: flex;
            align-items: flex-start;
            gap: 1px;
            margin-bottom: 15px;
            min-height: 50px;
        }

        .sig-label {
            font-weight: bold;
            font-size: 11px;
            white-space: nowrap;
            min-width: 65px;
            margin-top: 30px;
        }

        .sig-line {
            border-bottom: 1px dashed #000;
            min-width: 25%;
            min-height: 40px;
        }

        .pathologist-section {
            margin-bottom: 5px;
        }

        .path-label {
            font-weight: bold;
            font-size: 11px;
            display: inline-block;
            min-width: 130px;
        }

        .path-value {
            font-size: 11px;
            display: inline;
            line-height: 1.2;
        }

        .note-section {
            background-color: #d3d3d3;
            padding: 8px;
            margin: 12px 0;
            font-size: 10px;
            line-height: 1.3;
            border-left: 5px solid #000;
        }

        .note-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .note-text {
            text-align: justify;
            font-style: italic;
        }

        .comments-section {
            margin-top: 8px;
        }

        .action-buttons {
            text-align: center;
            margin: 30px 0 40px;
            gap: 10px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 20px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-print {
            background-color: #0d6efd;
            color: white;
        }

        .btn-print:hover {
            background-color: #0b5ed7;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        @media (max-width: 1024px) {
            .print-container {
                padding: 20px 20px;
                max-width: 100%;
            }

            .header h1 {
                font-size: 16px;
            }

            .top-section {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
                font-size: 10px;
            }

            .top-label {
                min-width: 90px;
            }

            .two-col-section {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .field-content {
                min-height: 30px;
                padding: 4px;
                font-size: 10px;
            }

            .field-label {
                font-size: 10px;
            }
        }

        @media (max-width: 768px) {
            .print-container {
                padding: 15px 12px;
                max-width: 100%;
            }

            .header h1 {
                font-size: 14px;
                letter-spacing: 0;
            }

            .top-section {
                grid-template-columns: 1fr;
                gap: 8px;
                font-size: 9px;
                margin-bottom: 8px;
            }

            .top-label {
                min-width: 80px;
                font-size: 9px;
            }

            .top-value {
                font-size: 9px;
            }

            .field-label {
                font-size: 9px;
                margin-bottom: 2px;
            }

            .field-content {
                min-height: 25px;
                padding: 3px;
                font-size: 9px;
                margin-bottom: 6px;
            }

            .two-col-section {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .path-label {
                min-width: 110px;
                font-size: 9px;
            }

            .path-value {
                font-size: 9px;
            }

            .note-section {
                padding: 6px;
                margin: 8px 0;
                font-size: 8px;
            }

            .sig-label {
                min-width: 50px;
                font-size: 9px;
            }

            .sig-line {
                min-width: 40%;
            }

            .btn {
                padding: 8px 15px;
                font-size: 12px;
            }
        }

        @media print {
            body {
                background-color: white;
                padding: 0;
                margin: 0;
                font-family: 'Times New Roman', Times, serif;
                line-height: 1.3;
            }

            .print-container {
                box-shadow: none;
                max-width: 100%;
                padding: 30px 35px;
                background-color: white;
                width: 100%;
                font-size: 12px;
                page-break-inside: avoid;
            }

            .action-buttons {
                display: none !important;
            }

            .search-again-container {
                display: none !important;
            }
        }

</head>

<body class="patient-page-with-header">

    <?php include("header.php"); ?>

    <?php
    // Visible debug block to help track GET params and query results
    echo '<div style="position:fixed;left:8px;top:8px;z-index:9999;background:#fff;border:1px solid #eee;padding:8px;font-size:12px;color:#111;max-width:95%;box-shadow:0 2px 6px rgba(0,0,0,0.08);">';
    echo '<strong>DEBUG</strong><br/>';
    echo 'REQ: ' . htmlspecialchars(json_encode($_REQUEST)) . '<br/>';
    echo 'hospital_number: ' . htmlspecialchars($hospital_number) . '<br/>';
    echo 'record_number_year: ' . htmlspecialchars($record_number_year) . '<br/>';
    echo 'stmt_prepared: ' . ($stmt ? 'yes' : 'no') . '<br/>';
    echo 'query_rows: ' . ($query instanceof mysqli_result ? $query->num_rows : 'null') . '<br/>';
    echo '</div>';
    ?>

    <div class="container mt-5 mb-5">

        <?php
        // Only show search form if no results or no search performed
        if ($query === null || $query->num_rows == 0) {
            ?>

        <div class="find-report-container">
            <h2>Find Your Report</h2>
            <div class="divider"></div>

            <form method="GET" action="patient_report.php">
                <!-- Hospital Number Search -->
                <div class="form-group">
                    <label class="form-group-label">Hospital Number</label>
                    <input 
                        type="text" 
                        name="hospital_number"
                        placeholder="Enter hospital number"
                        value="<?php echo htmlspecialchars($hospital_number); ?>">
                    <div class="example-text">Example: 749, 123, etc.</div>
                </div>

                <!-- OR Divider -->
                <div class="or-divider">OR</div>

                <!-- Record Number / Year Search -->
                <div class="form-group">
                    <label class="form-group-label">Record Number / Year</label>
                    <input 
                        type="text" 
                        name="record_number_year"
                        placeholder="Enter record number and year"
                        value="<?php echo htmlspecialchars($record_number_year); ?>">
                    <div class="example-text">Example: B4001/2026 (Letter+Number/Year)</div>
                </div>

                <button type="submit" class="btn-find">Find My Report</button>
            </form>
        </div>

            <?php
        } else {
            // Show search again button when viewing results
            ?>

        <div class="search-again-container">
            <a href="patient_report.php" class="btn-search-again">Search Again</a>
        </div>

            <?php
        }
        ?>

        <?php
        // Display reports directly without table
        if ($query !== null && $query->num_rows > 0) {
            while ($row = $query->fetch_assoc()) {
                // Fetch full report data
                $report_id = $row['id'];
                $stmt_full = $conn->prepare("SELECT * FROM reports WHERE id = ?");
                $stmt_full->bind_param('i', $report_id);
                $stmt_full->execute();
                $report_full = $stmt_full->get_result()->fetch_assoc();
                ?>

        <!-- PROFESSIONAL REPORT VIEW -->
        <div class="print-container">

            <!-- Header -->
            <div class="header">
                <h1>Histopathology Report</h1>
            </div>

            <!-- Top Section - Three Columns -->
            <div class="top-section">
                <!-- Column 1 -->
                <div class="top-col">
                    <div class="top-row">
                        <span class="top-label">HistoPathology Number:</span>
                        <span class="top-value"><?php echo htmlspecialchars($report_full['record_number']); ?></span>
                    </div>
                    <div class="top-row">
                        <span class="top-label">Patient Name:</span>
                        <span class="top-value"><?php echo htmlspecialchars(trim($report_full['first_name'] . ' ' . $report_full['middle_name'] . ' ' . $report_full['last_name'])); ?></span>
                    </div>
                    <div class="top-row">
                        <span class="top-label">Gender:</span>
                        <span class="top-value"><?php echo htmlspecialchars($report_full['gender']); ?></span>
                    </div>
                    <div class="top-row">
                        <span class="top-label">Referring Physician:</span>
                        <span class="top-value"><?php echo htmlspecialchars($report_full['referring_physician']); ?></span>
                    </div>
                </div>

                <!-- Column 2 -->
                <div class="top-col">
                    <div class="top-row">
                        <span class="top-label">Hospital No.:</span>
                        <span class="top-value"><?php echo htmlspecialchars($report_full['hospital_number']); ?></span>
                    </div>
                    <div class="top-row">
                        <span class="top-label">Age:</span>
                        <span class="top-value"><?php echo htmlspecialchars($report_full['age']); ?></span>
                    </div>
                    <div class="top-row">
                        <span class="top-label">Date of Receipt (BS):</span>
                        <span class="top-value"><?php echo htmlspecialchars($report_full['date_receipt']); ?></span>
                    </div>
                    <div class="top-row">
                        <span class="top-label">Date of Dispatch (BS):</span>
                        <span class="top-value"><?php echo htmlspecialchars($report_full['date_dispatch']); ?></span>
                    </div>
                </div>

                <!-- Column 3 -->
                <div class="top-col">
                    <div class="top-row">
                        <span class="top-label">Year:</span>
                        <span class="top-value"><?php echo htmlspecialchars($report_full['report_year']); ?></span>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Clinical Features -->
            <div class="fields-section">
                <div class="field-label">Clinical Features:</div>
                <div class="field-content"><?php echo nl2br(htmlspecialchars($report_full['clinical_features'])); ?></div>
            </div>

            <!-- Biopsy Site and Procedure -->
            <div class="two-col-section">
                <div class="two-col-field">
                    <div class="field-label">Biopsy Site:</div>
                    <div class="field-content"><?php echo nl2br(htmlspecialchars($report_full['biopsy_site'])); ?></div>
                </div>
                <div class="two-col-field">
                    <div class="field-label">Procedure Performed:</div>
                    <div class="field-content"><?php echo nl2br(htmlspecialchars($report_full['procedure_performed'])); ?></div>
                </div>
            </div>

            <!-- Gross Description -->
            <div class="full-width-section">
                <div class="field-label">Gross Description:</div>
                <div class="field-content"><?php echo nl2br(htmlspecialchars($report_full['gross_description'])); ?></div>
            </div>

            <!-- Microscopic Description and Diagnosis -->
            <div class="two-col-section">
                <div class="two-col-field">
                    <div class="field-label">Microscopic Description:</div>
                    <div class="field-content"><?php echo nl2br(htmlspecialchars($report_full['microscopic_description'])); ?></div>
                </div>
                <div class="two-col-field">
                    <div class="field-label">Diagnosis:</div>
                    <div class="field-content"><?php echo nl2br(htmlspecialchars($report_full['diagnosis'])); ?></div>
                </div>
            </div>

            <!-- Signature Section -->
            <div class="signature-section">
                <div class="sig-container">
                    <div class="sig-label">Signature:</div>
                    <div class="sig-line"></div>
                </div>

                <div class="pathologist-section">
                    <span class="path-label">Pathologist:</span>
                    <span class="path-value"><?php echo htmlspecialchars($report_full['pathologist']); ?></span>
                </div>

                <div class="pathologist-section">
                    <span class="path-label">Consultant Pathologist:</span>
                    <span class="path-value"><?php echo htmlspecialchars($report_full['consultant_pathologist']); ?></span>
                </div>
            </div>

            <!-- Comments (if exists) -->
            <?php if (!empty($report_full['comment'])): ?>
                <div class="comments-section">
                    <div class="field-label">Comments:</div>
                    <div class="field-content"><?php echo nl2br(htmlspecialchars($report_full['comment'])); ?></div>
                </div>
            <?php endif; ?>

            <!-- Note Section -->
            <div class="note-section">
                <div class="note-title">NOTE:</div>
                <div class="note-text">The opinion/diagnosis is based on the tissue submitted and may not represent entire lesion (depends on the nature of sampling and provided information) and should not be interpreted in isolation. A correlation with clinical, radiological and other laboratory parameters is strongly recommended before any therapeutic intervention. This report is made only for the welfare of the patient, not for any legal purpose.</div>
            </div>

        </div>

        <div class="action-buttons">
            <button class="btn btn-print" onclick="window.print()">🖨️ Print / Save as PDF</button>
            <button class="btn btn-back" onclick="history.back()">← Go Back</button>
        </div>

                <?php
            }
        } elseif ($query !== null && $query->num_rows == 0 && (!empty($hospital_number) || !empty($record_number_year))) {
            ?>

            <div class="alert alert-danger">
                No report found for <?php echo !empty($record_number_year) ? htmlspecialchars($record_number_year) : 'the entered search details'; ?>.
            </div>

            <?php
        }
        ?>

    </div>

</body>

</html>