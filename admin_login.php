<?php
session_start();

$login_error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_company_approvals.php");
        exit;
    } else {
        $login_error = "Username and password are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - FutureBot</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
        }
        
        html, body {
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e6f8e8 0%, #e4f0e8 100%);
            color: #2c3e50;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Animated Background */
        .background-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(67, 97, 238, 0.05);
            animation: float 15s infinite ease-in-out;
        }

        .circle:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .circle:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 70%;
            left: 80%;
            animation-delay: 2s;
        }

        .circle:nth-child(3) {
            width: 60px;
            height: 60px;
            top: 40%;
            left: 85%;
            animation-delay: 4s;
        }

        .circle:nth-child(4) {
            width: 100px;
            height: 100px;
            top: 80%;
            left: 15%;
            animation-delay: 6s;
        }

        .circle:nth-child(5) {
            width: 70px;
            height: 70px;
            top: 20%;
            left: 70%;
            animation-delay: 8s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0);
            }
            25% {
                transform: translateY(-20px) translateX(10px);
            }
            50% {
                transform: translateY(10px) translateX(-15px);
            }
            75% {
                transform: translateY(-15px) translateX(-10px);
            }
        }

        /* Navbar */
        nav {
            width: 100%;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(243, 253, 246, 0.95);
            box-shadow: 0 4px 20px rgba(71, 71, 71, 0.23);
            position: fixed;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(67, 97, 238, 0.1);
        }
        
        nav .logo {
            font-size: 1.8rem;
            font-weight: bold;
            letter-spacing: 1px;
            background: linear-gradient(90deg, #4361ee, #3a0ca3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        nav .logo i {
            font-size: 1.5rem;
        }
        
        nav .nav-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        nav .nav-buttons button {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        nav .nav-buttons button:hover {
            background: linear-gradient(135deg, #3a0ca3, #4361ee);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(67, 97, 238, 0.4);
        }

        /* Main Content */
        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            margin-top: 100px;
            padding: 0 20px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Login Card */
        .login-card {
            background: rgba(243, 253, 246, 0.95);
            box-shadow: 0 4px 20px rgba(71, 71, 71, 0.23);
            padding: 50px 40px;
            border-radius: 16px;
            border: 1px solid rgba(67, 97, 238, 0.1);
            position: relative;
            overflow: hidden;
            animation: slideUp 0.8s ease-out;
            width: 100%;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 0px;
            background: linear-gradient(90deg, #4361ee, #3a0ca3);
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            margin: 0 auto 25px;
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
            transition: all 0.3s ease;
        }

        .login-icon:hover {
            transform: scale(1.05) rotate(5deg);
            box-shadow: 0 12px 30px rgba(67, 97, 238, 0.4);
        }

        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
        }

        .login-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #4361ee, #3a0ca3);
            border-radius: 2px;
        }

        .login-subtitle {
            color: #5a6c7d;
            font-size: 1.1rem;
            margin-top: 25px;
            line-height: 1.6;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background: #f8f9fa;
            color: #2c3e50;
            font-size: 16px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #4361ee;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .form-input::placeholder {
            color: #95a5a6;
        }

        .form-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #4361ee;
            font-size: 1.1rem;
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
            position: relative;
            overflow: hidden;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
        }

        /* Alerts */
        .alert {
            background: rgba(231, 76, 60, 0.1);
            border-left: 4px solid #e74c3c;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
            word-wrap: break-word;
            animation: shake 0.5s ease;
            width: 100%;
        }

        .alert.alert-success {
            background: rgba(46, 204, 113, 0.1);
            border-left: 4px solid #2ecc71;
            color: #27ae60;
        }

        .alert.alert-danger {
            color: #c0392b;
        }

        /* Back Link */
        .back-link {
            margin-top: 30px;
            text-align: center;
            font-size: 15px;
            color: #7f8c8d;
        }

        .back-link a {
            color: #4361ee;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            border-radius: 8px;
            background: rgba(67, 97, 238, 0.05);
        }

        .back-link a:hover {
            background: rgba(67, 97, 238, 0.1);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.15);
        }

        /* Animations */
        @keyframes slideUp { 
            from { 
                opacity: 0; 
                transform: translateY(30px); 
            } 
            to { 
                opacity: 1; 
                transform: translateY(0); 
            } 
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        /* Footer Styles */
        footer {
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px 20px;
            margin-top: 50px;
            border-top: 1px solid rgba(67, 97, 238, 0.1);
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.05);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.5rem;
            font-weight: bold;
            background: linear-gradient(90deg, #4361ee, #3a0ca3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .footer-links {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .footer-links a {
            color: #5a6c7d;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .footer-links a:hover {
            color: #4361ee;
            transform: translateY(-2px);
        }

        .footer-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: #4361ee;
            transition: width 0.3s ease;
        }

        .footer-links a:hover::after {
            width: 100%;
        }

        .footer-social {
            display: flex;
            gap: 20px;
            margin: 10px 0;
        }

        .footer-social a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
        }

        .footer-social a:hover {
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 6px 15px rgba(67, 97, 238, 0.4);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(67, 97, 238, 0.1);
            width: 100%;
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            nav {
                padding: 15px 20px;
            }
            
            .main-content {
                padding: 20px 15px;
                margin-top: 80px;
            }
            
            .login-card {
                padding: 40px 30px;
            }
            
            .login-title {
                font-size: 1.8rem;
            }
            
            .login-icon {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }
        }

        @media (max-width: 500px) {
            nav { 
                flex-direction: column; 
                gap: 10px; 
                padding: 15px 20px;
            }
            
            nav .nav-buttons {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .login-card {
                padding: 30px 20px;
            }
            
            .login-title {
                font-size: 1.6rem;
            }
            
            .login-icon {
                width: 70px;
                height: 70px;
                font-size: 1.8rem;
            }
            
            .form-input {
                padding: 12px 15px 12px 40px;
                font-size: 15px;
            }
            
            .form-icon {
                left: 12px;
                font-size: 1rem;
            }
            
            .footer-links {
                gap: 15px;
            }
        }
    </style>
</head>
<body>

<!-- Animated Background -->
<div class="background-animation">
    <div class="circle"></div>
    <div class="circle"></div>
    <div class="circle"></div>
    <div class="circle"></div>
    <div class="circle"></div>
</div>

<!-- Navbar -->
<nav>
    <div class="logo">
        <i class="fas fa-robot"></i>FutureBot
    </div>
    <div class="nav-buttons">
        <button onclick="location.href='index.php'"><i class="fas fa-home"></i> Home</button>
        <button onclick="location.href='admin_dashboard.php'"><i class="fas fa-tachometer-alt"></i> Dashboard</button>
    </div>
</nav>

<div class="main-content">
    <div class="login-card">
        <div class="login-header">
            <div class="login-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h2 class="login-title">Admin Login</h2>
            <p class="login-subtitle">Secure access to FutureBot administration panel. Enter your credentials to continue.</p>
        </div>

        <?php if (!empty($login_error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($login_error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <i class="fas fa-user form-icon"></i>
                <input type="text" name="username" class="form-input" placeholder="Admin Username" required />
            </div>
            
            <div class="form-group">
                <i class="fas fa-lock form-icon"></i>
                <input type="password" name="password" class="form-input" placeholder="Password" required />
            </div>
            
            <button type="submit" class="submit-btn">
                <i class="fas fa-sign-in-alt"></i> Login to Admin Panel
            </button>
        </form>

        <div class="back-link">
            <a href="index.php">
                <i class="fas fa-arrow-left"></i> Back to Main Site
            </a>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    <div class="footer-content">
        <div class="footer-logo">
            <i class="fas fa-robot"></i>FutureBot
        </div>
        
        <div class="footer-links">
            <a href="index.php">Home</a>
            <a href="about.php">About Us</a>
            <a href="privacy.php">Privacy Policy</a>
            <a href="terms.php">Terms of Service</a>
            <a href="contact.php">Contact Us</a>
        </div>
        
        <div class="footer-social">
            <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" title="GitHub"><i class="fab fa-github"></i></a>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2024 FutureBot. All rights reserved. | Empowering students with AI-driven career guidance</p>
        </div>
    </div>
</footer>

<script>
    // Add interactive effects to form inputs
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.form-input');
        
        inputs.forEach(input => {
            // Add focus effect
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
                this.parentElement.style.transition = 'transform 0.3s ease';
            });
            
            // Remove focus effect
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Add loading state to submit button
        const form = document.querySelector('form');
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('.submit-btn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authenticating...';
            submitBtn.disabled = true;
        });
    });

    // Prevent horizontal overflow
    window.addEventListener('resize', function() {
        document.body.style.overflowX = 'hidden';
    });
</script>
</body>
</html>