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
                    <!-- Courses Table -->
                    <div class="card-custom shadow mt-4 p-4">
                        <h5 class="mb-4">Courses List</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="coursesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>S no</th>
                                        <th>Course Name</th>
                                        <th>Course Code</th>
                                        <th>Department</th>
                                        <th>Branch</th>
                                        <th>Course Type</th>
                                        <th>Duration</th>
                                        <th>Seats</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="coursesApproveReject">

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            function loadCourses() {
                $.ajax({
                    url: '../api/get_courses.php', // adjust path
                    type: 'GET',
                    dataType: 'json',
                    success: function (courses) {
                        let tbody = '';
                        courses.forEach((course, index) => {
                            let statusBadge = course.status === 'approved' ? 'bg-success' :
                                course.status === 'rejected' ? 'bg-danger' : 'bg-warning';
                            let statusText = course.status ? course.status.charAt(0).toUpperCase() + course.status.slice(1) : 'Pending';

                            tbody += `<tr>
                        <td>${index + 1}</td>
                        <td>${course.course_name}</td>
                        <td>${course.course_code}</td>
                        <td>${course.department}</td>
                        <td>${course.branch}</td>
                        <td>${course.course_type}</td>
                        <td>${course.duration}</td>
                        <td>${course.seat_allotment}</td>
                        <td><span class="badge ${statusBadge}">${statusText}</span></td>
                        <td>
                            <button class="btn btn-success btn-sm me-1 approveBtn">Approve</button>
                            <button class="btn btn-danger btn-sm rejectBtn">Reject</button>
                        </td>
                    </tr>`;
                        });
                        $('#coursesApproveReject').html(tbody);
                    },
                    error: function (err) {
                        console.error('Error fetching courses:', err);
                    }
                });
            }

            loadCourses();

            // Approve/Reject buttons
            $('#coursesApproveReject').on('click', '.approveBtn, .rejectBtn', function () {
                let row = $(this).closest('tr');
                let badge = row.find('td:nth-child(9) span');
                if ($(this).hasClass('approveBtn')) {
                    badge.removeClass('bg-warning bg-danger').addClass('bg-success').text('Approved');
                } else {
                    badge.removeClass('bg-warning bg-success').addClass('bg-danger').text('Rejected');
                }
            });
        });
    </script>
</body>

</html>