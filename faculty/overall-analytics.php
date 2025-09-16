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
    <!-- Custom Styles -->
    <link rel="stylesheet" href="../stylesheet/responsive.css">
    <link rel="stylesheet" href="../stylesheet/styles.css">
    <style>
        .card-custom {
            background-color: #fff;
            border-radius: 12px;
            padding: 2rem;
        }

        canvas {
            height: 300px !important;
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

                        <!-- Header + Dropdown -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="pb-3">Overall Course Analytics</h4>
                            <select id="courseSelect" class="form-select w-auto">
                                <option value="" disabled selected>Select a Course</option>
                                <option value="DS">Data Structures</option>
                                <option value="AI">Artificial Intelligence</option>
                                <option value="DB">Database Systems</option>
                            </select>

                        </div>

                        <!-- Stats Boxes -->
                        <div class="row text-center mb-4">
                            <div class="col-md-3">
                                <div class="p-3 border rounded">
                                    <h5>Total Students</h5>
                                    <p class="fs-4 fw-bold" id="statStudents">-</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 border rounded">
                                    <h5>Average GPA</h5>
                                    <p class="fs-4 fw-bold" id="statGPA">-</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 border rounded">
                                    <h5>Average Score</h5>
                                    <p class="fs-4 fw-bold" id="statAvg">-</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 border rounded">
                                    <h5>Top Score</h5>
                                    <p class="fs-4 fw-bold" id="statTop">-</p>
                                </div>
                            </div>
                        </div>

                        <!-- Student Table -->
                        <table class="table table-bordered table-striped mt-4">
                            <thead class="table-light">
                                <tr>
                                    <th>Student Name</th>
                                    <th>Register No</th>
                                    <th>GPA</th>
                                    <th>Overall Score (%)</th>
                                </tr>
                            </thead>
                            <tbody id="studentTable">
                                <!-- Filled dynamically -->
                            </tbody>
                        </table>

                        <!-- Course Outcome Analysis -->
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
        const COURSE_DATA = {
            DS: {
                stats: { students: 120, gpa: 7.8, avg: 82, top: 98 },
                students: [
                    { name: 'John Doe', reg: 'REG12345', gpa: 8.7, score: 89 },
                    { name: 'Jane Smith', reg: 'REG54321', gpa: 7.9, score: 83 },
                    { name: 'Alex Kumar', reg: 'REG67890', gpa: 8.2, score: 85 },
                ],
                co: [
                    { co: 'CO1', title: 'Apply fundamental data structures effectively.', attainment: 84, avg: 73.6 },
                    { co: 'CO2', title: 'Analyze time and space complexity.', attainment: 80, avg: 78.9 },
                    { co: 'CO3', title: 'Implement advanced data structures.', attainment: 77, avg: 67.4 },
                ]
            },
            AI: {
                stats: { students: 95, gpa: 8.2, avg: 87, top: 99 },
                students: [
                    { name: 'Rahul Raj', reg: 'REG11111', gpa: 8.9, score: 94 },
                    { name: 'Meena Devi', reg: 'REG22222', gpa: 8.1, score: 88 },
                ],
                co: [
                    { co: 'CO1', title: 'Build basic AI models.', attainment: 90, avg: 85.2 },
                    { co: 'CO2', title: 'Train and tune ML algorithms.', attainment: 88, avg: 80.5 },
                ]
            },
            DB: {
                stats: { students: 110, gpa: 7.5, avg: 79, top: 95 },
                students: [
                    { name: 'Arjun', reg: 'REG33333', gpa: 7.9, score: 82 },
                    { name: 'Priya', reg: 'REG44444', gpa: 7.6, score: 78 },
                ],
                co: [
                    { co: 'CO1', title: 'Design normalized databases.', attainment: 83, avg: 70.6 },
                    { co: 'CO2', title: 'Write efficient SQL queries.', attainment: 85, avg: 77.9 },
                ]
            }
        };

        const studentTable = document.getElementById('studentTable');
        const coContainer = document.getElementById('coContainer');

        function generateDistribution(mean = 70, stdDev = 10) {
            const x = [], y = [];
            for (let i = 0; i <= 100; i++) {
                const exponent = -Math.pow(i - mean, 2) / (2 * Math.pow(stdDev, 2));
                const value = (1 / (stdDev * Math.sqrt(2 * Math.PI))) * Math.exp(exponent);
                x.push(i); y.push(value * 1000);
            }
            return { x, y };
        }

        function renderCourse(courseId) {
            const data = COURSE_DATA[courseId];

            // Stats
            document.getElementById('statStudents').textContent = data.stats.students;
            document.getElementById('statGPA').textContent = data.stats.gpa;
            document.getElementById('statAvg').textContent = data.stats.avg + '%';
            document.getElementById('statTop').textContent = data.stats.top + '%';

            // Student table
            studentTable.innerHTML = '';
            data.students.forEach(s => {
                studentTable.innerHTML += `
          <tr>
            <td>${s.name}</td>
            <td>${s.reg}</td>
            <td>${s.gpa}</td>
            <td>${s.score}</td>
          </tr>`;
            });

            // CO charts
            coContainer.innerHTML = '';
            data.co.forEach((item, idx) => {
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
                coContainer.appendChild(card);

                const ctx = card.querySelector('canvas').getContext('2d');
                const { x, y } = generateDistribution(item.avg, 10);

                const gradient = ctx.createLinearGradient(0, 0, ctx.canvas.width, 0);
                gradient.addColorStop(0, 'rgba(54, 162, 235, 0.3)');
                gradient.addColorStop(1, 'rgba(255, 99, 132, 0.3)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: x,
                        datasets: [{
                            data: y,
                            fill: true,
                            backgroundColor: gradient,
                            borderColor: '#333',
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
                                        borderDash: [4, 4]
                                    }
                                }
                            }
                        },
                        scales: {
                            x: { min: 0, max: 100, title: { display: true, text: 'Score' } },
                            y: { ticks: { display: false } }
                        }
                    }
                });
            });
        }

        document.getElementById('courseSelect').addEventListener('change', e => {
            const selected = e.target.value;
            if (selected) {
                renderCourse(selected);
            }
        });

    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>