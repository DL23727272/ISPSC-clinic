<?php
session_start();
require_once 'db_connection.php';

header('Content-Type: application/json'); // tell frontend we return JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['admin-username']);
    $password = trim($_POST['admin-password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Check if active
        if ($row['status'] !== 'active') {
            echo json_encode(["success" => false, "message" => "Account is inactive. Please contact admin."]);
            exit;
        }

        // Verify password
        if (password_verify($password, $row['password_hash'])) {
            // Store PHP session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['campus'] = $row['campus'];

            // Update last login
            $update = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $update->bind_param("i", $row['id']);
            $update->execute();

            // ✅ Return employee_id and role as JSON
            echo json_encode([
                "success" => true,
                "employee_id" => $row['employee_id'], 
                "role" => $row['role'],
                "campus" => $row['campus']
            ]);
            exit;
        } else {
            echo json_encode(["success" => false, "message" => "Invalid password!"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "User not found!"]);
    }
}
?>