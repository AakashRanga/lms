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
        .card-custom {
            background-color: #fff;
            border-radius: 12px;
            padding: 1rem;
        }

        .course-container {
            display: flex;
            flex-wrap: wrap;
        }

        .course-item {
            position: relative;
            /* add this */
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s ease, box-shadow 0.2s ease;
            height: 100%;
        }

        .count-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background-color: #2ca789;
            color: white;
            font-weight: 600;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
        }


        .course-item:hover {
            background-color: #f8f9fa;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .course-radio {
            margin-bottom: 0.5rem;
            /* space between radio and label */
        }

        .course-label {
            font-size: 0.9rem;
            line-height: 1.2;
        }



        @media (max-width: 576px) {
            .course-item {
                padding: 0.6rem;
            }

            .course-label {
                font-size: 0.85rem;
            }

            .count-badge {
                width: 24px;
                height: 24px;
                font-size: 0.75rem;
            }
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
                    <div class="card-custom mt-4 p-4">
                        <h5 class="mb-4">Courses Details</h5>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3 course-container">
                            <!-- Course 1 -->
                            <div class="col">
                                <div class="h-100">
                                    <label class="course-item d-flex" for="course1">
                                        <input type="radio" name="course" id="course1" class="course-radio" />
                                        <span class="course-label">ACA0401 - Tractor and Automotive Engines for
                                            Sustainable Mobility - Dhatchayani</span>
                                        <span class="count-badge">40</span>

                                    </label>

                                </div>
                            </div>

                            <!-- Course 2 -->
                            <div class="col">
                                <div class="h-100">
                                    <label class="course-item" for="course2">
                                        <input type="radio" name="course" id="course2" class="course-radio" />
                                        <span class="course-label">EEA0901 - Power System II for Hybrid EV -
                                            Meenakshi</span>
                                        <span class="count-badge">39</span>

                                    </label>

                                </div>
                            </div>

                            <!-- Course 3 -->
                            <div class="col">
                                <div class="h-100">
                                    <label class="course-item" for="course3">
                                        <input type="radio" name="course" id="course3" class="course-radio" />
                                        <span class="course-label">CTA0201 - Wireless and Mobile Communication for Smart
                                            Connectivity - Aakash</span>
                                        <span class="count-badge">0</span>

                                    </label>

                                </div>
                            </div>

                            <!-- Course 4 -->
                            <div class="col">
                                <div class="h-100">
                                    <label class="course-item" for="course4">
                                        <input type="radio" name="course" id="course4" class="course-radio" />
                                        <span class="course-label">UCM0201 - Mathematical Foundations of Computer
                                            Science - Dr. G. MICHAEL</span>
                                        <span class="count-badge">4</span>

                                    </label>

                                </div>
                            </div>

                            <!-- Course 5 -->
                            <div class="col">
                                <div class="h-100">
                                    <label class="course-item" for="course1">
                                        <input type="radio" name="course" id="course1" class="course-radio" />
                                        <span class="course-label">ACA0401 - Tractor and Automotive Engines for
                                            Sustainable Mobility - Dhatchayani</span>
                                        <span class="count-badge">40</span>

                                    </label>

                                </div>
                            </div>

                            <!-- Course 6 -->
                            <div class="col">
                                <div class="h-100">
                                    <label class="course-item" for="course1">
                                        <input type="radio" name="course" id="course1" class="course-radio" />
                                        <span class="course-label">ACA0401 - Tractor and Automotive Engines for
                                            Sustainable Mobility - Dhatchayani</span>
                                        <span class="count-badge">40</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Course 7 -->
                            <div class="col">
                                <div class="h-100">
                                    <label class="course-item" for="course1">
                                        <input type="radio" name="course" id="course1" class="course-radio" />
                                        <span class="course-label">ACA0401 - Tractor and Automotive Engines for
                                            Sustainable Mobility - Dhatchayani</span>
                                        <span class="count-badge">40</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Course 8 -->
                            <div class="col">
                                <div class="h-100">
                                    <label class="course-item" for="course1">
                                        <input type="radio" name="course" id="course1" class="course-radio" />
                                        <span class="course-label">ACA0401 -Professional Certification for Database SQL
                                            Specialist Expert - Dhatchayani</span>
                                        <span class="count-badge">40</span>
                                    </label>
                                </div>
                            </div>

                        </div>
                        <div class="mt-4 text-center">
                            <button id="sendApprovalBtn" class="btn btn-secondary">Send Approval</button>
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