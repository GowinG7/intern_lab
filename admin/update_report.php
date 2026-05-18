<?php

include("auth_check.php");
include("../database/connection.php");

if (isset($_POST['update_report'])) {
    $id = $_POST['id'];

    $diagnosis = $_POST['diagnosis'];

    $microscopic_description =
        $_POST['microscopic_description'];

    $report_status = $_POST['report_status'];

    mysqli_query(
        $conn,

        "UPDATE reports SET

    diagnosis='$diagnosis',

    microscopic_description='$microscopic_description',

    report_status='$report_status'

    WHERE id='$id'
    "
    );

    header("location: manage_reports.php");
}

?>