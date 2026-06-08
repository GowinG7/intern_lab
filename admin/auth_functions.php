<?php

function canEditDeleteReport($conn, $report_id, $session_user_id)
{
    $stmt = mysqli_prepare(
        $conn,
        "SELECT created_by FROM reports WHERE id = ?"
    );

    if (!$stmt)
        return false;

    mysqli_stmt_bind_param($stmt, "i", $report_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (!$row)
        return false;

    return ((int) $row['created_by'] === (int) $session_user_id);
}