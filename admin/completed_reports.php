<?php

include("auth_check.php");
include("../database/connection.php");

$currentPage = basename(__FILE__);

$user_id = $_SESSION['admin_id'] ?? 0;
$role = $_SESSION['role'] ?? '';

$limit = 10;

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

if ($page < 1) {
     $page = 1;
}

$offset = ($page - 1) * $limit;

/*

 FILTER

*/

if ($role === "admin") {

     $filter = "report_status='Completed'";

} else {

     $filter = "
        report_status='Completed'
        AND created_by=" . (int) $user_id;
}

/*

TOTAL COUNT

*/

$countQuery = mysqli_query(
     $conn,
     "SELECT COUNT(*) AS total
     FROM reports
     WHERE $filter"
);

$totalRows = mysqli_fetch_assoc($countQuery)['total'] ?? 0;

$totalPages = ceil($totalRows / $limit);

/*

FETCH DATA

*/

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

     <title>Completed Reports</title>

     <?php include("../shared/links.php"); ?>

     <style>
          body {
               background-color: #e5f0eb !important;
               min-height: 100vh;
               margin: 0;
               padding-top: 90px;
               /* gives space below header */
          }

          .container-box {
               background: #fff;
               padding: 25px;
               border-radius: 10px;
               box-shadow: 0 5px 20px rgba(0, 0, 0, .08);
               margin-top: 20px;
          }

          .search-box {
               max-width: 500px;
          }

          .status-badge {
               padding: 5px 10px;
               border-radius: 12px;
               font-size: 12px;
               color: #fff;
          }

          .Completed {
               background: green;
          }
     </style>

</head>

<body>

     <?php include("header.php"); ?>

     <div class="container mt-3 container-box">

          <h4>Completed Reports</h4>

          <!-- SEARCH -->

          <div class="card p-3 mb-3">

               <div class="row align-items-center">

                    <div class="col-md-8">

                         <div class="input-group">

                              <input type="text" id="liveSearchInput" class="form-control search-box"
                                   placeholder="Search completed reports">

                              <button class="btn btn-primary" id="searchBtn">

                                   Search

                              </button>

                         </div>

                    </div>

                    <div class="col-md-4 text-end">

                         <small id="searchResultInfo" class="text-muted">
                         </small>

                    </div>

               </div>

          </div>

          <!-- TABLE -->

          <table class="table table-bordered table-striped">

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
                                   <span class="status-badge Completed">
                                        <?= htmlspecialchars($row['report_status']) ?>
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

     <script src="../js/completed_record_search.js"></script>

</body>

</html>