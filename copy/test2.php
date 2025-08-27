<?php
session_start();
require_once 'db_connection.php';

if (!isset($_GET['student_id']) || empty($_GET['student_id'])) {
    ?>
    <script>
        const sid = sessionStorage.getItem("student_id");
        if (sid) {
            window.location.href = window.location.pathname + "?student_id=" + encodeURIComponent(sid);
        } else {
            document.body.innerHTML = "No student_id found in session storage.";
        }
    </script>
    <?php
    exit;
}

$student_id = $conn->real_escape_string($_GET['student_id']);

// Fetch record with join
$sql = "SELECT shi.*, s.first_name, s.last_name, s.campus, shi.created_at
        FROM student_health_info shi
        JOIN students s ON shi.student_id = s.student_id
        WHERE shi.student_id = '$student_id'";

$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    die("Record not found for Student ID: " . htmlspecialchars($student_id));
}

$record = $result->fetch_assoc();
?>

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
</head>
<body>
    <h3>Editing record for <?php echo htmlspecialchars($record['first_name'] . " " . $record['last_name']); ?> (<?php echo htmlspecialchars($record['student_id']); ?>)</h3>

<form method="post" action="save_student.php">
    <label>Student ID:</label>
    <input type="text" name="student_id" value="<?php echo htmlspecialchars($record['student_id']); ?>" readonly><br><br>

    <label>Name:</label>
    <input type="text" value="<?php echo htmlspecialchars($record['first_name'] . " " . $record['last_name']); ?>" readonly><br><br>

    <label>Campus:</label>
    <input type="text" value="<?php echo htmlspecialchars($record['campus']); ?>" readonly><br><br>

    <label>Created At:</label>
    <input type="text" value="<?php echo htmlspecialchars($record['created_at']); ?>" readonly><br><br>

    <label>Health Condition:</label>
    <textarea name="health_condition"><?php echo htmlspecialchars($record['surgery'] ?? ''); ?></textarea><br><br>

    <button type="submit">Save Changes</button>
</form>

   <script>
    const studentId = sessionStorage.getItem("student_id");
    if (studentId && !window.location.search.includes("student_id")) {
        window.location.href = "student_edit.php?student_id=" + studentId;
    }
</script>
</body>
</html>
