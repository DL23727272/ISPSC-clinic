<?php
session_start();
require_once 'db_connection.php';

if (!isset($_GET['student_id']) || empty($_GET['student_id'])) {
    ?>
    <script>
        const sid = sessionStorage.getItem("student_id");
        if (sid) {
            window.location.href = window.location.pathname + "?student_id=" + encodeURIComponent(sid);
        } else {
            document.body.innerHTML = "No student_id found in session storage.";
        }
    </script>
    <?php
    exit;
}

$student_id = $conn->real_escape_string($_GET['student_id']);

if(!$student_id) exit('Invalid Student ID');

$stmt = $conn->prepare("SELECT * FROM students WHERE student_id=?");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

// Dropdown options
$sex_options = ["Male","Female"];
$civil_status_options = ["Single","Married","Divorced","Widowed"];
$religion_options = ["Roman Catholic","Christian","Islam","Iglesia ni Cristo","Others"];
$suffix_options = ["N/A","Jr.","Sr.","I","II","III","IV","V","VI"];
$semester_options = ["1ST SEMESTER","2ND SEMESTER","SUMMER/MIDYEAR"];
$year_options = [1,2,3,4,5];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Info</title>





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
              <a class="nav-link" aria-current="page" href="employee_medical.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="student_edit.php">Edit Health Info</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" style="color: yellow" href="student_info.php">Edit Personal Info</a>
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



            <form method="POST" id="editStudentForm">

                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student['student_id']); ?>">

                <div class="row mb-3">
                    <div class="col-md-4">
                    <label>Student ID</label>
                    <input type="text" class="form-control" name="register_student_id" value="<?php echo htmlspecialchars($student['student_id']); ?>" disabled>
                    </div>
                    <div class="col-md-4">
                    <label>Campus</label>
                        <select name="campus" class="form-select" id="campus" required data-current="<?php echo $student['campus']; ?>">
                            <option value="">SELECT CAMPUS</option>
                            <!-- Campus options will be populated by JS -->
                        </select>
                    </div>
                    <div class="col-md-4">
                    <label>Department</label>
                    <select class="form-select" name="department" id="department" data-current="<?php echo $student['department']; ?>">
                        <option value="">SELECT DEPARTMENT</option>
                    </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                    <label>Course</label>
                    <select class="form-select" name="course" id="course" data-current="<?php echo $student['course']; ?>">
                        <option value="">SELECT COURSE</option>
                    </select>
                    </div>
                    <div class="col-md-2">
                    <label>Year</label>
                    <select class="form-select" name="year">
                        <?php foreach($year_options as $y): ?>
                        <option value="<?php echo $y; ?>" <?php if($student['year']==$y) echo 'selected'; ?>><?php echo $y; ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                    <div class="col-md-3">
                    <label>Major</label>
                    <select class="form-select" name="major" id="major" data-current="<?php echo $student['major']; ?>">
                        <option value="">SELECT MAJOR</option>
                    </select>
                    </div>
                    <div class="col-md-3">
                    <label>Semester</label>
                    <select class="form-select" name="semester">
                        <?php foreach($semester_options as $sem): ?>
                        <option value="<?php echo $sem; ?>" <?php if($student['semester']==$sem) echo 'selected'; ?>><?php echo $sem; ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                </div>

                <!-- Name Fields -->
                <div class="row mb-3">
                    <div class="col-md-4">
                    <label>Last Name</label>
                    <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" required>
                    </div>
                    <div class="col-md-4">
                    <label>First Name</label>
                    <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($student['first_name']); ?>" required>
                    </div>
                    <div class="col-md-2">
                    <label>Middle Name</label>
                    <input type="text" class="form-control" name="middle_name" value="<?php echo htmlspecialchars($student['middle_name']); ?>">
                    </div>
                    <div class="col-md-2">
                    <label>Suffix</label>
                    <select class="form-select" name="suffix">
                        <?php foreach($suffix_options as $suf): ?>
                        <option value="<?php echo $suf; ?>" <?php if($student['suffix']==$suf) echo 'selected'; ?>><?php echo $suf; ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                    <label>Age</label>
                    <input type="number" class="form-control" name="age" value="<?php echo htmlspecialchars($student['age']); ?>">
                    </div>
                    <div class="col-md-4">
                    <label>Birthdate</label>
                    <input type="date" class="form-control" name="birthdate" value="<?php echo htmlspecialchars($student['birthdate']); ?>">
                    </div>
                    <div class="col-md-4">
                    <label>Sex</label>
                    <select class="form-select" name="sex">
                        <option value="">-- Select --</option>
                        <?php foreach($sex_options as $sex): ?>
                        <option value="<?php echo $sex; ?>" <?php if($student['sex']==$sex) echo 'selected'; ?>><?php echo $sex; ?></option>
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
                        <option value="<?php echo $cs; ?>" <?php if($student['civil_status']==$cs) echo 'selected'; ?>><?php echo $cs; ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                    <div class="col-md-6">
                    <label>Religion</label>
                    <select class="form-select" name="religion">
                        <option value="">-- Select --</option>
                        <?php foreach($religion_options as $rel): ?>
                        <option value="<?php echo $rel; ?>" <?php if($student['religion']==$rel) echo 'selected'; ?>><?php echo $rel; ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                </div>

                <!-- Address & Contact -->
                <div class="mb-3">
                    <label>Permanent Address</label>
                    <input type="text" class="form-control" name="permanent_address" value="<?php echo htmlspecialchars($student['permanent_address']); ?>">
                </div>
                <div class="mb-3">
                    <label>Phone Number</label>
                    <input type="text" class="form-control" name="phone_number" value="<?php echo htmlspecialchars($student['phone_number']); ?>">
                </div>

                <hr>
                <h5>Contact Person (Emergency)</h5>
                <div class="row mb-3">
                    <div class="col-md-4">
                    <label>Full Name</label>
                    <input type="text" class="form-control" name="contact_person" value="<?php echo htmlspecialchars($student['contact_person']); ?>">
                    </div>
                    <div class="col-md-4">
                    <label>Address</label>
                    <input type="text" class="form-control" name="contact_address" value="<?php echo htmlspecialchars($student['contact_address']); ?>">
                    </div>
                    <div class="col-md-4">
                    <label>Contact Number</label>
                    <input type="text" class="form-control" name="contact_number" value="<?php echo htmlspecialchars($student['contact_no']); ?>">
                    </div>
                </div>

                <hr>
                <div class="row mb-3">
                    <div class="col-md-6">
                    <label>Email</label>
                    <input type="email" class="form-control" name="register_email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                    </div>
                    <div class="col-md-6">
                    <label>Password</label>
                    <input type="password" class="form-control" name="register_password" placeholder="Leave blank to keep current">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Update Student</button>
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
    // Cascading dropdown data
    const data = {
    "MAIN CAMPUS": { departments: { "College of Teacher Education": { courses: ["Bachelor of Secondary Education","Bachelor of Elementary Education","Bachelor of Culture and the Arts Education","Bachelor of Physical Education"], majors: ["Science","English","Mathematics","Filipino","Social Studies","None"] }, "College of Arts and Sciences": { courses: ["Bachelor of Arts in English Language","Bachelor of Arts in Political Science","Bachelor of Science in Computer Science","Bachelor of Science in Midwifery","Bachelor of Science in Nursing"], majors: ["None"] }, "College of Business Education": { courses: ["Bachelor of Science in Business Administration","Bachelor of Science in Office Administration"], majors: ["Financial Management","Human Resources Development Management","None"] }, "School of Criminal Justice Education": { courses: ["Bachelor of Science in Criminology"], majors: ["None"] } } },
    "SANTA MARIA": { departments: { "College of Agriculture, Forestry, Engineering and Development Communication": { courses: ["Bachelor of Science in Agriculture","Bachelor of Science in Agricultural and Biosystems Engineering","Bachelor of Science in Forestry","Bachelor of Science in Agroforestry","Bachelor in Agricultural Technology","Bachelor of Science in Development Communication"], majors: ["Agronomy","Animal Husbandry","Horticulture","Post Harvest Technology","Agribusiness Management & Entrepreneurship","None"] }, "College of Teacher Education": { courses: ["Bachelor of Elementary Education","Bachelor of Secondary Education","Bachelor of Technology and Livelihood Education"], majors: ["Science","English","Mathematics","Filipino","Social Studies","Home Economics","Agro-Fishery Arts","None"] }, "College of Computing Studies": { courses: ["Bachelor of Science in Information Technology","Bachelor of Science in Information Systems"], majors: ["Web Development and Mobile Track","Networking and Cybersecurity Track","Business and Data Analytics","None"] }, "College of Business, Management and Entrepreneurship": { courses: ["Bachelor of Science in Hospitality Management"], majors: ["None"] } } },
    "NARVACAN": { departments: { "Fisheries Department": { courses: ["Bachelor of Science in Fisheries"], majors: ["None"] }, "Department of Teacher Education": { courses: ["Bachelor of Technology and Livelihood Education","Bachelor of Physical Education","Bachelor of Technical Vocational Teacher Education"], majors: ["Agri-fishery Arts","Home Economics","Fish Processing","Aquaculture","None"] } } },
    "SANTIAGO": { departments: { "Industrial Technology Department": { courses: ["Bachelor of Science in Industrial Technology","Bachelor of Science in Mechatronics Technology"], majors: ["Automotive Technology","Electrical Technology","Electronics Technology","Food Technology","Apparel Technology","None"] }, "Teacher Education Department": { courses: ["Bachelor in Technical-Vocational Teacher Education"], majors: ["Automotive Technology","Electrical Technology","Electronics Technology","Food and Service Management","Garments, Fashion, and Design"] } } },
    "TAGUDIN": { departments: { "College of Arts and Sciences": { courses: ["Bachelor of Science in Mathematics","Bachelor of Arts in Psychology","Bachelor of Arts in Social Science","Bachelor of Arts in English Language","Bachelor of Science in Information Technology","Bachelor of Science in Public Administration"], majors: ["Web Development and Mobile Track","None"] }, "College of Business, Management and Entrepreneurship": { courses: ["Bachelor of Science in Business Administration","Bachelor of Science in Entrepreneurship"], majors: ["Human Resource Development Management","Marketing Management","Financial Management","None"] }, "College of Teacher Education": { courses: ["Bachelor of Secondary Education","Bachelor of Elementary Education","Bachelor of Physical Education"], majors: ["Science","English","Mathematics","Filipino","Social Studies","None"] } } },
    "CANDON": { departments: { "College of Business and Hospitality Management": { courses: ["Bachelor of Science in Hospitality Management","Bachelor of Science in Tourism Management"], majors: ["None"] }, "College of Computing Studies": { courses: ["Bachelor of Science in Information Technology"], majors: ["None"] }, "College of Teacher Education": { courses: ["Bachelor of Secondary Education"], majors: ["Filipino"] } } },
    "CERVANTES": { departments: { "College of Arts and Science": { courses: [" Bachelor of Science in Information Technology","Bachelor of Science in Criminology"], majors: ["Web and Mobile Application","None"] }, "College of Teacher Education": { courses: ["Bachelor of Secondary Education","Bachelor of Elementary Education","Bachelor of Technology and Livelihood Education","Bachelor of Technical-Vocational Teacher Education"], majors: ["None","Mathematics","English","Science","Technology and Livelihood Education","Agri-Fishery Arts","Home Economics","Food Service Management","Agricultural Crops Production"] } } }
    };

    // Get dropdown elements
    const campusSelect = document.getElementById('campus');
    const departmentSelect = document.getElementById('department');
    const courseSelect = document.getElementById('course');
    const majorSelect = document.getElementById('major');


    function populateCampuses() {
        const currentCampus = campusSelect.getAttribute('data-current');
        campusSelect.innerHTML = '<option value="">SELECT CAMPUS</option>';
        Object.keys(data).forEach(campus => {
            const option = document.createElement('option');
            option.value = campus;
            option.textContent = campus;
            campusSelect.appendChild(option);
        });
        if(currentCampus) campusSelect.value = currentCampus;
        populateDepartments();
    }

    function populateDepartments() {
        const selectedCampus = campusSelect.value;
        departmentSelect.innerHTML = '<option value="">SELECT DEPARTMENT</option>';
        courseSelect.innerHTML = '<option value="">SELECT COURSE</option>';
        majorSelect.innerHTML = '<option value="">SELECT MAJOR</option>';

        if(data[selectedCampus]){
            Object.keys(data[selectedCampus].departments).forEach(dep => {
                const option = document.createElement('option');
                option.value = dep;
                option.textContent = dep;
                departmentSelect.appendChild(option);
            });
            const currentDep = departmentSelect.getAttribute('data-current');
            if(currentDep) departmentSelect.value = currentDep;
            populateCoursesAndMajors();
        }
    }

    function populateCoursesAndMajors() {
        const selectedCampus = campusSelect.value;
        const selectedDepartment = departmentSelect.value;
        courseSelect.innerHTML = '<option value="">SELECT COURSE</option>';
        majorSelect.innerHTML = '<option value="">SELECT MAJOR</option>';

        if(data[selectedCampus] && data[selectedCampus].departments[selectedDepartment]){
            const {courses, majors} = data[selectedCampus].departments[selectedDepartment];
            courses.forEach(c => {
                const option = document.createElement('option');
                option.value = c;
                option.textContent = c;
                courseSelect.appendChild(option);
            });
            majors.forEach(m => {
                const option = document.createElement('option');
                option.value = m;
                option.textContent = m;
                majorSelect.appendChild(option);
            });

            const currentCourse = courseSelect.getAttribute('data-current');
            const currentMajor = majorSelect.getAttribute('data-current');
            if(currentCourse) courseSelect.value = currentCourse;
            if(currentMajor) majorSelect.value = currentMajor;
        }
    }

    populateCampuses();
    campusSelect.addEventListener('change', populateDepartments);
    departmentSelect.addEventListener('change', populateCoursesAndMajors);

    // Form submission
        document.getElementById('editStudentForm').addEventListener('submit', function(e){
            e.preventDefault();
            const formData = new FormData(this);
            fetch('update_student.php', {method:'POST', body:formData})
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    Swal.fire({icon:'success', title:'Updated!', text:'Student information updated.', timer:2000, showConfirmButton:false})
                    .then(() => location.reload());
                } else {
                    Swal.fire({icon:'error', title:'Error!', text:data.message});
                }
            });
        });

</script>
</body>
</html>