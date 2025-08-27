<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method.';
    echo json_encode($response);
    exit;
}

// Collect and sanitize inputs
$employee_id      = $_POST['employee_id'] ?? '';
$first_name       = trim($_POST['first_name'] ?? '');
$last_name        = trim($_POST['last_name'] ?? '');
$middle_name      = trim($_POST['middle_name'] ?? '');
$campus           = $_POST['campus'] ?? '';
$age              = $_POST['age'] ?? null;
$age              = ($age === '' || !is_numeric($age)) ? null : (int)$age;
$birthdate        = $_POST['birthdate'] ?? null;
$sex              = $_POST['sex'] ?? '';
$permanent_address= $_POST['permanent_address'] ?? '';
$phone_number     = $_POST['phone_number'] ?? '';
$email            = trim($_POST['register_email'] ?? '');
$civil_status     = $_POST['civil_status'] ?? '';
$religion         = $_POST['religion'] ?? '';
$contact_person   = $_POST['contact_person'] ?? '';
$contact_address  = $_POST['contact_address'] ?? '';
$contact_number   = $_POST['contact_number'] ?? '';
$password         = $_POST['register_password'] ?? '';

// Validate required fields
if ($employee_id === '') {
    $response['message'] = 'Employee ID is missing.';
    echo json_encode($response);
    exit;
}
if ($email === '') {
    $response['message'] = 'Email is required.';
    echo json_encode($response);
    exit;
}

// Check for duplicate email
$email_check_stmt = $conn->prepare("SELECT employee_id FROM employees WHERE email = ? AND employee_id != ?");
$email_check_stmt->bind_param("ss", $email, $employee_id);
$email_check_stmt->execute();
$email_check_stmt->store_result();
if ($email_check_stmt->num_rows > 0) {
    $response['message'] = 'Email already exists for another employee.';
    $email_check_stmt->close();
    $conn->close();
    echo json_encode($response);
    exit;
}
$email_check_stmt->close();

// Build update query
$set_fields = "first_name=?, last_name=?, middle_name=?, campus=?, age=?, sex=?, permanent_address=?, phone_number=?, email=?, civil_status=?, religion=?, contact_person=?, contact_address=?, contact_no=?, birthdate=?";
$params = [$first_name, $last_name, $middle_name, $campus, $age, $sex, $permanent_address, $phone_number, $email, $civil_status, $religion, $contact_person, $contact_address, $contact_number, $birthdate];
$types = "ssssissssssssss"; // 15 parameters

if (!empty($password)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $set_fields .= ", password=?";
    $params[] = $hashed_password;
    $types .= "s"; // add 1 more type
}

// WHERE clause
$where_clause = " WHERE employee_id=?";
$params[] = $employee_id;
$types .= "s"; // employee_id is string

// Prepare and execute
$stmt = $conn->prepare("UPDATE employees SET $set_fields$where_clause");
if (!$stmt) {
    $response['message'] = "Prepare failed: " . $conn->error;
    echo json_encode($response);
    exit;
}

$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Employee updated successfully.';
} else {
    $response['message'] = 'Database error: ' . $stmt->error;
}

$stmt->close();
$conn->close();
echo json_encode($response);
