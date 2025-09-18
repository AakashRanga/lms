<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/logo1.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --logo-blue: #1976d2;
            --deep-blue: #0d47a1;
            --light-blue: #bbdefb;
            --contrast-orange: #ff6e40;
            --dark-text: #263238;
            --light-text: #f5f5f5;
            --gradient-start: #1a2980;
            --gradient-end: #26d0ce;
        }
    </style>

    <style>
        .navbar-brand {
            display: flex;
            align-items: flex-end;
            padding: 0.5rem 0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            gap: 10px;
        }

        .navbar-brand:hover {
            transform: translateY(-2px);
        }

        .navbar-brand::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--contrast-orange), transparent);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }

        .navbar-brand:hover::after {
            transform: translateX(100%);
        }

        .navbar-custom {
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            padding: 0.6rem 1rem;
            position: relative;
            z-index: 1000;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.5;
            z-index: -1;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .brand-name,
        .brand-name-offcanvas {
            font-weight: 700;
            font-size: 1.5rem;
            color: #2563eb;
            letter-spacing: -0.5px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
        }

        .brand-tagline,
        .brand-tagline-offcanvas {
            font-size: 0.7rem;
            color: #2563eb;
            font-weight: 400;
            letter-spacing: 1px;
        }

        @media (max-width: 576px) {
            .brand-tagline {
                display: none;
            }

            .brand-name {
                font-size: 1.2rem;
            }

            .btn-custom {
                padding: 0.4rem 1rem;
                font-size: 0.9rem;
            }

            .right-btn {
                display: none !important;
            }

            .sidebar-off a {
                text-decoration: none;
                color: #000;
            }

            .brand-name-offcanvas {
                font-size: small !important;
            }

            .navbar-custom {
                padding: 2px !important;
            }
        }

        @media (min-width:768px) {
            .btn-offcanvas {
                display: none !important;
            }
        }
    </style>
    <style>
        body {
            background: #f9fafb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Top Navbar */
        .navbar-custom {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 2rem;
        }

        .navbar-custom .btn-outline-primary {
            border-color: #2563eb;
            color: #2563eb;
        }

        .navbar-custom .btn-outline-primary:hover {
            background: #2563eb;
            color: #fff;
        }

        /* Register Card */
        .register-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            max-width: 480px;
            margin: 1.5rem auto;
        }

        .register-card h2 {
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .register-card p {
            text-align: center;
            color: #6b7280;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .password-hint {
            font-size: 0.85rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .forgot-link {
            text-align: right;
            display: block;
            margin-top: 0.25rem;
            font-size: 0.85rem;
        }

        .signin-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }

        .signin-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .signin-link a:hover {
            text-decoration: underline;
        }

        /* Footer */
        footer {
            margin-top: 4rem;
            border-top: 1px solid #e5e7eb;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #6b7280;
            font-size: 0.9rem;
        }

        .social-icons a {
            color: #6b7280;
            margin-left: 1rem;
            font-size: 1.2rem;
        }

        .social-icons a:hover {
            color: #2563eb;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <!-- <nav class="navbar navbar-custom">
        <div class="container d-flex justify-content-between">
            <a class="navbar-brand fw-bold text-primary" href="#">LMS</a>
            <div>
                <a href="login.php" class="btn btn-outline-primary">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                </a>
            </div>
        </div>
    </nav> -->
    <nav class="navbar navbar-custom navbar-expand-lg">
        <div class="container d-flex justify-content-between">


            <!-- Logo & Brand -->
            <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="#">
                <img src="images/logo1.png" style="width:40px;" alt="">
                <div class="brand-text ms-2">
                    <span class="brand-name">LMS Portal</span>
                    <span class="brand-tagline small">SIMATS DEEMED UNIVERSITY</span>
                </div>
            </a>

            <!-- Right side buttons -->
            <div class="right-btn d-flex gap-2 ">
                 <div>
                <a href="index.php" class="btn btn-outline-primary">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Login
                </a>
            </div>
                <!-- <a href="index.php"><button class="btn btn-primary">Login</button></a> -->
            </div>
            <!-- Offcanvas Toggle Button (Visible only on small screens) -->
            <button class="btn-offcanvas btn btn-primary d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </nav>

    <!-- Register Card -->
    <div class="register-card">
        <h2>Create Your Account</h2>
        <form id="registerForm">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="fullname" placeholder="John Doe" required>
                <label for="fullname">Full Name</label>
            </div>

            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" placeholder="john.doe@example.com" required>
                <label for="email">Email</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" placeholder="********" required>
                <label for="password">Password</label>
            </div>
            <div class="password-hint">Must be at least 8 characters long.</div>

            <div class="form-floating mt-3">
                <input type="password" class="form-control" id="confirm_password" placeholder="********" required>
                <label for="confirm_password">Confirm Password</label>
            </div>

            <a href="#" class="forgot-link">Forgot Password?</a>

            <button type="submit" class="btn btn-primary w-100 mt-3">Register</button>

            <div class="signin-link">
                Already have an account? <a href="index.php">Sign In</a>
            </div>
        </form>
    </div>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById("registerForm").addEventListener("submit", async function(e) {
            e.preventDefault();
            const fullname = document.getElementById("fullname").value.trim();
            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value.trim();
            const confirm = document.getElementById("confirm_password").value.trim();

            if (!fullname || !email || !password || !confirm) return alert("Please fill all fields");
            if (password !== confirm) return alert("Passwords do not match");
            if (password.length < 8) return alert("Password must be at least 8 characters");

            try {
                const res = await fetch("api/register.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        fullname,
                        email,
                        password
                    })
                });
                const data = await res.json();
                if (data.status === 200) {
                    alert("Registration successful!");
                    window.location.href = "login.php";
                } else {
                    alert(data.message);
                }
            } catch (err) {
                console.error(err);
                alert("Something went wrong. Please try again.");
            }
        });
    </script>
</body>

</html>