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
    <!-- Font Awesome 6 Free CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

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

        td strong {
            font-weight: 400;
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
                        "dashboard.php" => "Dashboard",
                        "course-admin.php" => "Course Admin",
                        "add-course.php" => "Add Course"
                    ];
                    $currentPage = basename($_SERVER['PHP_SELF']);
                    ?>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="active-course.php">Active Course</a></li>
                            <li class="breadcrumb-item"><a
                                    href="course-details.php?launch_c_id=<?php echo $launch_id; ?>">Course Details</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <?= $pageTitles[$currentPage] ?? ucfirst(pathinfo($currentPage, PATHINFO_FILENAME)) ?>
                            </li>
                        </ol>
                    </nav>
                    <br>

                    <div class="card-custom-assignemt p-4 border-rounded-none">
                        <div class="row">
                            <div class="col-md-5">
                                <h5 class="pb-3">Add Course Outcome</h5>
                                <form id="manage_co_form">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="coLevel"
                                            placeholder="Enter CO Level (e.g., CO1)" />
                                        <label for="coLevel">CO Level</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" placeholder="Enter course outcome description..."
                                            id="description" style="height: 100px"></textarea>
                                        <label for="description">Description</label>
                                    </div>

                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn btn-secondary w-30">Add Course Outcome</button>

                                    </div>
                                </form>

                            </div>

                            <div class="col-md-7">
                                <h5 class="pb-3">Current Course Outcomes</h5>
                                <!-- <div class="d-flex mb-3">
                                    <input type="text" class="form-control me-2"
                                        placeholder="Search by description or level..." aria-label="Search" />
                                    <select class="form-select" style="width: 150px;">
                                        <option selected>All Levels</option>
                                        <option value="CO1">CO1</option>
                                        <option value="CO2">CO2</option>
                                        <option value="CO3">CO3</option>
                                        <option value="CO4">CO4</option>
                                        <option value="CO5">CO5</option>
                                    </select>
                                </div> -->

                                <table class="table table-bordered align-middle">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width: 39px;">CO Level</th>
                                            <th scope="col" style="width: 71%;">Description</th>
                                            <th scope="col" style="width: 80px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- edit model -->
    <div class="modal fade" id="editOutcomeModal" tabindex="-1" aria-labelledby="editOutcomeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="editOutcomeForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editOutcomeModalLabel">Edit Course Outcome</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editOutcomeId" name="co_id" /> <!-- to store record id -->
                        <div class="mb-3">
                            <label for="editCoLevel" class="form-label">CO Level</label>
                            <input type="text" class="form-control" id="editCoLevel" name="co_level" required />
                        </div>
                        <div class="mb-3">
                            <label for="editDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editDescription" rows="4" name="course_description"
                                required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-secondary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- edit model end -->

    <!-- Include SweetAlert2 CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Add this script tag in your HTML head or before closing body -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- add course outcome -->
    <script>
        $(document).ready(function () {
            function getQueryParam(param) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(param);
            }

            $("#manage_co_form").submit(function (e) {
                e.preventDefault();

                // Get form values
                const coLevel = $("#coLevel").val().trim();
                const description = $("#description").val().trim();

                // Get launch_id from URL parameter "launch_c_id"
                const launchId = getQueryParam("launch_c_id");

                if (!coLevel || !description) {
                    Swal.fire("Warning", "Please fill in all fields", "warning");
                    return;
                }
                if (!launchId) {
                    Swal.fire("Error", "Launch ID missing from URL.", "error");
                    return;
                }

                $.ajax({
                    url: "api/manage_co.php",
                    type: "POST",
                    data: {
                        co_level: coLevel,
                        course_description: description,
                        launch_id: launchId,
                    },
                    dataType: "json",
                    success: function (response) {
                        Swal.fire({
                            title: "Success",
                            text: response.message,
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(() => {
                            window.location.reload();
                            $("form")[0].reset();
                        });
                    },
                    error: function () {
                        Swal.fire("Error", "An error occurred.", "error");
                    },
                });
            });
        });
    </script>
    <!-- end of add course outcome -->

    <!-- fetch course,edit and delete script start -->
    <script>
        $(document).ready(function () {
            const $tableBody = $("table tbody");
            const $searchInput = $("input[placeholder='Search by description or level...']");
            const $levelSelect = $("select.form-select");

            // Get launch_id from URL param (same way as before)
            function getQueryParam(param) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(param);
            }

            const launchId = getQueryParam("launch_c_id");
            if (!launchId) {
                alert("Missing launch_c_id in URL");
                return;
            }

            // Function to populate CO Level dropdown from DB API
            function populateCoLevels() {
                $.ajax({
                    url: "api/fetch_co_levels.php",
                    type: "GET",
                    dataType: "json",
                    data: { launch_id: launchId },
                    success: function (res) {
                        if (res.status === "success") {
                            $levelSelect.empty();
                            $levelSelect.append('<option selected>All Levels</option>');
                            res.data.forEach(function (level) {
                                $levelSelect.append(`<option value="${level}">${level}</option>`);
                            });
                        } else {
                            alert("Failed to load CO Levels: " + res.message);
                        }
                    },
                    error: function () {
                        alert("Error fetching CO Levels from server.");
                    }
                });
            }

            // Fetch CO Levels first, then fetch outcomes
            populateCoLevels();

            // Function to fetch outcomes and update table
            function fetchOutcomes() {
                $.ajax({
                    url: "api/fetch_course_outcomes.php",
                    type: "GET",
                    dataType: "json",
                    data: {
                        launch_id: launchId,
                        // search: search,
                        // level: level,
                    },
                    success: function (res) {
                        if (res.status === "success") {
                            $tableBody.empty();
                            if (res.data.length === 0) {
                                $tableBody.append('<tr><td colspan="3" class="text-center">No course outcomes found.</td></tr>');
                                return;
                            }
                            res.data.forEach(function (item) {
                                $tableBody.append(`
                            <tr data-id="${item.co_id}">
                                <td><strong>${item.co_level}</strong></td>
                                <td>${item.course_description}</td>
                                <td class="text-center">
    <!-- Edit Button -->
    <button type="button" class="btn btn-sm btn-outline-primary edit" 
        title="Edit" 
        style="margin-right:5px;"
        data-co_id="${item.co_id}" 
        data-co_level="${item.co_level}" 
        data-description="${item.course_description}"> <i class="fas fa-edit"></i>

    <!-- Delete Button -->
    <button type="button" class="btn btn-sm btn-outline-danger delete" 
        title="Delete" 
        data-coid="${item.co_id}"><i class="fas fa-trash"></i>
    </button>
</td>

                            </tr>
                        `);
                            });
                        } else {
                            alert("Error: " + res.message);
                        }
                    },
                    error: function () {
                        alert("Failed to fetch course outcomes.");
                    }
                });
            }

            // Initial fetch of outcomes after populating CO Levels (small delay)
            setTimeout(() => fetchOutcomes(), 300);

            // On search or filter change, fetch again
            $searchInput.on("input", function () {
                fetchOutcomes($(this).val(), $levelSelect.val());
            });
            $levelSelect.on("change", function () {
                fetchOutcomes($searchInput.val(), $(this).val());
            });

            // Handle Edit button click
            $(document).on('click', '.edit', function () {
                const id = $(this).data('co_id');
                const coLevel = $(this).data('co_level');
                const description = $(this).data('description');

                // Fill modal fields
                $('#editOutcomeId').val(id);
                $('#editCoLevel').val(coLevel);
                $('#editDescription').val(description);

                // Show Bootstrap 5 modal
                const modal = new bootstrap.Modal(document.getElementById('editOutcomeModal'));
                modal.show();
            });

            // Handle Delete button click
            $(document).on('click', '.delete', function () {
                const id = $(this).data('coid');
                console.log("Deleting CO ID:", id);

                // SweetAlert confirmation
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete the course outcome!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'api/delete_course_outcome.php',
                            type: 'POST',
                            data: { co_id: id },
                            dataType: 'json',
                            success: function (res) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: res.message || 'Course outcome deleted successfully!',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    // Refresh the table after delete
                                    fetchOutcomes($searchInput.val(), $levelSelect.val());
                                });
                            },
                            error: function () {
                                Swal.fire('Error', 'Failed to delete the course outcome.', 'error');
                            }
                        });
                    }
                });
            });

            // Handle Edit form submission
            $('#editOutcomeForm').submit(function (e) {
                e.preventDefault();

                const id = $('#editOutcomeId').val();
                const coLevel = $('#editCoLevel').val().trim();
                const description = $('#editDescription').val().trim();

                if (!coLevel || !description) {
                    Swal.fire('Warning', 'Please fill in all fields.', 'warning');
                    return;
                }

                $.ajax({
                    url: 'api/update_course_outcome.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        co_id: id,
                        co_level: coLevel,
                        course_description: description
                    },
                    success: function (res) {
                        Swal.fire({
                            title: 'Updated!',
                            text: res.message || 'Course outcome updated successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Close modal
                            const modalEl = document.getElementById('editOutcomeModal');
                            const modal = bootstrap.Modal.getInstance(modalEl);
                            modal.hide();

                            // Refresh table
                            fetchOutcomes($searchInput.val(), $levelSelect.val());
                        });
                    },
                    error: function () {
                        Swal.fire('Error', 'Failed to update the course outcome.', 'error');
                    }
                });
            });
        });
    </script>
    <!-- fetch course,edit,delete scritp end -->



</body>

</html>