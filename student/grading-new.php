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

        .stat-card {
            border-radius: 12px;
            padding: 1rem 1.5rem;
            box-shadow: 0 2px 6px rgb(0 0 0 / 0.05);
            background: white;
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 1rem;
            min-height: 100px;
        }

        .stat-icon {
            font-size: 2rem;
            padding: 0.5rem;
            border-radius: 50%;
            color: #2c7a7b;
            background-color: #def2f1;
        }

        .stat-text {
            flex-grow: 1;
        }

        .stat-value {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #666;
        }

        .badge-graded {
            background-color: #4caf50;
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 0.3em 0.7em;
            border-radius: 8px;
        }

        .badge-revision {
            background-color: #f9a825;
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 0.3em 0.7em;
            border-radius: 8px;
        }

        .badge-late {
            background-color: #d32f2f;
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 0.3em 0.7em;
            border-radius: 8px;
        }

        /* Recent activity icons */
        .activity-icon {
            font-size: 1.2rem;
            margin-right: 10px;
        }

        .activity-completed {
            color: #38a169;
        }

        .activity-badge {
            color: #d69e2e;
        }

        .activity-submitted {
            color: #3182ce;
        }

        .activity-feedback {
            color: #4a5568;
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

                        <!-- Overview Stats -->
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-md-3">
                                <div class="stat-card">
                                    <i class="bi bi-check2-circle stat-icon"></i>
                                    <div class="stat-text">
                                        <p class="stat-value text-primary">78%</p>
                                        <p class="stat-label mb-0">Overall Course Completion</p>
                                        <small class="text-muted">Across 5 enrolled courses</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="stat-card">
                                    <i class="bi bi-file-text stat-icon"
                                        style="color:#d69e2e; background-color:#fff4d9;"></i>
                                    <div class="stat-text">
                                        <p class="stat-value">85%</p>
                                        <p class="stat-label mb-0">Avg. Quiz Score</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="stat-card">
                                    <i class="bi bi-clock stat-icon"
                                        style="color:#48bb78; background-color:#def7ec;"></i>
                                    <div class="stat-text">
                                        <p class="stat-value">120 hrs</p>
                                        <p class="stat-label mb-0">Total Study Time</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="stat-card">
                                    <i class="bi bi-book stat-icon"
                                        style="color:#68d391; background-color:#d1fae5;"></i>
                                    <div class="stat-text">
                                        <p class="stat-value">5</p>
                                        <p class="stat-label mb-0">Enrolled Courses</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Over Time Chart -->
                        <div class="card-custom">
                            <h5 class="mb-3">Progress Over Time</h5>
                            <p class="text-muted mb-4">Track your course completion and quiz score trends.</p>
                            <canvas id="progressChart" height="130"></canvas>
                        </div>

                        <!-- Bottom row charts -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card-custom">
                                    <h6 class="mb-3">Quiz Performance by Module</h6>
                                    <p class="text-muted mb-3">Your average scores across different modules.</p>
                                    <canvas id="quizChart" height="180"></canvas>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card-custom">
                                    <h6 class="mb-3">Module Engagement</h6>
                                    <p class="text-muted mb-3">Time spent learning in each module.</p>
                                    <canvas id="engagementChart" height="180"></canvas>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>




            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Progress Over Time Chart
        const ctxProgress = document.getElementById('progressChart').getContext('2d');
        const progressChart = new Chart(ctxProgress, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                datasets: [
                    {
                        label: 'Completion Rate',
                        data: [60, 68, 75, 80, 85],
                        borderColor: '#3182ce',
                        backgroundColor: 'rgba(49, 130, 206, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 3,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Avg. Quiz Score',
                        data: [70, 75, 80, 83, 88],
                        borderColor: '#48bb78',
                        backgroundColor: 'rgba(72, 187, 120, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 3,
                        yAxisID: 'y',
                    },
                ],
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'nearest',
                    intersect: false,
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: '#e2e8f0',
                        },
                    },
                    x: {
                        grid: {
                            color: '#f1f5f9',
                        },
                    },
                },
            },
        });

        // Quiz Performance Chart (Bar)
        const ctxQuiz = document.getElementById('quizChart').getContext('2d');
        const quizChart = new Chart(ctxQuiz, {
            type: 'bar',
            data: {
                labels: ['Module 1', 'Module 2', 'Module 3', 'Module 4', 'Module 5'],
                datasets: [
                    {
                        label: 'Score',
                        data: [88, 76, 90, 65, 92],
                        backgroundColor: '#3182ce',
                    },
                ],
            },
            options: {
                indexAxis: 'x',
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: '#e2e8f0',
                        },
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                    },
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                },
            },
        });

        // Module Engagement Chart (Horizontal Bar)
        const ctxEngagement = document.getElementById('engagementChart').getContext('2d');
        const engagementChart = new Chart(ctxEngagement, {
            type: 'bar',
            data: {
                labels: ['Module 1', 'Module 2', 'Module 3', 'Module 4', 'Module 5'],
                datasets: [
                    {
                        label: 'Hours Spent',
                        data: [24, 18, 28, 16, 12],
                        backgroundColor: '#48bb78',
                    },
                ],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 32,
                        grid: {
                            color: '#e2e8f0',
                        },
                    },
                    y: {
                        grid: {
                            display: false,
                        },
                    },
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                },
            },
        });
    </script>

</body>

</html>