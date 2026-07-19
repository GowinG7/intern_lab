<?php

include("auth_check.php");
include("../database/connection.php");
include("auth_functions.php");

$currentPage = basename(__FILE__);

$user_id = $_SESSION['admin_id'];
$role = $_SESSION['role'];

$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1)
     $page = 1;

$offset = ($page - 1) * $limit;

/* 
   TOTAL COUNT
 */
if ($role == "admin") {
     $countSql = "SELECT COUNT(*) as total FROM reports";
} else {
     $countSql = "SELECT COUNT(*) as total FROM reports WHERE created_by = $user_id";
}

$countResult = mysqli_query($conn, $countSql);
$totalRows = mysqli_fetch_assoc($countResult)['total'] ?? 0;

$totalPages = ceil($totalRows / $limit);

/* 
   DATA FETCH
 */
if ($role == "admin") {

     $sql = "SELECT id, created_by, record_number, report_year,
            first_name, middle_name, last_name,
            hospital_number, biopsy_site, report_status
            FROM reports
            ORDER BY id DESC
            LIMIT $limit OFFSET $offset";

} else {

     $sql = "SELECT id, created_by, record_number, report_year,
            first_name, middle_name, last_name,
            hospital_number, biopsy_site, report_status
            FROM reports
            WHERE created_by = $user_id
            ORDER BY id DESC
            LIMIT $limit OFFSET $offset";
}

$query = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>

<head>
     <title>Manage Reports</title>
     <?php include("../shared/links.php"); ?>

     <link rel="stylesheet" href="../css/manage_reports.css">
</head>

<body>

     <?php include("header.php"); ?>

     <div class="container mt-3">

          <!-- SEARCH BAR SECTION -->
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
          <!-- 
         TABLE SECTION
    = -->
          <table class="table table-bordered table-striped">

               <thead>
                    <tr>
                         <th>S.No</th>
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
                              <td>
                                   <?= $serial++ ?>
                              </td>
                              <td>
                                   <?= $row['record_number'] ?>
                              </td>
                              <td>
                                   <?= $row['report_year'] ?>
                              </td>
                              <td>
                                   <?= $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] ?>
                              </td>
                              <td>
                                   <?= $row['hospital_number'] ?>
                              </td>
                              <td>
                                   <?= $row['biopsy_site'] ?>
                              </td>
                              <td>
                                   <?= $row['report_status'] ?>
                              </td>

                              <td>
                                   <a href="view_report_pdf.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">View</a>

                                   <?php if (canEditDeleteReport($conn, $row['id'], $_SESSION['admin_id'])) { ?>

                                        <a href="edit_report.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">Edit</a>

                                        <a href="delete_report.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                                             onclick="return confirm('Delete this report?')">
                                             Delete
                                        </a>

                                   <?php } ?>
                              </td>
                         </tr>

                    <?php } ?>

               </tbody>

          </table>

          <!-- 
         PAGINATION
    = -->
          <div class="mt-3">

               <?php for ($i = 1; $i <= $totalPages; $i++) { ?>

                    <a href="?page=<?= $i ?>" class="btn btn-sm <?= ($i == $page) ? 'btn-primary' : 'btn-secondary' ?>">
                         <?= $i ?>
                    </a>

               <?php } ?>

          </div>

     </div>

     <!-- 
     JS VARIABLES + SCRIPT
 -->
     <script>
          const USER_ROLE = "<?= $role ?>";
          const USER_ID = "<?= $user_id ?>";
     </script>

     <script src="../js/record_search.js"></script>

</body>

</html>
