<?php

include("auth_check.php");
include("../database/connection.php");

$id = $_GET['id'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM reports WHERE id='$id'"
);

$row = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Edit Report</title>

    <?php include("../shared/links.php"); ?>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <?php include_once("header.php"); ?>

    <div class="admin-content">
        <div class="container">
            <div class="main-box mx-auto">

                <h2 class="report-title">Edit Histopathology Report</h2>

                <form action="update_report.php" method="POST">

                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                    <!-- Report Information -->

                    <div class="row mb-3">
                        <div class="col-md-4 mb-3">
                            <label class="fw-bold">Histopathology Number</label>
                            <div class="d-flex align-items-center gap-2">
                                <select id="histopathology_number" name="histopathology_number" class="form-select"
                                    style="max-width: 110px; min-width: 90px;">
                                    <option value="FN" <?php echo $row['histopathology_number'] == 'FN' ? 'selected' : ''; ?>>FN</option>
                                    <option value="B" <?php echo $row['histopathology_number'] == 'B' ? 'selected' : ''; ?>>B</option>
                                </select>

                                <input type="text" name="record_number" id="record_number" class="form-control"
                                    style="max-width: 290px;" value="<?php echo $row['record_number']; ?>" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="fw-bold">Hospital Number</label>
                            <input type="text" name="hospital_number" class="form-control" value="<?php echo $row['hospital_number']; ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold">Year</label>
                            <input type="text" name="report_year" class="form-control" value="<?php echo $row['report_year']; ?>">
                        </div>


                    </div>


                    <!-- Patient Information -->

                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?php echo $row['first_name']; ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Middle Name</label>
                            <input type="text" name="middle_name" class="form-control" value="<?php echo $row['middle_name']; ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="<?php echo $row['last_name']; ?>">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label>Gender</label>

                            <select name="gender" class="form-select">
                                <option value="">Select</option>
                                <option value="Male" <?php echo $row['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo $row['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo $row['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>

                        </div>

                        <div class="col-md-2 mb-3">
                            <label>Age</label>
                            <input type="number" name="age" class="form-control" value="<?php echo $row['age']; ?>">
                        </div>


                        <div class="col-md-4 mb-3">
                            <label>Date of Receipt</label>
                            <input type="date" name="date_receipt" class="form-control" value="<?php echo $row['date_receipt']; ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Date of Dispatch</label>
                            <input type="date" name="date_dispatch" class="form-control" value="<?php echo $row['date_dispatch']; ?>">
                        </div>

                    </div>

                    <!-- Referring Physician -->

                    <div class="row">

                        <div class="col-md-12 mb-3">
                            <label>Referring Physician</label>
                            <input type="text" name="referring_physician" class="form-control" value="<?php echo $row['referring_physician']; ?>">
                        </div>

                    </div>

                    <!-- Clinical Features -->

                    <div class="row">

                        <div class="col-md-12 mb-3">
                            <label>Clinical Features</label>
                            <textarea name="clinical_features" rows="3" class="form-control"><?php echo $row['clinical_features']; ?></textarea>
                        </div>

                    </div>

                    <!-- Biopsy and Procedure -->

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Biopsy Site</label>
                            <input type="text" name="biopsy_site" class="form-control" value="<?php echo $row['biopsy_site']; ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Procedure Performed</label>
                            <input type="text" name="procedure_performed" class="form-control" value="<?php echo $row['procedure_performed']; ?>">
                        </div>

                    </div>

                    <!-- Gross Description -->

                    <div class="row">

                        <div class="col-md-12 mb-3">
                            <label>Gross Description</label>
                            <textarea name="gross_description" rows="4" class="form-control"><?php echo $row['gross_description']; ?></textarea>
                        </div>

                    </div>

                    <!-- Microscopic Description and Diagnosis -->

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Microscopic Description</label>
                            <textarea name="microscopic_description" rows="6" class="form-control"><?php echo $row['microscopic_description']; ?></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Diagnosis</label>
                            <textarea name="diagnosis" rows="6" class="form-control"><?php echo $row['diagnosis']; ?></textarea>
                        </div>

                    </div>

                    <!-- Pathologist -->

                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label>Pathologist</label>

                            <select id="patho" name="pathologist" class="form-select">
                                <option value="Dr.GG" <?php echo $row['pathologist'] == 'Dr.GG' ? 'selected' : ''; ?>>Dr.GG</option>
                                <option value="Dr.SS" <?php echo $row['pathologist'] == 'Dr.SS' ? 'selected' : ''; ?>>Dr.SS</option>
                                <option value="Dr.BT" <?php echo $row['pathologist'] == 'Dr.BT' ? 'selected' : ''; ?>>Dr.BT</option>
                                <option value="Dr.BG" <?php echo $row['pathologist'] == 'Dr.BG' ? 'selected' : ''; ?>>Dr.BG</option>
                            </select>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>Consultant Pathologist</label>

                            <select id="conpatho" name="consultant_pathologist" class="form-select">
                                <option value="Dr.GG" <?php echo $row['consultant_pathologist'] == 'Dr.GG' ? 'selected' : ''; ?>>Dr.GG</option>
                                <option value="Dr.SS" <?php echo $row['consultant_pathologist'] == 'Dr.SS' ? 'selected' : ''; ?>>Dr.SS</option>
                                <option value="Dr.BT" <?php echo $row['consultant_pathologist'] == 'Dr.BT' ? 'selected' : ''; ?>>Dr.BT</option>
                                <option value="Dr.BG" <?php echo $row['consultant_pathologist'] == 'Dr.BG' ? 'selected' : ''; ?>>Dr.BG</option>
                            </select>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>Report Status</label>

                            <select id="report_status" name="report_status" class="form-select">
                                <option value="Pending" <?php echo $row['report_status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Completed" <?php echo $row['report_status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                            </select>

                        </div>
                    </div>

                    <!-- Comment -->

                    <div class="row">

                        <div class="col-md-12 mb-3">
                            <label>Comment</label>
                            <textarea id="comment" name="comment" rows="3" class="form-control"><?php echo $row['comment']; ?></textarea>
                        </div>

                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="update_report" class="btn btn-primary">
                            Update Report
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

</body>

</html>