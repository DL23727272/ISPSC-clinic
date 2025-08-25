<?php
session_start();
require_once 'db_connection.php';

$error = '';
$success = '';
$active_tab = isset($_GET['tab']) && $_GET['tab'] === 'register' ? 'register' : 'login';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employee-id'])) {
    $username = sanitize_input($_POST['employee-id']);
    $password = sanitize_input($_POST['password']);
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = 'employee'");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $employee = $result->fetch_assoc();
        
        if (verify_password($password, $employee['password_hash'])) {
            $_SESSION['user_id'] = $employee['id'];
            $_SESSION['username'] = $employee['username'];
            $_SESSION['role'] = $employee['role'];
            $_SESSION['logged_in'] = true;
            
            header('Location: employee_medical.php');
            exit();
        } else {
            $error = 'Invalid password. Please try again.';
        }
    } else {
        $error = 'Employee ID not found. Please try again or register.';
    }
}

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register-employee-id'])) {
    $username = sanitize_input($_POST['register-employee-id']);
    $email = sanitize_input($_POST['email']);
    $password = sanitize_input($_POST['register-password']);
    
    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $error = 'Employee ID already exists. Please use a different ID or login.';
        $active_tab = 'register';
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new employee
        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'employee')");
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        
        if ($stmt->execute()) {
            $success = 'Registration successful! Please login with your new credentials.';
            $active_tab = 'login';
        } else {
            $error = 'Registration failed. Please try again.';
            $active_tab = 'register';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Portal | ISPSC CLINICA</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --employee-color: #27ae60;
            --light-gray: #ecf0f1;
            --dark-gray: #7f8c8d;
            --white: #ffffff;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-container {
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .portal-icon {
            font-size: 3rem;
            color: var(--employee-color);
            margin-bottom: 1.5rem;
        }

        .portal-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .portal-subtitle {
            font-size: 1rem;
            color: var(--dark-gray);
            font-weight: 400;
            margin-bottom: 2rem;
        }

        .form-container {
            background: var(--white);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: var(--box-shadow);
            margin-bottom: 1.5rem;
        }

        .form-tabs {
            display: flex;
            margin-bottom: 2rem;
            background-color: var(--light-gray);
            border-radius: 8px;
            padding: 4px;
        }

        .tab-button {
            flex: 1;
            padding: 0.75rem 1rem;
            border: none;
            background: transparent;
            border-radius: 6px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--dark-gray);
        }

        .tab-button.active {
            background-color: var(--secondary-color);
            color: var(--white);
        }

        .tab-button:hover:not(.active) {
            background-color: rgba(52, 152, 219, 0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .name-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .name-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: border-color 0.3s ease;
            background-color: var(--white);
            font-family: 'Roboto', sans-serif;
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--employee-color);
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
        }

        .form-input::placeholder {
            color: #aaa;
        }

        .form-select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        .login-button,
        .register-button {
            width: 100%;
            padding: 0.875rem 1rem;
            background-color: var(--employee-color);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .login-button:hover,
        .register-button:hover {
            background-color: #229954;
            transform: translateY(-1px);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--employee-color);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #229954;
            transform: translateX(-2px);
        }

        .hidden {
            display: none;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1rem;
            }
            
            .form-container {
                padding: 1.5rem;
            }
            
            .portal-title {
                font-size: 1.75rem;
            }

            .name-row {
                flex-direction: column;
                gap: 0;
            }

            .name-row .form-group {
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="portal-icon">
            <i class="fas fa-user-md"></i>
        </div>
        
        <h1 class="portal-title">Employee Portal</h1>
        <p class="portal-subtitle">Access your medical forms and records</p>
        
        <div class="form-container">
            <?php if ($error): ?>
                <div style="color: #c0392b; background: #fdecea; border-radius: 6px; padding: 0.75rem; margin-bottom: 1rem;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div style="color: #27ae60; background: #eafaf1; border-radius: 6px; padding: 0.75rem; margin-bottom: 1rem;">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            <div class="form-tabs">
                <button class="tab-button <?php echo $active_tab === 'login' ? 'active' : ''; ?>" id="login-tab">Login</button>
                <button class="tab-button <?php echo $active_tab === 'register' ? 'active' : ''; ?>" id="register-tab">Register</button>
            </div>
            
            <!-- Login Form -->
            <form id="login-form" <?php echo $active_tab === 'login' ? '' : 'class="hidden"'; ?> method="POST">
                <div class="form-group">
                    <label for="employee-id" class="form-label">Employee ID</label>
                    <input 
                        type="text" 
                        id="employee-id" 
                        name="employee-id" 
                        class="form-input" 
                        placeholder="Enter your employee ID"
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="Enter your password"
                        required
                    >
                </div>
                
                <button type="submit" class="login-button">
                    <i class="fas fa-sign-in-alt"></i>
                    Login
                </button>
            </form>

            <!-- Register Form -->
            <form id="register-form" <?php echo $active_tab === 'register' ? '' : 'class="hidden"'; ?> method="POST">
                <div class="name-row">
                    <div class="form-group">
                        <label for="first-name" class="form-label">First Name</label>
                        <input 
                            type="text" 
                            id="first-name" 
                            name="first-name" 
                            class="form-input" 
                            placeholder="First name"
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label for="last-name" class="form-label">Last Name</label>
                        <input 
                            type="text" 
                            id="last-name" 
                            name="last-name" 
                            class="form-input" 
                            placeholder="Last name"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="register-employee-id" class="form-label">Employee ID</label>
                    <input 
                        type="text" 
                        id="register-employee-id" 
                        name="register-employee-id" 
                        class="form-input" 
                        placeholder="Employee ID"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input" 
                        placeholder="email@example.com"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="department" class="form-label">Department</label>
                    <select 
                        id="department" 
                        name="department" 
                        class="form-select"
                        required
                    >
                        <option value="">Select Department</option>
                        <option value="administration">Administration</option>
                        <option value="nursing">Nursing</option>
                        <option value="medical">Medical</option>
                        <option value="pharmacy">Pharmacy</option>
                        <option value="laboratory">Laboratory</option>
                        <option value="radiology">Radiology</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="security">Security</option>
                        <option value="it">Information Technology</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="register-password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        id="register-password" 
                        name="register-password" 
                        class="form-input" 
                        placeholder="Create password"
                        required
                    >
                </div>
                
                <button type="submit" class="register-button">
                    <i class="fas fa-user-plus"></i>
                    Register
                </button>
            </form>
        </div>
        
        <a href="index.php" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Back to Home
        </a>
    </div>

    <script>
        // Tab switching functionality
        document.getElementById('login-tab').addEventListener('click', function() {
            // Update tab appearance
            document.getElementById('login-tab').classList.add('active');
            document.getElementById('register-tab').classList.remove('active');
            
            // Show/hide forms
            document.getElementById('login-form').classList.remove('hidden');
            document.getElementById('register-form').classList.add('hidden');
        });

        document.getElementById('register-tab').addEventListener('click', function() {
            // Update tab appearance
            document.getElementById('register-tab').classList.add('active');
            document.getElementById('login-tab').classList.remove('active');
            
            // Show/hide forms
            document.getElementById('register-form').classList.remove('hidden');
            document.getElementById('login-form').classList.add('hidden');
        });
    </script>
</body>
</html>