<?php
session_start();
require_once 'db_connection.php';


if (!isset($_GET['employee_id']) || empty($_GET['employee_id'])) {
    ?>
    <script>
        const sid = sessionStorage.getItem("employee_id");
        if (sid) {
            window.location.href = window.location.pathname + "?employee_id=" + encodeURIComponent(sid);
        } else {
            document.body.innerHTML = "No employee_id found in session storage.";
        }
    </script>
    <?php
    exit;
}

$employee_id = $conn->real_escape_string($_GET['employee_id']);

if(!$employee_id) exit('Invalid Employee ID');

$stmt = $conn->prepare("SELECT * FROM employees WHERE employee_id=?");
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
$stmt->close();

// Dropdown options
$sex_options = ["Male","Female"];
$civil_status_options = ["Single","Married","Divorced","Widowed"];
$religion_options = ["Roman Catholic","Christian","Islam","Iglesia ni Cristo","Others"];
$suffix_options = ["N/A","Jr.","Sr.","I","II","III","IV","V","VI"];
$year_options = [1,2,3,4,5];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Info</title>





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
    <link rel="icon" type="image/x-icon" href="img/logo.ico" />





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

     <nav class="navbar navbar-expand-lg sticky-top">
      <div class="container">
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <i class="navbar-toggler-icon" id="menu"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <!-- Left side -->
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link "aria-current="page" href="employee_medical.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="employee_edit.php">Edit Health Info</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active"   style="color: yellow"  href="employee_info.php">Edit Personal Info</a>
            </li>
          </ul>

          <!-- Right side (Logout) -->
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="index.php">
                <i class="fa-solid fa-power-off"></i> Logout
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

  


    <div class="container mt-5">
        <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9"> 
              <h2 class="mb-4 text-center fw-bold">Edit your Health Information Form</h2>

        <div class="mb-3 p-3 rounded" style="background-color: #d1ecf1;">
            <p class="small mt-2 mb-0">
                Instructions: For items that are not Applicable, LEAVE IT BLANK. 
                Mark with (√) if YES, and Leave it Blank for NO
            </p>
        </div>



            <form method="POST" id="editEmployeeForm">

                <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employee['employee_id']); ?>">

                <div class="row mb-3">
                    <div class="col-md-4">
                    <label>Employee ID</label>
                    <input type="text" class="form-control" name="register_employee_id" value="<?php echo htmlspecialchars($employee['employee_id']); ?>" disabled>
                    </div>
                    <div class="col-md-4">
                      <label>Campus</label>
                        <select name="campus" class="form-select" id="campus" required>
                            <option value="SANTA MARIA" <?php if($employee['campus'] == 'SANTA MARIA') echo 'selected'; ?>>SANTA MARIA</option>
                            <option value="NARVACAN" <?php if($employee['campus'] == 'NARVACAN') echo 'selected'; ?>>NARVACAN</option>
                            <option value="CANDON" <?php if($employee['campus'] == 'CANDON') echo 'selected'; ?>>CANDON</option>
                            <option value="MAIN CAMPUS" <?php if($employee['campus'] == 'MAIN CAMPUS') echo 'selected'; ?>>MAIN CAMPUS</option>
                            <option value="TAGUDIN" <?php if($employee['campus'] == 'TAGUDIN') echo 'selected'; ?>>TAGUDIN</option>
                            <option value="CERVANTES" <?php if($employee['campus'] == 'CERVANTES') echo 'selected'; ?>>CERVANTES</option>
                            <option value="SANTIAGO" <?php if($employee['campus'] == 'SANTIAGO') echo 'selected'; ?>>SANTIAGO</option>
                        </select>

                    </div>
                    
                </div>

                
                <!-- Name Fields -->
                <div class="row mb-3">
                    <div class="col-md-4">
                    <label>Last Name</label>
                    <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($employee['last_name']); ?>" required>
                    </div>
                    <div class="col-md-4">
                    <label>First Name</label>
                    <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($employee['first_name']); ?>" required>
                    </div>
                    <div class="col-md-2">
                    <label>Middle Name</label>
                    <input type="text" class="form-control" name="middle_name" value="<?php echo htmlspecialchars($employee['middle_name']); ?>">
                    </div>
                    <div class="col-md-2">
                    <label>Suffix</label>
                    <select class="form-select" name="suffix">
                        <?php foreach($suffix_options as $suf): ?>
                        <option value="<?php echo $suf; ?>" <?php if($employee['suffix']==$suf) echo 'selected'; ?>><?php echo $suf; ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                    <label>Age</label>
                    <input type="number" class="form-control" name="age" value="<?php echo htmlspecialchars($employee['age']); ?>">
                    </div>
                    <div class="col-md-4">
                    <label>Birthdate</label>
                    <input type="date" class="form-control" name="birthdate" value="<?php echo htmlspecialchars($employee['birthdate']); ?>">
                    </div>
                    <div class="col-md-4">
                    <label>Sex</label>
                    <select class="form-select" name="sex">
                        <option value="">-- Select --</option>
                        <?php foreach($sex_options as $sex): ?>
                        <option value="<?php echo $sex; ?>" <?php if($employee['sex']==$sex) echo 'selected'; ?>><?php echo $sex; ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                    <label>Civil Status</label>
                    <select class="form-select" name="civil_status">
                        <option value="">-- Select --</option>
                        <?php foreach($civil_status_options as $cs): ?>
                        <option value="<?php echo $cs; ?>" <?php if($employee['civil_status']==$cs) echo 'selected'; ?>><?php echo $cs; ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                    <div class="col-md-6">
                    <label>Religion</label>
                    <select class="form-select" name="religion">
                        <option value="">-- Select --</option>
                        <?php foreach($religion_options as $rel): ?>
                        <option value="<?php echo $rel; ?>" <?php if($employee['religion']==$rel) echo 'selected'; ?>><?php echo $rel; ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                </div>

                <!-- Address & Contact -->
                <div class="mb-3">
                    <label>Permanent Address</label>
                    <input type="text" class="form-control" name="permanent_address" value="<?php echo htmlspecialchars($employee['permanent_address']); ?>">
                </div>
                <div class="mb-3">
                    <label>Phone Number</label>
                    <input type="text" class="form-control" name="phone_number" value="<?php echo htmlspecialchars($employee['phone_number']); ?>">
                </div>

                <hr>
                <h5>Contact Person (Emergency)</h5>
                <div class="row mb-3">
                    <div class="col-md-4">
                    <label>Full Name</label>
                    <input type="text" class="form-control" name="contact_person" value="<?php echo htmlspecialchars($employee['contact_person']); ?>">
                    </div>
                    <div class="col-md-4">
                    <label>Address</label>
                    <input type="text" class="form-control" name="contact_address" value="<?php echo htmlspecialchars($employee['contact_address']); ?>">
                    </div>
                    <div class="col-md-4">
                    <label>Contact Number</label>
                    <input type="text" class="form-control" name="contact_number" value="<?php echo htmlspecialchars($employee['contact_no']); ?>">
                    </div>
                </div>

                <hr>
                <div class="row mb-3">
                    <div class="col-md-6">
                    <label>Email</label>
                    <input type="email" class="form-control" name="register_email" value="<?php echo htmlspecialchars($employee['email']); ?>" required>
                    </div>
                    <div class="col-md-6">
                    <label>Password</label>
                    <input type="password" class="form-control" name="register_password" placeholder="Leave blank to keep current">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Update Employee</button>
                </form>




        
        </div>
        </div>
    </div>


    
    
    <?php include "footer.php"?>
    <!-- Make sure this is included at the bottom -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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


    <script>
   

    // Form submission
        document.getElementById('editEmployeeForm').addEventListener('submit', function(e){
            e.preventDefault();
            const formData = new FormData(this);
            fetch('update_employee.php', {method:'POST', body:formData})
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    Swal.fire({icon:'success', title:'Updated!', text:'Employee information updated.', timer:2000, showConfirmButton:false})
                    .then(() => location.reload());
                } else {
                    Swal.fire({icon:'error', title:'Error!', text:data.message});
                }
            });
        });

</script>
</body>
</html>