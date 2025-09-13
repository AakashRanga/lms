<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Materials</title>
    <link rel="icon" type="image/png" href="../images/logo1.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="../stylesheet/responsive.css">
    <link rel="stylesheet" href="../stylesheet/styles.css">

    <style>
        .card-custom {
            background-color: #fff;
            border-radius: 12px;
            padding: 2rem;
        }

        .btn-small {
            height: 35px;
            line-height: 35px;
            padding: 0 20px;
            font-size: 0.9rem;
            border-radius: 0;
        }

        .btn-submit {
            background-color: #45B6AF;
            color: #fff;
            border: none;
        }

        .btn-submit:hover {
            background-color: #3ca19a;
        }

        .btn-clear {
            background-color: #9FA6B2;
            color: #fff;
            border: none;
        }

        .btn-clear:hover {
            background-color: #8c929e;
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
                    <div class="card-custom mt-4">
                        <h4 class="pb-3">Course Analytics</h4>

                        <div class="row text-center mb-4">
                            <div class="col-md-3">
                                <div class="p-3 border rounded">
                                    <h5>Total Students</h5>
                                    <p class="fs-4 fw-bold">120</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 border rounded">
                                    <h5>Average GPA</h5>
                                    <p class="fs-4 fw-bold">7.8</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 border rounded">
                                    <h5>Average Score</h5>
                                    <p class="fs-4 fw-bold">82%</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 border rounded">
                                    <h5>Top Score</h5>
                                    <p class="fs-4 fw-bold">98%</p>
                                </div>
                            </div>
                        </div>

                        <table class="table table-bordered table-striped mt-4">
                            <thead class="table-light">
                                <tr>
                                    <th>Student Name</th>
                                    <th>Register No</th>
                                    <th>GPA</th>
                                    <th>Overall Score (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>REG12345</td>
                                    <td>8.7</td>
                                    <td>89</td>
                                </tr>
                                <tr>
                                    <td>Jane Smith</td>
                                    <td>REG54321</td>
                                    <td>7.9</td>
                                    <td>83</td>
                                </tr>
                                <tr>
                                    <td>Alex Kumar</td>
                                    <td>REG67890</td>
                                    <td>8.2</td>
                                    <td>85</td>
                                </tr>
                            </tbody>
                        </table>



                        <!-- Class Performance Graph -->
                        <div class="mt-4">
                            <label class="form-label">Class Performance Graph</label>
                            <canvas id="classPerformanceGraph" height="140"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>


        // Class Performance Graph (Scores)
        const ctx2 = document.getElementById('classPerformanceGraph').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['John Doe', 'Jane Smith', 'Alex Kumar', 'Priya Sharma', 'David Lee'],
                datasets: [{
                    label: 'Overall Score (%)',
                    data: [89, 83, 85, 91, 76],
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>