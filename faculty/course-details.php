<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logo1.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="../stylesheet/responsive.css">
    <link rel="stylesheet" href="../stylesheet/styles.css">

    <style>
        /* ====== Dashboard Cards ====== */
        .dashboard-card {
            border: 1px solid #e5e7eb;
            border-radius: 1rem;
            background: #fff;
            transition: all 0.2s ease;
            height: 100%;
        }

        .dashboard-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .icon-circle {
            font-size: 2rem;
            color: #3b82f6;
            margin-bottom: 0.75rem;
        }

        .badge-new {
            background: #22c55e;
            color: #fff;
            font-size: 0.7rem;
            padding: 0.25em 0.5em;
            border-radius: 0.5rem;
        }

        @media (max-width: 576px) {
            .icon-circle {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid students-section">
        <div class="row">

            <!-- Sidebar -->
            <?php include('sidebar.php') ?>

            <!-- Main Content -->
            <div class="col-12 col-sm-10 col-md-10 col-lg-10 p-0">

                <!-- Topbar -->
                <?php include('topbar.php') ?>

                <!-- Page Content -->
                <div class="p-4 content-scroll">
                    <div class="card-custom mt-4 p-4">
                        <h6 class="mb-3">Active Course</h6>

                        <div class="container mt-4">
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">

                                <!-- Attendance -->
                                <div class="col">
                                    <a href="attendance.php" class="text-decoration-none text-dark">
                                        <div class="dashboard-card text-center p-4">
                                            <div class="d-flex justify-content-center align-items-center mb-2">
                                                <div class="icon-circle">
                                                    <i class="bi bi-rocket-takeoff"></i>
                                                </div>
                                                <span class="badge badge-new ms-2">New</span>
                                            </div>
                                            <h6 class="fw-semibold mb-1">Attendance</h6>
                                            <p class="text-muted small mb-0">Start a new course creation process.</p>
                                        </div>
                                    </a>
                                </div>

                                <!-- Course Materials -->
                                <div class="col">
                                    <a href="course-materials.php" class="text-decoration-none text-dark">
                                        <div class="dashboard-card text-center p-4">
                                            <div class="icon-circle mb-2">
                                                <i class="bi bi-book"></i>
                                            </div>
                                            <h6 class="fw-semibold mb-1">Course Materials</h6>
                                            <p class="text-muted small mb-0">View all ongoing teaching courses (5).</p>
                                        </div>
                                    </a>
                                </div>

                                <!-- Assignment Approval -->
                                <div class="col">
                                    <a href="assignment-approval.php" class="text-decoration-none text-dark">
                                        <div class="dashboard-card text-center p-4">
                                            <div class="icon-circle mb-2">
                                                <i class="bi bi-check-circle"></i>
                                            </div>
                                            <h6 class="fw-semibold mb-1">Assignment Approval</h6>
                                            <p class="text-muted small mb-0">Review and approve submitted assignments.
                                            </p>
                                        </div>
                                    </a>
                                </div>

                                <!-- Course Analytics -->
                                <div class="col">
                                    <a href="course-analytics.php" class="text-decoration-none text-dark">
                                        <div class="dashboard-card text-center p-4">
                                            <div class="icon-circle mb-2">
                                                <i class="bi bi-bar-chart-line"></i>
                                            </div>
                                            <h6 class="fw-semibold mb-1">Course Analytics</h6>
                                            <p class="text-muted small mb-0">Review performance and engagement metrics.
                                            </p>
                                        </div>
                                    </a>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>