<!-- Sidebar for Small, Medium, and Large Screens (≥576px) -->
<div class="col-sm-2 col-md-2 col-lg-2 d-none d-sm-block sidebar shadow bg-light p-3">
    <!-- Sidebar Header -->
    <div class="d-flex align-items-center mb-4">
        <img src="../images/logo1.png" alt="College Logo" class="me-2" style="width:40px; height:40px;">
        <h4 class="mb-0">Admin Panel</h4>
    </div>

    <!-- Sidebar Links -->
    <a href="student_reg.php" class="d-block mb-2">
        <i class="bi bi-people me-2"></i> Enroll Courses
    </a>

    <a href="mycourses.php" class="d-block mb-2">
        <i class="bi bi-box-arrow-right me-2"></i> My Courses
    </a>

    <a href="index.php" class="d-block">
        <i class="bi bi-box-arrow-right me-2"></i> Logout
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
        <a href="student_reg.php" class="d-block mb-2">
            <i class="bi bi-people me-2"></i> Enroll Courses
        </a>

        <a href="mycourses.php" class="d-block mb-2">
            <i class="bi bi-box-arrow-right me-2"></i> My Courses
        </a>

        <a href="index.php" class="d-block">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </a>
    </div>
</div>


<!-- Bootstrap JS (required for offcanvas) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>