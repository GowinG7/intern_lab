<?php

include("auth_check.php");
include("../database/connection.php");

$currentPage = basename(__FILE__);

$query = mysqli_query(
     $conn,
     "SELECT * FROM reports ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

     <title>Manage Reports</title>

     <?php include("../shared/links.php"); ?>

     <link rel="stylesheet" href="../css/style.css">

</head>

<body>
     <?php include_once("header.php"); ?>

     <div class="admin-content">
          <div class="container">

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

</body>

</html>