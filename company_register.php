<?php
require 'db.php';

$msg = '';

// Define allowed Gmail addresses (only these can register)
$allowedEmails = [
    'approvedcompany1@gmail.com',
    'approvedcompany2@gmail.com',
    'samihamaisha231@gmail.com',
    // Add more allowed emails here
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Safely get POST data with null coalescing operator
    $name = $_POST['name'] ?? '';
    $year = $_POST['start_year'] ?? '';
    $license = $_POST['trade_license'] ?? '';
    $email = $_POST['email'] ?? '';
    $passwordRaw = $_POST['password'] ?? '';
    $address = $_POST['address'] ?? '';

    $password = $passwordRaw ? password_hash($passwordRaw, PASSWORD_DEFAULT) : '';

    $document_path = '';
    if (isset($_FILES['auth_document']) && $_FILES['auth_document']['error'] === 0) {
        $target_dir = "uploads/";
        // Use uniqid prefix to avoid filename collisions
        $filename = uniqid() . '_' . basename($_FILES["auth_document"]["name"]);
        $document_path = $target_dir . $filename;

        if (!move_uploaded_file($_FILES["auth_document"]["tmp_name"], $document_path)) {
            $msg = "<div class='alert alert-danger'>❌ Failed to upload document.</div>";
        }
    }

    // Check if email is in allowed list
    if (!in_array($email, $allowedEmails)) {
        $msg = "<div class='alert alert-danger'>❌ This email is not authorized for registration. Please contact admin.</div>";
    } elseif (empty($name) || empty($year) || empty($license) || empty($email) || empty($passwordRaw)) {
        $msg = "<div class='alert alert-danger'>❌ Please fill in all required fields.</div>";
    } elseif (empty($msg)) {
        // Insert new company with status 'pending'
        $stmt = $conn->prepare("INSERT INTO companies (name, start_year, trade_license, email, password, address, document_path, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("sisssss", $name, $year, $license, $email, $password, $address, $document_path);

        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>✅ Registration successful! Your account is pending admin approval.</div>";
        } else {
            $msg = "<div class='alert alert-danger'>❌ Error: " . htmlspecialchars($stmt->error) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Company Registration - FutureBot</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
      }

      .registration-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        width: 100%;
      }

      /* Info Card (like mentor profile card) */
      .info-card {
        background: rgba(243, 253, 246, 0.95);
        box-shadow: 0 4px 20px rgba(71, 71, 71, 0.23);
        padding: 40px;
        border-radius: 16px;
        border: 1px solid rgba(67, 97, 238, 0.1);
        position: relative;
        overflow: hidden;
        animation: slideUp 0.8s ease-out;
      }

      .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 0px;
        background: linear-gradient(90deg, #4361ee, #3a0ca3);
      }

      .info-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-bottom: 25px;
      }

      .info-icon {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        margin-bottom: 20px;
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
      }

      .info-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
        position: relative;
      }

      .info-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #4361ee, #3a0ca3);
        border-radius: 2px;
      }

      .info-subtitle {
        color: #5a6c7d;
        font-size: 1rem;
        line-height: 1.6;
        text-align: center;
        margin-bottom: 25px;
      }

      /* Info details section */
      .info-details {
        margin-bottom: 25px;
        padding-top: 10px;
      }

      .detail-item {
        display: flex;
        margin-bottom: 15px;
        align-items: flex-start;
      }

      .detail-item i {
        color: #4361ee;
        margin-right: 15px;
        margin-top: 2px;
        width: 16px;
        text-align: center;
        font-size: 1.1rem;
      }

      .detail-item span {
        color: #5a6c7d;
        font-size: 1rem;
        line-height: 1.4;
      }

      .requirements-section {
        margin-top: 25px;
        text-align: center;
        padding: 20px;
        background: rgba(67, 97, 238, 0.05);
        border-radius: 12px;
        border: 1px solid rgba(67, 97, 238, 0.1);
      }

      .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
        position: relative;
        padding-bottom: 10px;
      }

      .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, #4361ee, #3a0ca3);
      }

      .requirements-list {
        text-align: left;
        margin-left: 20px;
      }

      .requirements-list li {
        margin-bottom: 10px;
        color: #5a6c7d;
      }

      /* Registration Form Card */
      .registration-card {
        background: rgba(243, 253, 246, 0.95);
        box-shadow: 0 4px 20px rgba(71, 71, 71, 0.23);
        padding: 40px;
        border-radius: 16px;
        border: 1px solid rgba(67, 97, 238, 0.1);
        position: relative;
        overflow: hidden;
        animation: slideUp 0.8s ease-out 0.2s both;
      }

      .registration-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 0px;
        background: linear-gradient(90deg, #4361ee, #3a0ca3);
      }

      .form-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 25px;
        position: relative;
        padding-bottom: 10px;
      }

      .form-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #4361ee, #3a0ca3);
      }

      .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
      }

      .form-group {
        margin-bottom: 20px;
      }

      .form-group.full-width {
        grid-column: 1 / -1;
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
        padding: 12px 15px;
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

      textarea.form-input {
        resize: vertical;
        min-height: 100px;
      }

      .file-input {
        padding: 10px;
      }

      .file-upload-area {
        border: 2px dashed #e0e0e0;
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s ease;
        cursor: pointer;
      }

      .file-upload-area:hover {
        border-color: #4361ee;
        background: rgba(67, 97, 238, 0.05);
      }

      .file-upload-area i {
        font-size: 2.5rem;
        color: #4361ee;
        margin-bottom: 10px;
      }

      .file-upload-text {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
      }

      .file-upload-hint {
        color: #5a6c7d;
        font-size: 0.9rem;
      }

      .file-name {
        margin-top: 10px;
        font-size: 0.9rem;
        color: #4361ee;
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
        gap: 8px;
        margin-top: 10px;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
      }

      .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
      }

      /* Alerts */
      .alert {
        background: rgba(231, 76, 60, 0.1);
        border-left: 4px solid #e74c3c;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
        font-weight: 600;
        word-wrap: break-word;
        animation: shake 0.5s ease;
      }

      .alert.alert-success {
        background: rgba(46, 204, 113, 0.1);
        border-left: 4px solid #2ecc71;
        color: #27ae60;
      }

      .alert.alert-danger {
        color: #c0392b;
      }

      /* Helper Text */
      .helper-text {
        display: block;
        font-size: 0.85rem;
        color: #5a6c7d;
        margin-top: 5px;
      }

      /* Back Link */
      .back-link {
        text-align: center;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid rgba(67, 97, 238, 0.1);
      }

      .back-link a {
        color: #4361ee;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        transition: all 0.3s ease;
      }

      .back-link a:hover {
        background: rgba(67, 97, 238, 0.1);
        transform: translateY(-2px);
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
      @media (max-width: 1024px) {
        .registration-container {
          grid-template-columns: 1fr;
          gap: 30px;
        }
      }

      @media (max-width: 768px) {
        nav {
          padding: 15px 20px;
        }
        
        .main-content {
          padding: 20px 15px;
        }
        
        .info-card, .registration-card {
          padding: 30px 20px;
        }
        
        .form-grid {
          grid-template-columns: 1fr;
        }
        
        .info-icon {
          width: 100px;
          height: 100px;
          font-size: 2.5rem;
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
        
        .info-card, .registration-card {
          padding: 25px 15px;
        }
        
        .info-icon {
          width: 80px;
          height: 80px;
          font-size: 2rem;
        }
        
        .info-title {
          font-size: 1.5rem;
        }
        
        .form-title {
          font-size: 1.3rem;
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
    <button onclick="location.href='login.php'"><i class="fas fa-sign-in-alt"></i> Login</button>
  </div>
</nav>

<div class="main-content">
  <div class="registration-container">
    <!-- Info Card -->
    <div class="info-card">
      <div class="info-header">
        <div class="info-icon">
          <i class="fas fa-building"></i>
        </div>
        <h2 class="info-title">Company Registration</h2>
        <p class="info-subtitle">Register your company to join FutureBot's partner network and access exclusive features for employers.</p>
      </div>

      <div class="info-details">
        <div class="detail-item">
          <i class="fas fa-check-circle"></i>
          <span><strong>Benefits:</strong> Verified company profiles with enhanced credibility</span>
        </div>
        <div class="detail-item">
          <i class="fas fa-users"></i>
          <span><strong>Access:</strong> Connect with talented students and professionals</span>
        </div>
        <div class="detail-item">
          <i class="fas fa-bullhorn"></i>
          <span><strong>Post:</strong> Internships, jobs, and projects directly</span>
        </div>
        <div class="detail-item">
          <i class="fas fa-chart-line"></i>
          <span><strong>Analytics:</strong> Detailed insights and dashboard</span>
        </div>
      </div>

      <div class="requirements-section">
        <h3 class="section-title">Registration Requirements</h3>
        <ul class="requirements-list">
          <li>Valid trade license document</li>
          <li>Official company email address</li>
          <li>Company registration details</li>
          <li>Admin approval required (2-3 business days)</li>
        </ul>
      </div>

      <div class="detail-item" style="margin-top: 25px;">
        <i class="fas fa-info-circle"></i>
        <span><strong>Note:</strong> Only approved company emails can register. Contact admin for authorization.</span>
      </div>
    </div>

    <!-- Registration Form Card -->
    <div class="registration-card">
      <?php if ($msg): ?>
        <div class="alert <?php echo strpos($msg, 'successful') !== false ? 'alert-success' : 'alert-danger'; ?>">
          <?php echo $msg; ?>
        </div>
      <?php endif; ?>

      <h3 class="form-title">Company Details</h3>
      <form method="POST" enctype="multipart/form-data" id="registrationForm" novalidate>
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label" for="name">Company Name *</label>
            <input type="text" name="name" id="name" class="form-input" placeholder="Enter company name" required>
            <span class="helper-text">Official registered name of your company</span>
          </div>

          <div class="form-group">
            <label class="form-label" for="start_year">Year Established *</label>
            <input type="number" name="start_year" id="start_year" class="form-input" placeholder="e.g., 2020" min="1900" max="2024" required>
            <span class="helper-text">Year your company was founded</span>
          </div>

          <div class="form-group">
            <label class="form-label" for="trade_license">Trade License No. *</label>
            <input type="text" name="trade_license" id="trade_license" class="form-input" placeholder="Enter license number" required>
            <span class="helper-text">Government issued trade license number</span>
          </div>

          <div class="form-group">
            <label class="form-label" for="email">Company Email *</label>
            <input type="email" name="email" id="email" class="form-input" placeholder="company@example.com" required>
            <span class="helper-text">Official company email address</span>
          </div>

          <div class="form-group full-width">
            <label class="form-label" for="password">Password *</label>
            <input type="password" name="password" id="password" class="form-input" placeholder="Create a secure password" required>
            <span class="helper-text">Minimum 8 characters with letters and numbers</span>
          </div>

          <div class="form-group full-width">
            <label class="form-label" for="address">Company Address *</label>
            <textarea name="address" id="address" class="form-input" rows="3" placeholder="Full physical address of your company" required></textarea>
            <span class="helper-text">Include street, city, state, and zip code</span>
          </div>

          <div class="form-group full-width">
            <label class="form-label">Company Document *</label>
            <div class="file-upload-area" onclick="document.getElementById('auth_document').click()">
              <i class="fas fa-cloud-upload-alt"></i>
              <div class="file-upload-text">Click to upload company document</div>
              <div class="file-upload-hint">PDF, DOC, DOCX, JPG, PNG (Max 5MB)</div>
              <input type="file" class="file-input" name="auth_document" id="auth_document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="display: none;" required>
            </div>
            <div id="file-name" class="file-name"></div>
            <span class="helper-text">Upload your trade license or company registration document</span>
          </div>
        </div>

        <button type="submit" class="submit-btn">
          <i class="fas fa-paper-plane"></i> Submit Registration
        </button>

        <div class="back-link">
          <a href="login.php">
            <i class="fas fa-arrow-left"></i> Already have an account? Login here
          </a>
        </div>
      </form>
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
// Auto-hide success message after 5 seconds
const successMsg = document.querySelector('.alert.alert-success');
if (successMsg) {
    setTimeout(() => {
        successMsg.style.opacity = '0';
        successMsg.style.transition = 'opacity 1s ease';
        setTimeout(() => successMsg.remove(), 1000);
    }, 5000);
}

// Add some interactivity to form inputs
document.addEventListener('DOMContentLoaded', function() {
  const inputs = document.querySelectorAll('.form-input');
  
  inputs.forEach(input => {
    // Add focus effect
    input.addEventListener('focus', function() {
      this.parentElement.style.transform = 'scale(1.02)';
    });
    
    // Remove focus effect
    input.addEventListener('blur', function() {
      this.parentElement.style.transform = 'scale(1)';
    });
  });

  // File upload display
  const fileInput = document.getElementById('auth_document');
  const fileNameDisplay = document.getElementById('file-name');
  const fileUploadArea = document.querySelector('.file-upload-area');
  
  if (fileInput) {
    fileInput.addEventListener('change', function(e) {
      if (this.files.length > 0) {
        const file = this.files[0];
        const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
        
        if (fileSize > 5) {
          alert('File size exceeds 5MB limit. Please choose a smaller file.');
          this.value = '';
          fileNameDisplay.textContent = '';
          fileUploadArea.querySelector('.file-upload-text').textContent = 'Click to upload company document';
          fileUploadArea.querySelector('.file-upload-text').style.color = '';
          fileUploadArea.style.borderColor = '#e0e0e0';
          fileUploadArea.style.background = '#f8f9fa';
          return;
        }
        
        fileNameDisplay.textContent = `Selected: ${file.name} (${fileSize} MB)`;
        
        // Update file upload area appearance
        fileUploadArea.querySelector('.file-upload-text').textContent = 'File selected ✓';
        fileUploadArea.querySelector('.file-upload-text').style.color = '#27ae60';
        fileUploadArea.style.borderColor = '#27ae60';
        fileUploadArea.style.background = 'rgba(46, 204, 113, 0.1)';
      }
    });
  }

  // Password strength indicator
  const passwordInput = document.getElementById('password');
  if (passwordInput) {
    passwordInput.addEventListener('input', function() {
      const password = this.value;
      const helper = this.nextElementSibling;
      
      if (password.length === 0) {
        helper.textContent = 'Minimum 8 characters with letters and numbers';
        helper.style.color = '#5a6c7d';
      } else if (password.length < 8) {
        helper.textContent = 'Password too short (minimum 8 characters)';
        helper.style.color = '#e74c3c';
      } else if (!/\d/.test(password) || !/[a-zA-Z]/.test(password)) {
        helper.textContent = 'Include both letters and numbers';
        helper.style.color = '#f39c12';
      } else {
        helper.textContent = 'Strong password ✓';
        helper.style.color = '#27ae60';
      }
    });
  }

  // Form submission handling
  const form = document.getElementById('registrationForm');
  if (form) {
    form.addEventListener('submit', function(e) {
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      
      // Show loading state
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
      submitBtn.disabled = true;
      
      // Re-enable after 5 seconds if form doesn't submit
      setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      }, 5000);
    });
  }

  // Year validation
  const yearInput = document.getElementById('start_year');
  if (yearInput) {
    yearInput.addEventListener('blur', function() {
      const year = parseInt(this.value);
      const currentYear = new Date().getFullYear();
      
      if (year && (year < 1900 || year > currentYear)) {
        this.setCustomValidity(`Year must be between 1900 and ${currentYear}`);
        this.style.borderColor = '#e74c3c';
      } else {
        this.setCustomValidity('');
        if (this.value) this.style.borderColor = '#27ae60';
      }
    });
  }
});
</script>

</body>
</html>