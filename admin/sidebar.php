<!-- Sidebar for Small, Medium, and Large Screens (≥576px) -->
<div class="col-sm-2 col-md-2 col-lg-2 d-none d-sm-block sidebar shadow bg-light p-3">
    <!-- Sidebar Header -->
    <div class="d-flex align-items-center mb-4">
        <img src="../images/logo1.png" alt="College Logo" class="me-2" style="width:40px; height:40px;">
        <h4 class="mb-0">Admin Panel</h4>
    </div>

    <!-- Sidebar Links -->
    <a href="dashboard.php" class="d-block mb-2">
        <i class="bi bi-house-door me-2"></i> Home
    </a>
    <a href="add_courses.php" class="d-block mb-2">
        <i class="bi bi-book me-2"></i> Courses
    </a>
    <a href="analytics.php" class="d-block mb-2">
        <i class="bi bi-bar-chart-line me-2"></i> Analytics
    </a>
    <a href="notifications.php" class="d-block mb-2">
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
        <a href="course-admin.php" class="d-block mb-2">
            <i class="bi bi-book me-2"></i> Courses
        </a>
        <a href="analytics.php" class="d-block mb-2">
            <i class="bi bi-bar-chart-line me-2"></i> Analytics
        </a>
        <a href="notifications.php" class="d-block mb-2">
            <i class="bi bi-bell me-2"></i> Notifications
        </a>
    </div>
</div>