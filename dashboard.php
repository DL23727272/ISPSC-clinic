<?php
session_start();
require_once 'db_connection.php';

$error = '';
$success = '';
$active_tab = isset($_GET['tab']) && $_GET['tab'] === 'register' ? 'register' : 'login';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | ISPSC CLINICA</title>
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
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --sidebar-width: 280px;
            --success-color: #27ae60;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--light-gray);
            color: var(--primary-color);
            line-height: 1.6;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: var(--white);
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            z-index: 1000;
            box-shadow: var(--box-shadow);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--secondary-color);
        }

        .logo i {
            font-size: 1.5rem;
        }

        .header-title {
            font-size: 1.1rem;
            color: var(--dark-gray);
            font-weight: 400;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--dark-gray);
            font-size: 0.95rem;
            cursor: pointer;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            border: none;
            background: transparent;
            font-family: 'Roboto', sans-serif;
        }

        .user-info:hover {
            background-color: var(--light-gray);
            color: var(--primary-color);
        }

        .user-info:focus {
            outline: 2px solid var(--secondary-color);
            outline-offset: 2px;
        }

        .logout-btn {
            color: var(--dark-gray);
            font-size: 1.1rem;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .logout-btn:hover {
            color: var(--admin-color);
        }

        /* Logout Confirmation Message */
        .logout-message {
            position: fixed;
            top: 90px;
            right: 2rem;
            background: var(--success-color);
            color: var(--white);
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: var(--box-shadow);
            font-size: 0.95rem;
            font-weight: 500;
            display: none;
            z-index: 1001;
            animation: slideIn 0.3s ease;
        }

        .logout-message.show {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logout-message.error {
            background: var(--admin-color);
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--white);
            border-right: 1px solid #e0e0e0;
            padding-top: 70px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-menu {
            padding: 2rem 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 2rem;
            color: var(--dark-gray);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .menu-item:hover {
            background-color: var(--light-gray);
            color: var(--primary-color);
        }

        .menu-item.active {
            background-color: var(--secondary-color);
            color: var(--white);
            border-left-color: var(--secondary-color);
        }

        .menu-item i {
            font-size: 1.1rem;
            width: 20px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding-top: 70px;
            padding: 70px 2rem 2rem 2rem;
        }

        /* Info Cards */
        .info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .info-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--box-shadow);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--white);
        }

        .card-icon.blue {
            background-color: var(--secondary-color);
        }

        .card-icon.green {
            background-color: var(--employee-color);
        }

        .card-icon.yellow {
            background-color: var(--warning-color);
        }

        .card-icon.purple {
            background-color: var(--purple-color);
        }

        .card-content h3 {
            font-size: 0.9rem;
            color: var(--dark-gray);
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .card-content .count {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .main-content {
                margin-left: 0;
                padding: 70px 1rem 2rem 1rem;
            }

            .header {
                padding: 0 1rem;
            }

            .info-cards {
                grid-template-columns: 1fr;
            }

            .user-info span {
                display: none;
            }

            .logout-message {
                right: 1rem;
                left: 1rem;
                right: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <div class="logo">
                    <i class="fas fa-chart-bar"></i>
                    <span>ISPSC CLINICA</span>
                </div>
                <span class="header-title">Admin Dashboard</span>
            </div>
            <div class="header-right">
                <button class="user-info" id="user-info-btn" title="Click to logout" aria-label="Admin User - Click to logout">
                    <span>Admin User</span>
                </button>
                <i class="fas fa-sign-out-alt logout-btn" title="Logout" id="logout-icon"></i>
            </div>
        </header>

        <!-- Logout Confirmation Message -->
        <div class="logout-message" id="logout-message" role="alert" aria-live="polite">
            <i class="fas fa-check-circle"></i>
            <span>Successfully logged out! Redirecting...</span>
        </div>

        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-menu">
                <a href="#" class="menu-item active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span>Patient Records</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Medical Forms</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Reports & Analytics</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Info Cards -->
            <div class="info-cards">
                <div class="info-card">
                    <div class="card-icon blue">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-content">
                        <h3>Total Patients</h3>
                        <div class="count">0</div>
                    </div>
                </div>

                <div class="info-card">
                    <div class="card-icon green">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="card-content">
                        <h3>Today's Visits</h3>
                        <div class="count">0</div>
                    </div>
                </div>

                <div class="info-card">
                    <div class="card-icon yellow">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="card-content">
                        <h3>Pending Forms</h3>
                        <div class="count">0</div>
                    </div>
                </div>

                <div class="info-card">
                    <div class="card-icon purple">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="card-content">
                        <h3>This Month</h3>
                        <div class="count">0</div>
                    </div>
                </div>
            </div>

            <!-- Add this block where you want to show messages (e.g., above the forms) -->
            <?php if ($error): ?>
                <div style="color: #e74c3c; margin: 1rem 0;"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div style="color: #27ae60; margin: 1rem 0;"><?php echo $success; ?></div>
            <?php endif; ?>


    <script>
        // Simple menu interaction
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all items
                document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
                
                // Add active class to clicked item
                this.classList.add('active');
            });
        });

        // Logout functionality
        function handleLogout() {
            const logoutMessage = document.getElementById('logout-message');
            
            // Show logout confirmation message
            logoutMessage.classList.add('show');
            
            // Simulate logout process and redirect after 2 seconds
            setTimeout(() => {
                // In a real application, you would clear session data here
                console.log('User logged out successfully');
                
                // Redirect to login page
                window.location.href = 'admin_login.php';
            }, 2000);
        }

        // Add click event to both user info button and logout icon
        document.getElementById('user-info-btn').addEventListener('click', handleLogout);
        document.getElementById('logout-icon').addEventListener('click', handleLogout);

        // Add keyboard support for user info button
        document.getElementById('user-info-btn').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                handleLogout();
            }
        });

        // Hide logout message if user clicks elsewhere
        document.addEventListener('click', function(e) {
            const logoutMessage = document.getElementById('logout-message');
            if (!e.target.closest('.header-right') && logoutMessage.classList.contains('show')) {
                logoutMessage.classList.remove('show');
            }
        });
    </script>
</body>
</html>