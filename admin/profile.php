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
$u_id = $_SESSION["userid"] ?? null;
if (!$u_id) {
    header("Location: ../index.php");
    exit();
}

$query = "SELECT name, email, reg_no, mobile, department, password FROM lms_login WHERE u_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $u_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="icon" type="image/png" href="../images/logo1.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="../stylesheet/responsive.css">
    <link rel="stylesheet" href="../stylesheet/styles.css">
    <style>
        .profile-card {
            background: #fff;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            padding: 1.5rem;
        }

        .profile {
            box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
        }

        .form-switch {
            display: flex;
            flex-direction: row-reverse;
            gap: 50px;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="container-fluid students-section">
        <div class="row">
            <!-- Sidebar -->
            <?php include('sidebar.php'); ?>

            <!-- Main Content -->
            <div class="col-12 col-sm-10 col-md-9 col-lg-10 p-0">
                <!-- Topbar -->
                <?php include('topbar.php'); ?>

                <!-- Page Content -->
                <div class="p-4 content-scroll">
                    <div class="profile">


                        <!-- Personal Information & Password -->
                        <div class="profile-card">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3>User Profile</h3>
                                <div class="form-check form-switch toggle-switch">
                                    <input class="form-check-input" type="checkbox" id="editToggle">
                                    <label class="form-check-label">Edit Mode</label>
                                </div>
                            </div>
                            <form id="profileForm">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                            <input type="text" class="form-control" name="name"
                                                value="<?php echo htmlspecialchars($user['name']); ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                            <input type="email" class="form-control" name="email"
                                                value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Registration Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-card-heading"></i></span>
                                            <input type="text" class="form-control" name="reg_no"
                                                value="<?php echo htmlspecialchars($user['reg_no']); ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Mobile Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-phone-fill"></i></span>
                                            <input type="text" class="form-control" name="mobile"
                                                value="<?php echo htmlspecialchars($user['mobile']); ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Department</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-building"></i></span>
                                            <input type="text" class="form-control" name="department"
                                                value="<?php echo htmlspecialchars($user['department']); ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password"
                                                value="<?php echo htmlspecialchars($user['password']); ?>" disabled
                                                id="passwordField">
                                            <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                                <i class="bi bi-eye-fill"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>


                                <div class="d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-primary" id="saveBtn" style="display:none;">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Toggle edit mode
        $("#editToggle").on("change", function () {
            let isEditable = $(this).is(":checked");
            // Enable all inputs (including password) when edit mode is on
            $("#profileForm input").prop("disabled", !isEditable);
            $("#saveBtn").toggle(isEditable);
        });

        // Save profile (all fields)
        $("#profileForm").on("submit", function (e) {
            e.preventDefault();
            $.ajax({
                url: "../api/update_profile.php",
                type: "POST",
                data: $(this).serialize() + "&action=update_profile",
                dataType: "json",
                success: function (res) {
                    alert(res.message);
                    // Optionally, disable inputs again after save
                    $("#profileForm input").prop("disabled", true);
                    $("#editToggle").prop("checked", false);
                    $("#saveBtn").hide();
                },
                error: function () {
                    alert("Error updating profile.");
                }
            });
        });

        // Toggle password visibility
        $("#togglePassword").on("click", function () {
            let passwordInput = $("#passwordField");
            let icon = $(this).find("i");
            if (passwordInput.attr("type") === "password") {
                passwordInput.attr("type", "text");
                icon.removeClass("bi-eye-fill").addClass("bi-eye-slash-fill");
            } else {
                passwordInput.attr("type", "password");
                icon.removeClass("bi-eye-slash-fill").addClass("bi-eye-fill");
            }
        });

    </script>
</body>

</html>