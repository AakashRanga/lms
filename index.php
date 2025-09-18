<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Applicant Login</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />

    <style>
        body {
            background: #f9fafb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Nav */
        .navbar-custom {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 2rem;
        }

        .navbar-custom .btn-outline-primary {
            border-color: #2563eb;
            color: #2563eb;
        }

        .navbar-custom .btn-primary {
            background-color: #2563eb;
            border: none;
        }

        /* Login Card */
        .login-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            max-width: 420px;
            margin: 4rem auto;
        }

        .login-card h2 {
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .login-card p {
            text-align: center;
            color: #6b7280;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .form-floating>.form-control:focus~label {
            color: #2563eb;
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
        }

        .forgot-link {
            text-align: right;
            display: block;
            margin-top: 0.25rem;
            font-size: 0.85rem;
        }

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }

        .register-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .btn:hover {
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="login-container">

        <!-- Top Navbar -->
        <nav class="navbar navbar-custom">
            <div class="container d-flex justify-content-between">
                <a class="navbar-brand fw-bold text-primary" href="#">LMS</a>
                <div class="d-flex gap-2">
                    <a href="register.php"><button class="btn btn-outline-primary">Sign Up</button></a>
                    <a href="index.php"> <button class="btn btn-primary">Login</button></a>
                </div>
            </div>
        </nav>

        <!-- Login Card -->
        <div class="login-card">
            <h2>Welcome Back</h2>
            <p>Log in to your account to continue building amazing flows.</p>

            <form id="loginForm">

                <!-- Email -->
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" placeholder="name@example.com" required>
                    <label for="email">Email</label>
                </div>

                <!-- Password -->
                <div class="form-floating mb-2 position-relative">
                    <input type="password" class="form-control" id="password" placeholder="Password" required>
                    <label for="password">Password</label>
                    <i class="bi bi-eye toggle-password" id="togglePassword"></i>
                </div>
                <a href="#" class="forgot-link">Forgot Password?</a>

                <!-- Login Button -->
                <button type="submit" class="btn btn-primary w-100 mt-3">Login</button>

                <!-- Register Link -->
                <div class="register-link">
                    Don't have an account? <a href="register.php">Register Now</a>
                </div>

            </form>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            togglePassword.classList.toggle('bi-eye');
            togglePassword.classList.toggle('bi-eye-slash');
        });

        // Handle login
        document.getElementById("loginForm").addEventListener("submit", async function (e) {
            e.preventDefault();
            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value.trim();
            if (!email || !password) return alert("Please enter both email and password");

            try {
                const res = await fetch("api/loginbk.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ email, password })
                });
                const data = await res.json();

                if (data.status === 200) {
                    if (data.user_type === "Admin") window.location.href = "admin/add_courses.php";
                    else if (data.user_type === "Student") window.location.href = "student/student_reg.php";
                    else if (data.user_type === "Faculty") window.location.href = "faculty/dashboard.php";
                    else alert("Unknown role. Please contact support.");
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