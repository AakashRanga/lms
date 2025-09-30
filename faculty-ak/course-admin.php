<?php
session_start();

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
        a {
            text-decoration: none;
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

                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <!-- <li class="breadcrumb-item"><a href="course-admin.php">Course Admin</a></li> -->
                            <li class="breadcrumb-item active" aria-current="page">Course Admin</li>
                        </ol>
                    </nav>

                    <div class="card-custom mt-4 p-4">
                        <h6 class="mb-3">Course Management</h6>

                        <div class="container mt-5">

                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">

                                <!-- Add Course -->
                                <!-- <div class="col">
                                    <a href="launch-course.php" class="text-decoration-none text-dark">
                                        <div class="feature-card h-100">
                                            <i class="bi bi-plus-circle feature-icon"></i>
                                            <h6 class="fw-semibold">Add Course</h6>
                                            <p class="text-muted small mb-0">Create and launch a new course.</p>
                                        </div>
                                    </a>
                                </div> -->

                                <!-- View Courses -->
                                <!-- <div class="col">
                                    <a href="view-courses.php" class="text-decoration-none text-dark">
                                        <div class="feature-card h-100">
                                            <i class="bi bi-list-ul feature-icon"></i>
                                            <h6 class="fw-semibold">View Courses</h6>
                                            <p class="text-muted small mb-0">Browse and manage all existing courses.</p>
                                        </div>
                                    </a>
                                </div> -->

                                <!-- Course Approval -->
                                <div class="col">
                                    <a href="course_ar.php" class="text-decoration-none text-dark">
                                        <div class="feature-card h-100">
                                            <i class="bi bi-check2-square feature-icon"></i>
                                            <h6 class="fw-semibold">Student Enrollment Requests</h6>
                                            <p class="text-muted small mb-0">Approve or reject enrolled requests.
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


</body>

</html>