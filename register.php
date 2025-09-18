<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />

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
    <nav class="navbar navbar-custom">
        <div class="container d-flex justify-content-between">
            <a class="navbar-brand fw-bold text-primary" href="#">LMS</a>
            <div>
                <a href="login.php" class="btn btn-outline-primary">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                </a>
            </div>
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
        document.getElementById("registerForm").addEventListener("submit", async function (e) {
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
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ fullname, email, password })
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