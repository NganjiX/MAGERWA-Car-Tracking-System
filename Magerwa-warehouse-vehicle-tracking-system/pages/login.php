<?php
// pages/login.php
session_start();
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAGERWA - Premium Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-page">
    <!-- Animated Background Orbs -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="auth-card">
                    <!-- Logo -->
                    <div class="logo">
                        <div class="logo-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h1>MAGERWA</h1>
                        <p>Premium Vehicle Tracking System</p>
                    </div>

                    <!-- Login Form -->
                    <form id="loginForm" onsubmit="handleLogin(event)">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control" id="email" 
                                       placeholder="admin@company.com" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="password" 
                                       placeholder="Enter your password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn-auth">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </button>
                    </form>

                    <div id="message" class="mt-3"></div>

                    <div class="auth-links">
                        <p>Don't have an account? <a href="signup.php">Create Account</a></p>
                    </div>

                    <!-- Features -->
                    <div class="mt-4 pt-3 border-top border-light border-opacity-10">
                        <div class="row text-center text-white-50 small">
                            <div class="col-4">
                                <i class="fas fa-shield-alt d-block mb-1"></i>
                                Secure
                            </div>
                            <div class="col-4">
                                <i class="fas fa-bolt d-block mb-1"></i>
                                Fast
                            </div>
                            <div class="col-4">
                                <i class="fas fa-cloud d-block mb-1"></i>
                                Cloud
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script>
    function handleLogin(event) {
        event.preventDefault();
        
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const messageDiv = document.getElementById('message');
        const btn = event.target.querySelector('button[type="submit"]');
        
        // Show loading state
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing in...';
        btn.disabled = true;
        
        fetch('../api/auth.php?action=login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.innerHTML = `
                    <div class="alert alert-success border-0 shadow-sm animate-fade-up">
                        <i class="fas fa-check-circle me-2"></i>${data.message}
                    </div>
                `;
                setTimeout(() => {
                    window.location.href = 'dashboard.php';
                }, 1000);
            } else {
                messageDiv.innerHTML = `
                    <div class="alert alert-danger border-0 shadow-sm animate-fade-up">
                        <i class="fas fa-exclamation-circle me-2"></i>${data.message}
                    </div>
                `;
                btn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Sign In';
                btn.disabled = false;
            }
        })
        .catch(error => {
            messageDiv.innerHTML = `
                <div class="alert alert-danger border-0 shadow-sm animate-fade-up">
                    <i class="fas fa-exclamation-circle me-2"></i>Network error. Please try again.
                </div>
            `;
            btn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Sign In';
            btn.disabled = false;
        });
    }
    </script>
</body>
</html>