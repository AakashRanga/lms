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
    <title>Assignments</title>
    <link rel="icon" type="image/png" href="../images/logo1.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="../stylesheet/responsive.css">
    <link rel="stylesheet" href="../stylesheet/styles.css">

    <style>
        .card-custom-assignemt {
            border-radius: 0px;
            border: 1px solid #f0f0f0;
        }

        #assignmentTabs {
            background-color: #f8f9fa;
            border-radius: 6px 6px 0 0;
            padding: 0.5rem;
        }

        #assignmentTabs .nav-link {
            color: #495057;
            font-weight: 500;
            border: none;
            margin-right: 5px;
        }

        #assignmentTabs .nav-link.active {
            background-color: #0d6efd;
            color: #fff;
            border-radius: 4px;
        }

        .form-div {
            border: 1px solid #f0f0f0;
            padding: 10px;
        }

        .form-table {
            padding-top: 20px;
        }

        .form-label {
            color: #000;
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
                    $currentPage = basename($_SERVER['PHP_SELF']);
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
                    <br>

                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs" id="assignmentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="add-tab" data-bs-toggle="tab"
                                data-bs-target="#addAssignment" type="button" role="tab">
                                Add Assignment
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="approval-tab" data-bs-toggle="tab"
                                data-bs-target="#assignmentApproval" type="button" role="tab">
                                Submission
                            </button>
                        </li>
                    </ul>

                    <div class="card-custom-assignemt p-4 border-rounded-none">
                        <div class="tab-content" id="assignmentTabsContent">
                            <!-- Add Assignment Tab -->
                            <div class="tab-pane fade show active" id="addAssignment" role="tabpanel">
                                <div class="form-div">
                                    <form id="assignment-approval-faculty" enctype="multipart/form-data">
                                        <!-- ✅ Fixed hidden inputs -->
                                        <input type="hidden" name="c_id" value="<?= htmlspecialchars($c_id) ?>">
                                        <input type="hidden" name="launch_c_id"
                                            value="<?= htmlspecialchars($launch_id) ?>">

                                        <!-- Title -->
                                        <div class="mb-3">
                                            <label class="form-label">Title</label>
                                            <input type="text" class="form-control" name="title"
                                                placeholder="Enter assignment title" required>
                                        </div>

                                        <!-- Instructions -->
                                        <div class="mb-3">
                                            <label class="form-label">Instructions <small
                                                    class="text-muted">(optional)</small></label>
                                            <textarea class="form-control" name="instruction" rows="3"
                                                placeholder="Enter instructions if any"></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4">
                                                <!-- Upload PDF -->
                                                <div class="mb-3">
                                                    <label class="form-label">Upload File (PDF only)</label>
                                                    <input type="file" class="form-control" name="file" accept=".pdf"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <!-- Total Marks -->
                                                <div class="mb-3">
                                                    <label class="form-label">Total Marks</label>
                                                    <select class="form-select" name="marks" required>
                                                        <option value="" selected disabled>Select marks</option>
                                                        <option value="10">10</option>
                                                        <option value="20">20</option>
                                                        <option value="25">25</option>
                                                        <option value="50">50</option>
                                                        <option value="100">100</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <!-- Due Date -->
                                                <div class="mb-3">
                                                    <label class="form-label">Due Date</label>
                                                    <input type="date" class="form-control" name="due_date" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Submit -->
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-secondary">Submit</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="form-table shadow p-3 mt-4">
                                    <h5>View Assignments</h5>
                                    <table class="table table-bordered mt-3" id="assignmentsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Topic</th>
                                                <th>Assigned Date</th>
                                                <th>Due Date</th>
                                                <th>View File</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="4" class="text-center">Loading...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>


                            </div>

                            <!-- Assignment Approval Tab -->
                            <div class="tab-pane fade" id="assignmentApproval" role="tabpanel">
                                <h4>Evaluvation</h4>
                                <table class="table table-bordered mt-3">
                                    <thead>
                                        <tr>
                                            <!-- <th><input type="checkbox" id="selectAll" class="form-check-input"></th> -->
                                            <th>Student Name</th>
                                            <th>Reg No</th>
                                            <th>Course Name</th>
                                            <th>Chapter </th>
                                            <th>Title</th>
                                            <th>File/Link</th>
                                            <th>Total Marks</th>
                                            <th>Enter Mark</th>
                                        </tr>
                                    </thead>
                                    <tbody id="assignmentTableBody">
                                        <!-- Rows will be populated by AJAX -->
                                    </tbody>

                                </table>
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-secondary" id="saveMarks">Submit</button>
                                </div>
                            </div>


                            <!-- pdf viewr -->
                            <div class="modal fade" id="pdfModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">PDF Viewer</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <iframe id="pdfFrame" width="100%" height="600px"
                                                style="border:none;"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Select All checkbox
        document.addEventListener('DOMContentLoaded', () => {
            const selectAll = document.getElementById('selectAll');
            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    document.querySelectorAll('.row-checkbox').forEach(cb => {
                        cb.checked = this.checked;
                    });
                });
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include SweetAlert2 CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- submit assignment -->
    <script>
        $(document).ready(function () {
            $("#assignment-approval-faculty").on("submit", function (e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: 'api/submit_assignment.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        let res = typeof response === "string" ? JSON.parse(response) : response;

                        if (res.status == 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Assignment submitted successfully!',
                                confirmButtonColor: '#3085d6'
                            }).then(() => {
                                $("#assignment-approval-faculty")[0].reset();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: res.message,
                                confirmButtonColor: '#d33'
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong. Please try again!',
                            confirmButtonColor: '#d33'
                        });
                    }
                });
            });
        });
    </script>

    <!-- fetch assignments -->
    <script>
        $(document).ready(function () {

            function getUrlParam(param) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(param);
            }

            function fetchAssignments() {
                const launchId = getUrlParam('launch_c_id');

                if (!launchId) {
                    $('#assignmentsTable tbody').html('<tr><td colspan="8" class="text-center">Invalid launch ID.</td></tr>');
                    return;
                }

                $.ajax({
                    url: 'api/fetch_assignments.php',
                    method: 'GET',
                    data: { launch_c_id: launchId },
                    dataType: 'json',
                    success: function (res) {
                        let tbody = $('#assignmentsTable tbody');
                        tbody.empty();

                        if (res.status === 200 && res.data.length > 0) {
                            res.data.forEach(function (a) {
                                tbody.append(`
                            <tr>
                                <td>${a.title}</td>
                                <td>${a.assigned_date}</td>
                                <td>${a.due_date}</td>
                                <td><a href="${a.notes}" target="_blank">View File</a></td>
                            </tr>
                        `);
                            });
                        } else {
                            tbody.append('<tr><td colspan="6" class="text-center">No assignments found.</td></tr>');
                        }
                    },
                    error: function () {
                        $('#assignmentsTable tbody').html('<tr><td colspan="6" class="text-center">Failed to load assignments.</td></tr>');
                    }
                });
            }

            fetchAssignments();
        });

    </script>

    <script>
        $(document).ready(function () {
            // Get launch_c_id from URL
            const urlParams = new URLSearchParams(window.location.search);
            var launch_c_id = urlParams.get('launch_c_id'); // e.g., ?launch_c_id=6

            if (!launch_c_id) {
                console.error("launch_c_id not provided in URL");
                return;
            }

            function fetchAssignments() {
                $.ajax({
                    url: 'api/fetch_assignments_faculty_evaluvation.php', // Your PHP file to fetch assignments
                    type: 'GET',
                    data: { launch_c_id: launch_c_id },
                    dataType: 'json',
                    success: function (response) {
                        var tbody = $('#assignmentTableBody');
                        tbody.empty(); // Clear existing rows

                        if (response.status === 200 && response.data.length > 0) {
                            response.data.forEach(function (assignment) {

                                // Process multiple files in notes
                                let filesHtml = '-';
                                if (assignment.notes) {
                                    filesHtml = assignment.notes
                                        .split(',')
                                        .map(filePath => {
                                            let fileName = filePath.split('/').pop();
                                            fileName = fileName.replace(/^\d+_/, '');

                                            return `
                                                <a href="javascript:void(0);" 
                                                class="pdf-link" 
                                                data-file="/workingproject/lms/student/${filePath}">
                                                ${fileName}
                                                </a>`;
                                        })
                                        .join(', ');
                                }

                                // Attach click event (after rendering)
                                $(document).on("click", ".pdf-link", function () {
                                    let fileUrl = $(this).data("file");
                                    $("#pdfFrame").attr("src", fileUrl); // Load PDF inside iframe
                                    $("#pdfModal").modal("show");        // Open modal
                                });
                                // <td><input type="checkbox" class="form-check-input row-checkbox"></td>


                                var row = `<tr>
                                    <td>${assignment.student_name || '-'}</td>
                                    <td>${assignment.student_reg_no || '-'}</td>
                                    <td>${assignment.course_name || '-'}</td>
                                    <td>${assignment.chapter_titles || '-'}</td>
                                    <td>${assignment.title}</td>
                                    <td>${filesHtml}</td>
                                    <td>${assignment.total_marks}
                                    <td style="width: 220px;">
    <div class="d-flex gap-2">
        <!-- Grade Dropdown -->
        <select class="form-select form-select-sm grade-input" data-id="${assignment.s_ass_id}">
            <option value="">Select Grade</option>
            <option value="A">A</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B">B</option>
            <option value="B-">B-</option>
            <option value="C+">C+</option>
            <option value="C">C</option>
            <option value="C-">C-</option>
            <option value="D">D</option>
            <option value="F">F</option>
        </select>
OR
        <!-- Marks Input -->
        <input type="text" 
               class="form-control form-control-sm mark-input" 
               data-id="${assignment.s_ass_id}" 
               placeholder="Enter %" 
               value="${assignment.marks || ''}">
    </div>
</td>
                                 
                                </tr>`;
                                tbody.append(row);
                            });
                        } else {
                            tbody.append('<tr><td colspan="8" class="text-center">No assignments found</td></tr>');
                        }

                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            }




            // Fetch assignments on page load
            fetchAssignments();
        });
    </script>

    <!-- Evaluvation -->

    <script>
        $("#saveMarks").on("click", function () {
            const inputs = $(".mark-input");
            let requests = 0; // number of AJAX requests sent
            let responses = 0; // number of AJAX responses received

            inputs.each(function () {
                let s_ass_id = $(this).data("id");
                let marks = $(this).val();
                let grade = $(`.grade-input[data-id="${s_ass_id}"]`).val();

                // Only send if value is entered
                if (marks !== "" || grade !== "") {
                    requests++; // count this AJAX request
                    let valueToSave = grade !== "" ? grade : marks;

                    $.post("api/update_remarks.php",
                        { s_ass_id: s_ass_id, value: valueToSave },
                        function (response) {
                            responses++; // increment on response
                            if (response.status !== 200) {
                                console.error("Error: " + response.message);
                            }

                            // Show SweetAlert when all requests have finished
                            if (responses === requests) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Saved!',
                                    text: 'Marks/Grades updated successfully.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        },
                        "json"
                    );
                }
            });

            // If no values were entered at all
            if (requests === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No input!',
                    text: 'Please enter marks or select grades before saving.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    </script>

</body>

</html>