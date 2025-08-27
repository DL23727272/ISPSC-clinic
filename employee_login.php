<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Portal | ISPSC CLINICA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="icon" type="image/x-icon" href="img/logo.ico" />
  <style>
    /* Keep form readable on very small devices */
    .card {
      border-radius: 12px;
    }
    @media (max-width: 576px) {
      h1 { font-size: 1.6rem; }
      p { font-size: 0.9rem; }
    }
  </style>
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10 col-xl-9"> <!-- Wider on large screens, but full width on mobile -->
        
        <!-- Header -->
        <div class="text-center mb-4">
          <div class="text-success mb-3" style="font-size:3rem;">
            <i class="fas fa-user-md"></i>
          </div>
          <h1 class="fw-bold">Employee Portal</h1>
          <p class="text-muted">Access your medical records and forms</p>
        </div>

        <!-- Card -->
        <div class="card shadow-sm p-4">
          
          <!-- Tabs -->
          <ul class="nav nav-pills justify-content-center mb-4 flex-wrap" id="authTabs">
            <li class="nav-item me-2 mb-2">
              <button class="nav-link active " id="login-tab" data-bs-toggle="tab" data-bs-target="#login-form">Login</button>
            </li>
            <li class="nav-item mb-2">
              <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register-form">Register</button>
            </li>
          </ul>

          <div class="tab-content">

           <!-- Login Form -->
                <div class="tab-pane fade show active" id="login-form">
                    <form method="POST" id="loginForm">
                        <div class="mb-3">
                        <label for="employee_id" class="form-label">Employee ID/Email</label>
                        <input type="text" id="employee_id" name="employee_id" class="form-control" placeholder="Enter your Employee ID or Email" required>
                        </div>
                        <div class="mb-3">
                        <label for="login_password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" id="login_password" name="password" class="form-control" placeholder="Enter your password" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleLoginPassword">
                            <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </form>
                </div>

          <!-- Register Form -->
          <div class="tab-pane fade" id="register-form">
            <form method="POST" class="mt-3" id="registerForm">

           

              <!-- Campus -->
           
              <div class="mb-3 row">
                <div class="col-md-4">
                  <label class="form-label">Employee ID</label>
                  <input type="text" class="form-control" name="register_employee_id" required>
                </div>
                <div class="col-md-4">
                <label class="form-label">Campus</label>
                <select id="campus" name="campus" class="form-select" required>
                  <option value="">SELECT CAMPUS</option>
                  <option value="SANTA MARIA">SANTA MARIA</option>
                  <option value="NARVACAN">NARVACAN</option>
                  <option value="CANDON">CANDON</option>
                  <option value="MAIN CAMPUS">MAIN CAMPUS</option>
                  <option value="TAGUDIN">TAGUDIN</option>
                  <option value="CERVANTES">CERVANTES</option>
                  <option value="SANTIAGO">SANTIAGO</option>
                </select>
                </div>
              </div>
                <hr>
              

              <!-- Name -->
              <div class="row mb-3">
                <div class="col-md-4">
                  <label class="form-label">Last Name</label>
                  <input type="text" class="form-control" name="last_name" required>
                </div>
                <div class="col-md-4">
                  <label class="form-label">First Name</label>
                  <input type="text" class="form-control" name="first_name" required>
                </div>
                <div class="col-md-2">
                  <label class="form-label">Middle Name</label>
                  <input type="text" class="form-control" name="middle_name">
                </div>
                <div class="col-md-2">
                  <label class="form-label">Suffix</label>
                  <select class="form-select" name="suffix">
                    <option value="N/A">N/A</option>
                    <option value="Jr.">Jr.</option>
                    <option value="Sr.">Sr.</option>
                    <option value="I">I</option>
                    <option value="II">II</option>
                    <option value="III">III</option>
                    <option value="IV">IV</option>
                    <option value="V">V</option>
                    <option value="VI">VI</option>
                  </select>
                </div>
              </div>

              <!-- Age, Birthdate -->
              <div class="row mb-3">
                <div class="col-md-4">
                  <label class="form-label">Age</label>
                  <input type="number" class="form-control" name="age">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Birthdate</label>
                  <input type="date" class="form-control" name="birthdate">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Sex</label>
                  <select class="form-select" name="sex">
                    <option value="">-- Select --</option>
                    <option>Male</option>
                    <option>Female</option>
                  </select>
                </div>
              </div>

              <!-- Civil Status, Religion -->
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Civil Status</label>
                  <select class="form-select" name="civil_status">
                    <option value="">-- Select --</option>
                    <option>Single</option>
                    <option>Married</option>
                    <option>Divorced</option>
                    <option>Widowed</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Religion</label>
                  <select class="form-select" name="religion">
                    <option value="">-- Select --</option>
                    <option>Roman Catholic</option>
                    <option>Christian</option>
                    <option>Islam</option>
                    <option>Iglesia ni Cristo</option>
                    <option>Others</option>
                  </select>
                </div>
              </div>

              <!-- Permanent Address & Phone -->
              <div class="mb-3">
                <label class="form-label">Permanent Address</label>
                <input type="text" class="form-control" name="permanent_address">
              </div>
              <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" class="form-control" name="phone_number">
              </div>

              <!-- Contact Person -->
              <hr>
              <h5>Contact Person (In case of Emergency)</h5>
              <div class="row mb-3">
                <div class="col-md-4">
                  <label class="form-label">Full Name</label>
                  <input type="text" class="form-control" name="contact_person">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Address</label>
                  <input type="text" class="form-control" name="contact_address">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Contact Number</label>
                  <input type="text" class="form-control" name="contact_number">
                </div>
              </div>

              <!-- Email & Password -->
              <hr>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Email</label>
                  <input type="email" class="form-control" name="register_email" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="register_password" name="register_password" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                      <i class="bi bi-eye"></i>
                    </button>
                  </div>
                </div>
              </div>

              <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-user-plus"></i> Register
              </button>

            </form>
          </div>
          <!-- End register form -->

          </div>
        </div>

        <!-- Back link -->
        <div class="text-center mt-5 pb-4">
          <a href="index.php" class="text-success text-decoration-none">
            <i class="fas fa-arrow-left"></i> Back to Home
          </a>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/employee_auth.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {

      function setupPasswordToggle(toggleId, inputId) {
        const toggleBtn = document.querySelector(toggleId);
        const passwordInput = document.querySelector(inputId);

        if (toggleBtn && passwordInput) {
          toggleBtn.addEventListener("click", function() {
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);

            // Toggle the eye / eye-slash icon
            this.querySelector("i").classList.toggle("bi-eye");
            this.querySelector("i").classList.toggle("bi-eye-slash");
          });
        }
      }

      // Attach toggles immediately for both login and register
      setupPasswordToggle("#toggleLoginPassword", "#login_password");
      setupPasswordToggle("#togglePassword", "#register_password");

    });

   



  </script>

</body>
</html>
