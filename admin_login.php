
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal | ISPSC CLINICA</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="img/logo.ico" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --admin-color: #e74c3c;
            --light-gray: #ecf0f1;
            --dark-gray: #7f8c8d;
            --white: #ffffff;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --dark-button: #2c3e50;
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .admin-container {
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .portal-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            position: relative;
        }

        .portal-icon i {
            position: relative;
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
            margin-bottom: 2.5rem;
        }

        .form-container {
            background: var(--white);
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: var(--box-shadow);
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: border-color 0.3s ease;
            background-color: var(--white);
            font-family: 'Roboto', sans-serif;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--admin-color);
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
        }

        .form-input.error {
            border-color: var(--admin-color);
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
        }

        .form-input::placeholder {
            color: #aaa;
        }

        .error-message {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--admin-color);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            display: none;
            text-align: center;
            border: 1px solid rgba(231, 76, 60, 0.2);
        }

        .error-message.show {
            display: block;
        }

        .admin-login-button {
            width: 100%;
            padding: 0.875rem 1rem;
            background-color: var(--dark-button);
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
            margin-top: 2rem;
        }

        .admin-login-button:hover {
            background-color: #1a252f;
            transform: translateY(-1px);
        }

        .admin-login-button:disabled {
            background-color: var(--dark-gray);
            cursor: not-allowed;
            transform: none;
        }

        .dashboard-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--secondary-color);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-align: center;
        }

        .dashboard-link:hover {
            color: #2980b9;
            background-color: rgba(52, 152, 219, 0.1);
            transform: translateY(-1px);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--admin-color);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #c0392b;
            transform: translateX(-2px);
        }

        @media (max-width: 480px) {
            .admin-container {
                padding: 1rem;
            }
            
            .form-container {
                padding: 2rem;
            }
            
            .portal-title {
                font-size: 1.75rem;
            }

            .portal-icon {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="portal-icon">
            <i class="fas fa-user-shield"></i>
        </div>
        
        <h1 class="portal-title">Admin Portal</h1>
        <p class="portal-subtitle">Secure access to clinic management</p>
        
        <div class="form-container">
           <form id="loginForm"  method="POST">
                <div class="error-message" id="error-message" role="alert" aria-live="polite">
                    <i class="fas fa-exclamation-triangle"></i>
                    Invalid credentials. Please check your username and password.
                </div>

                <div class="form-group">
                    <label for="admin-username" class="form-label">Admin Username</label>
                    <input 
                        type="text" 
                        id="admin-username" 
                        name="admin-username" 
                        class="form-input" 
                        placeholder="Enter admin username"
                        required
                        autocomplete="username"
                        aria-describedby="error-message"
                    >
                </div>
                
                <div class="form-group">
                    <label for="admin-password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        id="admin-password" 
                        name="admin-password" 
                        class="form-input" 
                        placeholder="Enter password"
                        required
                        autocomplete="current-password"
                        aria-describedby="error-message"
                    >
                </div>
                
                <button type="submit" class="admin-login-button" id="login-button">
                    <i class="fas fa-shield-alt"></i>
                    Admin Login
                </button>
            </form>
        </div>
        
        <a href="index.php" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Back to Home
        </a>
    </div>

    <script>


        document.getElementById("loginForm").addEventListener("submit", async function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            const response = await fetch("admin_process.php", {
                method: "POST",
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                // Save in sessionStorage
                sessionStorage.setItem("employee_id", result.employee_id);
                sessionStorage.setItem("role", result.role);
                sessionStorage.setItem("campus", result.campus);

                // Redirect
                window.location.href = "dashboard.php";
            } else {
               Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    text: result.message
                });
            }
        });

        // Add subtle animation on page load
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.admin-container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                container.style.transition = 'all 0.5s ease';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>