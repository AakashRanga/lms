<?php
session_start();
?>
<?php
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    // Not logged in → redirect to login
    header("Location: ../index.php");
    exit;
}

if (!isset($_SESSION["user_type"]) || $_SESSION["user_type"] !== "Faculty") {
    // Logged in but not Faculty → force logout
    session_destroy();
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Portal</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logo1.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="../stylesheet/responsive.css">
    <link rel="stylesheet" href="../stylesheet/styles.css">

    <style>
        @media (max-with:768px) {
            .topbar {
                justify-content: space-between !important;
            }
        }

        .card-custom {
            background-color: #fff;
            border-radius: 12px;
            padding: 1rem;
        }

        .course-container {
            display: flex;
            flex-wrap: wrap;
        }

        .course-item {
            position: relative;
            /* add this */
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s ease, box-shadow 0.2s ease;
            height: 100%;
        }

        .count-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background-color: #2ca789;
            color: white;
            font-weight: 600;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
        }


        .course-item:hover {
            background-color: #f8f9fa;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .course-radio {
            margin-bottom: 0.5rem;
            /* space between radio and label */
        }

        .course-label {
            font-size: 0.9rem;
            line-height: 1.2;
        }


        .feature-card {
            border: 1px solid #e5e7eb;
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            background: #fff;
            transition: all 0.2s ease;
        }

        .feature-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .feature-icon {
            font-size: 2rem;
            color: #3b82f6;
            margin-bottom: 0.75rem;
        }

        .badge-new {
            background: #22c55e;
            font-size: 0.7rem;
            margin-left: 0.5rem;
        }

        @media (max-width: 576px) {
            .course-item {
                padding: 0.6rem;
            }

            .course-label {
                font-size: 0.85rem;
            }

            .count-badge {
                width: 24px;
                height: 24px;
                font-size: 0.75rem;
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
            <div class="col-12 col-sm-10 col-md-9 col-lg-10 p-0">
                <!-- Topbar -->
                <?php include('topbar.php') ?>

                <!-- Page Content -->
                <div class="p-4 content-scroll">
                    <div class="card-custom mt-4 p-4">
                        <h5 class="mb-4">Dashboard</h5>

                        <div class="container mt-4">
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">

                                <!-- Course Admin -->
                                <!-- <div class="col">
                                    <a href="course-admin.php" class="text-decoration-none text-dark">
                                        <div class="feature-card h-100">
                                            <div class="d-flex justify-content-center align-items-center mb-2">
                                                <i class="bi bi-rocket-takeoff feature-icon"></i>
                                                <span class="badge badge-new text-white">New</span>
                                            </div>
                                            <h6 class="fw-semibold">Course Admin</h6>
                                            <p class="text-muted small mb-0">Start a new course creation process.</p>
                                        </div>
                                    </a>
                                </div> -->

                                <!-- Active Courses -->
                                <div class="col">
                                    <a href="active-course.php" class="text-decoration-none text-dark">
                                        <div class="feature-card h-100">
                                            <i class="bi bi-book feature-icon"></i>
                                            <h6 class="fw-semibold">Active Courses</h6>
                                            <p class="text-muted small mb-0">View all ongoing teaching courses (5).</p>
                                        </div>
                                    </a>
                                </div>

                                <!-- Assignment Approval -->
                                <!-- <div class="col">
                                    <a href="assignment-approval.php" class="text-decoration-none text-dark">
                                        <div class="feature-card h-100">
                                            <i class="bi bi-check-circle feature-icon"></i>
                                            <h6 class="fw-semibold">Assignment Approval</h6>
                                            <p class="text-muted small mb-0">Review and approve submitted assignments.
                                            </p>
                                        </div>
                                    </a>
                                </div> -->

                                <!-- Course Analytics -->
                                <!-- <div class="col">
                                    <a href="course-analytics.php" class="text-decoration-none text-dark">
                                        <div class="feature-card h-100">
                                            <i class="bi bi-bar-chart-line feature-icon"></i>
                                            <h6 class="fw-semibold">Course Analytics</h6>
                                            <p class="text-muted small mb-0">Review performance and engagement metrics.
                                            </p>
                                        </div>
                                    </a>
                                </div> -->

                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


</body>

</html>