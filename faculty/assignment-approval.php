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

    <!-- styles -->
    <style>
        .card-custom-assignemt {
            border-radius: 0px;
            border: 1px solid #f0f0f0;
        }

        /* Style the nav tab bar background */
        #assignmentTabs {
            background-color: #f8f9fa;
            /* light gray background */
            border-radius: 6px 6px 0 0;
            padding: 0.5rem;
        }

        /* Style each tab button */
        #assignmentTa bs .nav-link {
            color: #495057;
            font-weight: 500;
            border: none;
            margin-right: 5px;
        }

        /* Active tab styling */
        #assignmentTabs .nav-link.active {
            background-color: #0d6efd;
            /* Bootstrap primary */
            color: #fff;
            border-radius: 4px;
        }

        .form-div {
            border: 1px solid #f0f0f0;
            padding: 10px;
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
                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs" id="assignmentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="add-tab" data-bs-toggle="tab"
                                data-bs-target="#addAssignment" type="button" role="tab">
                                Add Assignment
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="view-tab" data-bs-toggle="tab" data-bs-target="#viewAssignment"
                                type="button" role="tab">
                                View Assignments
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



                        <!-- Tab Contents -->
                        <div class="tab-content" id="assignmentTabsContent">
                            <!-- Add Assignment Tab -->
                            <div class="tab-pane fade show active" id="addAssignment" role="tabpanel">
                                <h4 class="text-center pb-3">Add New Assignment</h4>
                                <div class="form-div">
                                    <form>
                                        <!-- Title -->
                                        <div class="mb-3">
                                            <label class="form-label">Title</label>
                                            <input type="text" class="form-control" placeholder="Enter assignment title"
                                                required>
                                        </div>

                                        <!-- Instructions (Optional) -->
                                        <div class="mb-3">
                                            <label class="form-label">Instructions <small
                                                    class="text-muted">(optional)</small></label>
                                            <textarea class="form-control" rows="3"
                                                placeholder="Enter instructions if any"></textarea>
                                        </div>

                                        <!-- Upload PDF -->
                                        <div class="mb-3">
                                            <label class="form-label">Upload File (PDF only)</label>
                                            <input type="file" class="form-control" accept=".pdf" required>
                                        </div>

                                        <!-- Total Marks Dropdown -->
                                        <div class="mb-3">
                                            <label class="form-label">Total Marks</label>
                                            <select class="form-select" required>
                                                <option value="" selected disabled>Select marks</option>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                        </div>

                                        <!-- Due Date -->
                                        <div class="mb-3">
                                            <label class="form-label">Due Date</label>
                                            <input type="date" class="form-control" required>
                                        </div>

                                        <!-- Submit -->
                                        <div class="d-flex justify-content-center"> <button type="submit"
                                                class="btn btn-secondary">Add
                                                Assignment</button>
                                        </div>
                                    </form>

                                </div>

                            </div>


                            <!-- View Assignments Tab -->
                            <div class="tab-pane fade" id="viewAssignment" role="tabpanel">
                                <h4>View Assignments</h4>
                                <table class="table table-bordered mt-3">
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
                                            <td>Algebra Basics</td>
                                            <td>2025-09-10</td>
                                            <td>2025-09-20</td>
                                            <td><a href="../materials/LARAVEL BASICS FOM SCRATCH.pdf"
                                                    target="_blank">View File</a></td>
                                        </tr>
                                        <tr>
                                            <td>Chemistry Basics</td>
                                            <td>2025-09-11</td>
                                            <td>2025-09-21</td>
                                            <td><a href="../materials/LARAVEL BASICS FOM SCRATCH.pdf"
                                                    target="_blank">View File</a></td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                            <!-- Assignment Approval Tab -->
                            <div class="tab-pane fade" id="assignmentApproval" role="tabpanel">
                                <h4>Assignment Approval</h4>
                                <table class="table table-bordered mt-3">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="selectAll" class="form-check-input">
                                            </th>
                                            <th>Student Name</th>
                                            <th>Register No</th>
                                            <th>Course Name</th>
                                            <th>Module/Unit</th>
                                            <th>Title</th>
                                            <th>File/Link</th>
                                            <th>Enter Mark</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td><input type="checkbox" class="form-check-input row-checkbox"></td>
                                            <td>John Doe</td>
                                            <td>REG12345</td>
                                            <td>Mathematics</td>
                                            <td>Unit 1</td>
                                            <td>Algebra Basics</td>
                                            <td><a href="../materials/LARAVEL BASICS FOM SCRATCH.pdf">View File</a></td>
                                            <td style="width: 190px;">
                                                <input type="text" class="form-control form-control-sm"
                                                    placeholder="Enter Mark in percentage">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><input type="checkbox" class="form-check-input row-checkbox"></td>
                                            <td>Jane Smith</td>
                                            <td>REG54321</td>
                                            <td>Science</td>
                                            <td>Unit 2</td>
                                            <td>Chemistry Basics</td>
                                            <td><a href="../materials/LARAVEL BASICS FOM SCRATCH.pdf">View File</a></td>
                                            <td style="width: 190px;">
                                                <input type="text" class="form-control form-control-sm"
                                                    placeholder="Enter Mark in percentage">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-secondary">Submit</button>
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
                    const checked = this.checked;
                    document.querySelectorAll('.row-checkbox').forEach(cb => {
                        cb.checked = checked;
                    });
                });
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>