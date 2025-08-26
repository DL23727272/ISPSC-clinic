<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input
    $student_id = $_POST['student_id'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $middle_name = $_POST['middle_name'] ?? '';
    $campus = $_POST['campus'] ?? '';
    $department = $_POST['department'] ?? '';
    $course = $_POST['course'] ?? '';
    $year = $_POST['year'] ?? '';
    $major = $_POST['major'] ?? '';
    $semester = $_POST['semester'] ?? '';
    $age = $_POST['age'] ?? '';
    $sex = $_POST['sex'] ?? '';
    $permanent_address = $_POST['permanent_address'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $email = $_POST['register_email'] ?? '';
    $civil_status = $_POST['civil_status'] ?? '';
    $religion = $_POST['religion'] ?? '';
    $contact_person = $_POST['contact_person'] ?? '';
    $contact_address = $_POST['contact_address'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';

    if ($student_id == '') {
        $response['message'] = 'Student ID is missing.';
        echo json_encode($response);
        exit;
    }

    // Prepare the update query
    $stmt = $conn->prepare("UPDATE students SET 
        first_name=?, last_name=?, middle_name=?, campus=?, department=?, course=?, year=?, major=?, semester=?, 
        age=?, sex=?, permanent_address=?, phone_number=?, email=?, civil_status=?, religion=?, 
        contact_person=?, contact_address=?, contact_no=? 
        WHERE student_id=?");

    $stmt->bind_param("ssssssssssssssssssss", 
        $first_name, $last_name, $middle_name, $campus, $department, $course, $year, $major, $semester,
        $age, $sex, $permanent_address, $phone_number, $email, $civil_status, $religion,
        $contact_person, $contact_address, $contact_number,
        $student_id
    );

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Student updated successfully.';
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
