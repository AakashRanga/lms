<?php
session_start();
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
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                                <div class="col">
                                    <a href="course-details.php" class="text-decoration-none text-dark">
                                        <div class="card h-100 shadow-sm">
                                            <img src="../images/reseacher-image.jpg" alt="Web Development"
                                                class="card-img-top">
                                            <div class="card-body">
                                                <h5 class="card-title">Web Development</h5>
                                                <p class="card-text d-flex flex-column gap-2">
                                                    <span>Attendance: 85%</span>
                                                    <span>Completed: 60%</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col">
                                    <a href="course-details.php" class="text-decoration-none text-dark">
                                        <div class="card h-100 shadow-sm">
                                            <img src="../images/reseacher-image.jpg" alt="Data Science"
                                                class="card-img-top">
                                            <div class="card-body">
                                                <h5 class="card-title">Data Science</h5>
                                                <p class="card-text d-flex flex-column gap-2">
                                                    <span>Attendance: 92%</span>
                                                    <span>Completed: 75%</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col">
                                    <a href="course-details.php" class="text-decoration-none text-dark">
                                        <div class="card h-100 shadow-sm">
                                            <img src="../images/reseacher-image.jpg" alt="Cyber Security"
                                                class="card-img-top">
                                            <div class="card-body">
                                                <h5 class="card-title">Cyber Security</h5>
                                                <p class="card-text d-flex flex-column gap-2">
                                                    <span>Attendance: 80%</span>
                                                    <span>Completed: 50%</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col">
                                    <a href="course-details.php" class="text-decoration-none text-dark">
                                        <div class="card h-100 shadow-sm">
                                            <img src="../images/reseacher-image.jpg" alt="AI & ML" class="card-img-top">
                                            <div class="card-body">
                                                <h5 class="card-title">AI & ML</h5>
                                                <p class="card-text d-flex flex-column gap-2">
                                                    <span>Attendance: 88%</span>
                                                    <span>Completed: 70%</span>
                                                </p>
                                            </div>
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