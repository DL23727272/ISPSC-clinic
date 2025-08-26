<?php
session_start();
require_once 'db_connection.php';

// Fetch distinct campuses for filter dropdown
$campuses = [];
$result = $conn->query("SELECT DISTINCT campus FROM students ORDER BY campus ASC");
while($row = $result->fetch_assoc()){
    $campuses[] = $row['campus'];
}

// Get search and campus filter values
$search = isset($_GET['search']) ? $_GET['search'] : '';
$campus_filter = isset($_GET['campus']) ? $_GET['campus'] : '';

// Prepare query
$sql = "SELECT s.student_id, s.campus, s.department, s.course, s.year, s.semester,
               s.last_name, s.first_name, s.middle_name, s.age, s.sex,
               s.permanent_address, s.phone_number, s.email,
               sh.blood_type, sh.allergy_alert, sh.disability, sh.created_at AS health_record_date
        FROM students s
        LEFT JOIN student_health_info sh ON s.student_id = sh.student_id
        WHERE 1";

if($search != ''){
    $search_esc = $conn->real_escape_string($search);
    $sql .= " AND (s.first_name LIKE '%$search_esc%' OR s.last_name LIKE '%$search_esc%' OR s.student_id LIKE '%$search_esc%')";
}

if($campus_filter != ''){
    $campus_esc = $conn->real_escape_string($campus_filter);
    $sql .= " AND s.campus = '$campus_esc'";
}

$sql .= " ORDER BY s.last_name ASC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | ISPSC CLINICA</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

   <style>
        /* Filters/Search */
        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        .filters input[type=text],
        .filters select {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
        }
        .filters input[type=text]:focus,
        .filters select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 5px rgba(52,152,219,0.3);
        }
        .filters button {
            padding: 8px 16px;
            border: none;
            background: var(--secondary-color);
            color: #fff;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .filters button:hover {
            background: #2980b9;
        }

       /* Table with grid lines */
        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            background: #fff;
            border: 1px solid #ccc; /* outer border */
        }
        table thead {
            background: var(--secondary-color);
            color: #fff;
        }
        table th, table td {
            padding: 12px 10px;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid #ddd; /* horizontal grid lines */
            border-right: 1px solid #ddd;  /* vertical grid lines */
        }
        table th:last-child, table td:last-child {
            border-right: none; /* remove right border on last column */
        }
        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        table tbody tr:hover {
            background: #e8f0fe;
        }
        table th:first-child, table td:first-child {
            border-left: 1px solid #ddd; /* left border for first column */
        }

        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --employee-color: #27ae60;
            --admin-color: #e74c3c;
            --warning-color: #f39c12;
            --purple-color: #9b59b6;
            --light-gray: #ecf0f1;
            --dark-gray: #7f8c8d;
            --white: #ffffff;
            --box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            --sidebar-width: 280px;
        }
        * {margin:0;padding:0;box-sizing:border-box;}
        body {font-family:'Roboto',sans-serif;background-color:var(--light-gray);color:var(--primary-color);}
        .dashboard-container {display:flex;min-height:100vh;}
        .header {position:fixed;top:0;left:0;right:0;height:70px;background:var(--white);border-bottom:1px solid #e0e0e0;display:flex;align-items:center;justify-content:space-between;padding:0 2rem;z-index:1000;box-shadow:var(--box-shadow);}
        .header-left {display:flex;align-items:center;gap:1rem;}
        .logo {display:flex;align-items:center;gap:0.5rem;font-size:1.25rem;font-weight:700;color:var(--secondary-color);}
        .logo i {font-size:1.5rem;}
        .header-title {font-size:1.1rem;color:var(--dark-gray);font-weight:400;}
        .header-right {display:flex;align-items:center;gap:1rem;}
        .user-info {display:flex;align-items:center;gap:0.5rem;color:var(--dark-gray);font-size:0.95rem;cursor:pointer;padding:0.5rem 0.75rem;border-radius:6px;transition:all 0.3s ease;border:none;background:transparent;font-family:'Roboto',sans-serif;}
        .user-info:hover {background-color:var(--light-gray);color:var(--primary-color);}
        .logout-btn {color:var(--dark-gray);font-size:1.1rem;cursor:pointer;transition:color 0.3s ease;}
        .logout-btn:hover {color:var(--admin-color);}
        .logout-message {position:fixed;top:90px;right:2rem;background:#27ae60;color:#fff;padding:1rem 1.5rem;border-radius:8px;box-shadow:var(--box-shadow);font-size:0.95rem;font-weight:500;display:none;z-index:1001;animation:slideIn 0.3s ease;}
        .logout-message.show {display:flex;align-items:center;gap:0.5rem;}
        @keyframes slideIn {from{transform:translateX(100%);opacity:0;}to{transform:translateX(0);opacity:1;}}
        .sidebar {width:var(--sidebar-width);background:var(--white);border-right:1px solid #e0e0e0;padding-top:70px;position:fixed;height:100vh;overflow-y:auto;}
        .sidebar-menu {padding:2rem 0;}
        .menu-item {display:flex;align-items:center;gap:1rem;padding:1rem 2rem;color:var(--dark-gray);text-decoration:none;font-size:0.95rem;font-weight:500;transition:all 0.3s ease;border-left:3px solid transparent;}
        .menu-item:hover {background-color:var(--light-gray);color:var(--primary-color);}
        .menu-item.active {background-color:var(--secondary-color);color:#fff;border-left-color:var(--secondary-color);}
        .menu-item i {font-size:1.1rem;width:20px;}
        .main-content {flex:1;margin-left:var(--sidebar-width);padding-top:70px;padding:70px 2rem 2rem 2rem;}
        .info-cards {display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1.5rem;}
        .info-card {background:#fff;border-radius:12px;padding:1.5rem;box-shadow:var(--box-shadow);display:flex;align-items:center;gap:1rem;}
        .card-icon {width:50px;height:50px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:#fff;}
        .card-icon.blue {background-color:var(--secondary-color);}
        .card-icon.green {background-color:var(--employee-color);}
        .card-icon.yellow {background-color:var(--warning-color);}
        .card-icon.purple {background-color:var(--purple-color);}
        .card-icon.red {background-color:var(--admin-color);}
        .card-icon.campus {background-color:#16a085;}
        .card-content h3 {font-size:0.9rem;color:var(--dark-gray);font-weight:500;margin-bottom:0.25rem;}
        .card-content .count {font-size:2rem;font-weight:700;color:var(--primary-color);}
        @media(max-width:768px){.sidebar{transform:translateX(-100%);transition:transform 0.3s ease;}.main-content{margin-left:0;padding:70px 1rem 2rem 1rem;}.header{padding:0 1rem;}.info-cards{grid-template-columns:1fr;}.user-info span{display:none;}.logout-message{right:1rem;left:1rem;}}
    </style>
</head>
<body>



<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Student Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="editStudentBody">
        <!-- Form will be loaded here via AJAX -->
      </div>
    </div>
  </div>
</div>








<div class="dashboard-container">
    <header class="header">
        <div class="header-left">
            <div class="logo"><i class="fas fa-chart-bar"></i><span>ISPSC CLINICA</span></div>
            <span class="header-title">Admin Dashboard</span>
        </div>
        <div class="header-right">
            <button class="user-info" id="user-info-btn"><span>Admin User</span></button>
            <i class="fas fa-sign-out-alt logout-btn" id="logout-icon"></i>
        </div>
    </header>

    <div class="logout-message" id="logout-message"><i class="fas fa-check-circle"></i><span>Successfully logged out! Redirecting...</span></div>

    <nav class="sidebar">
        <div class="sidebar-menu">
            <a href="#" class="menu-item "><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
            <a href="#" class="menu-item active"><i class="fas fa-users"></i><span>Patient Records</span></a>
            <a href="#" class="menu-item"><i class="fas fa-clipboard-list"></i><span>Medical Forms</span></a>
            <a href="#" class="menu-item"><i class="fas fa-chart-line"></i><span>Reports & Analytics</span></a>
            <a href="#" class="menu-item"><i class="fas fa-cog"></i><span>Settings</span></a>
        </div>
    </nav>

    <main class="main-content mt-5">
      <h2>Patient Records</h2>

        <form method="GET" class="filters">
            <input type="text" name="search" placeholder="Search by Name or ID" value="<?php echo htmlspecialchars($search); ?>">
            <select name="campus">
                <option value="">-- Select Campus --</option>
                <?php foreach($campuses as $campus): ?>
                    <option value="<?php echo htmlspecialchars($campus); ?>" <?php if($campus==$campus_filter) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($campus); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit"><i class="fas fa-search"></i> Search</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Campus</th>
                    <th>Department</th>
                    <th>Course</th>
                    <th>Year</th>
                    <th>Semester</th>
                    <th>Sex</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Email</th>
                    
                    <th>Health Record Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['last_name'].', '.$row['first_name'].' '.$row['middle_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['campus']); ?></td>
                        <td><?php echo htmlspecialchars($row['department']); ?></td>
                        <td><?php echo htmlspecialchars($row['course']); ?></td>
                        <td><?php echo htmlspecialchars($row['year']); ?></td>
                        <td><?php echo htmlspecialchars($row['semester']); ?></td>
                        <td><?php echo htmlspecialchars($row['sex']); ?></td>
                        <td><?php echo htmlspecialchars($row['permanent_address']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['health_record_date']); ?></td>
                        <td>
                        <button class="btn btn-sm btn-warning edit-btn" data-student-id="<?php echo $row['student_id']; ?>">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        </td>

                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="16" style="text-align:center;">No records found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>

<script>
// Make sidebar menu items toggle active
document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
    });
});

// Logout handler
function handleLogout() {
    const logoutMessage = document.getElementById('logout-message');
    logoutMessage.classList.add('show');
    setTimeout(() => { window.location.href = 'admin_login.php'; }, 2000);
}
document.getElementById('user-info-btn').addEventListener('click', handleLogout);
document.getElementById('logout-icon').addEventListener('click', handleLogout);





const data = {
  "MAIN CAMPUS": { departments: { "College of Teacher Education": { courses: ["Bachelor of Secondary Education","Bachelor of Elementary Education","Bachelor of Culture and the Arts Education","Bachelor of Physical Education"], majors: ["Science","English","Mathematics","Filipino","Social Studies","None"] }, "College of Arts and Sciences": { courses: ["Bachelor of Arts in English Language","Bachelor of Arts in Political Science","Bachelor of Science in Computer Science","Bachelor of Science in Midwifery","Bachelor of Science in Nursing"], majors: ["None"] }, "College of Business Education": { courses: ["Bachelor of Science in Business Administration","Bachelor of Science in Office Administration"], majors: ["Financial Management","Human Resources Development Management","None"] }, "School of Criminal Justice Education": { courses: ["Bachelor of Science in Criminology"], majors: ["None"] } } },
  "SANTA MARIA": { departments: { "College of Agriculture, Forestry, Engineering and Development Communication": { courses: ["Bachelor of Science in Agriculture","Bachelor of Science in Agricultural and Biosystems Engineering","Bachelor of Science in Forestry","Bachelor of Science in Agroforestry","Bachelor in Agricultural Technology","Bachelor of Science in Development Communication"], majors: ["Agronomy","Animal Husbandry","Horticulture","Post Harvest Technology","Agribusiness Management & Entrepreneurship","None"] }, "College of Teacher Education": { courses: ["Bachelor of Elementary Education","Bachelor of Secondary Education","Bachelor of Technology and Livelihood Education"], majors: ["Science","English","Mathematics","Filipino","Social Studies","Home Economics","Agro-Fishery Arts","None"] }, "College of Computing Studies": { courses: ["Bachelor of Science in Information Technology","Bachelor of Science in Information Systems"], majors: ["Web Development and Mobile Track","Networking and Cybersecurity Track","Business and Data Analytics","None"] }, "College of Business, Management and Entrepreneurship": { courses: ["Bachelor of Science in Hospitality Management"], majors: ["None"] } } },
  "NARVACAN": { departments: { "Fisheries Department": { courses: ["Bachelor of Science in Fisheries"], majors: ["None"] }, "Department of Teacher Education": { courses: ["Bachelor of Technology and Livelihood Education","Bachelor of Physical Education","Bachelor of Technical Vocational Teacher Education"], majors: ["Agri-fishery Arts","Home Economics","Fish Processing","Aquaculture","None"] } } },
  "SANTIAGO": { departments: { "Industrial Technology Department": { courses: ["Bachelor of Science in Industrial Technology","Bachelor of Science in Mechatronics Technology"], majors: ["Automotive Technology","Electrical Technology","Electronics Technology","Food Technology","Apparel Technology","None"] }, "Teacher Education Department": { courses: ["Bachelor in Technical-Vocational Teacher Education"], majors: ["Automotive Technology","Electrical Technology","Electronics Technology","Food and Service Management","Garments, Fashion, and Design"] } } },
  "TAGUDIN": { departments: { "College of Arts and Sciences": { courses: ["Bachelor of Science in Mathematics","Bachelor of Arts in Psychology","Bachelor of Arts in Social Science","Bachelor of Arts in English Language","Bachelor of Science in Information Technology","Bachelor of Science in Public Administration"], majors: ["Web Development and Mobile Track","None"] }, "College of Business, Management and Entrepreneurship": { courses: ["Bachelor of Science in Business Administration","Bachelor of Science in Entrepreneurship"], majors: ["Human Resource Development Management","Marketing Management","Financial Management","None"] }, "College of Teacher Education": { courses: ["Bachelor of Secondary Education","Bachelor of Elementary Education","Bachelor of Physical Education"], majors: ["Science","English","Mathematics","Filipino","Social Studies","None"] } } },
  "CANDON": { departments: { "College of Business and Hospitality Management": { courses: ["Bachelor of Science in Hospitality Management","Bachelor of Science in Tourism Management"], majors: ["None"] }, "College of Computing Studies": { courses: ["Bachelor of Science in Information Technology"], majors: ["None"] }, "College of Teacher Education": { courses: ["Bachelor of Secondary Education"], majors: ["Filipino"] } } },
  "CERVANTES": { departments: { "College of Arts and Science": { courses: [" Bachelor of Science in Information Technology","Bachelor of Science in Criminology"], majors: ["Web and Mobile Application","None"] }, "College of Teacher Education": { courses: ["Bachelor of Secondary Education","Bachelor of Elementary Education","Bachelor of Technology and Livelihood Education","Bachelor of Technical-Vocational Teacher Education"], majors: ["None","Mathematics","English","Science","Technology and Livelihood Education","Agri-Fishery Arts","Home Economics","Food Service Management","Agricultural Crops Production"] } } }
};
// Edit Student Modal JS
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const studentId = this.dataset.studentId;
        fetch('edit_student_form.php?student_id=' + studentId)
        .then(res => res.text())
        .then(html => {
            document.getElementById('editStudentBody').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('editStudentModal'));
            modal.show();

            // --- Cascading dropdowns ---
            const campusSelect = document.getElementById('campus');
            const departmentSelect = document.getElementById('department');
            const courseSelect = document.getElementById('course');
            const majorSelect = document.getElementById('major');

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
        });
    });
});

</script>
</body>
</html>
