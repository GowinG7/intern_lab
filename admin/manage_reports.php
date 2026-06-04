<?php
session_start();
include("auth_check.php");
include("../database/connection.php");

$currentPage = basename(__FILE__);

$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1)
     $page = 1;

$offset = ($page - 1) * $limit;

/* total count */
$countSql = "SELECT COUNT(*) as total FROM reports";
$countResult = mysqli_query($conn, $countSql);
$totalRows = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRows / $limit);

/* fetch page data */
$sql = "SELECT id, record_number, report_year,
        first_name, middle_name, last_name,
        hospital_number, biopsy_site, report_status
        FROM reports
        ORDER BY id DESC
        LIMIT $limit OFFSET $offset";

$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>
     <title>Manage Reports</title>
     <?php include("../shared/links.php"); ?>
</head>

<body>
     <?php include("header.php"); ?>

     <div class="container mt-3">

          <!-- SEARCH -->
          <input type="text" id="liveSearchInput" class="form-control"
               placeholder="Search by Name / Record No / Hospital No / Year / Biopsy Site">

          <div id="searchResultInfo" class="mt-2 text-muted"></div>

          <!-- TABLE -->
          <table class="table table-bordered mt-3">
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
                                   <a href="edit_report.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">Edit</a>
                                   <a href="delete_report.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                              </td>
                         </tr>
                    <?php } ?>
               </tbody>
          </table>

          <!-- PAGINATION -->
          <nav>
               <ul class="pagination">
                    <?php for ($p = 1; $p <= $totalPages; $p++) { ?>
                         <li class="page-item <?= ($p == $page) ? 'active' : '' ?>">
                              <a class="page-link" href="?page=<?= $p ?>">
                                   <?= $p ?>
                              </a>
                         </li>
                    <?php } ?>
               </ul>
          </nav>

     </div>

     <script src="../js/record_search.js"></script>
</body>

</html>