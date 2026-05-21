<?php
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BKMCH Patient Report Lookup</title>

    <?php include("../shared/links.php"); ?>

    <link rel="stylesheet" href="../css/style.css">

    <style>
        .lookup-box {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .lookup-box h2 {
            color: #1e293b;
            font-weight: 700;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #0d6efd;
        }

        .lookup-box label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .lookup-box .form-control {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 12px 15px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .lookup-box .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            color: #6c757d;
            font-weight: 600;
            position: relative;
        }

        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 48%;
            height: 1px;
            background-color: #dee2e6;
        }

        .divider::before {
            left: 0;
        }

        .divider::after {
            right: 0;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #bb2d3b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        }

        .error-message {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
            display: none;
        }

        .format-hint {
            color: #6c757d;
            font-size: 13px;
            margin-top: 5px;
            font-style: italic;
        }
    </style>

</head>

<body>

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-7">

                <div class="lookup-box">

                    <h2 class="text-center mb-4">
                        Find Your Report
                    </h2>

                    <form id="lookupForm" action="patient_report.php" method="GET">

                        <div class="mb-3">

                            <label for="hospital_number">Hospital Number</label>

                            <input type="text" id="hospital_number" name="hospital_number"
                                placeholder="Enter hospital number" class="form-control">

                            <div class="format-hint">Example: 749, 123, etc.</div>

                        </div>

                        <div class="divider">OR</div>

                        <div class="mb-3">

                            <label for="histopathology_full">Histopathology Number</label>

                            <input type="text" id="histopathology_full" name="histopathology_full"
                                placeholder="A4000/2026" class="form-control">

                            <div class="format-hint">Example: A4000/2026 (Letter + Number + Year)</div>

                            <div id="errorMessage" class="error-message"></div>

                        </div>

                        <!-- Hidden fields for parsed values -->
                        <input type="hidden" id="histopathology_number" name="histopathology_number">
                        <input type="hidden" id="record_number" name="record_number">
                        <input type="hidden" id="report_year" name="report_year">

                        <button type="submit" class="btn btn-danger w-100">

                            Find My Report

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <script>
        const form = document.getElementById('lookupForm');
        const histopathologyFullInput = document.getElementById('histopathology_full');
        const hospitalNumberInput = document.getElementById('hospital_number');
        const errorMessage = document.getElementById('errorMessage');

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const hospitalNumber = hospitalNumberInput.value.trim();
            const histopathologyFull = histopathologyFullInput.value.trim();

            // Clear previous error
            errorMessage.style.display = 'none';
            errorMessage.textContent = '';

            // Check if at least one field is filled
            if (!hospitalNumber && !histopathologyFull) {
                errorMessage.textContent = 'Please enter either Hospital Number or Histopathology Number';
                errorMessage.style.display = 'block';
                return false;
            }

            // If histopathology number is provided, parse it
            if (histopathologyFull) {
                // Format: A4000/2026
                const regex = /^([A-Z])(\d+)\/(\d{4})$/;
                const match = histopathologyFull.match(regex);

                if (!match) {
                    errorMessage.textContent = 'Invalid format. Please use format like: A4000/2026';
                    errorMessage.style.display = 'block';
                    return false;
                }

                // Set hidden fields with parsed values
                document.getElementById('histopathology_number').value = match[1]; // Letter
                document.getElementById('record_number').value = match[2]; // Number
                document.getElementById('report_year').value = match[3]; // Year
            }

            // Submit the form
            form.submit();
        });

        // Clear error when user starts typing
        histopathologyFullInput.addEventListener('input', function () {
            errorMessage.style.display = 'none';
        });
    </script>

</body>

</html>