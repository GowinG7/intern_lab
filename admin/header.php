<?php
require_once("../database/connection.php");
include_once("../shared/links.php");

?>

<link rel="stylesheet" href="../css/admin-header.css">

<!--  DESKTOP SIDEBAR  -->
<div class="admin-sidebar d-none d-lg-flex flex-column">
    <ul class="nav flex-column mt-2">
        <li class="nav-item">
            <a class="nav-link <?= $currentPage == 'dashboard.php' ? 'active' : '' ?>"
                href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $currentPage == 'report_form.php' ? 'active' : '' ?>" href="report_form.php">Add
                Report</a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= $currentPage == 'manage_reports.php' ? 'active' : '' ?>"
                href="manage_reports.php">Manage Reports</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $currentPage == 'total_reports.php' ? 'active' : '' ?>"
                href="total_reports.php">Total Reports</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $currentPage == 'completed_reports.php' ? 'active' : '' ?>"
                href="completed_reports.php">Completed Reports</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $currentPage == 'pending_reports.php' ? 'active' : '' ?>"
                href="pending_reports.php">Pending Reports</a>
        </li>


    </ul>
</div>

<!--  MOBILE SIDEBAR (OFFCANVAS)  -->
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="adminSidebarMobile">
    <div class="offcanvas-body p-0">
        <ul class="nav flex-column mt-2">
            <li class="nav-item">
                <a class="nav-link <?= $currentPage == 'dashboard.php' ? 'active' : '' ?>"
                    href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPage == 'report_form.php' ? 'active' : '' ?>" href="report_form.php">Add
                    Report</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPage == 'manage_reports.php' ? 'active' : '' ?>"
                    href="manage_reports.php">Manage Reports</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPage == 'total_reports.php' ? 'active' : '' ?>"
                    href="total_reports.php">Total Reports</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPage == 'completed_reports.php' ? 'active' : '' ?>"
                    href="completed_reports.php">Completed Reports</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPage == 'pending_reports.php' ? 'active' : '' ?>"
                    href="pending_reports.php">Pending Reports</a>
            </li>


        </ul>
    </div>
</div>

<!--  TOPBAR  -->
<nav class="navbar navbar-dark admin-header">
    <div class="container-fluid px-4 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <!-- Hamburger only on mobile -->
            <button class="btn btn-outline-light d-lg-none" data-bs-toggle="offcanvas"
                data-bs-target="#adminSidebarMobile">
                Ã¢ËÂ°
            </button>

            <!-- Logo + Title -->
            <a href="dashboard.php" class="d-flex align-items-center gap-2 text-decoration-none text-white">
                <img src="../shared/images/logo.png" alt="Logo" class="rounded-circle bg-white"
                    style="width:40px; height:40px; object-fit:cover;">
                <span class="navbar-brand fw-bold mb-0">Admin Panel</span>
            </a>
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- Welcome message -->
            <span class="admin-welcome">
                Welcome
                <strong><?php echo isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : 'Admin'; ?></strong>
            </span>

            <!-- Logout -->
            <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
        </div>
    </div>
</nav>
