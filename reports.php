<?php
require_once 'db_connection.php';
session_start();

$role = $_SESSION['role'];
$userCampus = $_SESSION['campus'] ?? null;

// Restrict data if campus admin
$campusCondition = "";
if ($role !== 'super_admin' && $userCampus) {
    $campusCondition = " WHERE campus = '" . $conn->real_escape_string($userCampus) . "'";
}

// --- Students Count ---
$studentCount = $conn->query("SELECT COUNT(*) as total FROM students $campusCondition")->fetch_assoc()['total'];

// --- Employees Count ---
$employeeCount = $conn->query("SELECT COUNT(*) as total FROM employees $campusCondition")->fetch_assoc()['total'];

// --- Common Diseases (students + employees) ---
$diseases = ['hypertension','diabetes','asthma','cancer','tuberculosis','heart_disease'];

$diseaseStats = [];
$studentStats = [];
$employeeStats = [];

foreach ($diseases as $d) {
    $studentQ = $conn->query("SELECT COUNT(*) as c FROM student_health_info 
                              INNER JOIN students s ON s.student_id=student_health_info.student_id 
                              WHERE $d=1 " . ($campusCondition ? "AND s.campus='".$conn->real_escape_string($userCampus)."'" : ""));
    $employeeQ = $conn->query("SELECT COUNT(*) as c FROM employee_health_info 
                               INNER JOIN employees e ON e.employee_id=employee_health_info.employee_id 
                               WHERE $d=1 " . ($campusCondition ? "AND e.campus='".$conn->real_escape_string($userCampus)."'" : ""));
    
    $studentStats[$d] = $studentQ->fetch_assoc()['c'];
    $employeeStats[$d] = $employeeQ->fetch_assoc()['c'];
}


// --- Total Health Inputs (students + employees) ---
$totalHealthInputs = $conn->query("
    SELECT (
        (SELECT COUNT(*) FROM student_health_info sh
         INNER JOIN students s ON s.student_id=sh.student_id
         " . ($campusCondition ? "WHERE s.campus='".$conn->real_escape_string($userCampus)."'" : "") . ")
        +
        (SELECT COUNT(*) FROM employee_health_info eh
         INNER JOIN employees e ON e.employee_id=eh.employee_id
         " . ($campusCondition ? "WHERE e.campus='".$conn->real_escape_string($userCampus)."'" : "") . ")
    ) as total
")->fetch_assoc()['total'];

// --- Per Campus Counts (Students + Employees) ---
$campuses = ['MAIN CAMPUS','SANTA MARIA','NARVACAN','SANTIAGO','TAGUDIN','CANDON','CERVANTES'];

// Students
$studentsByCampus = [];
$result = $conn->query("
    SELECT campus, sex, COUNT(*) as total 
    FROM students
    GROUP BY campus, sex
");
while ($row = $result->fetch_assoc()) {
    $campus = strtoupper($row['campus']);
    $gender = strtolower($row['sex']);
    $studentsByCampus[$campus][$gender] = $row['total'];
    $studentsByCampus[$campus]['total'] = 
        ($studentsByCampus[$campus]['total'] ?? 0) + $row['total'];
}

// Employees
$employeesByCampus = [];
$result = $conn->query("
    SELECT campus, sex, COUNT(*) as total 
    FROM employees
    GROUP BY campus, sex
");
while ($row = $result->fetch_assoc()) {
    $campus = strtoupper($row['campus']);
    $gender = strtolower($row['sex']);
    $employeesByCampus[$campus][$gender] = $row['total'];
    $employeesByCampus[$campus]['total'] = 
        ($employeesByCampus[$campus]['total'] ?? 0) + $row['total'];
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports Dashboard</title>
        <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="icon" type="image/x-icon" href="img/logo.ico" />

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
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
<div class="dashboard-container">
    <header class="header">
        <div class="header-left">
            <div class="logo">
                <i class="fas fa-chart-bar"></i>
                <span>ISPSC CLINICA</span>
            </div>
            <span class="header-title">
                 Admin Dashboard
                <?php if (isset($_SESSION['campus'])): ?>
                    - <?= htmlspecialchars($_SESSION['campus']); ?>
                <?php endif; ?> Campus
            </span>
        </div>
        <div class="header-right">
            <button class="user-info" id="user-info-btn">
                <span>
                    <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "Admin User"; ?>
                </span>
            </button>
            <i class="fas fa-sign-out-alt logout-btn" id="logout-icon"></i>
        </div>
    </header>


    <div class="logout-message" id="logout-message"><i class="fas fa-check-circle"></i><span>Successfully logged out! Redirecting...</span></div>

    <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
    <nav class="sidebar">
        <div class="sidebar-menu">
            <a href="dashboard.php" class="menu-item <?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
            </a>
            <a href="patients.php" class="menu-item <?= ($currentPage == 'patients.php') ? 'active' : '' ?>">
                <i class="fas fa-users"></i><span>Patient Informations</span>
            </a>
            <a href="health_records.php" class="menu-item <?= ($currentPage == 'health_records.php') ? 'active' : '' ?>">
                <i class="fas fa-clipboard-list"></i><span>Health Informations</span>
            </a>
            <a href="reports.php" class="menu-item <?= ($currentPage == 'reports.php') ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i><span>Reports & Analytics</span>
            </a>
            <!-- <a href="#" class="menu-item <?= ($currentPage == 'settings.php') ? 'active' : '' ?>">
                <i class="fas fa-cog"></i><span>Settings</span>
            </a> -->
        </div>
    </nav>

    <main class="main-content mt-5">
        <div class="container mt-5">

        

           <div class="container mt-4">
                <h2>Reports & Analytics <?= ($role !== 'super_admin') ? "- ".htmlspecialchars($userCampus) : ""; ?></h2>

                <div class="row text-center mt-4">
                    <div class="col-md-3">
                        <div class="card shadow p-3">
                          <h5>Total Health Inputs</h5>
                          <h2><?= $totalHealthInputs ?></h2>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow p-3">
                            <h5>Students</h5>
                            <h2><?= $studentCount ?></h2>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow p-3">
                            <h5>Employees</h5>
                            <h2><?= $employeeCount ?></h2>
                        </div>
                    </div>
                   
                </div>

                <!-- Disease Prevalence Chart -->
                <canvas id="diseaseChart" height="120"></canvas>
            </div>



             <div class="container">




                <!-- Students Row -->
                <h3 class="mb-3">Students</h3>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="info-card">
                            <div class="card-icon blue"><i class="fas fa-users"></i></div>
                            <div class="card-content">
                                <h4>Main Campus</h4>
                                <p>Total: <?= $studentsByCampus['MAIN CAMPUS']['total'] ?? 0 ?></p>
                                <p>Male: <?= $studentsByCampus['MAIN CAMPUS']['male'] ?? 0 ?></p>
                                <p>Female: <?= $studentsByCampus['MAIN CAMPUS']['female'] ?? 0 ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="info-card">
                            <div class="card-icon green"><i class="fas fa-users"></i></div>
                            <div class="card-content">
                                <h4>Santa Maria</h4>
                                <p>Total: <?= $studentsByCampus['SANTA MARIA']['total'] ?? 0 ?></p>
                                <p>Male: <?= $studentsByCampus['SANTA MARIA']['male'] ?? 0 ?></p>
                                <p>Female: <?= $studentsByCampus['SANTA MARIA']['female'] ?? 0 ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="info-card">
                            <div class="card-icon green"><i class="fas fa-users"></i></div>
                            <div class="card-content">
                                <h4>Narvacan</h4>
                                <p>Total: <?= $studentsByCampus['NARVACAN']['total'] ?? 0 ?></p>
                                <p>Male: <?= $studentsByCampus['NARVACAN']['male'] ?? 0 ?></p>
                                <p>Female: <?= $studentsByCampus['NARVACAN']['female'] ?? 0 ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="info-card">
                            <div class="card-icon red"><i class="fas fa-users"></i></div>
                            <div class="card-content">
                                <h4>Santiago</h4>
                                <p>Total: <?= $studentsByCampus['SANTIAGO']['total'] ?? 0 ?></p>
                                <p>Male: <?= $studentsByCampus['SANTIAGO']['male'] ?? 0 ?></p>
                                <p>Female: <?= $studentsByCampus['SANTIAGO']['female'] ?? 0 ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="info-card">
                            <div class="card-icon purple"><i class="fas fa-users"></i></div>
                            <div class="card-content">
                                <h4>Tagudin</h4>
                                <p>Total: <?= $studentsByCampus['TAGUDIN']['total'] ?? 0 ?></p>
                                <p>Male: <?= $studentsByCampus['TAGUDIN']['male'] ?? 0 ?></p>
                                <p>Female: <?= $studentsByCampus['TAGUDIN']['female'] ?? 0 ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="info-card">
                            <div class="card-icon green"><i class="fas fa-users"></i></div>
                            <div class="card-content">
                                <h4>Candon</h4>
                                <p>Total: <?= $studentsByCampus['CANDON']['total'] ?? 0 ?></p>
                                <p>Male: <?= $studentsByCampus['CANDON']['male'] ?? 0 ?></p>
                                <p>Female: <?= $studentsByCampus['CANDON']['female'] ?? 0 ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="info-card">
                            <div class="card-icon green"><i class="fas fa-users"></i></div>
                            <div class="card-content">
                                <h4>Cervantes</h4>
                                <p>Total: <?= $studentsByCampus['CERVANTES']['total'] ?? 0 ?></p>
                                <p>Male: <?= $studentsByCampus['CERVANTES']['male'] ?? 0 ?></p>
                                <p>Female: <?= $studentsByCampus['CERVANTES']['female'] ?? 0 ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employees Row -->
                <h3 class="mb-3 mt-5">Employees</h3>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="info-card">
                            <div class="card-icon blue"><i class="fas fa-user-tie"></i></div>
                            <div class="card-content">
                                <h4>Main Campus</h4>
                                <p>Total: <?= $employeesByCampus['MAIN CAMPUS']['total'] ?? 0 ?></p>
                                <p>Male: <?= $employeesByCampus['MAIN CAMPUS']['male'] ?? 0 ?></p>
                                <p>Female: <?= $employeesByCampus['MAIN CAMPUS']['female'] ?? 0 ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="info-card">
                            <div class="card-icon green"><i class="fas fa-user-tie"></i></div>
                            <div class="card-content">
                                <h4>Santa Maria</h4>
                                <p>Total: <?= $employeesByCampus['SANTA MARIA']['total'] ?? 0 ?></p>
                                <p>Male: <?= $employeesByCampus['SANTA MARIA']['male'] ?? 0 ?></p>
                                <p>Female: <?= $employeesByCampus['SANTA MARIA']['female'] ?? 0 ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="info-card">
                            <div class="card-icon green"><i class="fas fa-user-tie"></i></div>
                            <div class="card-content">
                                <h4>Narvacan</h4>
                                 <p>Total: <?= $employeesByCampus['NARVACAN']['total'] ?? 0 ?></p>
                                <p>Male: <?= $employeesByCampus['NARVACAN']['male'] ?? 0 ?></p>
                                <p>Female: <?= $employeesByCampus['NARVACAN']['female'] ?? 0 ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="info-card">
                            <div class="card-icon red"><i class="fas fa-user-tie"></i></div>
                            <div class="card-content">
                                <h4>Santiago</h4>
                                <p>Total: <?= $employeesByCampus['SANTIAGO']['total'] ?? 0 ?></p>
                                <p>Male: <?= $employeesByCampus['SANTIAGO']['male'] ?? 0 ?></p>
                                <p>Female: <?= $employeesByCampus['SANTIAGO']['female'] ?? 0 ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="info-card">
                            <div class="card-icon purple"><i class="fas fa-user-tie"></i></div>
                            <div class="card-content">
                                <h4>Tagudin</h4>
                                <p>Total: <?= $employeesByCampus['TAGUDIN']['total'] ?? 0 ?></p>
                                <p>Male: <?= $employeesByCampus['TAGUDIN']['male'] ?? 0 ?></p>
                                <p>Female: <?= $employeesByCampus['TAGUDIN']['female'] ?? 0 ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="info-card">
                            <div class="card-icon green"><i class="fas fa-user-tie"></i></div>
                            <div class="card-content">
                                <h4>Candon</h4>
                                <p>Total: <?= $employeesByCampus['CANDON']['total'] ?? 0 ?></p>
                                <p>Male: <?= $employeesByCampus['CANDON']['male'] ?? 0 ?></p>
                                <p>Female: <?= $employeesByCampus['CANDON']['female'] ?? 0 ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="info-card">
                            <div class="card-icon green"><i class="fas fa-user-tie"></i></div>
                            <div class="card-content">
                                <h4>Cervantes</h4>
                                <p>Total: <?= $employeesByCampus['CERVANTES']['total'] ?? 0 ?></p>
                                <p>Male: <?= $employeesByCampus['CERVANTES']['male'] ?? 0 ?></p>
                                <p>Female: <?= $employeesByCampus['CERVANTES']['female'] ?? 0 ?></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>




        </div>
    </main>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('diseaseChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_keys($studentStats)) ?>,
            datasets: [
                {
                    label: 'Students',
                    data: <?= json_encode(array_values($studentStats)) ?>,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.3,
                    fill: false,
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                    pointRadius: 5
                },
                {
                    label: 'Employees',
                    data: <?= json_encode(array_values($employeeStats)) ?>,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.3,
                    fill: false,
                    pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                    pointRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Disease Prevalence (Students vs Employees)' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });


    document.querySelectorAll('.menu-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            window.location.href = this.href; // navigate manually
        });
    });


    function handleLogout() {
        const logoutMessage = document.getElementById('logout-message');
        logoutMessage.classList.add('show');
        setTimeout(() => { window.location.href = 'admin_login.php'; }, 2000);
    }
    document.getElementById('user-info-btn').addEventListener('click', handleLogout);
    document.getElementById('logout-icon').addEventListener('click', handleLogout);
</script>


</body>
</html>
