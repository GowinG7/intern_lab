<?php include("../shared/links.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BKMCH Histopathology Report System</title>
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <!-- <div class="container">

        <div class="main-box"> -->
    <div class="container py-5">

        <div class="main-box mx-auto">

            <h2 class="report-title">Histopathology Report</h2>

            <form id="reportForm" action="save_report.php" method="POST">

                <!-- Report Information -->

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Histopathology Number</label>
                        <div class="d-flex align-items-center gap-2">
                            <select name="histopathology_number" class="form-select"
                                style="max-width: 110px; min-width: 90px;">
                                <option value="">Select</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                            </select>

                            <input type="text" name="record_number" id="record_number" class="form-control"
                                style="max-width: 290px;" value="44000">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="fw-bold">Hospital Number</label>
                        <input type="text" name="hospital_number" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold">Year</label>
                        <input type="text" name="year" class="form-control" value="2026">
                    </div>


                </div>


                <!-- Patient Information -->

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Middle Name</label>
                        <input type="text" name="middle_name" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-control">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Gender</label>

                        <select name="gender" class="form-select">
                            <option>Select</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>

                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Age</label>
                        <input type="number" name="age" class="form-control">
                    </div>


                    <div class="col-md-4 mb-3">
                        <label>Date of Receipt</label>
                        <input type="date" name="date_receipt" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Date of Dispatch</label>
                        <input type="date" name="date_dispatch" class="form-control">
                    </div>

                </div>

                <!-- Referring Physician -->

                <div class="row">

                    <div class="col-md-12 mb-3">
                        <label>Referring Physician</label>
                        <input type="text" name="referring_physician" class="form-control">
                    </div>

                </div>

                <!-- Clinical Features -->

                <div class="row">

                    <div class="col-md-12 mb-3">
                        <label>Clinical Features</label>
                        <textarea name="clinical_features" rows="3" class="form-control"></textarea>
                    </div>

                </div>

                <!-- Biopsy and Procedure -->

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Biopsy Site</label>
                        <input type="text" name="biopsy_site" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Procedure Performed</label>
                        <input type="text" name="procedure_performed" class="form-control">
                    </div>

                </div>

                <!-- Gross Description -->

                <div class="row">

                    <div class="col-md-12 mb-3">
                        <label>Gross Description</label>
                        <textarea name="gross_description" rows="4" class="form-control"></textarea>
                    </div>

                </div>

                <!-- Microscopic Description and Diagnosis -->

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Microscopic Description</label>
                        <textarea name="microscopic_description" rows="6" class="form-control"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Diagnosis</label>
                        <textarea name="diagnosis" rows="6" class="form-control"></textarea>
                    </div>

                </div>

                <!-- Pathologist -->

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label>Pathologist</label>

                        <select id="patho" name="pathologist" class="form-select">
                            <option value="Dr.GG">Dr.GG</option>
                            <option value="Dr.SS">Dr.SS</option>
                            <option value="Dr.BT">Dr.BT</option>
                            <option value="Dr.BG">Dr.BG</option>
                        </select>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Consultant Pathologist</label>

                        <select id="conpatho" name="consultant_pathologist" class="form-select">
                            <option value="Dr.GG">Dr.GG</option>
                            <option value="Dr.SS">Dr.SS</option>
                            <option value="Dr.BT">Dr.BT</option>
                            <option value="Dr.BG">Dr.BG</option>
                        </select>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Report Status</label>

                        <select id="report_status" name="report_status" class="form-select">
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                        </select>

                    </div>
                </div>

                <!-- Comment -->

                <div class="row">

                    <div class="col-md-12 mb-3">
                        <label>Comment</label>
                        <textarea id="comment" name="comment" rows="3" class="form-control"></textarea>
                    </div>

                </div>
                <div class="text-center mt-4">
                    <button type="submit" name="save_report" class="btn btn-primary">
                        Save Report
                    </button>
                </div>

            </form>

        </div>

    </div>

</body>

</html>