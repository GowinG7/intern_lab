<?php

include("auth_check.php");
include("../database/connection.php");

$currentPage = basename(__FILE__);

$user_id = $_SESSION['admin_id'] ?? 0;
$role = $_SESSION['role'] ?? '';

//Pagination
$limit = 10;

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

//role filter
if ($role === "admin") {
    $filter = "1=1";
} else {
    $filter = "created_by = " . (int) $user_id;
}

//total count
$countQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM reports
     WHERE $filter"
);

$totalRows = mysqli_fetch_assoc($countQuery)['total'] ?? 0;
$totalPages = ceil($totalRows / $limit);

//fetch data
$query = mysqli_query(
    $conn,
    "SELECT
        id,
        created_by,
        record_number,
        hospital_number,
        report_year,
        first_name,
        middle_name,
        last_name,
        biopsy_site,
        report_status,
        created_at
     FROM reports
     WHERE $filter
     ORDER BY created_at DESC
     LIMIT $limit OFFSET $offset"
);

?>

<!DOCTYPE html>

<html lang="en">

<head>
    <title>Total Reports</title>

    <?php include("../shared/links.php"); ?>

    <link rel="stylesheet" href="../css/total_reports.css">


</head>

<body>


    <?php include("header.php"); ?>

    <div class="container mt-3 container-box">

        <h4>Total Reports</h4>

        <!-- SEARCH SECTION -->

        <div class="card p-3 mb-3">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <div class="input-group">

                        <input type="text" id="liveSearchInput" class="form-control search-box"
                            placeholder="Search by Name / Record No / Hospital No / Year / Biopsy Site">

                        <button class="btn btn-primary" id="searchBtn">
                            Search
                        </button>

                    </div>

                </div>

                <div class="col-md-4 text-end">
                    <small id="searchResultInfo" class="text-muted"></small>
                </div>

            </div>

        </div>

        <!-- TABLE -->

        <table class="table table-bordered table-striped mt-3">

            <thead>

                <tr>
                    <th>SN.</th>
                    <th>Record No</th>
                    <th>Year</th>
                    <th>Patient Name</th>
                    <th>Hospital No</th>
                    <th>Biopsy Site</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

            </thead>

            <tbody id="reportTableBody">

                <?php

                $serial = $offset + 1;

                while ($row = mysqli_fetch_assoc($query)) {

                    $status = $row['report_status'];

                    ?>

                    <tr>

                        <td><?= $serial++ ?></td>

                        <td><?= htmlspecialchars($row['record_number']) ?></td>

                        <td><?= htmlspecialchars($row['report_year']) ?></td>

                        <td>
                            <?= htmlspecialchars(
                                trim(
                                    $row['first_name'] . ' ' .
                                    $row['middle_name'] . ' ' .
                                    $row['last_name']
                                )
                            ) ?>
                        </td>

                        <td><?= htmlspecialchars($row['hospital_number']) ?></td>

                        <td><?= htmlspecialchars($row['biopsy_site']) ?></td>

                        <td>
                            <span class="status-badge <?= $status ?>">
                                <?= htmlspecialchars($status) ?>
                            </span>
                        </td>


                        <td>

                            <a href="view_report_pdf.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
                                View
                            </a>

                        </td>
                    </tr>

                    <?php
                }
                ?>

            </tbody>

        </table>

        <!-- PAGINATION -->

        <div class="mt-3">

            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>

                <a href="?page=<?= $i ?>" class="btn btn-sm <?= ($i == $page) ? 'btn-primary' : 'btn-secondary' ?>">
                    <?= $i ?>
                </a>

            <?php } ?>

        </div>

    </div>


    <script>
        const USER_ROLE = "<?= $role ?>";
        const USER_ID = "<?= $user_id ?>";
    </script>

    <script src="../js/record_search.js"></script>

</body>

</html>
