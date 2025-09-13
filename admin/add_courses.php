<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/logo1.png">

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
            <div class="col-12 col-sm-10 col-md-10 col-lg-10 p-0">
                <!-- Topbar -->
                <?php include('topbar.php') ?>
                <!-- Page Content -->
                <div class="p-4 content-scroll">
                    <!-- Add New Course Form -->
                    <div class="card-custom shadow mt-4 p-4">
                        <h5 class="mb-4">Add New Course</h5>
                        <form id="addCourseForm">
                            <div class="row g-3">
                                <!-- Course Name -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="courseName" name="courseName"
                                            placeholder="Course Name" required>
                                        <label for="courseName">Course Name</label>
                                    </div>
                                </div>

                                <!-- Course Code -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="courseCode" name="courseCode"
                                            placeholder="Course Code" required>
                                        <label for="courseCode">Course Code</label>
                                    </div>
                                </div>


                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-center mt-3">
                                <button type="submit" class="btn btn-secondary w-20">Add Course</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-custom shadow mt-4 p-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-4">All Courses</h5>

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
                                        <th>Course Name</th>
                                        <th>Code</th>
                                        <th>Seat Allotment</th>
                                        <th>Duration</th>
                                        <!-- <th>Department</th>
                                        <th>Branch</th> -->
                                        <th>Type</th>
                                        <th>Faculty Name</th>
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

                    <form id="addCourseForm">
                        <div class="row g-3">
                            <!-- Course Name -->
                            <div class="col-lg-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="courseName" name="courseName"
                                        placeholder="Course Name" required>
                                    <label for="courseName">Course Name</label>
                                </div>
                            </div>

                            <!-- Course Code -->
                            <div class="col-lg-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="courseCode" name="courseCode"
                                        placeholder="Course Code" required>
                                    <label for="courseCode">Course Code</label>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
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

        // Add course via AJAX
        $('#addCourseForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '../api/add_course.php',
                type: 'POST',
                data: new FormData(this),
                contentType: false, // required for FormData
                processData: false, // required for FormData
                dataType: 'json', // <-- let jQuery parse JSON automatically
                success: function(data) {
                    if (data.status == 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        $('#addCourseForm')[0].reset();
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning',
                            text: data.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong: ' + error
                    });
                }
            });
        });

        function loadCourses() {
            $.ajax({
                url: '../api/get_courses.php',
                type: 'GET',
                dataType: 'json',
                success: function(courses) {
                    let tbody = '';
                    courses.forEach((course, index) => {
                        tbody += `<tr>
                    <td>${index + 1}</td>
                    <td>${course.course_name}</td>
                    <td>${course.course_code}</td>
                    <td>${course.seat_allotment}</td>
                    <td>${course.duration}</td>
                    <td>${course.course_type}</td>
                    <td>${course.faculty_name}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editcourse"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>`;
                    });
                    $('#coursesTableBody').html(tbody);
                },
                error: function(xhr, status, error) {
                    console.log('Error fetching courses:', error);
                }
            });
        }

        // Load courses on page load
        $(document).ready(function() {
            loadCourses();
        });
    </script>




    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>