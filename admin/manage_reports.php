<?php

include("auth_check.php");
include("../database/connection.php");

$currentPage = basename(__FILE__);

// Handle single-record view
$whereClause = "1";

if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
     // Show only the just-saved record when redirected with ?id=...
     $id = (int) $_GET['id'];
     $whereClause = "id = $id";
} else {
}

// pagination settings
$limit = 10;
$page = isset($_GET['page']) && ctype_digit($_GET['page']) && (int) $_GET['page'] > 0 ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// count matching rows
$countSql = "SELECT COUNT(*) AS total FROM `reports` WHERE $whereClause";
$countResult = mysqli_query($conn, $countSql);
$totalRows = ($countResult) ? (int) mysqli_fetch_assoc($countResult)['total'] : 0;
$totalPages = ($totalRows > 0) ? (int) ceil($totalRows / $limit) : 1;

// main query with limit/offset
$sql = "SELECT `id`, `histopathology_number`, `record_number`, `hospital_number`, `report_year`, `first_name`, `middle_name`, `last_name`, `gender`, `age`, `date_receipt`, `date_dispatch`, `referring_physician`, `clinical_features`, `biopsy_site`, `procedure_performed`, `gross_description`, `microscopic_description`, `diagnosis`, `pathologist`, `consultant_pathologist`, `report_status`, `comment`, `created_at` FROM `reports` WHERE $whereClause ORDER BY report_year ASC, CAST(SUBSTRING(record_number, 2) AS UNSIGNED) ASC LIMIT $limit OFFSET $offset";

$query = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

     <title>Manage Reports</title>

     <?php include("../shared/links.php"); ?>

     <link rel="stylesheet" href="../css/style.css">

     <style>
          .search-container {
               background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
               padding: 20px;
               border-radius: 10px;
               box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
               margin-bottom: 25px;
          }

          .search-container form {
               display: flex;
               gap: 10px;
               align-items: center;
               flex-wrap: wrap;
          }

          .search-container input[type="text"] {
               padding: 10px 15px;
               border: 2px solid #dee2e6;
               border-radius: 6px;
               font-size: 14px;
               transition: all 0.3s ease;
               flex: 1;
               min-width: 320px;
               width: 100%;
               max-width: 620px;
          }

          .search-container input[type="text"]:focus {
               outline: none;
               border-color: #0d6efd;
               box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
               background-color: #fff;
          }

          .search-container .btn {
               padding: 10px 20px;
               font-weight: 600;
               border-radius: 6px;
               transition: all 0.3s ease;
               cursor: pointer;
          }

          .search-container .btn-primary {
               background-color: #0d6efd;
               border: none;
               color: white;
          }

          .search-container .btn-primary:hover {
               background-color: #0b5ed7;
               transform: translateY(-2px);
               box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
          }

          .search-container .btn-secondary {
               background-color: #6c757d;
               border: none;
               color: white;
               text-decoration: none;
          }

          .search-container .btn-secondary:hover {
               background-color: #5a6268;
               transform: translateY(-2px);
               box-shadow: 0 4px 12px rgba(108, 117, 125, 0.4);
          }

          .search-container .btn-search {
               min-width: 120px;
               white-space: nowrap;
          }

          .search-container .search-field-wrap {
               flex: 1;
               min-width: 320px;
               max-width: 620px;
          }

          .search-info {
               font-size: 13px;
               color: #6c757d;
               margin-top: 10px;
               font-weight: 500;
          }
     </style>

</head>

<body>
     <?php include_once("header.php"); ?>

     <div class="admin-content">
          <div class="container">

               <?php if (isset($_GET['saved']) && $_GET['saved'] == 1): ?>
                    <div id="savedAlert" class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                         Report Saved Successfully
                         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <script>
                         setTimeout(function () {
                              const alertBox = document.getElementById('savedAlert');

                              if (alertBox) {
                                   alertBox.classList.remove('show');
                                   setTimeout(function () {
                                        alertBox.remove();
                                   }, 300);
                              }
                         }, 7000);
                    </script>
               <?php endif; ?>

               <div class="search-container">
                    <form onsubmit="return false;">
                         <div class="search-field-wrap">
                              <input type="text" id="liveSearchInput" class="form-control"
                                   placeholder="🔍 Search by year, biopsy site, patient name, record number, or hospital number...">
                         </div>
                         <button type="button" class="btn btn-primary btn-search">Search</button>
                    </form>
                    <div id="searchResultInfo" class="search-info"></div>
               </div>

               <table class="table table-bordered table-striped">
                    <thead>
                         <tr>
                              <th>ID</th>
                              <th>Record Number</th>
                              <th>Year</th>
                              <th>Patient Name</th>
                              <th>Hospital Number</th>
                              <th>Biopsy Site</th>
                              <th>Status</th>
                              <th>Action</th>
                         </tr>
                    </thead>
                    <tbody>
                         <?php

                         $counter = $offset + 1;
                         while ($row = mysqli_fetch_assoc($query)) {
                              ?>

                              <tr>

                                   <td><?php echo $counter; ?></td>

                                   <td><?php echo $row['record_number']; ?></td>
                                   <td>
                                        <?php
                                        echo $row['report_year']
                                             ?>
                                   </td>

                                   <td>
                                        <?php
                                        echo $row['first_name'] . " " . $row['middle_name'] . " " .
                                             $row['last_name'];
                                        ?>
                                   </td>


                                   <td>
                                        <?php
                                        echo $row['hospital_number']
                                             ?>
                                   </td>

                                   <td>
                                        <?php
                                        echo $row['biopsy_site']
                                             ?>
                                   </td>

                                   <td>

                                        <?php echo $row['report_status']; ?>

                                   </td>

                                   <td>

                                        <a href="view_report_pdf.php?id=<?php echo $row['id']; ?>"
                                             class="btn btn-warning btn-sm">

                                             View

                                        </a>

                                        <a href="edit_report.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">

                                             Edit

                                        </a>

                                        <a href="delete_report.php?id=<?php echo $row['id']; ?>"
                                             class="btn btn-danger btn-sm" onclick="return confirm('Delete this report?')">

                                             Delete

                                        </a>

                                   </td>

                              </tr>

                              <?php
                              $counter++;
                         }
                         ?>
                    </tbody>

               </table>
               <?php if ($totalPages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-3">
                         <ul class="pagination">
                              <?php
                              $params = $_GET;
                              for ($p = 1; $p <= $totalPages; $p++):
                                   $params['page'] = $p;
                                   $url = 'manage_reports.php?' . http_build_query($params);
                                   ?>
                                   <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= htmlspecialchars($url) ?>"><?= $p ?></a>
                                   </li>
                              <?php endfor; ?>
                         </ul>
                    </nav>
               <?php endif; ?>
          </div>
     </div>

     <script src="../js/live_search.js"></script>

</body>

</html>