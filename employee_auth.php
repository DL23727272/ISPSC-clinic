<?php
session_start();
require_once 'db_connection.php';
require_once 'email_sender.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Unknown request'];

// ----------------- LOGIN -----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $employee_id = trim($_POST['employee_id']); 
    $password    = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, employee_id, email, password FROM employees 
                            WHERE employee_id = ? OR email = ?");
    if ($stmt) {
        $stmt->bind_param("ss", $employee_id, $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $employee = $result->fetch_assoc();
            if (password_verify($password, $employee['password'])) {
                // Set session
                $_SESSION['employee_id'] = $employee['id'];
                $_SESSION['employee_no'] = $employee['employee_id'];
                $_SESSION['logged_in']   = true;

                $response = [
                    'status'       => 'success', 
                    'message'      => 'Login successful! Redirecting...',
                    'employee_id'  => $employee['employee_id'],
                    'email'        => $employee['email']
                ];
            } else {
                $response = ['status' => 'error', 'message' => 'Invalid password. Please try again.'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Employee ID/Email not found.'];
        }
        $stmt->close();
    }

    echo json_encode($response);
    exit;
}

// ----------------- REGISTER -----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $employee_id   = trim($_POST['register_employee_id']); // rename in form later
    $last_name     = trim($_POST['last_name']);
    $first_name    = trim($_POST['first_name']);
    $middle_name   = trim($_POST['middle_name']);
    $suffix        = $_POST['suffix']; 
    $age           = trim($_POST['age']);
    $campus        = trim($_POST['campus']);
    $birthdate     = trim($_POST['birthdate']);
    $sex           = trim($_POST['sex']);
    $permanent_address = trim($_POST['permanent_address']);
    $phone_number  = trim($_POST['phone_number']);
    $civil_status  = trim($_POST['civil_status']);
    $religion      = trim($_POST['religion']);
    $contact_person  = trim($_POST['contact_person']);
    $contact_address = trim($_POST['contact_address']);
    $contact_number  = trim($_POST['contact_number']);
    $email         = trim($_POST['register_email']);
    $password      = trim($_POST['register_password']);

    $age = !empty($age) ? intval($age) : NULL;
    $birthdate = !empty($birthdate) ? $birthdate : NULL;

    // Check for existing employee
    $stmt = $conn->prepare("SELECT id FROM employees WHERE employee_id = ? OR email = ?");
    if ($stmt) {
        $stmt->bind_param("ss", $employee_id, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response = ['status' => 'error', 'message' => 'Employee ID or Email already exists. Please login.'];
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO employees 
                (employee_id, last_name, first_name, middle_name, suffix, age, campus, birthdate, sex, permanent_address, phone_number, civil_status, religion, contact_person, contact_address, contact_no, email, password) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param(
                    "sssssisissssssssss",
                    $employee_id,
                    $last_name,
                    $first_name,
                    $middle_name,
                    $suffix,
                    $age,
                    $campus,
                    $birthdate,
                    $sex,
                    $permanent_address,
                    $phone_number,
                    $civil_status,
                    $religion,
                    $contact_person,
                    $contact_address,
                    $contact_number,
                    $email,
                    $hashed_password
                );

                if ($stmt->execute()) {
                    // Send email confirmation
                    $loginLink = "https://ispsc-clinica.personatab.com/";
                    sendRegistrationEmail($email, $password, $loginLink);

                    $response = ['status' => 'success', 'message' => 'Registration successful! Please check your email & login.'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Registration failed. Please try again.'];
                }
            }
        }
        $stmt->close();
    }
}

echo json_encode($response);
exit;
?>
