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
    <link rel="icon" type="image/png" href="../images/logo1.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../stylesheet/responsive.css">
    <link rel="stylesheet" href="../stylesheet/styles.css">

    <style>
        .course-card {
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .chapter-accordion .accordion-button {
            font-weight: 500;
        }

        .course-card ul {
            margin-bottom: 0;
            /* Removes bottom space of the list */
            padding-left: 0;
            /* Removes default left padding */
            list-style-type: none;
            /* Removes bullets */
        }

        .course-card li {
            margin-bottom: 5px;
            /* Optional: Small spacing between list items */
        }

        .course-card li a {
            text-decoration: none;
            /* Removes underline */
            color: #000;
            /* Bootstrap primary color (optional for consistency) */
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <?php include('sidebar.php') ?>

            <!-- Main Content -->
            <div class="col-12 col-sm-10 col-md-9 col-lg-10 p-0">

                <!-- Topbar -->
                <?php include('topbar.php') ?>

                <!-- Page Content -->
                <div class="p-4">
                    <div class="course-card shadow p-4">
                        <h4 class="mb-4"> Attendance</h4>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>S No.</th>
                                        <th>Course Code</th>
                                        <th style="width: 410px;">Course Name</th>
                                        <th>Class Attended</th>
                                        <th>Attended Hours</th>
                                        <th>Total Class</th>
                                        <th>Total Hours</th>
                                        <th>%</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>SPIC774</td>
                                        <td>Product Design and Development for product development </td>
                                        <td>208</td>
                                        <td>208</td>
                                        <td>226</td>
                                        <td>226</td>
                                        <td><span class="badge bg-success">92 %</span></td>
                                        <td><a href="attendance-details.php?course=SPIC774"
                                                class="btn btn-danger btn-sm">Details</a></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>MMA1221</td>
                                        <td>Mentor Mentee Meeting</td>
                                        <td>7</td>
                                        <td>7</td>
                                        <td>9</td>
                                        <td>9</td>
                                        <td><span class="badge bg-warning text-dark">78 %</span></td>
                                        <td><a href="attendance-details.php?course=MMA1221"
                                                class="btn btn-danger btn-sm">Details</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>