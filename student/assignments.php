<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Submitted Assignments</title>
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

        .document-list {
            display: none;
            margin-top: 15px;
            padding-left: 0;
            /* Remove default UL padding */
            list-style: none;
            /* Remove bullets */
        }

        .document-list li {
            margin-bottom: 8px;
            list-style-type: none;
        }

        .document-list a {
            text-decoration: none;
            /* Remove underline */
            color: #000;
            /* Black text color */
        }

        .document-list a:hover {
            color: #0d6efd;
            /* Optional: Bootstrap primary color on hover */
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <?php include('sidebar.php') ?>

            <!-- Main Content -->
            <div class="col-12 col-sm-10 col-md-10 col-lg-10 p-0">

                <!-- Topbar -->
                <?php include('topbar.php') ?>

                <!-- Page Content -->
                <div class="p-4">
                    <div class="course-card shadow p-4">
                        <h4 class="mb-4">📝 Submitted Assignments</h4>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>S No.</th>
                                        <th>Assignment Name</th>
                                        <th>Grade</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Product Design Report</td>
                                        <td><span class="badge bg-success">A+</span></td>
                                        <td>
                                            <button class="btn btn-primary btn-sm view-btn" data-target="doc1">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="document-list" id="doc1">
                                        <td colspan="4">
                                            <ul>
                                                <li><a href="../materials/LARAVEL BASICS FOM SCRATCH.pdf"
                                                        target="_blank">* assignment1.docx</a></li>

                                            </ul>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>2</td>
                                        <td>Mentor Mentee Reflection</td>
                                        <td><span class="badge bg-warning text-dark">B+</span></td>
                                        <td>
                                            <button class="btn btn-primary btn-sm view-btn" data-target="doc2">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="document-list" id="doc2">
                                        <td colspan="4">
                                            <ul>
                                                <li><a href="../materials/LARAVEL BASICS FOM SCRATCH.pdf"
                                                        target="_blank">* assignment1.docx</a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Toggle documents when "View" button is clicked
        document.querySelectorAll('.view-btn').forEach(button => {
            button.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const docRow = document.getElementById(targetId);

                // Toggle visibility
                if (docRow.style.display === 'table-row') {
                    docRow.style.display = 'none';
                } else {
                    docRow.style.display = 'table-row';
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>