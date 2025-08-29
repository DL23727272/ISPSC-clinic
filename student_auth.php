<?php
session_start();
require_once 'db_connection.php';
require_once 'email_sender.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Unknown request'];

// ----------------- LOGIN -----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $student_id = trim($_POST['student_id']); 
    $password   = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, student_id, email, password FROM students 
                            WHERE student_id = ? OR email = ?");
    if ($stmt) {
        $stmt->bind_param("ss", $student_id, $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $student = $result->fetch_assoc();
            if (password_verify($password, $student['password'])) {
                // set PHP session
                $_SESSION['student_id'] = $student['id'];
                $_SESSION['student_no'] = $student['student_id'];
                $_SESSION['logged_in']  = true;

                // return JSON with student_id and email
                $response = [
                    'status'      => 'success', 
                    'message'     => 'Login successful! Redirecting...',
                    'student_id'  => $student['student_id'],
                    'email'       => $student['email']
                ];
            } else {
                $response = ['status' => 'error', 'message' => 'Invalid password. Please try again.'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Student ID/Email not found.'];
        }
        $stmt->close();
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}


// ----------------- REGISTER -----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $student_id   = trim($_POST['register_student_id']);
    $last_name    = trim($_POST['last_name']);
    $first_name   = trim($_POST['first_name']);
    $middle_name  = trim($_POST['middle_name']);
    $suffix = $_POST['suffix']; 
    $age          = trim($_POST['age']);
    $campus      = trim($_POST['campus']);
    $department  = trim($_POST['department']);
    $course      = trim($_POST['course']);
    $year        = trim($_POST['year']);
    $major       = trim($_POST['major']);
    $semester    = trim($_POST['semester']);
    $birthdate    = trim($_POST['birthdate']);
    $sex          = trim($_POST['sex']);
    $permanent_address = trim($_POST['permanent_address']);
    $phone_number = trim($_POST['phone_number']);
    $civil_status = trim($_POST['civil_status']);
    $religion     = trim($_POST['religion']);
    $contact_person = trim($_POST['contact_person']);
    $contact_address = trim($_POST['contact_address']);
    $contact_number = trim($_POST['contact_number']);
    $email        = trim($_POST['register_email']);
    $password     = trim($_POST['register_password']);

    $age = !empty($age) ? intval($age) : NULL;
    $birthdate = !empty($birthdate) ? $birthdate : NULL;

    // Check for existing student
    $stmt = $conn->prepare("SELECT id FROM students WHERE student_id = ? OR email = ?");
    if ($stmt) {
        $stmt->bind_param("ss", $student_id, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response = ['status' => 'error', 'message' => 'Student ID or Email already exists. Please login.'];
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

           $sql = "INSERT INTO students 
                (student_id, last_name, first_name, middle_name, suffix, age, campus, department, course, year, major, semester, birthdate, sex, permanent_address, phone_number, civil_status, religion, contact_person, contact_address, contact_no, email, password) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


            // ✅ Prepare the INSERT query here
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param(
                    "sssssisssssssssssssssss", // 23 parameters: 1 int (age), rest strings
                    $student_id,
                    $last_name,
                    $first_name,
                    $middle_name,
                    $suffix,
                    $age,
                    $campus,
                    $department,
                    $course,
                    $year,
                    $major,
                    $semester,
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
                    // Get values
                    $email = $_POST['register_email'];
                    $password = $_POST['register_password'];
                    $loginLink = "https://ispsc-clinica.personatab.com/"; 

                    // Try sending email
                    $mailResult = sendRegistrationEmail($email, $password, $loginLink);

                    if ($mailResult === true) {
                        $response = [
                            'status'  => 'success',
                            'message' => 'Registration successful! Please check your email & login.'
                        ];
                    } else {
                        // Registration worked, but email failed
                        $response = [
                            'status'  => 'warning',
                            'message' => 'Registration successful, but email could not be sent: ' . $mailResult
                        ];
                    }
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
