<?php

include("../database/connection.php");

// Check if ID is provided for report display
$report_id = isset($_GET['id']) ? intval($_GET['id']) : null;

// If ID is provided, fetch the report
$report_data = null;
if ($report_id) {
    $stmt = $conn->prepare("SELECT * FROM reports WHERE id = ?");
    $stmt->bind_param('i', $report_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $report_data = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $report_data ? 'Histopathology Report' : 'BKMCH Patient Report Lookup'; ?></title>

    <?php include("../shared/links.php"); ?>

    <link rel="stylesheet" href="../css/style.css">

    <style>
        <?php if (!$report_data): ?>
        /* SEARCH FORM STYLES */

        body {
            margin: 0;
            min-height: 100vh;
            position: relative;
            font-family: Arial, Helvetica, sans-serif;
            background: url('images/bkmch-bg.jpg') center center / cover no-repeat fixed;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.55);
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
        }
        
        .lookup-box {
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(6px);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .lookup-box h2 {
            color: #0f8a22;
            font-weight: 700;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #ecf0f7;
        }

        .lookup-box label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .lookup-box .form-control {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 12px 15px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .lookup-box .form-control:focus {
            border-color: #139745;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            color: #6c757d;
            font-weight: 600;
            position: relative;
            z-index: 1;
            padding: 0 10px;
        }

        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 45%;
            height: 1px;
            background-color: #dee2e6;
            z-index: 0;
        }

        .divider::before { left: 0; }
        .divider::after  { right: 0; }

        .btn-danger {
            background-color: #25c457;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #428d40e8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(29, 116, 55, 0.4);
        }

        .btn-danger:focus,
        .btn-danger:active,
        .btn-danger.focus,
        .btn-danger:not(:disabled):not(.disabled).active {
            background-color: #25c457;
            border-color: #25c457;
            color: #fff;
            box-shadow: none;
        }

        .error-message {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
            display: none;
        }

        .format-hint {
            color: #6c757d;
            font-size: 13px;
            margin-top: 5px;
            font-style: italic;
        }
        <?php else: ?>
        /* PROFESSIONAL REPORT STYLES */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #f5f5f5;
            padding: 20px;
            line-height: 1.3;
        }

        .print-container {
            background-color: white;
            width: 100%;
            max-width: 850px;
            margin: 0 auto;
            padding: 30px 35px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-size: 12px;
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

        /* Top Section - Three Columns */
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

        /* Divider line */
        .divider {
            border-top: 2px solid #000;
            margin: 10px 0px;
        }

        /* Fields Section */
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

        /* Two Column Layout */
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

        /* Full Width Field */
        .full-width-section {
            margin-bottom: 8px;
        }

        .full-width-section .field-content {
            min-height: 40px;
        }

        /* Signature Section */
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

        /* Note Section */
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

        /* Comments */
        .comments-section {
            margin-top: 8px;
        }

        /* Action Buttons */
        .action-buttons {
            text-align: center;
            margin-top: 20px;
            gap: 10px;
            display: flex;
            justify-content: center;
        }

        .btn {
            padding: 10px 20px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
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

        /* Tablet View - 1024px and below */
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

        /* Mobile View - 768px and below */
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
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

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
                page-break-inside: avoid;
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
                page-break-inside: avoid;
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
                page-break-inside: avoid;
            }

            .full-width-section .field-content {
                min-height: 40px;
            }

            .signature-section {
                margin-top: 12px;
                margin-bottom: 12px;
                page-break-inside: avoid;
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
                page-break-inside: avoid;
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
                page-break-inside: avoid;
            }

            .action-buttons {
                display: none !important;
            }
        }
        <?php endif; ?>

        
    </style>

</head>

<body>

    <?php if (!$report_data): ?>
    <!-- SEARCH FORM VIEW -->
    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-7">

                <div class="lookup-box">

                    <h2 class="text-center  mb-4">
                        BPKMCH Histopathology Report
                    </h2>

                    <form id="lookupForm" action="patient_view.php" method="POST">

                        <div class="mb-3">

                            <label for="hospital_number">Hospital Number</label>

                            <input type="text" id="hospital_number" name="hospital_number"
                                placeholder="Enter hospital number" class="form-control">

                            <!-- <div class="format-hint">Example: 749, 123, etc.</div> -->

                        </div>

                        <div class="divider">OR</div>

                        <div class="mb-3">

                            <label for="record_number_year">Record Number / Year</label>

                            <input type="text" id="record_number_year" name="record_number_year"
                                placeholder="" class="form-control">

                            <div class="format-hint">Example: B4001/2026 (Histopathology Number/Year)</div>

                            <div id="errorMessage" class="error-message"></div>

                        </div>

                        <button type="submit" class="btn btn-danger w-100">

                            Find My Report

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <?php else: ?>
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
                    <span class="top-value"><?php echo htmlspecialchars($report_data['record_number']); ?></span>
                </div>
                <div class="top-row">
                    <span class="top-label">Patient Name:</span>
                    <span
                        class="top-value"><?php echo htmlspecialchars(trim($report_data['first_name'] . ' ' . $report_data['middle_name'] . ' ' . $report_data['last_name'])); ?></span>
                </div>
                <div class="top-row">
                    <span class="top-label">Gender:</span>
                    <span class="top-value"><?php echo htmlspecialchars($report_data['gender']); ?></span>
                </div>
                <div class="top-row">
                    <span class="top-label">Referring Physician:</span>
                    <span class="top-value"><?php echo htmlspecialchars($report_data['referring_physician']); ?></span>
                </div>
            </div>

            <!-- Column 2 -->
            <div class="top-col">
                <div class="top-row">
                    <span class="top-label">Hospital No.:</span>
                    <span class="top-value"><?php echo htmlspecialchars($report_data['hospital_number']); ?></span>
                </div>
                <div class="top-row">
                    <span class="top-label">Age:</span>
                    <span class="top-value"><?php echo htmlspecialchars($report_data['age']); ?></span>
                </div>
                <div class="top-row">
                    <span class="top-label">Date of Receipt (BS):</span>
                    <span class="top-value"><?php echo htmlspecialchars($report_data['date_receipt']); ?></span>
                </div>
                <div class="top-row">
                    <span class="top-label">Date of Dispatch (BS):</span>
                    <span class="top-value"><?php echo htmlspecialchars($report_data['date_dispatch']); ?></span>
                </div>
            </div>

            <!-- Column 3 -->
            <div class="top-col">
                <div class="top-row">
                    <span class="top-label">Year:</span>
                    <span class="top-value"><?php echo htmlspecialchars($report_data['report_year']); ?></span>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Clinical Features -->
        <div class="fields-section">
            <div class="field-label">Clinical Features:</div>
            <div class="field-content"><?php echo nl2br(htmlspecialchars($report_data['clinical_features'])); ?></div>
        </div>

        <!-- Biopsy Site and Procedure -->
        <div class="two-col-section">
            <div class="two-col-field">
                <div class="field-label">Biopsy Site:</div>
                <div class="field-content"><?php echo nl2br(htmlspecialchars($report_data['biopsy_site'])); ?></div>
            </div>
            <div class="two-col-field">
                <div class="field-label">Procedure Performed:</div>
                <div class="field-content"><?php echo nl2br(htmlspecialchars($report_data['procedure_performed'])); ?></div>
            </div>
        </div>

        <!-- Gross Description -->
        <div class="full-width-section">
            <div class="field-label">Gross Description:</div>
            <div class="field-content"><?php echo nl2br(htmlspecialchars($report_data['gross_description'])); ?></div>
        </div>

        <!-- Microscopic Description and Diagnosis -->
        <div class="two-col-section">
            <div class="two-col-field">
                <div class="field-label">Microscopic Description:</div>
                <div class="field-content"><?php echo nl2br(htmlspecialchars($report_data['microscopic_description'])); ?></div>
            </div>
            <div class="two-col-field">
                <div class="field-label">Diagnosis:</div>
                <div class="field-content"><?php echo nl2br(htmlspecialchars($report_data['diagnosis'])); ?></div>
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
                <span class="path-value"><?php echo htmlspecialchars($report_data['pathologist']); ?></span>
            </div>

            <div class="pathologist-section">
                <span class="path-label">Consultant Pathologist:</span>
                <span class="path-value"><?php echo htmlspecialchars($report_data['consultant_pathologist']); ?></span>
            </div>
        </div>

        <!-- Comments (if exists) - Before Note -->
        <?php if (!empty($report_data['comment'])): ?>
            <div class="comments-section">
                <div class="field-label">Comments:</div>
                <div class="field-content"><?php echo nl2br(htmlspecialchars($report_data['comment'])); ?></div>
            </div>
        <?php endif; ?>

        <!-- Note Section - At Bottom -->
        <div class="note-section">
            <div class="note-title">NOTE:</div>
            <div class="note-text">The opinion/diagnosis is based on the tissue submitted and may not represent entire
                lesion (depends on the nature of sampling and provided information) and should not be interpreted in
                isolation. A correlation with clinical, radiological and other laboratory parameters is strongly
                recommended before any therapeutic intervention. This report is made only for the welfare of the
                patient, not for any legal purpose.</div>
        </div>

    </div>

    <?php endif; ?>

    <!-- Action Buttons - Only Show on Report View -->
    <?php if ($report_data): ?>
    <div class="action-buttons">
        <button class="btn btn-print" onclick="window.print()">🖨️ Print / Save as PDF</button>
        <button class="btn btn-back" onclick="history.back()">← Go Back</button>
    </div>
    <?php endif; ?>

    <script>
        const form = document.getElementById('lookupForm');
        const recordNumberYearInput = document.getElementById('record_number_year');
        const hospitalNumberInput = document.getElementById('hospital_number');
        const errorMessage = document.getElementById('errorMessage');

        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const hospitalNumber = hospitalNumberInput.value.trim();
                const recordNumberYear = recordNumberYearInput.value.trim();

                // Clear previous error
                errorMessage.style.display = 'none';
                errorMessage.textContent = '';

                // Check if at least one field is filled
                if (!hospitalNumber && !recordNumberYear) {
                    errorMessage.textContent = 'Please enter either Hospital Number or Record Number/Year (e.g., B4001/2026)';
                    errorMessage.style.display = 'block';
                    return false;
                }

                // If record number/year is provided, validate format
                if (recordNumberYear) {
                    // Format: A4000/2026
                    const regex = /^([A-Z])(\d+)\/(\d{4})$/;
                    const match = recordNumberYear.match(regex);

                    if (!match) {
                        errorMessage.textContent = 'Invalid format. Please use format like: B4001/2026';
                        errorMessage.style.display = 'block';
                        return false;
                    }
                }

                // Submit the form
                form.submit();
            });

            // Clear error when user starts typing
            recordNumberYearInput.addEventListener('input', function () {
                errorMessage.style.display = 'none';
            });
        }
    </script>

</body>

</html>