<?php

include("auth_check.php");
include("../database/connection.php");

$currentPage = basename(__FILE__);

// Set default dates
$today = date('Y-m-d');
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-01'); // First day of month
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : $today;
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'All';

// Build WHERE clause for date filtering
$date_where = "AND DATE(created_at) BETWEEN '$from_date' AND '$to_date'";

/* Total Reports */

$total_where = "WHERE 1=1 $date_where";
if ($status_filter !== 'All') {
    $total_where .= " AND report_status='$status_filter'";
}

$total_query = mysqli_query(
    $conn,
    "SELECT * FROM reports $total_where"
);

$total_reports = mysqli_num_rows($total_query);



/* Completed Reports */

$completed_query = mysqli_query(
    $conn,
    "SELECT * FROM reports
     WHERE report_status='Completed' $date_where"
);

$completed_reports = mysqli_num_rows($completed_query);



/* Pending Reports */

$pending_query = mysqli_query(
    $conn,
    "SELECT * FROM reports
     WHERE report_status='Pending' $date_where"
);

$pending_reports = mysqli_num_rows($pending_query);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>

    <?php include("../shared/links.php"); ?>

    <link rel="stylesheet" href="../css/dashboard.css">

    <style>
        /* Filter Analytics Section */
        .filter-section {
            background: white;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .filter-section h3 {
            color: #1e293b;
            font-weight: 700;
            margin-bottom: 12px;
            font-size: 16px;
        }

        .filter-controls {
            display: flex;
            flex-wrap: nowrap;
            gap: 12px;
            align-items: flex-end;
            margin-bottom: 0;
            overflow-x: auto;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 3px;
            min-width: 110px;
        }

        .filter-group label {
            font-weight: 600;
            color: #495057;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .filter-group input,
        .filter-group select {
            padding: 8px 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.2);
        }

        .filter-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: nowrap;
            align-items: flex-end;
        }

        .filter-buttons .btn {
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 5px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-apply {
            background-color: #0d6efd;
            color: white;
        }

        .btn-apply:hover {
            background-color: #0b5ed7;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
        }

        .btn-reset {
            background-color: #6c757d;
            color: white;
        }

        .btn-reset:hover {
            background-color: #5a6268;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(108, 117, 125, 0.3);
        }

        .quick-filters {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #e9ecef;
        }

        .quick-filter-btn {
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 16px;
            border: 1px solid #dee2e6;
            background-color: #f8f9fa;
            color: #495057;
            transition: all 0.2s ease;
            cursor: pointer;
            white-space: nowrap;
        }

        .quick-filter-btn:hover {
            background-color: #e9ecef;
            border-color: #0d6efd;
            color: #0d6efd;
        }

        .filter-info {
            color: #6c757d;
            font-size: 12px;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid #e9ecef;
        }

        @media (max-width: 1024px) {
            .filter-controls {
                overflow-x: auto;
            }

            .filter-group {
                min-width: 100px;
            }
        }

        @media (max-width: 768px) {
            .filter-controls {
                flex-wrap: wrap;
            }

            .filter-group {
                min-width: 80px;
                flex: 1;
            }

            .filter-buttons {
                flex-wrap: wrap;
                width: 100%;
            }

            .filter-buttons .btn {
                flex: 1;
                min-width: 80px;
            }
        }
    </style>

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
                                <option value="All" <?php echo $status_filter === 'All' ? 'selected' : ''; ?>>All Categories</option>
                                <option value="Completed" <?php echo $status_filter === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="Pending" <?php echo $status_filter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            </select>
                        </div>

                        <div class="filter-buttons">
                            <button type="submit" class="btn btn-apply">Apply Filter</button>
                            <a href="dashboard.php" class="btn btn-reset">Reset</a>
                        </div>
                    </div>

                    <!-- Quick Filters -->
                    <div class="quick-filters">
                        <button type="button" class="quick-filter-btn" onclick="setDateFilter('today')">Today</button>
                        <button type="button" class="quick-filter-btn" onclick="setDateFilter('last7d')">Last 7d</button>
                        <button type="button" class="quick-filter-btn" onclick="setDateFilter('last30d')">Last 30d</button>
                        <button type="button" class="quick-filter-btn" onclick="setDateFilter('thismonth')">This Month</button>
                    </div>

                    <!-- Filter Info -->
                    <div class="filter-info">
                        Showing data from <strong><?php echo date('M d, Y', strtotime($from_date)); ?></strong> to <strong><?php echo date('M d, Y', strtotime($to_date)); ?></strong>
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

    <script>
        function setDateFilter(filterType) {
            const today = new Date();
            let fromDate, toDate;

            toDate = today;

            switch(filterType) {
                case 'today':
                    fromDate = new Date(today);
                    break;
                case 'last7d':
                    fromDate = new Date(today);
                    fromDate.setDate(fromDate.getDate() - 7);
                    break;
                case 'last30d':
                    fromDate = new Date(today);
                    fromDate.setDate(fromDate.getDate() - 30);
                    break;
                case 'thismonth':
                    fromDate = new Date(today.getFullYear(), today.getMonth(), 1);
                    break;
            }

            // Format dates as YYYY-MM-DD
            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };

            document.getElementById('from_date').value = formatDate(fromDate);
            document.getElementById('to_date').value = formatDate(toDate);

            // Submit form automatically
            document.querySelector('.filter-form').submit();
        }
    </script>

</body>

</html>