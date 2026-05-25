<?php
if (!isset($currentPage)) {
    $currentPage = '';
}
?>

<style>
    :root {
        --patient-navbar-height: 76px;
        --patient-navbar-bg: #7b1431;
    }

    .patient-navbar {
        min-height: var(--patient-navbar-height);
        background: linear-gradient(90deg, #6d1029 0%, #8f1c3d 48%, #6d1029 100%);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.16);
        padding: 0.6rem 0;
    }

    .patient-navbar .navbar-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #fff;
        text-decoration: none;
    }

    .patient-navbar .navbar-brand:hover {
        color: #fff;
    }

    .patient-logo {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        object-fit: cover;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.18);
        flex: 0 0 auto;
    }

    .patient-brand-text {
        display: flex;
        flex-direction: column;
        line-height: 1.05;
    }

    .patient-brand-title {
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    .patient-brand-subtitle {
        font-size: 0.78rem;
        opacity: 0.9;
    }

    .patient-navbar .nav-link {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 600;
        padding: 0.45rem 0.8rem;
        border-radius: 999px;
    }

    .patient-navbar .nav-link:hover,
    .patient-navbar .nav-link.active {
        color: #fff;
        background: rgba(255, 255, 255, 0.14);
    }

    .patient-page-with-header {
        padding-top: var(--patient-navbar-height);
    }

    @media (max-width: 768px) {
        :root {
            --patient-navbar-height: 70px;
        }

        .patient-navbar .container-fluid {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .patient-logo {
            width: 40px;
            height: 40px;
        }

        .patient-brand-title {
            font-size: 0.92rem;
        }

        .patient-brand-subtitle {
            font-size: 0.7rem;
        }
    }

    @media print {
        .patient-navbar {
            display: none !important;
        }

        .patient-page-with-header {
            padding-top: 0 !important;
        }
    }
</style>

<nav class="navbar navbar-expand-lg patient-navbar navbar-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="patient_lookup.php">
            <img src="images/logo.png" alt="BPKMCH Logo" class="patient-logo">
            <span class="patient-brand-text">
                <span class="patient-brand-title">B.P. Koirala Memorial Cancer Hospital</span>
                <span class="patient-brand-subtitle">Patient Report Portal</span>
            </span>
        </a>

        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#patientNavbarNav"
            aria-controls="patientNavbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="patientNavbarNav">
            <div class="navbar-nav align-items-lg-center gap-lg-2 mt-3 mt-lg-0">
                <a class="nav-link <?= $currentPage === 'lookup' ? 'active' : '' ?>" href="patient_lookup.php">Lookup</a>
                <a class="nav-link <?= $currentPage === 'view' ? 'active' : '' ?>" href="patient_view.php">Reports</a>
            </div>
        </div>
    </div>
</nav>