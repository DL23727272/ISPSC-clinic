<?php
// Converted from HTML to PHP - add backend logic below as needed
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISPSC CLINICA | Medical Records Management</title>
    <link rel="icon" type="image/x-icon" href="img/logo.ico" />





    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <script src="
    https://cdn.jsdelivr.net/npm/sweetalert2@11.22.4/dist/sweetalert2.all.min.js
    "></script>
    <link href="
    https://cdn.jsdelivr.net/npm/sweetalert2@11.22.4/dist/sweetalert2.min.css
    " rel="stylesheet">
    <link href="styles.css" rel="stylesheet" />





    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    
    <header class="header">
      <div class="container">
        <div
          class="d-flex flex-column align-items-center justify-content-center text-center"
        >
          <div>
            <img
              src="img/ispsc.png"
              alt="ISPSC Logo"
              width="100"
              height="100"
              class="me-3"
            />
            <img
              class="bagong-pilipinas"
              src="img/bagong-pilipinas.png"
              alt="ISPSC Logo"
              width="120"
              height="120"
              class="me-3"
            />
          </div>
          <div>
            <h1 class="ispsc-logo mb-0">REPUBLIC OF THE PHILIPPINES</h1>
            <hr class="my-2 border-white" />
            <h1 class="ispsc-logo mb-0">
              ILOCOS SUR POLYTECHNIC STATE COLLEGE
            </h1>
            <h2 class="ispsc-logo mb-0">ILOCOS SUR, PHILIPPINES</h2>
          </div>
        </div>
      </div>
    </header>
    
    <div class="login-container">
        <header class="login-header">
            <h1>ISPSC CLINICA</h1>
            <p class="tagline">Secure • Efficient • Comprehensive Medical Records Management</p>
            <!-- Admin Icon Button -->
            <button id="show-admin-btn" class="admin-icon-btn" title="Admin Access" style="background:none;border:none;cursor:pointer;">
                <i class="fas fa-lock" style="font-size:1.5em;color:#333;"></i>
            </button>
        </header>

        <div class="access-options">
            <!-- Student QR Access -->
            <div class="access-card student-access">
                <div class="card-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h3>Student Access</h3>
                <p>Scan to Register or Log In for medical form</p>
                <button id="scan-qr-btn" class="btn btn-primary m-2">
                    <i class="fas fa-qrcode"></i> Scan QR Code
                </button>
                <div id="qr-scanner" class="qr-scanner hidden">
                    <div class="scanner-placeholder"></div>
                    <p class="scanner-instruction">Point your camera at your student QR code</p>
                    <button id="cancel-scan" class="btn btn-secondary">Cancel</button>
                </div>
                
                <a href="student_login.php" class="btn btn-primary m">
                    <i class="fas fa-sign-in-alt"></i> Student Login
                </a>
            </div>

            <!-- Employee Access -->
            <div class="access-card employee-access">
                <div class="card-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3>Employee Portal</h3>
                <p>Register or login to access medical form</p>
                <a href="employee_login.php" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Employee Login
                </a>
            </div>

            <!-- Admin Access (hidden by default) -->
            <div class="access-card admin-access" id="admin-access-card" style="display:none;">
                <div class="card-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h3>Admin Dashboard</h3>
                <p>Secure access to manage all medical records and reports</p>
                <a href="admin_login.php" class="btn btn-primary">
                    <i class="fas fa-shield-alt"></i> Admin Access
                </a>
            </div>
        </div>

       
    </div>
    <?php include "footer.php"?>
    <script>
    // Toggle admin card and other cards on icon click
    document.getElementById('show-admin-btn').onclick = function() {
        var adminCard = document.getElementById('admin-access-card');
        var studentCard = document.querySelector('.student-access');
        var employeeCard = document.querySelector('.employee-access');
        if (adminCard.style.display === 'block') {
            adminCard.style.display = 'none';
            studentCard.style.display = 'block';
            employeeCard.style.display = 'block';
        } else {
            adminCard.style.display = 'block';
            studentCard.style.display = 'none';
            employeeCard.style.display = 'none';
        }
    };
    </script>
    <script src="assets/js/script.js"></script>
</body>
</html>