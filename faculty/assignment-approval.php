<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Course Material Approval</title>
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
            <div class="col-12 col-sm-10 col-md-10 col-lg-10 p-0">
                <!-- Topbar -->
                <?php include('topbar.php') ?>

                <!-- Page Content -->
                <div class="p-4 content-scroll">
                    <div class="card-custom mt-4 p-4">
                        <h3>Pending Course Materials</h3>

                        <div class="container mt-5">
                            <table class="table table-bordered mt-3">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Register No</th>
                                        <th>Course Name</th>
                                        <th>Module/Unit</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>File/Link</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Example row 1 -->
                                    <tr>
                                        <td>John Doe</td>
                                        <td>REG12345</td>
                                        <td>Mathematics</td>
                                        <td>Unit 1</td>
                                        <td>Algebra Basics</td>
                                        <td>PDF</td>
                                        <td><a href="#">View File</a></td>
                                        <td>
                                            <button class="btn btn-success btn-sm">Approve</button>
                                            <button class="btn btn-danger btn-sm">Reject</button>
                                        </td>
                                    </tr>
                                    <!-- Example row 2 -->
                                    <tr>
                                        <td>Jane Smith</td>
                                        <td>REG98765</td>
                                        <td>Physics</td>
                                        <td>Module 3</td>
                                        <td>Newton's Laws</td>
                                        <td>Video</td>
                                        <td><a href="#">View File</a></td>
                                        <td>
                                            <button class="btn btn-success btn-sm">Approve</button>
                                            <button class="btn btn-danger btn-sm">Reject</button>
                                        </td>
                                    </tr>
                                    <!-- Example row 3 -->
                                    <tr>
                                        <td>Alex Kumar</td>
                                        <td>REG54321</td>
                                        <td>Chemistry</td>
                                        <td>Unit 2</td>
                                        <td>Periodic Table</td>
                                        <td>Link</td>
                                        <td><a href="https://example.com" target="_blank">Visit Link</a></td>
                                        <td>
                                            <button class="btn btn-success btn-sm">Approve</button>
                                            <button class="btn btn-danger btn-sm">Reject</button>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>