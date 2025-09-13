<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Login</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/logo1.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="stylesheet/styles.css">
    <link rel="stylesheet" href="stylesheet/responsive.css">
</head>

<body>
    <?php include('navbar.php'); ?>

    <section class="login-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7 col-12">
                    <form class="login-form shadow p-4 rounded bg-light" id="loginForm">
                        <h3 class="text-center mb-4">Login</h3>

                        <!-- Username -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                                required autocomplete="off">
                            <label for="username">Username</label>
                        </div>

                        <!-- Password -->
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" required autocomplete="off">
                            <label for="password">Password</label>
                        </div>

                        <!-- Login Button -->
                        <button type="submit" class="btn btn-secondary w-100">Login</button>

                        <!-- Optional: Register link -->
                        <!-- <div class="text-center mt-3">
                            Don't have an account? <a href="admission-enquiry.php"
                                class="text-decoration-none">Register</a>
                        </div> -->
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>

</html>