<?php

include("auth_check.php");
include("../database/connection.php");

$user_id = $_SESSION['admin_id'] ?? 0;
$role = $_SESSION['role'] ?? '';

// Dates
$today = date('Y-m-d');
$from_date = $_GET['from_date'] ?? date('Y-m-01');
$to_date = $_GET['to_date'] ?? $today;
$status_filter = $_GET['status'] ?? 'All';

// Base condition (ROLE FILTER)
if ($role === "admin") {
    $base_filter = "1=1";
} else {
    $base_filter = "created_by = " . (int) $user_id;
}

// DATE FILTER (safe string)
$date_filter = "AND DATE(created_at) BETWEEN '$from_date' AND '$to_date'";

/*  TOTAL REPORTS  */

$total_where = "WHERE $base_filter $date_filter";

if ($status_filter !== 'All') {
    $total_where .= " AND report_status = '" . mysqli_real_escape_string($conn, $status_filter) . "'";
}

$total_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM reports $total_where"
);

$total_reports = mysqli_fetch_assoc($total_query)['total'] ?? 0;


/*  COMPLETED REPORTS  */

$completed_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM reports
     WHERE $base_filter
     AND report_status='Completed'
     $date_filter"
);

$completed_reports = mysqli_fetch_assoc($completed_query)['total'] ?? 0;


/*  PENDING REPORTS  */

$pending_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM reports
     WHERE $base_filter
     AND report_status='Pending'
     $date_filter"
);

$pending_reports = mysqli_fetch_assoc($pending_query)['total'] ?? 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>

    <?php include("../shared/links.php"); ?>

    <link rel="stylesheet" href="../css/dashboard.css">

</head>

<body>
    <?php include_once("header.php"); ?>

    <div class="admin-content">
        <div class="container">

            <!-- <div class="mb-4">

                <a href="report_form.php" class="btn btn-primary me-2">

                    Add Report

                </a>

                <a href="manage_reports.php" class="btn btn-dark">

                    Manage Reports

                </a>

            </div> -->

            <!-- Filter Analytics Section -->
            <div class="filter-section">
                <h3>Filter Analytics</h3>

                <form method="GET" class="filter-form">
                    <div class="filter-controls">
                        <div class="filter-group">
                            <label for="from_date">From Date</label>
                            <input type="date" id="from_date" name="from_date" value="<?php echo $from_date; ?>">
                        </div>

                        <div class="filter-group">
                            <label for="to_date">To Date</label>
                            <input type="date" id="to_date" name="to_date" value="<?php echo $to_date; ?>">
                        </div>

                        <div class="filter-group">
                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <option value="All" <?php echo $status_filter === 'All' ? 'selected' : ''; ?>>All
                                    Categories</option>
                                <option value="Completed" <?php echo $status_filter === 'Completed' ? 'selected' : ''; ?>>
                                    Completed</option>
                                <option value="Pending" <?php echo $status_filter === 'Pending' ? 'selected' : ''; ?>>
                                    Pending</option>
                            </select>
                        </div>

                        <div class="filter-buttons">
                            <button type="submit" class="btn btn-apply">Apply Filter</button>
                            <a href="dashboard.php" class="btn btn-reset">Reset</a>
                        </div>
                    </div>

                    <!-- Quick Filters -->
                    <div class="quick-filters">
                        <button type="button" class="quick-filter-btn" data-date-filter="today">Today</button>
                        <button type="button" class="quick-filter-btn" data-date-filter="last7d">Last
                            7d</button>
                        <button type="button" class="quick-filter-btn" data-date-filter="last30d">Last
                            30d</button>
                        <button type="button" class="quick-filter-btn" data-date-filter="thismonth">This
                            Month</button>
                    </div>

                    <!-- Filter Info -->
                    <div class="filter-info">
                        Showing data from <strong><?php echo date('M d, Y', strtotime($from_date)); ?></strong> to
                        <strong><?php echo date('M d, Y', strtotime($to_date)); ?></strong>
                        <?php if ($status_filter !== 'All') { ?>
                            | Status: <strong><?php echo $status_filter; ?></strong>
                        <?php } ?>
                    </div>
                </form>
            </div>

            <div class="row">

                <!-- Total Reports -->

                <div class="col-md-4 mb-3">

                    <div class="dashboard-card bg-blue">

                        <h4>Total Reports</h4>

                        <h2>

                            <?php echo $total_reports; ?>

                        </h2>

                    </div>

                </div>

                <!-- Completed Reports -->

                <div class="col-md-4 mb-3">

                    <div class="dashboard-card bg-green">

                        <h4>Completed Reports</h4>

                        <h2>

                            <?php echo $completed_reports; ?>

                        </h2>

                    </div>

                </div>

                <!-- Pending Reports -->

                <div class="col-md-4 mb-3">

                    <div class="dashboard-card bg-orange">

                        <h4>Pending Reports</h4>

                        <h2>

                            <?php echo $pending_reports; ?>

                        </h2>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <script src="../js/dashboard-filters.js"></script>

</body>

</html>
