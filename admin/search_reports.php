<?php

session_start();
include("../database/connection.php");

header("Content-Type: application/json; charset=UTF-8");

/* 
   SESSION SAFETY CHECK
 */
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['role'])) {
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized access"
    ]);
    exit;
}

$user_id = (int) $_SESSION['admin_id'];
$role = $_SESSION['role'];

/* 
   READ INPUT SAFELY
 */
$rawInput = file_get_contents("php://input");
$input = json_decode($rawInput, true);

if (!is_array($input)) {
    $input = [];
}

$search = isset($input['search']) ? trim($input['search']) : "";

/* 
   BASE ROLE FILTER
 */
if ($role === "admin") {
    $where = "1=1";
} else {
    $where = "created_by = $user_id";
}

/* 
   SEARCH FILTER
 */
if (!empty($search)) {

    $safe = mysqli_real_escape_string($conn, $search);

    $searchCondition = "(
        first_name LIKE '%$safe%' OR
        middle_name LIKE '%$safe%' OR
        last_name LIKE '%$safe%' OR
        record_number LIKE '%$safe%' OR
        hospital_number LIKE '%$safe%' OR
        biopsy_site LIKE '%$safe%' OR
        report_year LIKE '%$safe%'
    )";

    $where .= " AND " . $searchCondition;
}

/* 
   FINAL QUERY
 */
$sql = "SELECT 
            id,
            created_by,
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
        ORDER BY id DESC
        LIMIT 100";

$result = mysqli_query($conn, $sql);

/* 
   SQL ERROR DEBUG (IMPORTANT)
 */
if (!$result) {
    echo json_encode([
        "success" => false,
        "message" => "SQL Error",
        "error" => mysqli_error($conn)
    ]);
    exit;
}

/* 
   BUILD RESPONSE
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
   OUTPUT JSON
 */
echo json_encode([
    "success" => true,
    "count" => count($data),
    "data" => $data
]);