<?php

include "../includes/config.php";

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

$lauch_course_id = $_GET['launch_c_id'] ?? null;

$course_name = 'Null';
$course_code = 'Null';
$course_id = 'Null';
$faculty_id = 'Null';

if ($lauch_course_id) {


    // JOIN query to fetch course_name and course_code directly
    $stmt = $conn->prepare("
                SELECT c.course_name, c.course_code, c.c_id, lc.faculty_id, lc.id
                FROM launch_courses lc
                INNER JOIN course c ON lc.c_id = c.c_id
                WHERE lc.id = ?
            ");
    $stmt->bind_param("s", $lauch_course_id);
    $stmt->execute();
    $stmt->bind_result($course_name, $course_code, $course_id, $faculty_id, $launch_c_id);
    $stmt->fetch();
    $stmt->close();
}
?>
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
        a {
            text-decoration: none;
        }

        .card-custom {
            background-color: #fff;
            border-radius: 12px;
            padding: 1rem;
        }

        .table th,
        .table td {
            vertical-align: middle !important;
        }

        .table thead th {
            font-weight: 600;
            letter-spacing: 0.5px;
            font-size: 0.75rem;
        }

        .table tbody td {
            font-size: 0.85rem;
        }

        .btn-sm {
            font-size: 0.75rem;
            padding: 2px 10px;
        }

        .dropdown-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <div class="container-fluid students-section">
        <div class="row">
            <!-- didebar -->
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
                            <li class="breadcrumb-item"><a
                                    href="course-details.php?launch_c_id=<?php echo $lauch_course_id; ?>">Course Details
                                </a></li>

                            <li class="breadcrumb-item active" aria-current="page">
                                Manage Course Materials
                            </li>
                        </ol>
                    </nav>

                    <!-- Add New Course Form -->

                    <div class="card-custom shadow mt-4 p-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-4 course_name_code">DSA02 Data Structures</h5>

                            <!-- 🔍 Search Box -->
                            <div class="mb-3">
                                <input type="text" id="courseSearch" class="form-control"
                                    placeholder="Search courses...">
                            </div>

                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle" id="coursesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>S No</th>
                                        <th>Chapter Number</th>
                                        <th>Chapter Name</th>
                                        <th>Material</th>
                                        <th>Flipped Class</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="coursesTableBody">
                                    <!-- Courses will be loaded here -->
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editcourse" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width:100%;max-width: 730px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Update Course</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form id="editCourseForm">
                        <div class="row g-3">
                            <!-- Course Name -->
                            <div class="col-lg-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="course_Name" name="courseName"
                                        placeholder="Course Name" required>
                                    <label for="courseName">Course Name</label>
                                </div>
                            </div>

                            <!-- Course Code -->
                            <div class="col-lg-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="course_Code" name="courseCode"
                                        placeholder="Course Code" required>
                                    <label for="courseCode">Course Code</label>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-floating">
                                    <select name="status" id="status_" class="form-control" required>
                                        <option value="" disabled>Select Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="saveChangesBtn" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Search in table
        $('#courseSearch').on('keyup', function() {
            let searchValue = $(this).val().toLowerCase();
            $('#coursesTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchValue) > -1);
            });
        });


        function loadCourses() {
            // Example: get the launch_c_id from URL or a hidden field
            const launch_c_id = new URLSearchParams(window.location.search).get('launch_c_id');

            $.ajax({
                url: `api/fetch_launch_courses.php?launch_c_id=${launch_c_id}`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        let tbody = '';

                        // 🟩 Display the first course name and code
                        if (response.data.length > 0) {
                            const firstCourse = response.data[0];
                            $('.course_name_code').text(`${firstCourse.course_name} [${firstCourse.course_code}]`);
                        }

                        response.data.forEach((course, index) => {
                            // Each course can have multiple materials (chapters)
                            course.materials.forEach((mat) => {
                                mat.modules.forEach((module) => {
                                    tbody += `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${module.chapter_no}</td>
                                            <td>${module.chapter_title}</td>
                                            <td>${module.materials}</td>
                                            <td>${module.flipped_class}</td>
                                            <td>
                                            <button class="btn btn-sm btn-primary edit-btn"
                                                data-launch="${course.launch_id}"
                                                data-material="${mat.cm_id}"
                                                data-module="${module.mid}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            </td>
                                        </tr>`;
                                });
                            });
                        });

                        $('#coursesTableBody').html(tbody);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error fetching materials:', error);
                }
            });

        }


        $(document).on('click', '.edit-btn', function() {
            let courseId = $(this).data('launch_id');
            let courseName = $(this).data('mid');
            let courseCode = $(this).data('code');
            let status = $(this).data('status');

            // Populate modal form fields
            $('#course_Name').val(courseName);
            $('#course_Code').val(courseCode);
            $('#status_').val(status);

            // Optional: store ID in hidden input for updating later
            if ($('#courseId').length === 0) {
                $('#editCourseForm').append('<input type="hidden" id="courseId" name="courseId">');
            }
            $('#courseId').val(courseId);
        });


        $('#saveChangesBtn').on('click', function() {
            let formData = $('#editCourseForm').serialize();

            $.ajax({
                url: 'api/update_course.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#editcourse').modal('hide');
                        loadCourses(); // refresh table
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error updating course:', error);
                }
            });
        });


        // Load courses on page load
        $(document).ready(function() {
            loadCourses();
        });
    </script>

</body>

</html>