<?php
session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    // Not logged in → redirect to login
    header("Location: ../index.php");
    exit;
}

if (!isset($_SESSION["user_type"]) || $_SESSION["user_type"] !== "Student") {
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
    <style>
        .status-approved {
            background-color: #d4edda !important;
            /* light green */
            border: 1px solid #28a745;
        }

        .status-pending {
            background-color: #fff3cd !important;
            /* light yellow */
            border: 1px solid #ffc107;
        }

        .status-rejected {
            background-color: #f8d7da !important;
            /* light red */
            border: 1px solid #dc3545;
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
                <div class="p-4 content-scroll student-reg">
                    <div class="card-custom mt-4 p-4">
                        <h5 class="mb-4">Courses Details</h5>
                        <!-- Slot Dropdown -->
                        <div class="col-lg-12 col-md-6 mt-3 mb-5">
                            <div class="d-flex justify-content-center">
                                <div class="form-floating" style="width: 40%;">
                                    <select class="form-select" id="course_slot" name="course_slot" required>
                                        <option value="" selected disabled>Select Slot</option>
                                    </select>
                                    <label for="course_slot">Course Slot</label>
                                </div>
                            </div>
                        </div>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3 course-container">
                            <!-- Course 1 -->

                            <!-- <div class="col">
                                <div class="h-100">
                                    <label class="course-item d-flex" for="course1">
                                        <input type="radio" name="course" id="course1" class="course-radio" />
                                        <span class="course-label">ACA0401 - Tractor and Automotive Engines for
                                            Sustainable Mobility - Dhatchayani</span>
                                        <span class="count-badge">40</span>
                                    </label>
                                </div>
                            </div> -->

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Fetch Lauch Slot -->
    <script>
        $(document).ready(function () {
            $.ajax({
                url: 'api/fetch_launch_course.php',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.status === "success") {
                        const slots = Object.keys(response.data);

                        // Fill slot dropdown
                        let slotOptions = '<option value="" selected disabled>Select Slot</option>';
                        slots.forEach(slot => {
                            slotOptions += `<option value="${slot}">${slot}</option>`;
                        });
                        $('#course_slot').html(slotOptions);

                        // On slot change -> show courses
                        $('#course_slot').on('change', function () {
                            const selectedSlot = $(this).val();
                            const courses = response.data[selectedSlot];
                            let courseHtml = "";

                            courses.forEach((course, index) => {
                                const id = `course_${selectedSlot}_${index}`;

                                let statusClass = "";
                                if (course.student_status === "pending") {
                                    statusClass = "status-pending"; // yellow
                                } else if (course.student_status === "approved") {
                                    statusClass = "status-approved"; // green
                                } else if (course.student_status === "rejected") {
                                    statusClass = "status-rejected"; // red
                                }

                                courseHtml += `
                                    <div class="col">
                                        <div class="h-100">
                                            <label class="course-item d-flex ${statusClass}" for="${id}">
                                                <input type="radio" name="course" id="${id}" class="course-radio" 
                                                    value="${course.launch_c_id}" required />
                                                <span class="course-label">
                                                    ${course.course_code} - ${course.course_name} - ${course.faculty_name}
                                                </span>
                                                <span class="count-badge">${course.seat_count}</span>
                                            </label>
                                        </div>
                                    </div>`;
                            });


                            $(".course-container").html(courseHtml);
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching courses:", error);
                }
            });
        });
    </script>

    <!-- send approval -->
    <script>
        $(document).ready(function () {
            $("#sendApprovalBtn").on("click", function () {
                const selectedCourse = $("input[name='course']:checked").val();
                const selectedSlot = $("#course_slot").val();
                const studentName = $("#student_name").val(); // hidden input / session
                const studentRegNo = $("#student_reg_no").val(); // hidden input / session

                if (!selectedCourse || !selectedSlot) {
                    alert("Please select a slot and course before sending approval.");
                    return;
                }

                $.ajax({
                    url: 'api/send_student_approval.php',
                    type: 'POST',
                    data: {
                        launch_c_id: selectedCourse,
                        student_name: studentName,
                        student_reg_no: studentRegNo,
                        slot: selectedSlot
                    },
                    success: function (response) {
                        if (response.status === "success") {
                            alert("✅ " + response.message);
                            location.reload();
                        } else {
                            alert("❌ " + response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error:", error);
                        alert("Something went wrong, please try again.");
                    }
                });
            });
        });
    </script>
</body>

</html>