<?php

include("auth_check.php");
include("../database/connection.php");

$currentPage = basename(__FILE__);

// Handle search
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$whereClause = "1";

if (!empty($searchTerm)) {
     $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
     $whereClause = "(
          CONCAT(first_name, ' ', last_name) LIKE '%$searchTerm%' OR 
          record_number LIKE '%$searchTerm%' OR 
          hospital_number LIKE '%$searchTerm%' OR
          report_year LIKE '%$searchTerm%'
     )";
}

$query = mysqli_query(
     $conn,
     "SELECT `id`, `histopathology_number`, `record_number`, `hospital_number`, `report_year`, `first_name`, `middle_name`, `last_name`, `gender`, `age`, `date_receipt`, `date_dispatch`, `referring_physician`, `clinical_features`, `biopsy_site`, `procedure_performed`, `gross_description`, `microscopic_description`, `diagnosis`, `pathologist`, `consultant_pathologist`, `report_status`, `comment`, `created_at` FROM `reports` WHERE $whereClause ORDER BY report_year ASC, CAST(SUBSTRING(record_number, 2) AS UNSIGNED) ASC"
);

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
               min-width: 250px;
               max-width: 450px;
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

               <div class="search-container">
                    <form method="GET" action="manage_reports.php">
                         <input 
                              type="text" 
                              id="liveSearchInput"
                              class="form-control" 
                              placeholder="🔍 Search by year, patient name, record number, or hospital number..." 
                              value="<?php echo htmlspecialchars($searchTerm); ?>">
                         <button type="submit" class="btn btn-primary">Search</button>
                         <?php if (!empty($searchTerm)): ?>
                              <a href="manage_reports.php" class="btn btn-secondary">Clear</a>
                         <?php endif; ?>
                    </form>
                    <div id="searchResultInfo" class="search-info"></div>
               </div>

               <table class="table table-bordered table-striped">

                    <tr>
                         <th>ID</th>
                         <th>Record Number</th>
                         <th>Year</th>
                         <th>Patient Name</th>

                         <th>Hospital Number</th>

                         <th>Status</th>
                         <th>Action</th>
                    </tr>

                    <?php

                    $counter = 1;
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
                                   echo $row['first_name'] . " " .
                                        $row['last_name'];
                                   ?>
                              </td>


                              <td>
                                   <?php
                                   echo $row['hospital_number']
                                        ?>
                              </td>


                              <td>

                                   <?php echo $row['report_status']; ?>

                              </td>

                              <td>

                                   <a href="view_report_pdf.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">

                                        View

                                   </a>

                                   <a href="edit_report.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">

                                        Edit

                                   </a>

                                   <a href="delete_report.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this report?')">

                                        Delete

                                   </a>

                              </td>

                         </tr>

                         <?php
                         $counter++;
                    }
                    ?>

               </table>
          </div>
     </div>

     <script src="../js/live_search.js"></script>

</body>

</html>