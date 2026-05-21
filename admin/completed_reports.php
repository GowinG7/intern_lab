<?php

include("auth_check.php");
include("../database/connection.php");

$currentPage = basename(__FILE__);

$query = mysqli_query(
     $conn,
     "SELECT `id`, `histopathology_number`, `record_number`, `hospital_number`, `report_year`, `first_name`, `middle_name`, `last_name`, `gender`, `age`, `date_receipt`, `date_dispatch`, `referring_physician`, `clinical_features`, `biopsy_site`, `procedure_performed`, `gross_description`, `microscopic_description`, `diagnosis`, `pathologist`, `consultant_pathologist`, `report_status`, `comment`, `created_at` FROM `reports` WHERE report_status='Completed' ORDER BY report_year ASC, CAST(SUBSTRING(record_number, 2) AS UNSIGNED) ASC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

     <title>Completed Reports</title>

     <?php include("../shared/links.php"); ?>

     <link rel="stylesheet" href="../css/style.css">

     <style>
          /* Page Container */
          .admin-content {
               background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
               min-height: 100vh;
               padding: 30px 0;
          }

          .container {
               background: white;
               border-radius: 12px;
               padding: 40px;
               box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
          }

          /* Page Title */
          h2 {
               color: #1e293b;
               font-weight: 700;
               margin-bottom: 30px;
               padding-bottom: 15px;
               border-bottom: 3px solid #0d6efd;
               display: inline-block;
          }

          /* Table Wrapper */
          .table-wrapper {
               overflow-x: auto;
               border-radius: 10px;
               box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
          }

          /* Table Styling */
          .table {
               margin: 0;
               border-collapse: collapse;
          }

          .table thead tr {
               background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
          }

          .table thead th {
               color: white;
               font-weight: 600;
               padding: 15px 12px;
               border: none;
               text-align: left;
               font-size: 14px;
               text-transform: uppercase;
               letter-spacing: 0.5px;
          }

          .table tbody tr {
               border-bottom: 1px solid #e9ecef;
               transition: all 0.3s ease;
          }

          .table tbody tr:hover {
               background-color: #f8f9fa;
               box-shadow: inset 0 0 10px rgba(13, 110, 253, 0.05);
          }

          .table tbody td {
               padding: 14px 12px;
               color: #495057;
               font-size: 14px;
               vertical-align: middle;
          }

          /* Status Badge */
          .status-badge {
               display: inline-block;
               padding: 8px 14px;
               border-radius: 20px;
               font-weight: 600;
               font-size: 12px;
               text-transform: uppercase;
               letter-spacing: 0.5px;
               box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
               transition: transform 0.2s ease;
          }

          .status-badge:hover {
               transform: translateY(-2px);
               box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
          }

          .status-completed {
               background-color: #198754;
               color: white;
          }

          .status-pending {
               background-color: #ffc107;
               color: #000;
          }

          /* Action Buttons */
          .btn-sm {
               padding: 6px 12px;
               font-size: 12px;
               font-weight: 600;
               border-radius: 6px;
               transition: all 0.3s ease;
               margin-right: 5px;
          }

          .btn-warning {
               background-color: #0dcaf0;
               border: none;
               color: white;
          }

          .btn-warning:hover {
               background-color: #0aa2c7;
               transform: translateY(-2px);
               box-shadow: 0 4px 12px rgba(13, 202, 240, 0.4);
          }

          /* Responsive Design */
          @media (max-width: 768px) {
               .container {
                    padding: 20px;
               }

               .table thead th {
                    padding: 12px 8px;
                    font-size: 12px;
               }

               .table tbody td {
                    padding: 10px 8px;
                    font-size: 12px;
               }

               .btn-sm {
                    padding: 5px 10px;
                    font-size: 11px;
               }
          }
     </style>

</head>

<body>
     <?php include_once("header.php"); ?>

     <div class="admin-content">
          <div class="container">

               <h2 class="mb-4">Completed Reports</h2>

               <div class="table-wrapper">
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

                              $statusClass = ($row['report_status'] === 'Completed') ? 'status-completed' : 'status-pending';

                              ?>

                              <tr>

                                   <td><?php echo $counter; ?></td>

                                   <td><?php echo $row['record_number']; ?></td>

                                   <td><?php echo $row['report_year']; ?></td>

                                   <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>

                                   <td><?php echo $row['hospital_number']; ?></td>

                                   <td>

                                        <span class="status-badge <?php echo $statusClass; ?>">

                                             <?php echo $row['report_status']; ?>

                                        </span>

                                   </td>

                                   <td>

                                        <a href="view_report_pdf.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">

                                             View

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
     </div>

     <?php include_once("footer.php"); ?>

     <?php include_once("footer.php"); ?>

</body>

</html>
