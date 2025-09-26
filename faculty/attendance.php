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
    <title>Attendance</title>
    <link rel="icon" type="image/png" href="../images/logo1.png">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logo1.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="../stylesheet/responsive.css">
    <link rel="stylesheet" href="../stylesheet/styles.css">


    <style>
        .card-custom {
            background-color: #fff;
            border-radius: 12px;
            padding: 1rem;
        }

        table th {
            background: #f8f9fa;
            font-weight: 600;
            font-size: 0.95rem;
        }

        table td {
            vertical-align: middle;
        }

        .present-icon {
            color: #16a34a;
            font-size: 1.2rem;
        }

        .absent-icon {
            color: #dc2626;
            font-size: 1.2rem;
        }

        .btn-small {
            height: 30px;
            line-height: 30px;
            padding: 0 15px;
            /* horizontal padding only */
            font-size: 0.85rem;
        }

        .btn-submit {
            background-color: #45B6AF;
            border-color: #45B6AF;
            color: #fff;
            border: none;
            border-radius: 0;
        }

        .btn-clear {
            background-color: #9FA6B2;
            border-color: #9FA6B2;
            color: #fff;
            border: none;
            border-radius: 0;
        }

        .btn-submit:hover {
            background-color: #45B6AF;
        }

        .btn-clear:hover {
            background-color: #9FA6B2;
            color: #fff;
        }

        .table td,
        .table th {
            padding: 4px 8px;
            line-height: 1.2;
            font-size: 0.9rem;
        }

        li a {
            text-decoration: none !important;
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
                    <?php
                    $pageTitles = [
                        "dashboard.php" => "Dashboard",
                        "course-admin.php" => "Course Admin",
                        "add-course.php" => "Add Course"
                    ];

                    $currentPage = basename($_SERVER['PHP_SELF']); // e.g. add-course.php
                    ?>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="active-course.php">Active Course</a></li>
                            <li class="breadcrumb-item"><a href="course-details.php">Course Details</a></li>

                            <li class="breadcrumb-item active" aria-current="page">
                                <?= $pageTitles[$currentPage] ?? ucfirst(pathinfo($currentPage, PATHINFO_FILENAME)) ?>
                            </li>

                        </ol>
                    </nav>
                    <div class="card-custom mt-4 p-4">
                        <h6 class="mb-3">Attendance</h6>

                        <div class="table-responsive">
                            <table class="table table-bordered  align-middle text-center">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Reg No</th>
                                        <th>Student Name</th>
                                        <th>Present</th>
                                        <th>Absent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>192311307</td>
                                        <td>CHIKATLA SRI GANESH</td>
                                        <td>
                                            <i class="bi bi-check-circle present-icon"></i>
                                            <input type="checkbox" name="present[]" class="form-check-input ms-2">
                                        </td>
                                        <td>
                                            <i class="bi bi-x-circle absent-icon"></i>
                                            <input type="checkbox" name="absent[]" class="form-check-input ms-2">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>192311311</td>
                                        <td>CHOLLANGI SAI AKHIL</td>
                                        <td>
                                            <i class="bi bi-check-circle present-icon"></i>
                                            <input type="checkbox" name="present[]" class="form-check-input ms-2">
                                        </td>
                                        <td>
                                            <i class="bi bi-x-circle absent-icon"></i>
                                            <input type="checkbox" name="absent[]" class="form-check-input ms-2">
                                        </td>
                                    </tr>
                                    <!-- More students here... -->
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 text-end d-flex justify-content-center gap-2">
                            <button class="btn btn-submit text-light px-4 btn-small" type="submit">Submit</button>

                            <button class="btn btn-clear px-4 btn-small" type="reset">Clear</button>
                        </div>



                    </div>
                </div>

            </div>
        </div>
    </div>


</body>

</html>