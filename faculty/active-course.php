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
        a{
            text-decoration: none;
        }
        .course-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            transition: all 0.2s ease;
            height: 100%;
        }

        .course-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .icon-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .icon-circle i {
            font-size: 1.5rem;
            color: #3b82f6;
        }

        .course-card h6 {
            font-size: 1rem;
        }

        .course-card p {
            font-size: 0.85rem;
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
                    <?php
                    $pageTitles = [
                        "dashboard.php"    => "Dashboard",
                        "course-admin.php" => "Course Admin",
                        "add-course.php"   => "Add Course"
                    ];

                    $currentPage = basename($_SERVER['PHP_SELF']); // e.g. add-course.php
                    ?>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <!-- <li class="breadcrumb-item"><a href="course-admin.php">Course Admin</a></li> -->
                            <li class="breadcrumb-item active" aria-current="page">
                                <?= $pageTitles[$currentPage] ?? ucfirst(pathinfo($currentPage, PATHINFO_FILENAME)) ?>
                            </li>
                        </ol>
                    </nav>

                    <div class="card-custom mt-4 p-4">
                        <h6 class="mb-3">Active Course</h6>

                        <div class="container mt-4">
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">

                                <!-- Course Item -->
                                <div class="col">
                                    <a href="course-details.php" class="text-decoration-none text-dark">
                                        <div class="course-card text-center p-4">
                                            <div class="icon-circle mb-3">
                                                <i class="bi bi-person"></i>
                                            </div>
                                            <h6 class="fw-semibold mb-1">Anatomy</h6>
                                            <p class="text-muted small mb-0">Code: BDS101</p>
                                        </div>
                                    </a>
                                </div>

                                <div class="col">
                                    <a href="course-details.php" class="text-decoration-none text-dark">
                                        <div class="course-card text-center p-4">
                                            <div class="icon-circle mb-3">
                                                <i class="bi bi-clipboard"></i>
                                            </div>
                                            <h6 class="fw-semibold mb-1">Physiology</h6>
                                            <p class="text-muted small mb-0">Code: BDS102</p>
                                        </div>
                                    </a>
                                </div>

                                <div class="col">
                                    <a href="course-details.php" class="text-decoration-none text-dark">
                                        <div class="course-card text-center p-4">
                                            <div class="icon-circle mb-3">
                                                <i class="bi bi-bar-chart-line"></i>
                                            </div>
                                            <h6 class="fw-semibold mb-1">Biochemistry</h6>
                                            <p class="text-muted small mb-0">Code: BDS103</p>
                                        </div>
                                    </a>
                                </div>

                                <div class="col">
                                    <a href="course-details.php" class="text-decoration-none text-dark">
                                        <div class="course-card text-center p-4">
                                            <div class="icon-circle mb-3">
                                                <i class="bi bi-check-circle"></i>
                                            </div>
                                            <h6 class="fw-semibold mb-1">Dental Materials</h6>
                                            <p class="text-muted small mb-0">Code: BDS104</p>
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