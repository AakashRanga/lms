<?php
include "../includes/config.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logo1.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="../stylesheet/responsive.css">
    <link rel="stylesheet" href="../stylesheet/styles.css">
    <style>
        .card img {
            height: 180px;
            object-fit: cover;
        }

        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
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
                    <div class="card-custom mt-4 p-4">
                        <h4 class="mb-4 fw-semibold">My Enrolled Courses</h4>

                        <div class="container mt-4">
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                                <!-- Course Card -->



                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     
        <script>
            $(document).ready(function() {
                $.ajax({
                    url: "api/get_student_courses.php",
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            let courses = response.data;
                            let container = $(".row-cols-1");
                            container.empty();

                            if (courses.length === 0) {
                                container.append(`<div class="col"><p>No courses enrolled.</p></div>`);
                            } else {
                                courses.forEach(course => {
                                    container.append(`
                            <div class="col">
                                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                                <img src="${course.thumbnail_url}" 
                                        class="card-img-top" 
                                        alt="${course.course_name}">


                                    <div class="card-body">
                                        <h5 class="card-title fw-semibold">${course.course_name}</h5>
                                        <p class="text-muted mb-1">${course.course_code}</p>
                                        <div class="progress rounded-pill" style="height: 6px;">
                                            <div class="progress-bar bg-primary" role="progressbar" 
                                                 style="width: ${course.progress}%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2 small text-muted">
                                            <span>Progress</span>
                                            <span>${course.progress}%</span>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 pb-4">
                                        <a href="course-details.php?cm_id=${course.cm_id}&launch_c=${course.launch_id}" 
                                           class="btn btn-outline-secondary w-100 mx-auto d-block">View Course</a>
                                    </div>
                                </div>
                            </div>
                        `);
                                });
                            }
                        } else {
                            Swal.fire("Error", response.message, "error");
                        }
                    },
                    error: function(xhr) {
                        Swal.fire("Error", xhr.responseText, "error");
                    }
                });
            });
        </script>

</body>

</html>