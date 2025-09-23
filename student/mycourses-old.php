<?php
session_start();
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
        .card img {
            height: 180px;
            object-fit: cover;
        }

        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
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
                        <h4 class="mb-4 fw-semibold">My Enrolled Courses</h4>

                        <div class="container mt-4">
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                                <!-- Course Card -->
                                <div class="col">
                                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                                        <img src="../images/reseacher-image.jpg" class="card-img-top"
                                            alt="Introduction to Web Development">
                                        <div class="card-body">
                                            <h5 class="card-title fw-semibold">Introduction to Web Development</h5>
                                            <p class="text-muted mb-1">CS101</p>

                                            <!-- Progress -->
                                            <div class="progress rounded-pill" style="height: 6px;">
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                    style="width: 75%"></div>
                                            </div>
                                            <div class="d-flex justify-content-between mt-2 small text-muted">
                                                <span>Progress</span>
                                                <span>75%</span>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-0 pb-4">
                                            <a href="course-details.php"
                                                class="btn btn-outline-secondary w-100 mx-auto d-block">View Details</a>
                                        </div>
                                    </div>
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