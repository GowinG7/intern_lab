<?php

include("auth_check.php");
include("../database/connection.php");

if (isset($_POST['update_report'])) {
    $id = $_POST['id'];

    $histopathology_number = $_POST['histopathology_number'];
    $hospital_number = $_POST['hospital_number'];
    $report_year = $_POST['report_year'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $date_receipt = $_POST['date_receipt'];
    $date_dispatch = $_POST['date_dispatch'];
    $referring_physician = $_POST['referring_physician'];
    $clinical_features = $_POST['clinical_features'];
    $biopsy_site = $_POST['biopsy_site'];
    $procedure_performed = $_POST['procedure_performed'];
    $gross_description = $_POST['gross_description'];
    $microscopic_description = $_POST['microscopic_description'];
    $diagnosis = $_POST['diagnosis'];
    $pathologist = $_POST['pathologist'];
    $consultant_pathologist = $_POST['consultant_pathologist'];
    $report_status = $_POST['report_status'];
    $comment = $_POST['comment'];

    mysqli_query(
        $conn,
        "UPDATE reports SET
        histopathology_number='$histopathology_number',
        hospital_number='$hospital_number',
        report_year='$report_year',
        first_name='$first_name',
        middle_name='$middle_name',
        last_name='$last_name',
        gender='$gender',
        age='$age',
        date_receipt='$date_receipt',
        date_dispatch='$date_dispatch',
        referring_physician='$referring_physician',
        clinical_features='$clinical_features',
        biopsy_site='$biopsy_site',
        procedure_performed='$procedure_performed',
        gross_description='$gross_description',
        microscopic_description='$microscopic_description',
        diagnosis='$diagnosis',
        pathologist='$pathologist',
        consultant_pathologist='$consultant_pathologist',
        report_status='$report_status',
        comment='$comment'
        WHERE id='$id'"
    );

    header("location: manage_reports.php");
}

?>