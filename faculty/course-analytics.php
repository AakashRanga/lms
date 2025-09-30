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

$c_id = $_GET['c_id'] ?? '';                  
$launch_id = $_GET['launch_c_id'] ?? '';      
?>
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

        canvas {
            height: 300px !important;
            /* or any value you prefer */
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
                      
                        "course-admin.php" => "Course Admin",
                        "add-course.php" => "Add Course"
                    ];

                    $currentPage = basename($_SERVER['PHP_SELF']); // e.g. add-course.php
                    ?>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <!-- <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li> -->
                            <li class="breadcrumb-item"><a href="active-course.php">Active Course</a></li>
                            <li class="breadcrumb-item"><a href="course-details.php?launch_c_id=<?php echo $launch_id; ?>">Course Details</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <?= $pageTitles[$currentPage] ?? ucfirst(pathinfo($currentPage, PATHINFO_FILENAME)) ?>
                            </li>
                        </ol>
                    </nav>
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



                        <!-- =========================
                             Course Outcome Analysis
                        ========================= -->
                        <div class="mt-5">
                            <h4 class="pb-3">Course Outcome Analysis</h4>
                            <div class="row" id="coContainer"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.4.0"></script>
    <script>
        const CO_DATA = [
            { co: 'CO1', title: 'Ability to apply fundamental data structures effectively.', attainment: 84, avg: 73.6 },
            { co: 'CO2', title: 'Proficiency in statistical modeling techniques for analysis.', attainment: 80, avg: 78.9 },
            { co: 'CO3', title: 'Skill in machine learning algorithm implementation and tuning.', attainment: 77, avg: 67.4 },
            { co: 'CO4', title: 'Competency in evaluating model performance and identifying biases.', attainment: 84, avg: 83.7 },
            { co: 'CO5', title: 'Understanding of ethical implications and societal impact in AI.', attainment: 90, avg: 88.5 },
        ];

        function generateDistribution(mean = 70, stdDev = 10) {
            const x = [], y = [];
            for (let i = 0; i <= 100; i += 1) {
                const exponent = -Math.pow(i - mean, 2) / (2 * Math.pow(stdDev, 2));
                const value = (1 / (stdDev * Math.sqrt(2 * Math.PI))) * Math.exp(exponent);
                x.push(i);
                y.push(value * 1000); // scale up
            }
            return { x, y };
        }

        const container = document.getElementById('coContainer');

        CO_DATA.forEach((item, idx) => {
            const card = document.createElement('div');
            card.className = 'col-md-4 mb-4';
            card.innerHTML = `
            <div class="border rounded p-3 h-100 shadow-sm">
                <h6 class="fw-bold mb-1">${item.co}</h6>
                <p class="small">${item.title}</p>
                <canvas id="coChart${idx}" height="260"></canvas>
                <div class="mt-2 text-center">
                    <span class="fw-bold">${item.attainment}%</span> Attainment &nbsp;|&nbsp; 
                    <span class="fw-bold">${item.avg}%</span> Avg Score
                </div>
                
            </div>
        `;
            container.appendChild(card);

            const ctx = card.querySelector(`#coChart${idx}`).getContext('2d');
            const { x, y } = generateDistribution(item.avg, 10);

            // Create left-to-right blue-to-red gradient
            const gradient = ctx.createLinearGradient(0, 0, ctx.canvas.width, 0);
            gradient.addColorStop(0, 'rgba(54, 162, 235, 0.3)'); // Blue
            gradient.addColorStop(1, 'rgba(255, 99, 132, 0.3)'); // Red

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: x,
                    datasets: [{
                        label: 'Score Distribution',
                        data: y,
                        fill: true,
                        backgroundColor: gradient,
                        borderColor: '#333', // dark gray border
                        tension: 0.4,
                        pointRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        annotation: {
                            annotations: {
                                attainmentLine: {
                                    type: 'line',
                                    xMin: item.attainment,
                                    xMax: item.attainment,
                                    borderColor: 'black',
                                    borderWidth: 1,
                                    borderDash: [4, 4],
                                    label: {
                                        enabled: true,
                                        content: 'Attainment',
                                        position: 'start',
                                        color: '#000',
                                        backgroundColor: 'rgba(255,255,255,0.8)',
                                        font: {
                                            weight: 'bold'
                                        }
                                    }
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: { display: true, text: 'Score' },
                            min: 0,
                            max: 100,
                            grid: { display: false }
                        },
                        y: {
                            title: { display: true, text: 'Students' },
                            ticks: { display: false },
                            grid: { drawTicks: false }
                        }
                    }
                }
            });
        });
    </script>


</body>

</html>