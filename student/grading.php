<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grading</title>
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
            <div class="col-12 col-sm-10 col-md-9 col-lg-10 p-0">
                <!-- Topbar -->
                <?php include('topbar.php') ?>

                <!-- Page Content -->
                <div class="p-4 content-scroll">
                    <div class="card-custom mt-4">
                        <h4 class="pb-3">Course Analytics</h4>

                        <div class="row">
                            <!-- GPA -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="0.01" max="10" min="0" class="form-control" id="gpa"
                                        name="gpa" placeholder="GPA" required value="8.6" readonly>
                                    <label for="gpa">GPA</label>
                                </div>
                            </div>

                            <!-- Overall Score -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="0.01" max="100" min="0" class="form-control"
                                        id="overall_score" name="overall_score" placeholder="Overall Score" required
                                        value="86.5" readonly>
                                    <label for="overall_score">Overall Score (%)</label>
                                </div>
                            </div>
                        </div>


                        <!-- Performance Graph -->
                        <div class="col-12">
                            <label class="form-label mt-3">Student Performance Graph</label>
                            <canvas id="performanceGraph" height="120"></canvas>
                        </div>


                    </div>
                </div>

                <!-- Chart.js CDN -->
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                <script>
                    const ctx = document.getElementById('performanceGraph').getContext('2d');
                    const performanceChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Assignment 1', 'Assignment 2', 'Midterm', 'Project', 'Final'],
                            datasets: [{
                                label: 'Performance (%)',
                                data: [78, 85, 82, 90, 88], // example values
                                borderWidth: 2,
                                borderColor: '#4e73df',
                                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointRadius: 4,
                                pointBackgroundColor: '#4e73df'
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: true
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100
                                }
                            }
                        }
                    });
                </script>


            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>