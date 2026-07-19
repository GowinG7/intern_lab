# Intern Lab - Histopathology Report Portal

## About the project

Intern Lab is a web-based histopathology report management portal for B.P. Koirala Memorial Cancer Hospital. It gives hospital staff a structured way to create, manage, search, and publish patient reports, while giving patients a simple way to view completed reports online.

The system separates administrative work from patient access:

- The **admin portal** is used by authorized staff to manage report records.
- The **patient portal** is used to find and read completed reports without exposing administrative controls.

## What it solves

Managing pathology reports manually can make it difficult to find past records, track report status, and provide patients with timely access to completed results. This project helps by:

- Organizing report information in one system.
- Assigning and managing report numbers.
- Tracking whether a report is pending or completed.
- Allowing staff to search and update reports efficiently.
- Allowing patients to look up completed reports using their hospital number or report number/year.
- Providing print-friendly report views for sharing or saving as PDF.

## How the project works

1. An authorized staff member signs in through the admin portal.
2. The staff member creates a report and enters patient, clinical, biopsy, diagnosis, and pathologist information.
3. Reports are stored with a status such as **Pending** or **Completed**.
4. Administrators can search, edit, delete, review, and filter report records from the dashboard and report-management pages.
5. A patient enters a hospital number or a report number/year in the patient portal.
6. The patient portal returns only matching completed reports and provides a print-friendly report view.

## Project structure

```text
intern_lab/
|
+-- admin/                         # Protected staff/admin portal
|   +-- login.php                  # Admin sign-in page
|   +-- dashboard.php              # Report statistics and filters
|   +-- report_form.php            # Create a new report
|   +-- manage_reports.php         # List, search, edit, and delete reports
|   +-- completed_reports.php      # Completed report records
|   +-- pending_reports.php        # Pending report records
|   +-- total_reports.php          # All report records
|   +-- view_report_pdf.php        # Printable report view
|   +-- auth_check.php             # Protects authenticated pages
|   +-- auth_functions.php         # Authorization helpers
|   +-- header.php                 # Shared admin navigation
|   +-- ...
|
+-- patient/                       # Public patient report portal
|   +-- patient_lookup.php         # Search by hospital or report number
|   +-- patient_view.php           # Displays matching report results
|   +-- patient_report.php         # Patient report details/print view
|   +-- header.php                 # Shared patient navigation
|
+-- database/
|   +-- connection.php             # MySQL database connection
|
+-- shared/
|   +-- links.php                  # Shared Bootstrap and global CSS links
|   +-- images/                    # Logo and background assets
|
+-- css/                           # Stylesheets
|   +-- style.css                  # Global styles and design tokens
|   +-- dashboard.css              # Dashboard-specific styles
|   +-- admin-header.css           # Admin navigation styles
|   +-- patient-header.css         # Patient navigation styles
|   +-- ...                        # Other page-specific styles
|
+-- js/                            # Client-side behavior
|   +-- dashboard-filters.js       # Dashboard quick-date filters
|   +-- report-form-toggles.js     # Pathologist form controls
|   +-- record_search.js           # Report search behavior
|   +-- auto_record_number.js      # Automatic record-number generation
|   +-- ...
|
+-- README.md                      # Project documentation
```

## Front-end organization

The codebase keeps presentation and behavior separated from the PHP page flow:

- `css/style.css` holds global styles and design tokens.
- Page-specific styles are stored in focused files inside `css/`.
- JavaScript behavior is stored in `js/`, including dashboard filtering, form toggles, search, and record-number generation.
- PHP pages remain responsible for rendering data, processing requests, and controlling access.

This organization makes future changes easier: visual changes can be made in CSS, interactive changes in JavaScript, and business/data logic in PHP.

## Security implemented

The project includes the following security-oriented measures:

- **Session-based authentication:** admin pages use login sessions to restrict access.
- **Authorization checks:** staff access is controlled before protected admin pages are displayed.
- **Role-aware report visibility:** non-admin users are limited to the reports they created where applicable.
- **Prepared statements:** patient report lookups use parameterized database queries to reduce SQL injection risk.
- **Output escaping:** report and user values are rendered with `htmlspecialchars()` to reduce cross-site scripting (XSS) risk.
- **Completed-report restriction:** the patient portal is intended to expose only reports marked as completed.
- **Server-side control:** report lookup, access rules, and database queries are handled in PHP rather than trusting only browser-side behavior.
