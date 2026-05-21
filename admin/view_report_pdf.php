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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histopathology Report</title>
    <style>
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
            margin: 10px 0;
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
    </style>
</head>

<body>

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
                    <span class="top-value"><?php echo $row['record_number']; ?></span>
                </div>
                <div class="top-row">
                    <span class="top-label">Patient Name:</span>
                    <span
                        class="top-value"><?php echo trim($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']); ?></span>
                </div>
                <div class="top-row">
                    <span class="top-label">Gender:</span>
                    <span class="top-value"><?php echo $row['gender']; ?></span>
                </div>
                <div class="top-row">
                    <span class="top-label">Referring Physician:</span>
                    <span class="top-value"><?php echo $row['referring_physician']; ?></span>
                </div>
            </div>

            <!-- Column 2 -->
            <div class="top-col">
                <div class="top-row">
                    <span class="top-label">Hospital No.:</span>
                    <span class="top-value"><?php echo $row['hospital_number']; ?></span>
                </div>
                <div class="top-row">
                    <span class="top-label">Age:</span>
                    <span class="top-value"><?php echo $row['age']; ?></span>
                </div>
                <div class="top-row">
                    <span class="top-label">Date of Receipt (BS):</span>
                    <span class="top-value"><?php echo $row['date_receipt']; ?></span>
                </div>
                <div class="top-row">
                    <span class="top-label">Date of Dispatch (BS):</span>
                    <span class="top-value"><?php echo $row['date_dispatch']; ?></span>
                </div>
            </div>

            <!-- Column 3 -->
            <div class="top-col">
                <div class="top-row">
                    <span class="top-label">Year:</span>
                    <span class="top-value"><?php echo $row['report_year']; ?></span>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Clinical Features -->
        <div class="fields-section">
            <div class="field-label">Clinical Features:</div>
            <div class="field-content"><?php echo nl2br(htmlspecialchars($row['clinical_features'])); ?></div>
        </div>

        <!-- Biopsy Site and Procedure -->
        <div class="two-col-section">
            <div class="two-col-field">
                <div class="field-label">Biopsy Site:</div>
                <div class="field-content"><?php echo nl2br(htmlspecialchars($row['biopsy_site'])); ?></div>
            </div>
            <div class="two-col-field">
                <div class="field-label">Procedure Performed:</div>
                <div class="field-content"><?php echo nl2br(htmlspecialchars($row['procedure_performed'])); ?></div>
            </div>
        </div>

        <!-- Gross Description -->
        <div class="full-width-section">
            <div class="field-label">Gross Description:</div>
            <div class="field-content"><?php echo nl2br(htmlspecialchars($row['gross_description'])); ?></div>
        </div>

        <!-- Microscopic Description and Diagnosis -->
        <div class="two-col-section">
            <div class="two-col-field">
                <div class="field-label">Microscopic Description:</div>
                <div class="field-content"><?php echo nl2br(htmlspecialchars($row['microscopic_description'])); ?></div>
            </div>
            <div class="two-col-field">
                <div class="field-label">Diagnosis:</div>
                <div class="field-content"><?php echo nl2br(htmlspecialchars($row['diagnosis'])); ?></div>
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
                <span class="path-value"><?php echo $row['pathologist']; ?></span>
            </div>

            <div class="pathologist-section">
                <span class="path-label">Consultant Pathologist:</span>
                <span class="path-value"><?php echo $row['consultant_pathologist']; ?></span>
            </div>
        </div>

        <!-- Comments (if exists) - Before Note -->
        <?php if (!empty($row['comment'])): ?>
            <div class="comments-section">
                <div class="field-label">Comments:</div>
                <div class="field-content"><?php echo nl2br(htmlspecialchars($row['comment'])); ?></div>
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

    <!-- Action Buttons -->
    <div class="action-buttons">
        <button class="btn btn-print" onclick="window.print()">🖨️ Print / Save as PDF</button>
        <button class="btn btn-back" onclick="history.back()">← Go Back</button>
    </div>

</body>

</html>