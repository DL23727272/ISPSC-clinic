<?php
require_once 'db_connection.php';
$student_id = $_GET['student_id'] ?? '';
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

<form method="POST" id="editStudentForm">

  <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student['student_id']); ?>">

  <div class="row mb-3">
    <div class="col-md-4">
      <label>Student ID</label>
      <input type="text" class="form-control" name="register_student_id" value="<?php echo htmlspecialchars($student['student_id']); ?>" required>
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

// Populate campus dropdown from data object
// const campusSelect = document.getElementById('campus');
// const departmentSelect = document.getElementById('department');
// const courseSelect = document.getElementById('course');
// const majorSelect = document.getElementById('major');

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

</script>
