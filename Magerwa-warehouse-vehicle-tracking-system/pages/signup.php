<?php
// pages/signup.php
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
    <title>MAGERWA - Premium Sign Up</title>
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
                <div class="auth-card" style="max-width: 500px;">
                    <!-- Logo -->
                    <div class="logo">
                        <div class="logo-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h1>Create Account</h1>
                        <p>Join MAGERWA Premium Platform</p>
                    </div>

                    <!-- Signup Form -->
                    <form id="signupForm" onsubmit="handleSignup(event)" class="form-premium">
                        <div class="form-group">
                            <label for="names">Full Names</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" class="form-control" id="names" 
                                       placeholder="John Doe" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control" id="email" 
                                       placeholder="john@company.com" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-phone"></i>
                                </span>
                                <input type="tel" class="form-control" id="phone" 
                                       placeholder="0788000000" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="national_id">National ID</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-id-card"></i>
                                </span>
                                <input type="text" class="form-control" id="national_id" 
                                       placeholder="1234567890123456" required>
                            </div>
                            <small class="text-white-50" style="font-size: 0.75rem;">
                                <i class="fas fa-info-circle me-1"></i>16 digits required
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="password" 
                                       placeholder="Create a strong password" required>
                            </div>
                            <div class="password-strength mt-2" id="passwordStrength">
                                <div class="d-flex gap-1">
                                    <div class="flex-grow-1" style="height: 3px; background: rgba(255,255,255,0.1); border-radius: 2px;"></div>
                                    <div class="flex-grow-1" style="height: 3px; background: rgba(255,255,255,0.1); border-radius: 2px;"></div>
                                    <div class="flex-grow-1" style="height: 3px; background: rgba(255,255,255,0.1); border-radius: 2px;"></div>
                                    <div class="flex-grow-1" style="height: 3px; background: rgba(255,255,255,0.1); border-radius: 2px;"></div>
                                </div>
                                <small class="text-white-50" id="strengthText">Enter a password</small>
                            </div>
                        </div>

                        <button type="submit" class="btn-auth">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </button>
                    </form>

                    <div id="message" class="mt-3"></div>

                    <div class="auth-links">
                        <p>Already have an account? <a href="login.php">Sign In</a></p>
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
    // Password strength checker
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strength = checkPasswordStrength(password);
        const bars = document.querySelectorAll('#passwordStrength .flex-grow-1');
        const text = document.getElementById('strengthText');
        
        const colors = ['#dc3545', '#ffc107', '#0d6efd', '#198754'];
        const labels = ['Weak', 'Fair', 'Good', 'Strong'];
        
        bars.forEach((bar, index) => {
            if (index < strength) {
                bar.style.background = colors[strength - 1] || colors[0];
                bar.style.opacity = '1';
            } else {
                bar.style.background = 'rgba(255,255,255,0.1)';
                bar.style.opacity = '0.3';
            }
        });
        
        text.textContent = password.length > 0 ? labels[strength - 1] || 'Weak' : 'Enter a password';
        text.style.color = strength > 0 ? colors[strength - 1] : 'rgba(255,255,255,0.5)';
    });

    function checkPasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
        if (password.match(/\d/)) strength++;
        if (password.match(/[^a-zA-Z\d]/)) strength++;
        return strength;
    }

    function handleSignup(event) {
        event.preventDefault();
        
        const names = document.getElementById('names').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const national_id = document.getElementById('national_id').value;
        const password = document.getElementById('password').value;
        const messageDiv = document.getElementById('message');
        const btn = event.target.querySelector('button[type="submit"]');
        
        // Validate National ID
        if (!/^[0-9]{16}$/.test(national_id)) {
            window.MAGERWA.notifications.error('National ID must be exactly 16 digits');
            return;
        }
        
        // Show loading state
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating account...';
        btn.disabled = true;
        
        fetch('../api/auth.php?action=signup', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ names, email, phone, national_id, password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.MAGERWA.notifications.success('Account created successfully!');
                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 1500);
            } else {
                window.MAGERWA.notifications.error(data.message);
                btn.innerHTML = '<i class="fas fa-user-plus me-2"></i>Create Account';
                btn.disabled = false;
            }
        })
        .catch(error => {
            window.MAGERWA.notifications.error('Network error. Please try again.');
            btn.innerHTML = '<i class="fas fa-user-plus me-2"></i>Create Account';
            btn.disabled = false;
        });
    }
    </script>
</body>
</html>