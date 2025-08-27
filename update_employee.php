<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input
    $employee_id = $_POST['employee_id'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $middle_name = $_POST['middle_name'] ?? '';
    $campus = $_POST['campus'] ?? '';
    $age = $_POST['age'] ?? '';
    $birthdate = $_POST['birthdate'] ?? '';
    $sex = $_POST['sex'] ?? '';
    $permanent_address = $_POST['permanent_address'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $email = $_POST['register_email'] ?? '';
    $civil_status = $_POST['civil_status'] ?? '';
    $religion = $_POST['religion'] ?? '';
    $contact_person = $_POST['contact_person'] ?? '';
    $contact_address = $_POST['contact_address'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';

    if ($employee_id == '') {
        $response['message'] = 'Employee ID is missing.';
        echo json_encode($response);
        exit;
    }

    $stmt = $conn->prepare("UPDATE employees SET 
        first_name=?, last_name=?, middle_name=?, campus=?,  
        age=?, sex=?, permanent_address=?, phone_number=?, email=?, civil_status=?, religion=?, 
        contact_person=?, contact_address=?, contact_no=?, birthdate=? 
        WHERE employee_id=?");

    // 16 parameters total → "ssssissssssssssi"
    $stmt->bind_param("ssssissssssssssi", 
        $first_name, $last_name, $middle_name, $campus, 
        $age, $sex, $permanent_address, $phone_number, $email, $civil_status, $religion,
        $contact_person, $contact_address, $contact_number, $birthdate,
        $employee_id
    );



    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Employee updated successfully.';
    } else {
        $response['message'] = 'Database error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    echo json_encode($response);
    exit;
}

$response['message'] = 'Invalid request method.';
echo json_encode($response);
