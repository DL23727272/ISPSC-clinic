<?php
require_once 'db_connection.php';

// List of campuses with email + username
$admins = [
    ['campus' => 'SANTA MARIA', 'username' => 'admin_sm', 'email' => 'admin_sm@gmail.com'],
    ['campus' => 'NARVACAN',   'username' => 'admin_nv', 'email' => 'admin_nv@gmail.com'],
    ['campus' => 'CANDON',     'username' => 'admin_cd', 'email' => 'admin_cd@gmail.com'],
    ['campus' => 'MAIN CAMPUS','username' => 'admin_mc', 'email' => 'admin_mc@gmail.com'],
    ['campus' => 'TAGUDIN',    'username' => 'admin_tg', 'email' => 'admin_tg@gmail.com'],
    ['campus' => 'CERVANTES',  'username' => 'admin_cv', 'email' => 'admin_cv@gmail.com'],
    ['campus' => 'SANTIAGO',   'username' => 'admin_st', 'email' => 'admin_st@gmail.com']
];

// Default password for all admins
$defaultPassword = "Admin123";
$hashedPassword = password_hash($defaultPassword, PASSWORD_DEFAULT);

// Insert campus admins
foreach ($admins as $admin) {
    $sql = "INSERT INTO users (username, email, password_hash, role, campus) VALUES (?, ?, ?, 'admin', ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $admin['username'], $admin['email'], $hashedPassword, $admin['campus']);
    mysqli_stmt_execute($stmt);
}

// Insert super admin
$superUsername = "superadmin";
$superEmail = "superadmin@gmail.com";
$superPassword = password_hash("SuperAdmin123", PASSWORD_DEFAULT);

$sqlSuper = "INSERT INTO users (username, email, password_hash, role, campus) VALUES (?, ?, ?, 'super_admin', 'ALL')";
$stmtSuper = mysqli_prepare($conn, $sqlSuper);
mysqli_stmt_bind_param($stmtSuper, "sss", $superUsername, $superEmail, $superPassword);
mysqli_stmt_execute($stmtSuper);

echo "Admin accounts and Super Admin account created successfully.";

/*
Created Accounts:

Santa Maria Admin → username: admin_sm | email: admin_sm@gmail.com | password: Admin123
Narvacan Admin    → username: admin_nv | email: admin_nv@gmail.com | password: Admin123
Candon Admin      → username: admin_cd | email: admin_cd@gmail.com | password: Admin123
Main Campus Admin → username: admin_mc | email: admin_mc@gmail.com | password: Admin123
Tagudin Admin     → username: admin_tg | email: admin_tg@gmail.com | password: Admin123
Cervantes Admin   → username: admin_cv | email: admin_cv@gmail.com | password: Admin123
Santiago Admin    → username: admin_st | email: admin_st@gmail.com | password: Admin123

Super Admin       → username: superadmin | email: superadmin@gmail.com | password: SuperAdmin123
*/
?>
