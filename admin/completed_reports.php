<?php

include("auth_check.php");
include("../database/connection.php");

$currentPage = basename(__FILE__);

$query = mysqli_query(
     $conn,
     "SELECT `id`, `histopathology_number`, `record_number`, `hospital_number`, `report_year`, `first_name`, `middle_name`, `last_name`, `gender`, `age`, `date_receipt`, `date_dispatch`, `referring_physician`, `clinical_features`, `biopsy_site`, `procedure_performed`, `gross_description`, `microscopic_description`, `diagnosis`, `pathologist`, `consultant_pathologist`, `report_status`, `comment`, `created_at` FROM `reports` WHERE report_status='Completed' ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

     <title>Completed Reports</title>

     <?php include("../shared/links.php"); ?>

     <link rel="stylesheet" href="../css/style.css">

</head>

<body>
     <?php include_once("header.php"); ?>

     <div class="admin-content">
          <div class="container">

               <h2 class="mb-4">Completed Reports</h2>

               <table class="table table-bordered table-striped">

                    <tr>
                         <th>ID</th>
                         <th>Record Number</th>
                         <th>Patient Name</th>
                         <th>Gender</th>
                         <th>Age</th>
                         <th>Date Receipt</th>
                         <th>Diagnosis</th>
                         <th>Pathologist</th>
                         <th>Action</th>
                    </tr>

                    <?php

                    while ($row = mysqli_fetch_assoc($query)) {
                         ?>

                         <tr>

                              <td><?php echo $row['id']; ?></td>

                              <td><?php echo $row['record_number']; ?></td>

                              <td>

                                   <?php

                                   echo $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name'];

                                   ?>

                              </td>

                              <td><?php echo $row['gender']; ?></td>

                              <td><?php echo $row['age']; ?></td>

                              <td><?php echo $row['date_receipt']; ?></td>

                              <td><?php echo $row['diagnosis']; ?></td>

                              <td><?php echo $row['pathologist']; ?></td>

                              <td>

                                   <a href="view_report_pdf.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">

                                        View

                                   </a>

                              </td>

                         </tr>

                         <?php
                    }
                    ?>

               </table>
          </div>
     </div>

     <?php include_once("footer.php"); ?>

</body>

</html>
