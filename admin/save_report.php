<?php

include("../database/connection.php");

if (isset($_POST['save_report'])) {

    $histopathology_number = $_POST['histopathology_number'];

    $record_number = $_POST['record_number'];

    $hospital_number = $_POST['hospital_number'];

    $year = $_POST['year'];

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



    $query = "INSERT INTO reports(

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
    created_at

    )

    VALUES(

    '$histopathology_number',
    '$record_number',
    '$hospital_number',
    '$year',
    '$first_name',
    '$middle_name',
    '$last_name',
    '$gender',
    '$age',
    '$date_receipt',
    '$date_dispatch',
    '$referring_physician',
    '$clinical_features',
    '$biopsy_site',
    '$procedure_performed',
    '$gross_description',
    '$microscopic_description',
    '$diagnosis',
    '$pathologist',
    '$consultant_pathologist',
    '$report_status',
    '$comment',
    NOW()

    )";



    $result = mysqli_query($conn, $query);



    if ($result) {
        echo "
        <script>

        alert('Report Saved Successfully');

        window.location.href='manage_reports.php';

        </script>
        ";
    } else {
        echo mysqli_error($conn);
    }

}

?>