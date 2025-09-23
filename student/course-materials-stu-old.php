<?php
session_start();
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

        .video-container {
            position: relative;
        }

        .video-click-left,
        .video-click-right {
            position: absolute;
            top: 0;
            height: 50%;
            width: 30%;
            cursor: pointer;
            z-index: 1;
        }

        .video-click-left {
            left: 0;
        }

        .video-click-right {
            right: 0;
        }

        /* Styling for the play/pause overlay icon */
        .video-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
            font-size: 3rem;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 2;
            pointer-events: none;
        }

        .video-container:hover .video-overlay,
        .video-overlay.visible {
            opacity: 1;
        }

        .bg-white {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .bg-white:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
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

                <div class="p-4">
                    <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
                        <h2 class="fw-bold mb-2">Introduction to Web Development</h2>
                        <p class="text-muted mb-3">
                            Master the fundamentals of modern web development, from HTML and CSS to JavaScript and
                            responsive design.
                            This course provides a solid foundation for aspiring front-end developers.
                        </p>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold">Overall Progress</span>
                            <span class="text-muted small">65%</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: 65%;"></div>
                        </div>
                    </div>

                    <h4 class="mb-3 fw-semibold">Course Chapters</h4>
                    <div class="row g-3">

                        <!-- Chapter 1 -->
                        <!-- Chapter 1 -->
                        <div class="col-md-6 col-lg-4">
                            <a href="course_materials_detail.php" class="text-decoration-none text-dark">
                                <div class="bg-white rounded-4 shadow-sm p-3 h-100">
                                    <h5 class="fw-semibold">HTML Essentials</h5>
                                    <p class="text-muted small mb-2">
                                        Learn the core elements and structure of web pages, including semantics and
                                        accessibility.
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center small mb-1">
                                        <span>Completion</span>
                                        <span class="text-muted">100%</span>
                                    </div>
                                    <div class="progress rounded-pill" style="height: 6px;">
                                        <div class="progress-bar bg-primary" style="width: 100%;"></div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Chapter 2 -->
                        <div class="col-md-6 col-lg-4">
                            <a href="course_materials_detail.php" class="text-decoration-none text-dark">
                                <div class="bg-white rounded-4 shadow-sm p-3 h-100">
                                    <h5 class="fw-semibold">Styling with CSS</h5>
                                    <p class="text-muted small mb-2">
                                        Dive into Cascading Style Sheets to control layout, typography, and visual
                                        presentation.
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center small mb-1">
                                        <span>Completion</span>
                                        <span class="text-muted">80%</span>
                                    </div>
                                    <div class="progress rounded-pill" style="height: 6px;">
                                        <div class="progress-bar bg-primary" style="width: 80%;"></div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Chapter 3 -->
                        <div class="col-md-6 col-lg-4">
                            <a href="course_materials_detail.php" class="text-decoration-none text-dark">
                                <div class="bg-white rounded-4 shadow-sm p-3 h-100">
                                    <h5 class="fw-semibold">JavaScript Fundamentals</h5>
                                    <p class="text-muted small mb-2">
                                        Understand variables, functions, DOM manipulation, and event handling for
                                        interactive experiences.
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center small mb-1">
                                        <span>Completion</span>
                                        <span class="text-muted">50%</span>
                                    </div>
                                    <div class="progress rounded-pill" style="height: 6px;">
                                        <div class="progress-bar bg-primary" style="width: 50%;"></div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Chapter 4 -->
                        <div class="col-md-6 col-lg-4">
                            <a href="course_materials_detail.php" class="text-decoration-none text-dark">
                                <div class="bg-white rounded-4 shadow-sm p-3 h-100">
                                    <h5 class="fw-semibold">Responsive Design</h5>
                                    <p class="text-muted small mb-2">
                                        Implement techniques like Flexbox, Grid, and media queries to adapt your site to
                                        any screen size.
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center small mb-1">
                                        <span>Completion</span>
                                        <span class="text-muted">20%</span>
                                    </div>
                                    <div class="progress rounded-pill" style="height: 6px;">
                                        <div class="progress-bar bg-primary" style="width: 20%;"></div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Chapter 5 -->
                        <div class="col-md-6 col-lg-4">
                            <a href="course_materials_detail.php" class="text-decoration-none text-dark">
                                <div class="bg-white rounded-4 shadow-sm p-3 h-100">
                                    <h5 class="fw-semibold">Introduction to React</h5>
                                    <p class="text-muted small mb-2">
                                        Explore the basics of React, components, state, and props for building dynamic
                                        user interfaces.
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center small mb-1">
                                        <span>Completion</span>
                                        <span class="text-muted">0%</span>
                                    </div>
                                    <div class="progress rounded-pill" style="height: 6px;">
                                        <div class="progress-bar bg-primary" style="width: 0%;"></div>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>

                    <div class="mt-4 d-flex gap-2">

                        <a href="mycourses.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back to All Courses
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
    </script>
</body>


</html>