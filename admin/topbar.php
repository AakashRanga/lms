<!-- Topbar -->
<div class="topbar d-flex justify-content-between justify-content-md-end align-items-center shadow p-3 shadow-sm">

    <!-- Brand (for mobile) -->
    <div class="brand d-lg-none d-flex align-items-center">
        <img src="../images/logo1.png" alt="College Logo" class="me-2" style="width:35px; height:35px;">
    </div>

    <!-- Nav links for larger screens -->
    <div class="nav-links d-none d-md-flex gap-3">
        <li class="nav-item navbar-dropdown dropdown-user dropdown list-unstyled">
            <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown">
                <div class="avatar">
                    <img src="../images/logo1.png" alt="User" class="w-px-40 h-auto rounded-circle" />
                </div>
            </a>

            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="#">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-online">
                                    <img src="../images/logo1.png" alt="User" class="w-px-40 h-auto rounded-circle" />
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-semibold d-block"><?php echo $_SESSION["name"]; ?></span>
                                <small class="text-muted">Admin</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <a class="dropdown-item" href="profile.php">
                        <i class="bi bi-person me-2"></i>
                        <span class="align-middle">My Profile</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#" id="logoutBtn">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        <span class="align-middle">Log Out</span>
                    </a>
                </li>
            </ul>
        </li>
    </div>

    <!-- Mobile Toggle Button -->
    <div class="d-md-none">
        <button class="btn btn-outline-primary" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
            ☰
        </button>
    </div>

</div>

<script>
    document.getElementById("logoutBtn").addEventListener("click", function (e) {
        e.preventDefault(); // stop default link
        fetch("api/logout.php", { method: "POST" })
            .then(res => res.json())
            .then(data => {
                if (data.status === 200) {
                    window.location.href = "../"; // go to login page
                } else {
                    alert("Logout failed: " + data.message);
                }
            })
            .catch(err => console.error(err));
    });
</script>
<!-- Bootstrap JS Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>