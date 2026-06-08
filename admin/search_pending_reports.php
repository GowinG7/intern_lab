<?php

session_start();
include("../database/connection.php");

header("Content-Type: application/json; charset=UTF-8");

/*

SESSION CHECK

*/

if (!isset($_SESSION['admin_id'])) {

    echo json_encode([
        "success" => false,
        "message" => "Unauthorized"
    ]);

    exit;
}

/*

INPUT

*/

$rawInput = file_get_contents("php://input");
$input = json_decode($rawInput, true);

if (!is_array($input)) {
    $input = [];
}

$search = trim($input['search'] ?? '');

/*

USER INFO

*/

$user_id = $_SESSION['admin_id'] ?? 0;
$role = $_SESSION['role'] ?? '';

/*

BASE FILTER

*/

if ($role === "admin") {

    $where = "report_status = 'pending'";

} else {

    $where = "
        report_status = 'pending'
        AND created_by = " . (int) $user_id;
}

/*

SEARCH FILTER

*/

if (!empty($search)) {

    $safe = mysqli_real_escape_string($conn, $search);

    $where .= " AND (

        first_name LIKE '%$safe%' OR

        middle_name LIKE '%$safe%' OR

        last_name LIKE '%$safe%' OR

        record_number LIKE '%$safe%' OR

        hospital_number LIKE '%$safe%' OR

        biopsy_site LIKE '%$safe%' OR

        report_year LIKE '%$safe%'

    )";
}

/*

QUERY

*/

$sql = "

SELECT

    id,
    record_number,
    report_year,
    first_name,
    middle_name,
    last_name,
    hospital_number,
    biopsy_site,
    report_status

FROM reports

WHERE $where

ORDER BY
    report_year ASC,
    CAST(SUBSTRING(record_number, 2) AS UNSIGNED) ASC

LIMIT 100

";

$result = mysqli_query($conn, $sql);

/*

QUERY ERROR

*/

if (!$result) {

    echo json_encode([
        "success" => false,
        "message" => mysqli_error($conn)
    ]);

    exit;
}

/*
 RESPONSE DATA

*/

$data = [];

while ($row = mysqli_fetch_assoc($result)) {

    $row['full_name'] = trim(

        $row['first_name'] . ' ' .
        $row['middle_name'] . ' ' .
        $row['last_name']

    );

    $data[] = $row;
}

/*

JSON RESPONSE

*/

echo json_encode([

    "success" => true,

    "count" => count($data),

    "data" => $data

]);