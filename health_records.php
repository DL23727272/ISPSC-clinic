<?php
session_start();
require_once 'db_connection.php';
// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: admin_login.php");
    exit;
}
// Handle search, campus, and type filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$campus = isset($_GET['campus']) ? $_GET['campus'] : '';
$type   = isset($_GET['type']) ? $_GET['type'] : 'student'; // default student
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
// Check if logged-in user is admin and restrict campus
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    // Assume you store admin campus in session
    $adminCampus = $_SESSION['campus'];  
}

// Build base query
if ($type === 'employee') {
    $sql = "SELECT ehi.id, ehi.employee_id AS person_id, ehi.created_at, e.first_name, e.last_name, e.campus
            FROM employee_health_info ehi
            LEFT JOIN employees e ON ehi.employee_id = e.employee_id
            WHERE 1";
} else {
    $sql = "SELECT shi.id, shi.student_id AS person_id, shi.created_at, s.first_name, s.last_name, s.campus
            FROM student_health_info shi
            LEFT JOIN students s ON shi.student_id = s.student_id
            WHERE 1";
}

// Apply search
if(!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (person_id LIKE '%$search%' OR first_name LIKE '%$search%' OR last_name LIKE '%$search%')";
}

// Restrict campus for admin
if (isset($adminCampus)) {
    $sql .= " AND campus = '".$conn->real_escape_string($adminCampus)."'";
} elseif(!empty($campus)) {
    // If not admin, use campus filter from dropdown
    $campus = $conn->real_escape_string($campus);
    $sql .= " AND campus = '$campus'";
}

$sql .= " ORDER BY campus ASC, id DESC";
$result = $conn->query($sql);

// Fetch campuses depending on type (but limit if admin)
if ($type === 'employee') {
    if (isset($adminCampus)) {
        $campus_result = $conn->query("SELECT DISTINCT campus FROM employees WHERE campus = '".$conn->real_escape_string($adminCampus)."' ORDER BY campus ASC");
    } else {
        $campus_result = $conn->query("SELECT DISTINCT campus FROM employees ORDER BY campus ASC");
    }
} else {
    if (isset($adminCampus)) {
        $campus_result = $conn->query("SELECT DISTINCT campus FROM students WHERE campus = '".$conn->real_escape_string($adminCampus)."' ORDER BY campus ASC");
    } else {
        $campus_result = $conn->query("SELECT DISTINCT campus FROM students ORDER BY campus ASC");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Health Records</title>
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
                <h2 class="mb-4">Student Health Information Records</h2>

                <form class="row g-3 mb-3" method="get">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" 
                            placeholder="Search by ID or Name" 
                            value="<?= htmlspecialchars($search) ?>">
                    </div>

                    <div class="col-md-3">
                        <select name="type" class="form-select" onchange="this.form.submit()">
                            <option value="student" <?= $type === 'student' ? 'selected' : '' ?>>Students</option>
                            <option value="employee" <?= $type === 'employee' ? 'selected' : '' ?>>Employees</option>
                        </select>
                    </div>


                    
                    <div class="col-md-3">
                        <?php if ($role === 'super_admin'): ?>
                            <!-- Super Admin: Can select from all campuses -->
                            <select name="campus" class="form-select" onchange="this.form.submit()">
                                <option value="">All Campuses</option>
                                <?php while($c = $campus_result->fetch_assoc()): ?>
                                    <option value="<?= htmlspecialchars($c['campus']) ?>" 
                                            <?= $campus == $c['campus'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['campus']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        <?php else: ?>
                            <!-- Campus Admin: Show their campus (disabled) -->
                            <select class="form-select" disabled>
                                <option selected><?= htmlspecialchars($adminCampus) ?></option>
                            </select>
                            <!-- Pass their campus as hidden field so it's still submitted -->
                            <input type="hidden" name="campus" value="<?= htmlspecialchars($adminCampus) ?>">
                        <?php endif; ?>
                    </div>




                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                        <a href="health_records.php" class="btn btn-secondary">Reset</a>
                    </div>
                </form>


                <table class="table table-bordered table-striped">
                <thead>
                        <tr>
                            <th><?= $type === 'employee' ? 'Employee ID' : 'Student ID' ?></th>
                            <th>Name</th>
                            <th>Campus</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['person_id']) ?></td>
                                    <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                                    <td><?= htmlspecialchars($row['campus']) ?></td>
                                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                                    <td>
                                        
                                        <?php if($type === 'student'): ?>
                                            <a href="pdf.php?student_id=<?= $row['person_id'] ?>&type=<?= $type ?>" target="_blank" class="btn btn-sm btn-success">Print</a>
                                            <a href="edit_health_info.php?id=<?= $row['id'] ?>&type=<?= $type ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="delete_health.php?id=<?= $row['id'] ?>&type=<?= $type ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">Delete</a>
                                        <?php elseif($type === 'employee'): ?>
                                            <a href="pdf.php?id=<?= $row['id'] ?>&type=<?= $type ?>" target="_blank" class="btn btn-sm btn-success">Print</a>
                                            <a href="edit_employee_health_info.php?id=<?= $row['id'] ?>&type=<?= $type ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="delete_health.php?id=<?= $row['id'] ?>&type=<?= $type ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">Delete</a>
                                        <?php endif; ?>
                                    </td>

                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">No records found.</td></tr>
                        <?php endif; ?>
                    </tbody>


                </table>
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
