<?php
session_start();
$cmid = $_GET['cm_id'];
$launchid = $_GET['launch_c'];
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    // Not logged in → redirect to login
    header("Location: ../index.php");
    exit;
}

if (!isset($_SESSION["user_type"]) || $_SESSION["user_type"] !== "Student") {
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
    <title>Student Portal</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logo1.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="../stylesheet/responsive.css">
    <link rel="stylesheet" href="../stylesheet/styles.css">
    <style>
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
                        <h5 class="mb-4">Courses Details</h5>

                        <div class="container mt-4">
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">

                                <!-- Course Materials -->
                                <div class="col">
                                    <a href="course-materials-stu.php?cm_id=<?php echo $cmid; ?>&launch_c=<?php echo $launchid; ?>"
                                        class="text-decoration-none text-dark">
                                        <div class="feature-card h-100">
                                            <i class="bi bi-folder2-open feature-icon"></i>
                                            <h6 class="fw-semibold">Course Materials</h6>
                                            <p class="text-muted small mb-0">Upload and manage study resources.
                                            </p>
                                        </div>
                                    </a>
                                </div>

                                <!-- Attendance -->
                                <div class="col">
                                    <a href="attendance.php" class="text-decoration-none text-dark">
                                        <div class="feature-card h-100">
                                            <i class="bi bi-people feature-icon"></i>
                                            <h6 class="fw-semibold">Attendance</h6>
                                            <p class="text-muted small mb-0">Track student attendance for
                                                courses.</p>
                                        </div>
                                    </a>
                                </div>

                                <!-- Assignments -->
                                <div class="col">
                                    <a href="assignments.php?cm_id=<?php echo $cmid; ?>&launch_c=<?php echo $launchid; ?>"
                                        class="text-decoration-none text-dark">
                                        <div class="feature-card h-100">
                                            <i class="bi bi-journal-text feature-icon"></i>
                                            <h6 class="fw-semibold">Assignments</h6>
                                            <p class="text-muted small mb-0">Create and manage student
                                                assignments.</p>
                                        </div>
                                    </a>
                                </div>

                                <!-- Grading -->
                                <div class="col">
                                    <a href="grading.php" class="text-decoration-none text-dark">
                                        <div class="feature-card h-100">
                                            <i class="bi bi-award feature-icon"></i>
                                            <h6 class="fw-semibold">Grading</h6>
                                            <p class="text-muted small mb-0">Evaluate and publish student
                                                grades.</p>
                                        </div>
                                    </a>
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