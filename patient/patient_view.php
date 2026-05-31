<?php

include("../database/connection.php");

$hospital_number = isset($_REQUEST['hospital_number']) ? trim($_REQUEST['hospital_number']) : '';
$record_number_year = isset($_REQUEST['record_number_year']) ? trim($_REQUEST['record_number_year']) : '';

$query = null;

// Build the query based on which search parameter is provided
if (!empty($hospital_number)) {
    $sql = "SELECT `id`, `record_number`, `hospital_number`, `report_year`, `first_name`, `last_name`, `report_status` FROM reports WHERE hospital_number = ? ORDER BY report_year ASC, CAST(SUBSTRING(record_number, 2) AS UNSIGNED) ASC";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('s', $hospital_number);
        $stmt->execute();
        $query = $stmt->get_result();
    }
} elseif (!empty($record_number_year)) {
    if (strpos($record_number_year, '/') !== false) {
        list($record_number, $year) = explode('/', $record_number_year, 2);
        $record_number = trim($record_number);
        $year = trim($year);

        $sql = "SELECT `id`, `record_number`, `hospital_number`, `report_year`, `first_name`, `last_name`, `report_status` FROM reports WHERE record_number = ? AND report_year = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('ss', $record_number, $year);
            $stmt->execute();
            $query = $stmt->get_result();
        }
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
            margin: 20px auto 30px;
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
            background-color: #25c457;
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

        .divider {
            border-top: 2px solid #000;
            margin: 10px 0;
        }
    </style>
</head>

<body class="patient-page-with-header">

    <?php include("header.php"); ?>

    <div class="container mt-5 mb-5">

        <?php
        if ($query === null || $query->num_rows == 0) {
            // No results, show search form again
            ?>
            <div class="alert alert-danger">
                No report found for <?php echo !empty($record_number_year) ? htmlspecialchars($record_number_year) : 'the entered search details'; ?>.
            </div>
            <div style="text-align:center;margin-top:20px;"><a href="patient_lookup.php" class="btn-back btn">Back to
                    search</a></div>
            <?php
        } else {
            // Render each found report
            while ($row = $query->fetch_assoc()) {
                $report_id = $row['id'];
                $stmt_full = $conn->prepare("SELECT * FROM reports WHERE id = ?");
                $stmt_full->bind_param('i', $report_id);
                $stmt_full->execute();
                $report_full = $stmt_full->get_result()->fetch_assoc();
                if (!$report_full) {
                    continue;
                }

                $is_pending = isset($report_full['report_status']) && strtolower(trim($report_full['report_status'])) === 'pending';
                ?>

                <div class="print-container">
                    <div class="header">
                        <h1>Histopathology Report</h1>
                    </div>

                    <?php if ($is_pending): ?>
                        <div class="alert alert-warning" style="margin: 20px 0;">
                            Your report <?php echo htmlspecialchars($report_full['record_number'] . '/' . $report_full['report_year']); ?> is not ready yet.
                        </div>

                        <?php if (!empty($report_full['comment'])): ?>
                            <div class="comments-section">
                                <div class="field-label">Comment from the Pathologist:</div>
                                <div class="field-content"><?php echo nl2br(htmlspecialchars($report_full['comment'])); ?></div>
                            </div>
                        <?php endif; ?>

                        <div class="action-buttons">
                            <button class="btn btn-back" onclick="window.location.href='patient_lookup.php'">← Go Back</button>
                        </div>
                    <?php else: ?>
                        <div class="top-section">
                            <div class="top-col">
                                <div class="top-row">
                                    <span class="top-label">HistoPathology Number:</span>
                                    <span class="top-value"><?php echo htmlspecialchars($report_full['record_number']); ?></span>
                                </div>
                                <div class="top-row">
                                    <span class="top-label">Patient Name:</span>
                                    <span
                                        class="top-value"><?php echo htmlspecialchars(trim($report_full['first_name'] . ' ' . $report_full['middle_name'] . ' ' . $report_full['last_name'])); ?></span>
                                </div>
                                <div class="top-row">
                                    <span class="top-label">Gender:</span>
                                    <span class="top-value"><?php echo htmlspecialchars($report_full['gender']); ?></span>
                                </div>
                                <div class="top-row">
                                    <span class="top-label">Referring Physician:</span>
                                    <span
                                        class="top-value"><?php echo htmlspecialchars($report_full['referring_physician']); ?></span>
                                </div>
                            </div>
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
                            <div class="top-col">
                                <div><span class="top-label">Year:</span>
                                    <span><?php echo htmlspecialchars($report_full['report_year']); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="field-label">Clinical Features:</div>
                        <div class="field-content"><?php echo nl2br(htmlspecialchars($report_full['clinical_features'])); ?></div>

                        <div class="two-col-section" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <div>
                                <div class="field-label">Biopsy Site:</div>
                                <div class="field-content"><?php echo nl2br(htmlspecialchars($report_full['biopsy_site'])); ?></div>
                            </div>
                            <div>
                                <div class="field-label">Procedure Performed:</div>
                                <div class="field-content">
                                    <?php echo nl2br(htmlspecialchars($report_full['procedure_performed'])); ?>
                                </div>
                            </div>
                        </div>

                        <div class="field-label">Gross Description:</div>
                        <div class="field-content"><?php echo nl2br(htmlspecialchars($report_full['gross_description'])); ?></div>

                        <div class="two-col-section">
                            <div class="two-col-field">
                                <div class="field-label">Microscopic Description:</div>
                                <div class="field-content">
                                    <?php echo nl2br(htmlspecialchars($report_full['microscopic_description'])); ?>
                                </div>
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
                                <span
                                    class="path-value"><?php echo htmlspecialchars($report_full['consultant_pathologist']); ?></span>
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
                            <div class="note-text">The opinion/diagnosis is based on the tissue submitted and may not represent
                                entire lesion (depends on the nature of sampling and provided information) and should not be
                                interpreted in isolation. A correlation with clinical, radiological and other laboratory parameters
                                is strongly recommended before any therapeutic intervention. This report is made only for the
                                welfare of the patient, not for any legal purpose.</div>
                        </div>
                        <div class="action-buttons">
                            <button class="btn btn-print" onclick="window.print()">🖨️ Print / Save as PDF</button>
                            <button class="btn btn-back" onclick="window.location.href='patient_lookup.php'">← Go Back</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php
            }
        }
        ?>

    </div>

</body>

</html>