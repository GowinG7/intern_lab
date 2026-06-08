<?php
session_start();
include("../database/connection.php");

/* 🔒 must be logged in */
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 👤 logged-in user (creator)
    $created_by = $_SESSION['admin_id'];


    // INPUT DATA

    $histopathology_number = $_POST['histopathology_number'] ?? '';
    $record_number = $_POST['record_number'] ?? '';
    $hospital_number = $_POST['hospital_number'] ?? '';
    $year = $_POST['year'] ?? '';

    $first_name = $_POST['first_name'] ?? '';
    $middle_name = $_POST['middle_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $age = $_POST['age'] ?? '';
    $date_receipt = $_POST['date_receipt'] ?? '';
    $date_dispatch = $_POST['date_dispatch'] ?? '';
    $referring_physician = $_POST['referring_physician'] ?? '';
    $clinical_features = $_POST['clinical_features'] ?? '';
    $biopsy_site = $_POST['biopsy_site'] ?? '';
    $procedure_performed = $_POST['procedure_performed'] ?? '';
    $gross_description = $_POST['gross_description'] ?? '';
    $microscopic_description = $_POST['microscopic_description'] ?? '';
    $diagnosis = $_POST['diagnosis'] ?? '';


    $pathologist = '';

    if (
        isset($_POST['patho']) &&
        !empty($_POST['pathologist'])
    ) {
        $pathologist = trim($_POST['pathologist']);
    }

    $consultant_pathologist = '';

    if (
        isset($_POST['consultant_patho']) &&
        !empty($_POST['consultant_pathologist'])
    ) {
        $consultant_pathologist = trim($_POST['consultant_pathologist']);
    }


    $report_status = $_POST['report_status'] ?? '';
    $comment = $_POST['comment'] ?? '';


    // 1. DUPLICATE CHECK

    $checkQuery = mysqli_prepare(
        $conn,
        "SELECT id FROM reports WHERE record_number = ? AND report_year = ? LIMIT 1"
    );

    mysqli_stmt_bind_param($checkQuery, "ss", $record_number, $year);
    mysqli_stmt_execute($checkQuery);
    $resultCheck = mysqli_stmt_get_result($checkQuery);

    if (mysqli_fetch_assoc($resultCheck)) {

        mysqli_stmt_close($checkQuery);

        $_SESSION['error'] = "Duplicate record number found.";
        header("Location: report_form.php?duplicate=1");
        exit;
    }

    mysqli_stmt_close($checkQuery);


    // FINAL SAFETY CHECK (VERY IMPORTANT)
    $checkHospital = mysqli_prepare(
        $conn,
        "SELECT id FROM reports WHERE hospital_number = ? LIMIT 1"
    );

    mysqli_stmt_bind_param($checkHospital, "s", $hospital_number);
    mysqli_stmt_execute($checkHospital);
    $res = mysqli_stmt_get_result($checkHospital);

    if (mysqli_fetch_assoc($res)) {
        mysqli_stmt_close($checkHospital);

        $_SESSION['error'] = "Hospital number already exists.";
        header("Location: report_form.php?hospital_duplicate=1");
        exit;
    }

    mysqli_stmt_close($checkHospital);

    // 2. INSERT QUERY

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO reports (
            histopathology_number,
            record_number,
            hospital_number,
            report_year,
            first_name,
            middle_name,
            last_name,
            gender,
            age,
            date_receipt,
            date_dispatch,
            referring_physician,
            clinical_features,
            biopsy_site,
            procedure_performed,
            gross_description,
            microscopic_description,
            diagnosis,
            pathologist,
            consultant_pathologist,
            report_status,
            comment,
            created_by,
            created_at
        )
        VALUES (
            ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW()
        )"
    );

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssssssssssssssssi",
        $histopathology_number,
        $record_number,
        $hospital_number,
        $year,
        $first_name,
        $middle_name,
        $last_name,
        $gender,
        $age,
        $date_receipt,
        $date_dispatch,
        $referring_physician,
        $clinical_features,
        $biopsy_site,
        $procedure_performed,
        $gross_description,
        $microscopic_description,
        $diagnosis,
        $pathologist,
        $consultant_pathologist,
        $report_status,
        $comment,
        $created_by
    );

    // 
    // 3. EXECUTE
    // 
    if (mysqli_stmt_execute($stmt)) {

        $newId = mysqli_insert_id($conn);

        $_SESSION['success'] = "Report Saved Successfully";

        mysqli_stmt_close($stmt);

        header("Location: manage_reports.php?id=" . $newId);
        exit;

    } else {
        die("Insert failed: " . mysqli_stmt_error($stmt));
    }
}
?>