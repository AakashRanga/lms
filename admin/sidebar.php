<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Sidebar for Small, Medium, and Large Screens (≥576px) -->
<div class="col-sm-2 col-md-3 col-lg-2 d-none d-sm-block sidebar shadow bg-light p-3">
    <!-- Sidebar Header -->
    <div class="d-flex align-items-center mb-4">
        <img src="../images/logo1.png" alt="College Logo" class="me-2" style="width:40px; height:40px;">
        <h4 class="mb-0">Admin Panel</h4>
    </div>

    <!-- Sidebar Links -->
    <a href="#" class="d-block mb-2">
        <i class="bi bi-house-door me-2"></i> Home
    </a>
    <div class="mb-2">
        <!-- Main clickable toggle -->
        <a class="d-flex justify-content-between align-items-center text-decoration-none" href="#"
            data-bs-toggle="collapse" data-bs-target="#coursesMenu" aria-expanded="false">
            <span><i class="bi bi-book me-2"></i> Courses</span>
            <i class="bi bi-chevron-down"></i>
        </a>

        <!-- Collapsible submenu -->
        <div class="collapse ps-4 mt-1" id="coursesMenu">
            <ul class="list-styled mb-0">
                <li class="mb-1">
                    <a href="add_courses.php" class="text-decoration-none d-block">Add Course</a>
                </li>
                <li class="mb-1">
                    <a href="launch-course.php" class="text-decoration-none d-block">Launch Course</a>
                </li>
                <li>
                    <a href="course_approval.php" class="text-decoration-none d-block">Course Approval</a>
                </li>
            </ul>
        </div>
    </div>


    <a href="#" class="d-block mb-2">
        <i class="bi bi-bell me-2"></i> Notifications
    </a>
</div>

<!-- Offcanvas Sidebar for Extra-Small Screens (<576px) -->
<div class="offcanvas offcanvas-start d-sm-none" tabindex="-1" id="sidebarOffcanvas"
    aria-labelledby="sidebarOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title d-flex align-items-center" id="sidebarOffcanvasLabel">
            <img src="../images/logo1.png" alt="College Logo" class="me-2" style="width:35px; height:35px;">
            <span>Admin Panel</span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body sidebar">
        <a href="dashboard.php" class="d-block mb-2">
            <i class="bi bi-house-door me-2"></i> Home
        </a>
        <div class="mb-2">
            <!-- Main clickable toggle -->
            <a class="d-flex justify-content-between align-items-center text-decoration-none" href="#"
                data-bs-toggle="collapse" data-bs-target="#coursesMenu" aria-expanded="false">
                <span><i class="bi bi-book me-2"></i> Courses</span>
                <i class="bi bi-chevron-down"></i>
            </a>

            <!-- Collapsible submenu -->
            <div class="collapse ps-4 mt-1" id="coursesMenu">
                <ul class="list-styled mb-0">
                    <li class="mb-1">
                        <a href="add_courses.php" class="text-decoration-none d-block">Add Course</a>
                    </li>
                    <li>
                        <a href="course_approval.php" class="text-decoration-none d-block">Course Approval</a>
                    </li>
                </ul>
            </div>
        </div>
        <a href="course_approval.php" class="d-block mb-2">
            <i class="bi bi-bar-chart-line me-2"></i> Course Approval
        </a>

        <a href="notifications.php" class="d-block mb-2">
            <i class="bi bi-bell me-2"></i> Notifications
        </a>
    </div>
</div>