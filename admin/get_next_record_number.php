<?php

header('Content-Type: application/json');

include("auth_check.php");
include("../database/connection.php");

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['histopathology_number']) || !isset($input['year'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required parameters'
    ]);
    exit;
}

$histopathology_number = $input['histopathology_number'];
$year = $input['year'];

// Validate inputs
if (!preg_match('/^[A-Z]$/', $histopathology_number)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid histopathology number'
    ]);
    exit;
}

if (!preg_match('/^\d{4}$/', $year)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid year'
    ]);
    exit;
}

// Query to find the last record number for this letter and year
$query = mysqli_query(
    $conn,
    "SELECT MAX(CAST(record_number AS UNSIGNED)) as max_record_number 
     FROM reports 
     WHERE histopathology_number = '$histopathology_number' 
     AND report_year = '$year'"
);

if (!$query) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . mysqli_error($conn)
    ]);
    exit;
}

$result = mysqli_fetch_assoc($query);
$max_record_number = $result['max_record_number'];

// Calculate next record number
// Start from 4000 if no records exist for this series
$next_record_number = $max_record_number ? ($max_record_number + 1) : 4000;

echo json_encode([
    'success' => true,
    'next_record_number' => $next_record_number,
    'message' => 'Record number generated successfully'
]);

?>
