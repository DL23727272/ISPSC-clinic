<?php
session_start();
require_once 'db_connection.php';

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: admin_login.php");
    exit;
}

$role = $_SESSION['role'];       // "admin" or "superadmin"
$userCampus = $_SESSION['campus']; // Campus from users table

// Initialize counts
$total_students = 0;
$today_visits = 0;
$total_health_info = 0;
$total_users = 0;
$this_month_students = 0;
$campus_counts = [];
// Total Admin
$result = $conn->query("SELECT COUNT(*) FROM users");
if ($row = $result->fetch_row()) $total_users = $row[0];

// ---------------- Students ----------------
if ($role === 'super_admin') {
    // No campus restriction
    $result = $conn->query("SELECT COUNT(*) FROM students");
    if ($row = $result->fetch_row()) $total_students = $row[0];

    $student_campus_counts = [];
    $result = $conn->query("SELECT campus, COUNT(*) AS count FROM students GROUP BY campus");
    while ($row = $result->fetch_assoc()) {
        $student_campus_counts[$row['campus']] = $row['count'];
    }

    $result = $conn->query("SELECT COUNT(*) FROM student_health_info");
    if ($row = $result->fetch_row()) $total_health_info = $row[0];

    $result = $conn->query("SELECT COUNT(*) FROM students WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
    if ($row = $result->fetch_row()) $this_month_students = $row[0];

    $result = $conn->query("SELECT COUNT(*) FROM student_health_info WHERE DATE(created_at) = CURRENT_DATE()");
    if ($row = $result->fetch_row()) $today_visits = $row[0];

    $result = $conn->query("SELECT COUNT(*) FROM students WHERE sex = 'Male'");
    $male_students = $result->fetch_row()[0];

    $result = $conn->query("SELECT COUNT(*) FROM students WHERE sex = 'Female'");
    $female_students = $result->fetch_row()[0];

} else {
    // Admin restricted to their campus
    $stmt = $conn->prepare("SELECT COUNT(*) FROM students WHERE campus = ?");
    $stmt->bind_param("s", $userCampus);
    $stmt->execute();
    $stmt->bind_result($total_students);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM student_health_info sh
                            JOIN students s ON sh.student_id = s.id
                            WHERE s.campus = ?");
    $stmt->bind_param("s", $userCampus);
    $stmt->execute();
    $stmt->bind_result($total_health_info);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM students WHERE campus = ? 
                            AND MONTH(created_at) = MONTH(CURRENT_DATE()) 
                            AND YEAR(created_at) = YEAR(CURRENT_DATE())");
    $stmt->bind_param("s", $userCampus);
    $stmt->execute();
    $stmt->bind_result($this_month_students);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM student_health_info sh 
                            JOIN students s ON sh.student_id = s.id
                            WHERE s.campus = ? AND DATE(sh.created_at) = CURRENT_DATE()");
    $stmt->bind_param("s", $userCampus);
    $stmt->execute();
    $stmt->bind_result($today_visits);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM students WHERE campus = ? AND sex = 'Male'");
    $stmt->bind_param("s", $userCampus);
    $stmt->execute();
    $stmt->bind_result($male_students);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM students WHERE campus = ? AND sex = 'Female'");
    $stmt->bind_param("s", $userCampus);
    $stmt->execute();
    $stmt->bind_result($female_students);
    $stmt->fetch();
    $stmt->close();

    // Only show the admin’s campus in the cards
    $student_campus_counts = [$userCampus => $total_students];
}

// ---------------- Employees ----------------
if ($role === 'superadmin') {
    $result = $conn->query("SELECT COUNT(*) FROM employees");
    $total_employees = $result->fetch_row()[0];

    $result = $conn->query("SELECT COUNT(*) FROM employees WHERE sex = 'Male'");
    $male_employees = $result->fetch_row()[0];

    $result = $conn->query("SELECT COUNT(*) FROM employees WHERE sex = 'Female'");
    $female_employees = $result->fetch_row()[0];

    $employee_campus_counts = [];
    $result = $conn->query("SELECT campus, COUNT(*) AS count FROM employees GROUP BY campus");
    while ($row = $result->fetch_assoc()) {
        $employee_campus_counts[$row['campus']] = $row['count'];
    }
} else {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM employees WHERE campus = ?");
    $stmt->bind_param("s", $userCampus);
    $stmt->execute();
    $stmt->bind_result($total_employees);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM employees WHERE campus = ? AND sex = 'Male'");
    $stmt->bind_param("s", $userCampus);
    $stmt->execute();
    $stmt->bind_result($male_employees);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM employees WHERE campus = ? AND sex = 'Female'");
    $stmt->bind_param("s", $userCampus);
    $stmt->execute();
    $stmt->bind_result($female_employees);
    $stmt->fetch();
    $stmt->close();

    $employee_campus_counts = [$userCampus => $total_employees];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
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
            <a href="#" class="menu-item <?= ($currentPage == 'reports.php') ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i><span>Reports & Analytics</span>
            </a>
            <a href="#" class="menu-item <?= ($currentPage == 'settings.php') ? 'active' : '' ?>">
                <i class="fas fa-cog"></i><span>Settings</span>
            </a>
        </div>
    </nav>

    <main class="main-content mt-5">
        <div class="container mt-5">

           <!-- Content -->

        </div>
    </main>
</div>

<script>
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
