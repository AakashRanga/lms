<?php
include "../includes/config.php";

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    // Not logged in → redirect to login
    header("Location: ../index.php");
    exit;
}

if (!isset($_SESSION["user_type"]) || $_SESSION["user_type"] !== "Admin") {
    // Logged in but not Faculty → force logout
    session_destroy();
    header("Location: ../index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facutly Portal</title>
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
                <!-- Topbar with Breadcrumb -->
                <style>
                    a {
                        text-decoration: none;
                    }
                </style>

                <!-- Page Content -->
                <div class="p-4 content-scroll">
                    <!-- Breadcrumb -->
                    <!-- <nav aria-label="breadcrumb" style="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="course-admin.php">Course Admin</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Course</li>
                        </ol>
                    </nav> -->
                    <!-- Add New Course Form -->
                    <div class="card-custom shadow mt-4 p-4">
                        <h5 class="mb-4">Launch New Course</h5>
                        <form id="addCourseForm">
                            <div class="row g-3">
                                <!-- Course ID -->
                                <input type="hidden" id="courseid" name="courseid">

                                <!-- Course Name -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="courseName" name="courseName" required>
                                            <option value="" selected disabled>Select Course Name</option>
                                        </select>
                                        <label for="courseName">Course Name</label>
                                    </div>
                                </div>

                                <!-- Course Code -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="courseCode" name="courseCode" required>
                                            <option value="" selected disabled>Select Course Code</option>
                                        </select>
                                        <label for="courseCode">Course Code</label>
                                    </div>
                                </div>



                                <!-- Seat Allotment -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="seatAllotment" name="seatAllotment"
                                            placeholder="Seat Allotment" required>
                                        <label for="seatAllotment">Seat Allotment</label>
                                    </div>
                                </div>

                                <!-- Duration -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-control" name="faculty_id" id="faculty_id" required>
                                            
                                            <?php
                                               

                                                // Query to get faculty (assuming role = 'faculty' in login table)
                                                $sql = "SELECT u_id, name, reg_no FROM lms_login WHERE user_type = 'Faculty'";
                                                $result = mysqli_query($conn, $sql);

                                                if (mysqli_num_rows($result) > 0) {
                                                    echo '<option value="" selected disabled>Select Faculty</option>'; // Default option
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        // ID will be the value, Name will be shown in dropdown
                                                        echo '<option value="'.$row['u_id'].'">'.$row['name'].' / Faculty ID - '.$row['reg_no'].'</option>';
                                                    }
                                                } else {
                                                    echo '<option value="">No Faculty Found</option>';
                                                }

                                                mysqli_close($conn);
                                                ?>

                                        </select>
                                        <label for="duration">Duration</label>
                                    </div>
                                </div>

                                <!-- Course type -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="course_type" name="course_type" required>
                                            <option value="" selected disabled>Select Course Type</option>
                                            <option value="core">Core</option>
                                            <option value="elective">Elective</option>
                                            <option value="certificate">Certificate</option>
                                        </select>
                                        <label for="course_type">Course Type</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="slot" name="slot" placeholder="Slot"
                                            required>
                                        <label for="slot">Slot</label>
                                    </div>
                                </div>

                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-center mt-3">
                                <button type="submit" class="btn btn-secondary w-20">Add Course</button>
                            </div>
                        </form>
                    </div>

                    <!-- 📊 Courses Table -->
                    <div class="card-custom shadow mt-4 p-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-4">Launched Courses</h5>

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
                                      
                                        <th>Type</th>
                                        <th>Faculty Name</th>
                                        <th>Slot</th>
                                        <!-- <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody id="coursesTableBody">
                                    <!-- Courses will be loaded here -->
                                </tbody>

                            </table>
                        </div>
                    </div>

                    <!-- 🔍 JavaScript for search -->
                    <script>
                        document.getElementById('courseSearch').addEventListener('keyup', function() {
                            const searchValue = this.value.toLowerCase();
                            const rows = document.querySelectorAll('#coursesTable tbody tr');

                            rows.forEach(row => {
                                const text = row.textContent.toLowerCase();
                                row.style.display = text.includes(searchValue) ? '' : 'none';
                            });
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editcourse" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width:100%;max-width: 1030px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Update Course</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addCourseForm">
                        <div class="row g-3">
                            <!-- Course Code -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="courseCode" name="courseCode" required>
                                        <option value="" selected disabled>Select Course Code</option>
                                    </select>
                                    <label for="courseCode">Course Code</label>
                                </div>
                            </div>

                            <!-- Course Name -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="courseName" name="courseName" required>
                                        <option value="" selected disabled>Select Course Name</option>
                                    </select>
                                    <label for="courseName">Course Name</label>
                                </div>
                            </div>




                            <!-- Seat Allotment -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="seatAllotment" name="seatAllotment"
                                        placeholder="Seat Allotment" required>
                                    <label for="seatAllotment">Seat Allotment</label>
                                </div>
                            </div>

                            <!-- Duration -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="duration" name="duration"
                                        placeholder="Duration" required>
                                    <label for="duration">Duration</label>
                                </div>
                            </div>

                            <!-- Course type -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="course_type" name="course_type" required>
                                        <option value="" selected disabled>Select Course Type</option>
                                        <option value="core">Core</option>
                                        <option value="elective">Elective</option>
                                        <option value="certificate">Certificate</option>
                                    </select>
                                    <label for="course_type">Course Type</label>
                                </div>
                            </div>

                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-center mt-3">
                            <button type="submit" class="btn btn-secondary w-20">Add Course</button>
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

        $('#addCourseForm').on('submit', function(e) {
            e.preventDefault();

            const $form = $(this);
            const $btn = $form.find('button[type="submit"]');
            $btn.prop('disabled', true);

            $.ajax({
                url: 'api/launch_course.php',
                type: 'POST',
                data: new FormData(this),
                contentType: false,
                processData: false,
                dataType: 'json',
                headers: {
                    'Accept': 'application/json'
                },
                success: function(data, textStatus, xhr) {
                    // Success callback for 2xx responses
                    if (data && data.status == 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        $form[0].reset();
                        loadCourses();
                    } else {
                        // Defensive: server returned 2xx but status isn't 200 in payload
                        const msg = (data && data.message) ? data.message : 'Unknown response from server';
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning',
                            text: msg
                        });
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    // Try to extract exact message from JSON response
                    let message = null;

                    // 1) If jQuery parsed JSON, it'll be in responseJSON
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        // 2) Try to parse responseText
                        try {
                            const parsed = JSON.parse(xhr.responseText);
                            if (parsed && parsed.message) message = parsed.message;
                        } catch (err) {
                            // not JSON or parse failed
                        }
                    }

                    // 3) Fallback to statusText / errorThrown
                    if (!message) {
                        message = (errorThrown && errorThrown !== 'error') ? errorThrown : textStatus;
                        // also include HTTP code if available
                        if (xhr.status) message = `(${xhr.status}) ${message}`;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: message
                    });

                    // optional: log full response for debugging
                    console.error('AJAX Error:', {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        responseText: xhr.responseText
                    });
                },
                complete: function() {
                    // re-enable button
                    $btn.prop('disabled', false);
                }
            });
        });

        function loadCourses() {
            $.ajax({
                url: 'api/get_courses.php',
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
                  
                    <td>${course.course_type}</td>
                    <td>${course.faculty_name}</td>
                    <td>${course.slot}</td>

                   
                </tr>`;
                    });
                    $('#coursesTableBody').html(tbody);
                },
                error: function(xhr, status, error) {
                    console.log('Error fetching courses:', error);
                }
            });
        }
        // <td>
        //     <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editcourse"><i class="bi bi-pencil"></i></button>
        //     <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
        // </td>
        function loadCourseDropdowns() {
            $.ajax({
                url: 'api/fetch_course.php',
                type: 'GET',
                dataType: 'json',
                success: function(courses) {
                    let nameOptions = '<option value="" selected disabled>Select Course Name</option>';
                    let codeOptions = '<option value="" selected disabled>Select Course Code</option>';

                    courses.forEach(course => {
                        // Course Name dropdown (value = course_name, data-code = course_code)
                        nameOptions += `<option value="${course.course_name}" data-code="${course.course_code}" data-id="${course.c_id}">
                                    ${course.course_name}
                                </option>`;

                        // Course Code dropdown (value = course_code, data-name = course_name)
                        codeOptions += `<option value="${course.course_code}" data-name="${course.course_name}" data-id="${course.c_id}">
                                    ${course.course_code}
                                </option>`;
                    });

                    $('#courseName').html(nameOptions);
                    $('#courseCode').html(codeOptions);

                    // When selecting Course Name -> auto select Course Code
                    $('#courseName').on('change', function() {
                        let selectedCode = $(this).find(':selected').data('code');
                        let selectedId = $(this).find(':selected').data('id');
                        $('#courseCode').val(selectedCode);
                        $('#courseid').val(selectedId);
                    });

                    // When selecting Course Code -> auto select Course Name
                    $('#courseCode').on('change', function() {
                        let selectedName = $(this).find(':selected').data('name');
                        let selectedId = $(this).find(':selected').data('id');
                        $('#courseName').val(selectedName);
                        $('#courseid').val(selectedId);
                    });
                },
                error: function(xhr, status, error) {
                    console.log('Error fetching courses:', error);
                }
            });
        }


        // Load courses on page load
        $(document).ready(function() {
            loadCourses();
            loadCourseDropdowns();
        });
    </script>

</body>

</html>